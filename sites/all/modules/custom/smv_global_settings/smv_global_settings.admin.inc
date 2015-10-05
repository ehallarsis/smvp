<?php

function smv_global_settings_setup_form($form, &$form_state) {
	global $user;

	if(in_array('administrator', $user->roles)){
	  $form['setup_key'] = array(
	    '#type' => 'textfield',
	    '#title' => t('Key'),
	    '#size' => 100,
	    '#maxlength' => 100,
	    '#required' => TRUE,
	  );

	  $form['setup_value'] = array(
	    '#type' => 'textfield',
	    '#title' => t('Value'),    
	    '#size' => 100,
	    '#maxlength' => 100,
	    '#required' => TRUE,
	  );

	  $form['description'] = array(
	    '#type' => 'textfield',
	    '#title' => t('Description'),    
	    '#size' => 100,
	    '#maxlength' => 100,
	    '#required' => TRUE,
	  );  

		$form['submit'] = array(
		  '#type' => 'submit',
		  '#value' => t('Save'),
		);

	  return $form;
	}else{
		drupal_access_denied();
	}

}

function smv_global_settings_setup_form_submit($form, &$form_state){
	$key  	= $form_state['values']['setup_key'];
	$value  = $form_state['values']['setup_value'];
	$desc  	= $form_state['values']['description'];

  db_insert('smv_global_settings', array())
    ->fields(array(
      'setup_key'   => $key,
      'setup_value' => $value,
      'description' => $desc,
    ))
    ->execute();

	drupal_set_message(t('Successfully saved global settings.'));
}


function smv_global_settings_setup_edit_form($form, &$form_state) {
	global $user;

	$key = arg(2);
	$record = db_query('SELECT id, description, setup_value FROM {smv_global_settings} 
		WHERE setup_key = :setup_key',array(':setup_key' => $key))->fetchObject();

	if(isset($key) && !empty($record)){	

		drupal_set_title(t($record->description));

		$form['id'] = array('#type' => 'value', '#value' => $record->id);

	  $form['setup_value'] = array(
	    '#type' => 'textfield',
	    '#title' => t('Number of days'),    
	    '#size' => 100,
	    '#maxlength' => 100,
	    '#required' => TRUE,
	    '#description' => t('Automatically delete after this many days.'),
	    '#default_value' => isset($record->setup_value) ? $record->setup_value : '',
	  );

		$form['submit'] = array(
		  '#type' => 'submit',
		  '#value' => t('Save'),
		);

	  $form['cancel'] = array(
	    '#markup' => l(t('Cancel'), "global-settings/housekeeping"),
	  );

	  return $form;

	}else{
		drupal_not_found();
	}
}	

function smv_global_settings_setup_edit_form_validate($form, &$form_state) {
 	if (!is_numeric($form_state['values']['setup_value'])) {
    form_set_error('setup_value', t('Please enter a number.'));
  }
}

function smv_global_settings_setup_edit_form_submit($form, &$form_state){
	
	$id   = $form_state['values']['id'];
	$setup_value = $form_state['values']['setup_value'];

	if(isset($setup_value) && isset($id)){

		$record = db_query('SELECT id, description, setup_value FROM {smv_global_settings} 
			WHERE id = :id',array(':id' => $id))->fetchObject();

	  $query = db_update('smv_global_settings')
	    ->fields(array('setup_value' => $setup_value))
	    ->condition('id', $id);	
	  $query->execute();	

		drupal_set_message(t(' '.$record->description.' was successfully updated.'));
		$form_state['redirect'] = 'global-settings/housekeeping';	  
	}
}