<?php

function smv_ldap_adduser_form($form, &$form_state) {

  // Display page 2 if $form_state['page_num'] == 2
  if (!empty($form_state['page_num']) && $form_state['page_num'] == 2) {
    return smv_ldap_adduser_confirmation_form($form, $form_state);
  }

  // Otherwise we build page 1.
  $form_state['page_num'] = 1;

  $form['smv_username'] = array(
    '#type' => 'textfield',
    '#title' => t('Username'),
    '#description' => "Please enter username",
    '#size' => 100,
    '#maxlength' => 100,
    '#required' => TRUE,
    '#default_value' => !empty($form_state['values']['smv_username']) ? $form_state['values']['smv_username'] : '',
  );

  $form['smv_username_next'] = array(
    '#type' => 'submit',
    '#value' => 'Verify',
    '#validate' => array('smv_ldap_adduser_verify_form_validate'),
    '#submit' => array('smv_ldap_adduser_verify_form_submit'),    
  );
  return $form;
}

/**
 * Submit handler next button.
 *
 * Capture the values from page one and store them away so they can be used
 * at final submit time.
 */
function smv_ldap_adduser_verify_form_validate($form, &$form_state) {

  $result = db_query('SELECT s.host, s.port, s.basedn, s.user, s.password, s.cn FROM {smcfb_ldap_settings} s WHERE s.id = :id', array(':id' => 1));

  foreach ($result as $record) {
     $ldapconfig['host'] = $record->host;
     $ldapconfig['port'] = intval($record->port);
     $ldapconfig['basedn'] = $record->basedn;
     $username = $record->user;
     $password = base64_decode($record->password);
     $cn = $record->cn;
  }

  $ds = ldap_connect($ldapconfig['host'], $ldapconfig['port']);
  ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

  // service BaseDN
  $dn[] = $ldapconfig['basedn'];

  // other BaseDN
  // QUERY DB
  $result2 = db_query('SELECT b.dn FROM {smcfb_other_basedn} b');
  foreach ($result2 as $record) {
     $dn[] = $record->dn;
  }

  $baseDN = "CN=" . $cn . "," . $ldapconfig['basedn'];
  $bind=ldap_bind($ds, $baseDN, $password);

  if ($bind) {
    // Match connection elements to count of dn elements
    for($x=0; $x < count($dn); $x++) {
        $conn[] = $ds;
    }

    $person = $form_state["values"]["smv_username"];

    if(smv_vendor_accounts_existing_username($person)) {
      form_set_error('smv_username', t('User account already exists.'));
    }      

    $filter="(|(cn=$person)(name=$person)(samaccountname=$person))"; 
    
    if(!($result = @ldap_search($conn, $dn, $filter))) { //uncomment later
      form_set_error('smv_username', t("Unable to search ldap server"));
    } else {

      $search = false; 

      foreach ($result as $value) { 
        if(ldap_count_entries($ds, $value) > 0){ 
          $search = $value; 
          break; 
        } 
      }

      if ($search) {

        $info = ldap_get_entries($ds, $search);
        // query to check if account has already been creted
        $n = db_query("SELECT count(uid) FROM {users} WHERE name = '" . $form_state['values']['smv_username'] . "'")->fetchField();
        // set error message if so
        if ($n > 0) form_set_error('smv_username', t('User account already exists.'));

        $user = array();
        for ($i=0; $i<$info["count"]; $i++) {
          $user['cn'] = $info[$i]["cn"][0];
          //$user['distinguishedname'] = $info[$i]["distinguishedname"][0];
          $user['name'] = $info[$i]["name"][0];
          $user['mail'] = isset($info[$i]["mail"]) ? $info[$i]["mail"][0] : '';
          $user['samaccountname'] = $info[$i]["samaccountname"][0];

          $rows[] = array(
            array('data' => t('Username:')),
            array('data' => t($user['samaccountname'])),
          );
          $rows[] = array(
            array('data' => t('Name:')),
            array('data' => t($user['cn'])),
          );
          $rows[] = array(
            array('data' => t('Email Address:')),
            array('data' => t($user['mail'])),  
          );

          //$html_data = "<p>LDAP Information</p>";
          $html_data = theme('table', array('rows' => $rows));
        }
        $form_state['ldap_html_data'] = $html_data;
        $form_state['user_data'] = $user;
      } else {
        form_set_error('smv_username', t('Unable to find account in Active Directory.'));
      }
    }
  } else {
    form_set_error('smv_username', t('Unable to connect to LDAP server.'));
  } 

  ldap_close($ds);
}


/**
 * Validate handler for the next button on first page.
 */
function smv_ldap_adduser_verify_form_submit($form, &$form_state) {

  // Values are saved for each page.
  // to carry forward to subsequent pages in the form.
  // and we tell FAPI to rebuild the form.
  $form_state['page_values'][1] = $form_state['values'];

  if (!empty($form_state['page_values'][2])) {
    $form_state['values'] = $form_state['page_values'][2];
  }

  // When form rebuilds, it will look at this to figure which page to build.
  $form_state['page_num'] = 2;
  $form_state['rebuild'] = TRUE;

}


/**
 * Returns the form for the second page.
 */ 
function smv_ldap_adduser_confirmation_form($form, &$form_state) {

  drupal_set_title(t('User Information'));

  $form['smv_ldap_info'] = array(
    '#type' => 'item',
    '#markup' => $form_state['ldap_html_data'],
  );
    
  $option_roles = array();
  $dbroles = db_query("SELECT rid, name FROM {role} 
    WHERE rid != 1 AND rid != 2 AND rid != 3 AND rid != 5 AND rid != 7 AND rid != 12 and rid != 13 AND rid != 19 ORDER BY name");

  $option_roles[12] = 'Site Administrator';
  if($dbroles){
    foreach ($dbroles as $dbrole) {
      $option_roles[$dbrole->rid] = $dbrole->name;
    }  
  }

  //Assign role
  $form['role'] = array(
    '#type' => 'select',
    '#title' => t(''),
    '#options' => $option_roles,  
    '#empty_option' => t('- Role -'),
    //'#required' => TRUE,
  );

  $purch_organizations = taxonomy_get_tree(6);
  $option_purchorg = array(); 
  foreach ($purch_organizations as $org) {
    $option_purchorg[$org->tid] = $org->name;
  }

  $form['purchasing_org'] = array(
    '#type' => 'select',
    '#title' => t(''),
    '#options' => $option_purchorg,
    '#empty_option' => t('- Purchasing Organization -'),
    '#states' => array(
      'disabled' => array(
        ':input[name="role"]' => array('value' => 12),
      ),                   
    ),        
  );

  /*If purchasing is selected, allow selection of purchasing group
  $purch_organizations = taxonomy_get_tree(6);
  $option_purchorg = array(); 
  foreach ($purch_organizations as $org) {
    $option_purchorg[$org->tid] = $org->name;
  } 
  $form['purchasing_org'] = array(
    '#type' => 'select',
    '#title' => t('Purchasing group'),
    '#options' => $option_purchorg, 
    '#states' => array(
      'visible' => array( 
        ':input[name="role"]' => array('value' => 8),
      ),
    ),
  );*/

  $form['smv_ldap_adduser_confirm'] = array(
    '#type' => 'submit',
    '#value' => t('Submit'),
    '#submit' => array('smv_ldap_adduser_confirmation_form_submit'),
  );
  $form['smv_ldap_adduser_back'] = array(
    '#type' => 'submit',
    '#value' => t('Back'),
    '#submit' => array('smv_ldap_adduser_confirmation_form_back'),
    // We won't bother validating the required 'color' field, since they
    // have to come back to this page to submit anyway.
    '#limit_validation_errors' => array(),
  );

   $form['#validate'][] = 'smv_ldap_adduser_confirmation_validate';  

  return $form;  
}

function smv_ldap_adduser_confirmation_validate($form, &$form_state){
  if(!isset($form_state['values']['role']) || $form_state['values']['role'] == ''){
    form_set_error('role', t('Please select a user role.'));
  }
}

/**
 * Back button handler submit handler.
 *
 * Since #limit_validation_errors = array() is set, values from page 2
 * will be discarded. We load the page 1 values instead.
 */
function smv_ldap_adduser_confirmation_form_back($form, &$form_state) {
  $form_state['values'] = $form_state['page_values'][1];
  $form_state['page_num'] = 1;
  $form_state['rebuild'] = TRUE;
}

/**
 * The page 2 submit handler.
 *
 * This is the final submit handler. Gather all the data together and output
 * it in a drupal_set_message().
 */
function smv_ldap_adduser_confirmation_form_submit($form, &$form_state) {

  $page_one_values = $form_state['page_values'][1];

  $rid = $form_state['values']['role'];

  if($rid == 12){
    $roles = array($rid => TRUE);
  }else{
    $roles = array($rid => TRUE, 19 => TRUE);
  }
  
  $tid = isset($form_state['values']['purchasing_org']) ? $form_state['values']['purchasing_org'] : '';

  $new_user = array(
    'name' => $form_state['user_data']['samaccountname'],
    'pass' => '',
    'mail' => $form_state['user_data']['mail'],
    'init' => $form_state['user_data']['mail'],
    'field_internalusers_fullname' => array(LANGUAGE_NONE => array(array('value' => $form_state['user_data']['cn']))),
    'status' => 1,
    'access' => REQUEST_TIME,
    'roles' => $roles,
    'field_internalusers_pcode' => ($tid != '') ? array(LANGUAGE_NONE => array(array('tid' => $tid))) : '',
  );

  $account = user_save(null, $new_user);

  $form_state['redirect'] = url('company-users', array('absolute' => true));
  drupal_set_message(t('The form has been submitted. Added @username.', 
    array('@username' => $page_one_values['smv_username'])));  
  
}   