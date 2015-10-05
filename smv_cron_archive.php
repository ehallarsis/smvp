<?php

/**
* System cron script - File Archiving
* @author Jen Andes
* @copyright Primesoft Phils. Inc
*/
error_reporting(E_ALL^ E_WARNING);
define('DRUPAL_ROOT', getcwd());
include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

/******** PURCHASE ORDER ***********/

$key = 'purchase-order-archive';
$po_archive = db_query('SELECT id, description, setup_value FROM {smv_global_settings} 
	WHERE setup_key = :setup_key',array(':setup_key' => $key))->fetchObject();

//VENDOR ARCHIVE - Get all Paid PO that are more than :purchase-order-archive of days and move to archive

if($po_archive && $po_archive->setup_value != ""){

	$intervaldays = intval($po_archive->setup_value);

	$results = db_query('SELECT n.nid, pv.field_po_vendorno_value as vendorno, pn.field_po_number_value as ponumber FROM {node} n 
		LEFT JOIN {field_data_field_po_sync_status} pss ON pss.entity_id = n.nid 
		LEFT JOIN {field_data_field_po_number} pn ON pn.entity_id = n.nid 		 
		LEFT JOIN {field_data_field_po_vendorno} pv ON pv.entity_id = n.nid
		LEFT JOIN {field_data_field_po_status} ps ON ps.entity_id = n.nid 
		LEFT JOIN {field_data_field_po_archive} pa ON pa.entity_id = n.nid
		LEFT JOIN {field_data_field_po_payment_date} pd ON pd.entity_id = n.nid 
		WHERE n.type = :type 
			AND pss.field_po_sync_status_value = :sync_status 
			AND ps.field_po_status_value = :status 
			AND pa.field_po_archive_value != :archive 
			AND DATE(pd.field_po_payment_date_value) <= DATE_SUB(NOW(), INTERVAL :intervaldays DAY)',  
		array('type'=>'purchase_order', 'sync_status' => 1, 'status' => "Paid", 'archive' => 1, 'intervaldays' => $intervaldays ))->fetchAll();

	if($results){
		foreach ($results as $r) {

			$vendorno = $r->vendorno;
			$ponumber = $r->ponumber;			
			$nid 			= $r->nid;
			
			//update po status to archive
			$node = node_load($r->nid);

			//Purchase Order - csv and pdf
			$fids = db_query('SELECT csv.field_po_csv_fid as csv_fid, pdf.field_po_pdf_fid as pdf_fid FROM {node} n 
				LEFT JOIN {field_data_field_po_csv} csv ON csv.entity_id = n.nid 
				LEFT JOIN {field_data_field_po_pdf} pdf ON pdf.entity_id = n.nid 
				WHERE n.nid = :nid', array('nid'=>$r->nid))->fetchAll();

			if($fids){
				foreach ($fids as $fid) {
					//csv
					if($fid->csv_fid){
						smv_sitecron_move_to_vendor_archive_folder($fid->csv_fid, $vendorno);			
						$filepath_csv = 'sm_vendor/vendors/'.$vendorno.'/archive';
						$node->field_po_csv_filepath[LANGUAGE_NONE][0]['value'] = $filepath_csv;
					}	

					//pdf
					if($fid->pdf_fid){
						smv_sitecron_move_to_vendor_archive_folder($fid->pdf_fid, $vendorno);			
						$filepath_pdf = 'sm_vendor/vendors/'.$vendorno.'/archive';
						$node->field_po_pdf_filepath[LANGUAGE_NONE][0]['value'] = $filepath_pdf;	
					}																			
				}			
			}

			$node->field_po_archive[LANGUAGE_NONE][0]['value'] = 1;
			field_attach_update('node', $node);
			entity_get_controller('node')->resetCache(array($node->nid));			

			//Move to achive also - GR, IR, Payment Details
			smv_sitecron_archive_goods_receceipt($ponumber,$vendorno);
			smv_sitecron_archive_invoice_receceipt($ponumber,$vendorno);
			smv_sitecron_archive_payment_details($ponumber,$vendorno);
		}		
	}
}

$key = 'purchase-order-full-archive';
$po_full_archive = db_query('SELECT id, description, setup_value FROM {smv_global_settings} 
	WHERE setup_key = :setup_key',array(':setup_key' => $key))->fetchObject();

//Delete record and files that more than :purchase-order-full-archive of days.
if($po_full_archive && $po_full_archive->setup_value != ""){

	$intervaldays = intval($po_full_archive->setup_value);

	$results_archive = db_query('SELECT n.nid, pn.field_po_number_value as ponumber FROM {node} n 
	LEFT JOIN {field_data_field_po_number} pn ON pn.entity_id = n.nid 		 
	LEFT JOIN {field_data_field_po_status} ps ON ps.entity_id = n.nid 
	LEFT JOIN {field_data_field_po_archive} pa ON pa.entity_id = n.nid
	LEFT JOIN {field_data_field_po_payment_date} pd ON pd.entity_id = n.nid 
	WHERE n.type = :type 
		AND ps.field_po_status_value = :status 
		AND pa.field_po_archive_value = :archive 
		AND DATE(pd.field_po_payment_date_value) <= DATE_SUB(NOW(), INTERVAL :intervaldays DAY)',  
	array('type'=>'purchase_order', 'status' => "Paid", 'archive' => 1, 'intervaldays' => $intervaldays))->fetchAll();

	if($results_archive){
		foreach ($results_archive as $archive) {
			$ponumber = $archive->ponumber;

			//delete purchase order and its record
			node_delete($archive->nid);

			//delete corresponding gr, ir, payment details
			if($gr_nid = smv_sitefeeds_goodsreceipt_nid($ponumber)){
				node_delete($gr_nid);
			}
			if($ir_nid = smv_sitefeeds_invoicereceipt_nid($ponumber)){
				node_delete($ir_nid);
			}
			if($pd_nid = smv_sitefeeds_paymentdetails_nid($ponumber)){
				node_delete($pd_nid);
			}
		}
	}	
}

/******** NON PO ***********/
/*
$results_nonpo = db_query('SELECT n.nid, pv.field_nonpo_vendorno_value as vendorno FROM {node} n 
	LEFT JOIN {field_data_field_nonpo_sync_status} pss ON pss.entity_id = n.nid 	 
	LEFT JOIN {field_data_field_nonpo_vendorno} pv ON pv.entity_id = n.nid
	LEFT JOIN {field_data_field_nonpo_archive} pa ON pa.entity_id = n.nid
	LEFT JOIN {field_data_field_nonpo_invoice_date} pd ON pd.entity_id = n.nid 
	WHERE n.type = :type 
		AND pss.field_nonpo_sync_status_value = :sync_status 
		AND pa.field_nonpo_archive_value != :archive 
		AND DATE(pd.field_nonpo_invoice_date_value) <= DATE_SUB(NOW(), INTERVAL 1 YEAR)',  
	array('type'=>'non_po', 'sync_status' => 1, 'archive' => 1))->fetchAll();

if($results_nonpo){
	foreach ($results_nonpo as $nonpo) {

		$vendorno = $nonpo->vendorno;			
		
		//update status to archive
		$node_nonpo = node_load($nonpo->nid);

		//csv and pdf
		$nonpo_fids = db_query('SELECT csv.field_nonpo_csv_fid as csv_fid, pdf.field_nonpo_pdf_fid as pdf_fid FROM {node} n 
			LEFT JOIN {field_data_field_nonpo_csv} csv ON csv.entity_id = n.nid 
			LEFT JOIN {field_data_field_nonpo_pdf} pdf ON pdf.entity_id = n.nid
			WHERE n.nid = :nid', array('nid'=>$nonpo->nid))->fetchAll();

		if($nonpo_fids){
			foreach ($nonpo_fids as $fid) {
				//csv
				if($fid->csv_fid){
					smv_sitecron_move_to_vendor_archive_folder($fid->csv_fid, $vendorno);			
					$node_nonpo->field_nonpo_csv_filepath[LANGUAGE_NONE][0]['value'] = 'sm_vendor/vendors/'.$vendorno.'/archive';
				}					
				//pdf
				if($fid->pdf_fid){
					smv_sitecron_move_to_vendor_archive_folder($fid->pdf_fid, $vendorno);			
					$node_nonpo->field_nonpo_pdf_filepath[LANGUAGE_NONE][0]['value'] = 'sm_vendor/vendors/'.$vendorno.'/archive';
				}																							
			}			
		}

		$node_nonpo->field_nonpo_archive[LANGUAGE_NONE][0]['value'] = 1;
		field_attach_update('node', $node_nonpo);
		entity_get_controller('node')->resetCache(array($node_nonpo->nid));	
	}
}

//Delete record and files that more than 10 YEARS.
$results_nonpo_archive = db_query('SELECT n.nid FROM {node} n 	 
LEFT JOIN {field_data_field_nonpo_archive} pa ON pa.entity_id = n.nid
LEFT JOIN {field_data_field_nonpo_invoice_date} pd ON pd.entity_id = n.nid 
WHERE n.type = :type 
	AND pa.field_nonpo_archive_value = :archive 
	AND DATE(pd.field_nonpo_invoice_date_value) <= DATE_SUB(NOW(), INTERVAL 10 YEAR)',  
array('type'=>'non_po', 'archive' => 1))->fetchAll();

if($results_nonpo_archive){
	foreach ($results_nonpo_archive as $nonpo_archive) {
		//delete record
		node_delete($nonpo_archive->nid);
	}
}
*/

/******** PAYMENT VOUCHER ***********/

$key = 'payment-voucher-archive';
$pv_archive = db_query('SELECT id, description, setup_value FROM {smv_global_settings} 
	WHERE setup_key = :setup_key',array(':setup_key' => $key))->fetchObject();

if($pv_archive && $pv_archive->setup_value != ""){

	$intervaldays = intval($pv_archive->setup_value);

	$results_pv = db_query('SELECT n.nid, pv.field_pv_vendor_number_value as vendorno FROM {node} n 
		LEFT JOIN {field_data_field_pv_sync_status} pss ON pss.entity_id = n.nid 	 
		LEFT JOIN {field_data_field_pv_vendor_number} pv ON pv.entity_id = n.nid
		LEFT JOIN {field_data_field_pv_archive} pa ON pa.entity_id = n.nid
		LEFT JOIN {field_data_field_pv_date} pd ON pd.entity_id = n.nid 
		WHERE n.type = :type 
			AND pss.field_pv_sync_status_value = :sync_status 
			AND pa.field_pv_archive_value != :archive 
			AND DATE(pd.field_pv_date_value) <= DATE_SUB(NOW(), INTERVAL :intervaldays DAY)',  
		array('type'=>'payment_voucher', 'sync_status' => 1, 'archive' => 1, 'intervaldays' => $intervaldays))->fetchAll();

	if($results_pv){
		foreach ($results_pv as $pv) {

			$vendorno = $pv->vendorno;
			
			//update status to archive
			$node_pv = node_load($pv->nid);

			//csv and pdf
			$pv_fids = db_query('SELECT csv.field_pv_csv_fid as csv_fid, pdf.field_pv_pdf_fid as pdf_fid, xls.field_pv_xls_fid as xls_fid 
				FROM {node} n 
				LEFT JOIN {field_data_field_pv_csv} csv ON csv.entity_id = n.nid 
				LEFT JOIN {field_data_field_pv_pdf} pdf ON pdf.entity_id = n.nid
				LEFT JOIN {field_data_field_pv_xls} xls ON xls.entity_id = n.nid
				WHERE n.nid = :nid', array('nid'=>$pv->nid))->fetchAll();

			if($pv_fids){
				foreach ($pv_fids as $fid) {
					//csv
					if($fid->csv_fid){
						smv_sitecron_move_to_vendor_archive_folder($fid->csv_fid, $vendorno);			
						$node_pv->field_pv_csv_filepath[LANGUAGE_NONE][0]['value'] = 'sm_vendor/vendors/'.$vendorno.'/archive';
					}					
					//pdf
					if($fid->pdf_fid){
						smv_sitecron_move_to_vendor_archive_folder($fid->pdf_fid, $vendorno);			
						$node_pv->field_pv_pdf_filepath[LANGUAGE_NONE][0]['value'] = 'sm_vendor/vendors/'.$vendorno.'/archive';
					}		
					//xls
					if($fid->xls_fid){
						smv_sitecron_move_to_vendor_archive_folder($fid->xls_fid, $vendorno);			
						$node_pv->field_pv_xls_filepath[LANGUAGE_NONE][0]['value'] = 'sm_vendor/vendors/'.$vendorno.'/archive';
					}																																							
				}			
			}

			$node_pv->field_pv_archive[LANGUAGE_NONE][0]['value'] = 1;
			field_attach_update('node', $node_pv);
			entity_get_controller('node')->resetCache(array($node_pv->nid));	
		}
	}
}

$key = 'payment-voucher-full-archive';
$pv_full_archive = db_query('SELECT id, description, setup_value FROM {smv_global_settings} 
	WHERE setup_key = :setup_key',array(':setup_key' => $key))->fetchObject();

if($pv_full_archive && $pv_full_archive->setup_value != ""){

	$intervaldays = intval($pv_full_archive->setup_value);

	//Delete record and files that more than :payment-voucher-full-archive of days.
	$results_pv_archive = db_query('SELECT n.nid FROM {node} n 	 
	LEFT JOIN {field_data_field_pv_archive} pa ON pa.entity_id = n.nid
	LEFT JOIN {field_data_field_pv_date} pd ON pd.entity_id = n.nid 
	WHERE n.type = :type 
		AND pa.field_pv_archive_value = :archive 
		AND DATE(pd.field_pv_date_value) <= DATE_SUB(NOW(), INTERVAL :intervaldays DAY)',  
	array('type'=>'payment_voucher', 'archive' => 1, 'intervaldays' => $intervaldays))->fetchAll();

	if($results_pv_archive){
		foreach ($results_pv_archive as $pv_archive) {
			//delete record
			node_delete($pv_archive->nid);
		}
	}	
}

/******** BIR ***********/

$key = 'bir-archive';
$bir_archive = db_query('SELECT id, description, setup_value FROM {smv_global_settings} 
	WHERE setup_key = :setup_key',array(':setup_key' => $key))->fetchObject();

if($bir_archive && $bir_archive->setup_value != ""){

	$intervaldays = intval($bir_archive->setup_value);

	$results_bir = db_query('SELECT n.nid, pv.field_bir_vendor_number_value as vendorno FROM {node} n 
		LEFT JOIN {field_data_field_bir_sync_status} pss ON pss.entity_id = n.nid 	 
		LEFT JOIN {field_data_field_bir_vendor_number} pv ON pv.entity_id = n.nid
		LEFT JOIN {field_data_field_bir_archive} pa ON pa.entity_id = n.nid
		LEFT JOIN {field_data_field_bir_date} pd ON pd.entity_id = n.nid 
		WHERE n.type = :type 
			AND pss.field_bir_sync_status_value = :sync_status 
			AND pa.field_bir_archive_value != :archive 
			AND DATE(pd.field_bir_date_value) <= DATE_SUB(NOW(), INTERVAL :intervaldays DAY)',  
		array('type'=>'bir', 'sync_status' => 1, 'archive' => 1, 'intervaldays' => $intervaldays))->fetchAll();

	if($results_bir){
		foreach ($results_bir as $bir) {

			$vendorno = $bir->vendorno;
			
			//update status to archive
			$node_bir = node_load($bir->nid);

			//csv and pdf
			$bir_fids = db_query('SELECT pdf.field_bir_pdf_fid as pdf_fid FROM {node} n 
				LEFT JOIN {field_data_field_bir_pdf} pdf ON pdf.entity_id = n.nid
				WHERE n.nid = :nid', array('nid'=>$bir->nid))->fetchAll();

			if($bir_fids){
				foreach ($bir_fids as $fid) {	
					//pdf
					if($fid->pdf_fid){
						smv_sitecron_move_to_vendor_archive_folder($fid->pdf_fid, $vendorno);			
						$node_bir->field_bir_pdf_filepath[LANGUAGE_NONE][0]['value'] = 'sm_vendor/vendors/'.$vendorno.'/archive';
					}																		
				}			
			}

			$node_bir->field_bir_archive[LANGUAGE_NONE][0]['value'] = 1;
			field_attach_update('node', $node_bir);
			entity_get_controller('node')->resetCache(array($node_bir->nid));	
		}
	}	
}

$key = 'bir-full-archive';
$bir_full_archive = db_query('SELECT id, description, setup_value FROM {smv_global_settings} 
	WHERE setup_key = :setup_key',array(':setup_key' => $key))->fetchObject();

if($bir_full_archive && $bir_full_archive->setup_value != ""){

	$intervaldays = intval($bir_full_archive->setup_value);

	//Delete record and files that more than :bir-full-archive of days.
	$results_bir_archive = db_query('SELECT n.nid FROM {node} n 	 
	LEFT JOIN {field_data_field_bir_archive} pa ON pa.entity_id = n.nid
	LEFT JOIN {field_data_field_bir_date} pd ON pd.entity_id = n.nid 
	WHERE n.type = :type 
		AND pa.field_bir_archive_value = :archive 
		AND DATE(pd.field_bir_date_value) <= DATE_SUB(NOW(), INTERVAL :intervaldays DAY)',  
	array('type'=>'bir', 'archive' => 1, 'intervaldays' => $intervaldays))->fetchAll();

	if($results_bir_archive){
		foreach ($results_bir_archive as $bir_archive) {
			//delete record
			node_delete($bir_archive->nid);
		}
	}	
}

function smv_sitecron_archive_goods_receceipt($ponumber,$vendorno){

	if(!isset($ponumber) || !isset($vendorno)){
		return FALSE;
	}

	$nid = smv_sitefeeds_goodsreceipt_nid($ponumber);

	if($nid){		
		$node = node_load($nid);
		
		$fids = db_query('SELECT csv.field_gr_csv_fid as csv_fid FROM {node} n 
			LEFT JOIN {field_data_field_gr_csv} csv ON csv.entity_id = n.nid 
			WHERE n.nid = :nid', array('nid'=>$nid))->fetchAll();		
		
		if($fids){
			foreach ($fids as $fid) {
				if($fid->csv_fid){
					smv_sitecron_move_to_vendor_archive_folder($fid->csv_fid, $vendorno);			
					$node->field_gr_csv_filepath[LANGUAGE_NONE][0]['value'] = 'sm_vendor/vendors/'.$vendorno.'/archive';
				}																			
			}			
		}

		$node->field_gr_archive[LANGUAGE_NONE][0]['value'] = 1;
		field_attach_update('node', $node);
		entity_get_controller('node')->resetCache(array($node->nid));					
	}
}

function smv_sitecron_archive_invoice_receceipt($ponumber,$vendorno){

	if(!isset($ponumber) || !isset($vendorno)){
		return FALSE;
	}

	$nid = smv_sitefeeds_invoicereceipt_nid($ponumber);
	if($nid){
	
		$node = node_load($nid);
		
		$fids = db_query('SELECT csv.field_ir_csv_fid as csv_fid FROM {node} n 
			LEFT JOIN {field_data_field_ir_csv} csv ON csv.entity_id = n.nid 
			WHERE n.nid = :nid', array('nid'=>$nid))->fetchAll();		
		
		if($fids){
			foreach ($fids as $fid) {
				if($fid->csv_fid){
					smv_sitecron_move_to_vendor_archive_folder($fid->csv_fid, $vendorno);			
					$node->field_ir_csv_filepath[LANGUAGE_NONE][0]['value'] = 'sm_vendor/vendors/'.$vendorno.'/archive';
				}																			
			}			
		}

		$node->field_ir_archive[LANGUAGE_NONE][0]['value'] = 1;
		field_attach_update('node', $node);
		entity_get_controller('node')->resetCache(array($node->nid));					
	}
}

function smv_sitecron_archive_payment_details($ponumber,$vendorno){

	if(!isset($ponumber) || !isset($vendorno)){
		return FALSE;
	}

	$nid = smv_sitefeeds_paymentdetails_nid($ponumber);
	if($nid){
		
		$node = node_load($nid);
		
		$fids = db_query('SELECT csv.field_pd_csv_fid as csv_fid FROM {node} n 
			LEFT JOIN {field_data_field_pd_csv} csv ON csv.entity_id = n.nid 
			WHERE n.nid = :nid', array('nid'=>$nid))->fetchAll();		
		
		if($fids){
			foreach ($fids as $fid) {
				if($fid->csv_fid){
					smv_sitecron_move_to_vendor_archive_folder($fid->csv_fid, $vendorno);			
					$node->field_pd_csv_filepath[LANGUAGE_NONE][0]['value'] = 'sm_vendor/vendors/'.$vendorno.'/archive';
				}																			
			}			
		}

		$node->field_pd_archive[LANGUAGE_NONE][0]['value'] = 1;
		field_attach_update('node', $node);
		entity_get_controller('node')->resetCache(array($node->nid));					
	}
}

?>