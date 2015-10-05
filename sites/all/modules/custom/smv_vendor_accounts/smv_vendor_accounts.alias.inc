<?php

function smv_vendor_accounts_delete_confirm($form, &$form_state){
	global $user;

	$currentuser_vendorno = smv_vendor_accounts_vendoraccount_number();
	$currentuser_main			= smv_vendor_accounts_vendoraccount_main();

	$uid = arg(3);

	$account_vendorno = smv_vendor_accounts_vendoraccount_number($uid);

	if($currentuser_main == 1 && in_array('vendor', $user->roles) && $currentuser_vendorno == $account_vendorno && isset($uid)){

		$account_vendor = user_load($uid);
		$otheraccounts_link = url('other-accounts', array('absolute'=>TRUE));

		$form['uid_to_delete'] = array('#type' => 'value', '#value' => $account_vendor->uid);
		$form['name_to_delete'] = array('#type' => 'value', '#value' => $account_vendor->name);

	 	return confirm_form($form, 
	    t('Are you sure you want to delete %username?', array('%username' => $account_vendor->name)),
	    $otheraccounts_link,
	    t('This action cannot be undone.'),
	    t('Delete'),
	    t('Cancel')
	  );
	}else{
		drupal_access_denied();
	}
}

function smv_vendor_accounts_delete_confirm_submit($form, &$form_state) {
 	global $user;

 	$uid = $form_state['values']['uid_to_delete'];
	$name = $form_state['values']['name_to_delete'];

	$currentuser_vendorno = smv_vendor_accounts_vendoraccount_number();
	$currentuser_main			= smv_vendor_accounts_vendoraccount_main(); 	

	$account_vendorno = smv_vendor_accounts_vendoraccount_number($uid);

	if($currentuser_main == 1 && in_array('vendor', $user->roles) && $currentuser_vendorno == $account_vendorno && isset($uid)){	
		if(user_delete($uid)){
			drupal_set_message(t('User has been deleted successfully.'), 'status');
		}
	}

 	$form_state['redirect'] = 'other-accounts';
}

/*function smv_vendor_accounts_edit_form($form, &$form_state){
	global $user;

	$currentuser_vendorno = smv_vendor_accounts_vendoraccount_number();
	$currentuser_main			= smv_vendor_accounts_vendoraccount_main();
	
	$uid = arg(3);

	$account_vendorno = smv_vendor_accounts_vendoraccount_number($uid);

	if($currentuser_main == 1 && in_array('vendor', $user->roles) && $currentuser_vendorno == $account_vendorno && isset($uid)){	
		
		$account_vendor 	= user_load($uid);

    $alias_list_link = url('other-accounts', array('absolute'=>TRUE));

    $form['vendor-otheraccounts-link'] = array(    
      '#markup' => '<div class="otheraccounts"><a href="'.$alias_list_link.'">Back to Other Accounts list</a></div>',    
    );

		$form['uid'] = array('#type' => 'value', '#value' => $account_vendor->uid);

		$form['name'] = array(
	    '#title' => 'Alias',
	    '#type' => 'textfield',
	    '#required' => TRUE,
	    '#default_value' => isset($account_vendor->name) ? $account_vendor->name : '',
		);
		
		$form['mail'] = array(
		  '#title' => 'Email Address',
		  '#type' => 'textfield',
		  '#required' => TRUE,
		  '#default_value' => isset($account_vendor->mail) ? $account_vendor->mail : '',
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


function smv_vendor_accounts_edit_form_validate($form, &$form_state){
	
	$alias = $form_state['values']['name'];
	$mail = $form_state['values']['mail'];
	$uid 	 = $form_state['values']['uid'];

	//check if alias already exists as username
	$entry_username = db_query('SELECT uid FROM {users} WHERE LOWER(name) = LOWER(:alias) AND uid != :uid', 
		array('alias'=>$alias, 'uid'=>$uid))->fetchField();	
	if($entry_username){	
	  form_set_error('name', t('Alias already exists.'));
	}	

	//check if alias already exists
	$entry_alias = db_query('SELECT entity_id from {field_data_field_vendor_alias} 
		WHERE LOWER(field_vendor_alias_value) = LOWER(:alias)', array('alias'=>$alias))->fetchField();	
	if($entry_alias) {	
	  form_set_error('name', t('Alias already exists.'));
	}	

	if(!valid_email_address($mail)){
		form_set_error('mail', t('Please enter a valid email address.'));
	}

	//check if email already exists
	$entry_email = db_query('SELECT uid FROM {users} WHERE LOWER(mail) = LOWER(:mail) AND uid != :uid', 
		array('mail'=>$mail, 'uid'=>$uid))->fetchField();		
	if($entry_email){	
	  form_set_error('mail', t('E-mail Address already exists.'));
	}	

	$entry_email2 = db_query('SELECT entity_id from {field_data_field_vendor_email2} 
		WHERE LOWER(field_vendor_email2_value) = LOWER(:mail)', array('mail'=>$mail))->fetchField();
	if($entry_email2){	
	  form_set_error('mail', t('E-mail Address already exists.'));
	}	
}

function smv_vendor_accounts_edit_form_submit($form, &$form_state){
	global $user;

	$account = user_load($user->uid);

	$account_main_info = field_get_items('user', $account, 'field_vendor_main_account');
	$vendor_main = check_plain($account_main_info[0]['value']);

	if($vendor_main == 1 && in_array('vendor', $user->roles)){
		$name  = $form_state['values']['name'];
		$mail  = $form_state['values']['mail'];
		$uid   = $form_state['values']['uid'];

		$vendor_account = user_load($uid); 
		$update = array(
		  'name' => $name,
		  'mail' => $mail,
		);
		user_save($vendor_account, $update);

		drupal_set_message(t('Alias successfully updated.'));
		$form_state['redirect'] = 'other-accounts';
	}
}	*/