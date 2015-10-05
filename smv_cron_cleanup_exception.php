<?php
/**
* System cron script - Delete all expired exception files
* @author Jen Andes
* @copyright Primesoft Phils. Inc
*/
error_reporting(E_ALL^ E_WARNING);
define('DRUPAL_ROOT', getcwd());
include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$key = 'cleanup-exceptions';
$global_setting = db_query('SELECT id, description, setup_value FROM {smv_global_settings} 
	WHERE setup_key = :setup_key',array(':setup_key' => $key))->fetchObject();

if($global_setting && $global_setting->setup_value != ""){

	$intervaldays = intval($global_setting->setup_value);
	$i = 0;

	//Purchase Order
	$results = db_query('SELECT n.nid FROM {node} n 
		LEFT JOIN {field_data_field_po_sync_status} pss ON pss.entity_id = n.nid 
		WHERE n.type = :type 
			AND (pss.field_po_sync_status_value != :sync_status OR n.uid = :uid) 
			AND DATE(FROM_UNIXTIME(n.created)) <= DATE_SUB(NOW(), INTERVAL :intervaldays DAY)',  
		array('type'=>'purchase_order', 'sync_status' => 1, 'uid' => 0, 'intervaldays' => $intervaldays))->fetchAll();

	if($results){
		foreach($results as $po){
			node_delete($po->nid);
			$i++;
		}
	}

	//Goods Receipt
	$results_gr = db_query('SELECT n.nid FROM {node} n 
		LEFT JOIN {field_data_field_gr_sync_status} pss ON pss.entity_id = n.nid 
		WHERE n.type = :type 
			AND (pss.field_gr_sync_status_value != :sync_status OR n.uid = :uid) 
			AND DATE(FROM_UNIXTIME(n.created)) <= DATE_SUB(NOW(), INTERVAL :intervaldays DAY)',  
		array('type'=>'goods_receipt', 'sync_status' => 1, 'uid' => 0, 'intervaldays' => $intervaldays))->fetchAll();

	if($results_gr){
		foreach($results_gr as $gr){
			node_delete($gr->nid);
			$i++;
		}
	}

	//Invoice Receipt
	$results_ir = db_query('SELECT n.nid FROM {node} n 
		LEFT JOIN {field_data_field_ir_sync_status} pss ON pss.entity_id = n.nid 
		WHERE n.type = :type 
			AND (pss.field_ir_sync_status_value != :sync_status OR n.uid = :uid) 
			AND DATE(FROM_UNIXTIME(n.created)) <= DATE_SUB(NOW(), INTERVAL :intervaldays DAY)',  
		array('type'=>'invoice_receipt', 'sync_status' => 1, 'uid' => 0, 'intervaldays' => $intervaldays))->fetchAll();

	if($results_ir){
		foreach($results_ir as $ir){
			node_delete($ir->nid);
			$i++;
		}
	}

	//Payment Details
	$results_pd = db_query('SELECT n.nid FROM {node} n 
		LEFT JOIN {field_data_field_pd_sync_status} pss ON pss.entity_id = n.nid 
		WHERE n.type = :type 
			AND (pss.field_pd_sync_status_value != :sync_status OR n.uid = :uid) 
			AND DATE(FROM_UNIXTIME(n.created)) <= DATE_SUB(NOW(), INTERVAL :intervaldays DAY)',  
		array('type'=>'payment_details', 'sync_status' => 1, 'uid' => 0, 'intervaldays' => $intervaldays))->fetchAll();

	if($results_pd){
		foreach($results_pd as $pd){
			node_delete($pd->nid);
			$i++;
		}
	}	

	//Payment Voucher
	$results_pv = db_query('SELECT n.nid FROM {node} n 
		LEFT JOIN {field_data_field_pv_sync_status} pss ON pss.entity_id = n.nid 
		WHERE n.type = :type 
			AND (pss.field_pv_sync_status_value != :sync_status OR n.uid = :uid) 
			AND DATE(FROM_UNIXTIME(n.created)) <= DATE_SUB(NOW(), INTERVAL :intervaldays DAY)',  
		array('type'=>'payment_voucher', 'sync_status' => 1, 'uid' => 0, 'intervaldays' => $intervaldays))->fetchAll();

	if($results_pv){
		foreach($results_pv as $pv){
			node_delete($pv->nid);
			$i++;
		}
	}				

	//NON PO
	$results_nonpo = db_query('SELECT n.nid FROM {node} n 
		LEFT JOIN {field_data_field_nonpo_sync_status} pss ON pss.entity_id = n.nid 
		WHERE n.type = :type 
			AND (pss.field_nonpo_sync_status_value != :sync_status OR n.uid = :uid) 
			AND DATE(FROM_UNIXTIME(n.created)) <= DATE_SUB(NOW(), INTERVAL :intervaldays DAY)',  
		array('type'=>'non_po', 'sync_status' => 1, 'uid' => 0, 'intervaldays' => $intervaldays))->fetchAll();

	if($results_nonpo){
		foreach($results_nonpo as $nonpo){
			node_delete($nonpo->nid);
			$i++;
		}
	}					

	//BIR
	$results_bir = db_query('SELECT n.nid FROM {node} n 
		LEFT JOIN {field_data_field_bir_sync_status} pss ON pss.entity_id = n.nid 
		WHERE n.type = :type 
			AND (pss.field_bir_sync_status_value != :sync_status OR n.uid = :uid) 
			AND DATE(FROM_UNIXTIME(n.created)) <= DATE_SUB(NOW(), INTERVAL :intervaldays DAY)',  
		array('type'=>'bir', 'sync_status' => 1, 'uid' => 0, 'intervaldays' => $intervaldays))->fetchAll();

	if($results_bir){
		foreach($results_bir as $bir){
			node_delete($bir->nid);
			$i++;
		}
	}		

	if($i!= 0){
		drupal_set_message(t('Deleted @record_number record(s) successfully.', array('@record_number' => $i)), 'status');
	}				
}

?>