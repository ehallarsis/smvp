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

$results_po = db_query('SELECT n.nid, pv.field_po_vendorno_value as vendorno, pn.field_po_number_value as ponumber FROM {node} n 
	LEFT JOIN {field_data_field_po_sync_status} pss ON pss.entity_id = n.nid 
	LEFT JOIN {field_data_field_po_number} pn ON pn.entity_id = n.nid 		 
	LEFT JOIN {field_data_field_po_vendorno} pv ON pv.entity_id = n.nid
	LEFT JOIN {field_data_field_po_status} ps ON ps.entity_id = n.nid 
	LEFT JOIN {field_data_field_po_archive} pa ON pa.entity_id = n.nid
	WHERE n.type = :type 
		AND pss.field_po_sync_status_value = :sync_status 
		AND ps.field_po_status_value = :status 
		AND pa.field_po_archive_value != :archive',  
	array('type'=>'purchase_order', 'sync_status' => 1, 'status' => "Paid", 'archive' => 1))->fetchAll();

if($results_po){
	foreach ($results_po as $po){
	  $pd_nid = smv_sitefeeds_paymentdetails_nid($po->ponumber);
	  $pd_node = node_load($pd_nid);

	  $wrapper = entity_metadata_wrapper('node', $pd_node);
 		$pd_dates = array();
	  foreach ($wrapper->field_pd_items as $items) {
	    $pd_dates[] = $items->field_pd_line_date->value();
	  }	  
	  $max_date = max($pd_dates);

	  $ddate = date('Y-m-d H:i:s', $max_date);
	  $current_time = date('Y-m-d H:i:s');  
	  $current_time_object = new DateTime($current_time);
	  $time_given_object = new DateTime($ddate);
	  
	  $interval = date_diff($current_time_object, $time_given_object);
	  $year_interval = $interval->y;

	  
	}
}


?>