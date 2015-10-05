<?php

function smv_mail_logger_delete_confirm($form, &$form_state){
	global $user;

	$id = arg(2);

	if(!isset($id)){
		drupal_access_denied();
	}

	$record = db_query('SELECT mlid, subject, referenceId, vendorAccount FROM {mail_logger} 
		WHERE mlid = :id',array(':id' => $id))->fetchObject();

	if($record && $user->uid == $record->referenceId && (in_array('vendor', $user->roles) || in_array('internal-users', $user->roles)) ){

		if(in_array('internal-users', $user->roles)){
			$redirectlink = url('list-notifications', array('absolute'=>TRUE));
		}else{
			$redirectlink = url('notifications', array('absolute'=>TRUE));
		}
		
		$form['mlid_to_delete'] = array('#type' => 'value', '#value' => $record->mlid);
		$form['subj_to_delete'] = array('#type' => 'value', '#value' => $record->subject);

	 	return confirm_form($form, 
	    t('Are you sure you want to delete notification for %email?', array('%email' => $record->subject)),
	    $redirectlink,
	    t('This action cannot be undone.'),
	    t('Delete'),
	    t('Cancel')
	  );		
	}else{
		drupal_access_denied();
	}
}

function smv_mail_logger_delete_confirm_submit($form, &$form_state) {
 	global $user;

 	$mlid = $form_state['values']['mlid_to_delete'];

	if(isset($mlid)){			
		$delete = db_delete('mail_logger')
			->condition('mlid',$mlid)
			->execute();

		if($delete){
			drupal_set_message(t('Notification has been deleted successfully.'), 'status');
		}
	}

	if(in_array('internal-users', $user->roles)){
		$form_state['redirect'] = 'list-notifications';
	}else{
		$form_state['redirect'] = 'notifications';
	}
}