<?php
/**
* System cron script - Delete all expired notifications
* @author Jen Andes
* @copyright Primesoft Phils. Inc
*/
error_reporting(E_ALL^ E_WARNING);
define('DRUPAL_ROOT', getcwd());
include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$key = 'cleanup-notification';
$global_setting = db_query('SELECT id, description, setup_value FROM {smv_global_settings} 
	WHERE setup_key = :setup_key',array(':setup_key' => $key))->fetchObject();

if($global_setting && $global_setting->setup_value != ""){

	$intervaldays = intval($global_setting->setup_value);

	$results = db_query('SELECT m.mlid FROM {mail_logger} m
		WHERE DATE(FROM_UNIXTIME(m.date_sent)) <= DATE_SUB(NOW(), INTERVAL :intervaldays DAY)',
		array('intervaldays'=>$intervaldays))->fetchAll();

	if($results){

		$i = 0;
		foreach($results as $r){
			$delete = db_delete('mail_logger')
				->condition('mlid',$r->mlid)
				->execute();				
			
			//count deleted notification				
			if($delete){
				$i++;
			}
		}

		if($i!= 0){
			drupal_set_message(t('Deleted @notif_number notification(s) successfully.', array('@notif_number' => $i)), 'status');
		}else{
			drupal_set_message(t('Something went wrong. No notifications was deleted.'), 'status');
		}					
	}
}

?>