<?php

function smv_usertype_form($form, &$form_state){
  
   if (!empty($form_state['build_info']['args'])) {
    $rid = $form_state['build_info']['args'][0];
    $role_info = user_role_load($rid);

    $restricted = smv_usertype_restricted_role_ids();

    if (!empty($role_info) && !array_key_exists($rid,$restricted)) {
      $page_title = t("Edit User Type");
      drupal_set_title($page_title);
      $form['rid'] = array('#type' => 'value', '#value' => $rid);
    } else {
      drupal_not_found();
      drupal_exit();
    }
    $current_role = array($rid=>$role_info->name);
    $current_perms = user_role_permissions($current_role);
    $perms_array = $current_perms[$rid];
  }else{
    $perms_array = array();
  }

  $form['name'] = array(
    '#title' => 'Name',
    '#type' => 'textfield',
    '#required' => TRUE,
    '#default_value' => !empty($role_info) ? $role_info->name : '',
    '#rules' => array(
      array('rule' => 'alpha_dash', 'error'=>'Use only alpha numerics, hyphen and underscore at %field field.'),
    ),   
  );

  $form['markup_user_access'] = array(
    '#type' => 'markup',
    '#markup' => '<span>Choose User Access:</span>',    
  );

  $form['smv_po_view_all'] = array(
    '#type' => 'checkbox',
    '#title' => t('Purchase Order - View All'),
    '#description' => t('<span>Grants access to "Purchase Order" module. This allows viewing of all Purchase Order.</span>'),
    '#default_value' => _smv_usertype_role_has_permission($perms_array, 'smv_po_view_all'),
    '#states' => array(
      'disabled' => array(
        ':input[name="smv_po_view_by_po_org"]' => array('checked' => TRUE),
      ),                   
    ),      
  );  

  $form['smv_po_view_by_po_org'] = array(
    '#type' => 'checkbox',
    '#title' => t('Purchase Order - View by PO Organization'),
    '#description' => t('<span>Grants access to "Purchase Order" module. This allows viewing of Purchase Order under logged-in user PO organization.</span>'),
    '#default_value' => _smv_usertype_role_has_permission($perms_array, 'smv_po_view_by_po_org'),
    '#states' => array(
      'disabled' => array(
        ':input[name="smv_po_view_all"]' => array('checked' => TRUE),
      ),                   
    ),    
  );

  $form['smv_po_change_status'] = array(
    '#type' => 'checkbox',
    '#title' => t('Purchase Order - Change Status'),
    '#description' => t('<span>This allows changing of Purchase Order status to "Cancelled" or "Revised".</span>'),
    '#default_value' => _smv_usertype_role_has_permission($perms_array, 'smv_inlineupdate administer status'),  
  );  

  $purch_organizations = taxonomy_get_tree(6);
  $option_purchorg = array(); 
  foreach ($purch_organizations as $org) {
    $option_purchorg[$org->tid] = $org->name;
  }

  if(isset($rid)){
    $orgs = db_query('SELECT org_tids FROM {smv_usertype_org_status_change}
      WHERE rid = :rid', array(':rid' => $rid))->fetchField();    
    $org_tids = explode(',', $orgs);
  }else{  
    $org_tids = array();
  }

  $form['smv_po_change_status_org'] = array(
    '#title' => t('Allow change of PO Status in selected PO Organization:'),
    '#type' => 'checkboxes',
    '#options' => $option_purchorg,
    '#prefix' => '<div class="po-org-wrapper">',
    '#suffix' => '</div>',
    '#states' => array(
      'enabled' => array(
        ':input[name="smv_po_change_status"]' => array('checked' => TRUE),
      ),                   
    ),  
    '#default_value' => $org_tids,  
  );

  $form['smv_nonpo_view_all'] = array(
    '#type' => 'checkbox',
    '#title' => t('Non PO - View All'),
    '#description' => t('<span>Grants access to "Non PO" module. This allows user to view all Non-PO.</span>'),
    '#default_value' => _smv_usertype_role_has_permission($perms_array, 'smv_nonpo_view_all'),
  );

  $form['smv_notifications_view_own'] = array(
    '#type' => 'checkbox',
    '#title' => t('Notifications - View'),
    '#description' => t('<span>Grants access to "Notifications" module. This is a facility to enable user view own site notifications.</span>'),
    '#default_value' => _smv_usertype_role_has_permission($perms_array, 'smv_notifications_view_own'),  
  );

  $form['smv_payment_voucher_view_all'] = array(
    '#type' => 'checkbox',
    '#title' => t('Payment Voucher - View All'),
    '#description' => t('<span>Grants access to "Payment Voucher" module. This allows user to view all Payment Voucher.</span>'),
    '#default_value' => _smv_usertype_role_has_permission($perms_array, 'smv_payment_voucher_view_all'),
  );

  $form['smv_bir_view_all'] = array(
    '#type' => 'checkbox',
    '#title' => t('BIR Form 2307 - View All'),
    '#description' => t('<span>Grants access to "BIR" module. This allows user to view all BIR Form 2307.</span>'),
    '#default_value' => _smv_usertype_role_has_permission($perms_array,'smv_bir_view_all'),
  );      

  $form['smv_archive_po_view_all'] = array(
    '#type' => 'checkbox',
    '#title' => t('Archive - Purchase Order'),
    '#description' => t('<span>Grants access to "Archive" module. This allows user to view archive Purchase Order.</span>'),
    '#default_value' => _smv_usertype_role_has_permission($perms_array,'smv_archive_po_view_all'),
  );

  $form['smv_archive_nonpo_view_all'] = array(
    '#type' => 'checkbox',
    '#title' => t('Archive - Non PO'),
    '#description' => t('<span>Grants access to "Archive" module. This allows user to view all archive Non PO.</span>'),
    '#default_value' => _smv_usertype_role_has_permission($perms_array,'smv_archive_nonpo_view_all'),
  );

  $form['smv_archive_pv_view_all'] = array(
    '#type' => 'checkbox',
    '#title' => t('Archive - Payment Voucher'),
    '#description' => t('<span>Grants access to "Archive" module. This allows user to view all archive Payment Voucher.</span>'),
    '#default_value' => _smv_usertype_role_has_permission($perms_array,'smv_archive_pv_view_all'),
  );

  $form['smv_archive_bir_view_all'] = array(
    '#type' => 'checkbox',
    '#title' => t('Archive - BIR Form 2307'),
    '#description' => t('<span>Grants access to "Archive" module. This allows user to view all archive BIR Form 2307</span>'),
    '#default_value' => _smv_usertype_role_has_permission($perms_array,'smv_archive_bir_view_all'),
  );

  $form['smv_reports_po_monitoring'] = array(
    '#type' => 'checkbox',
    '#title' => t('Reports - PO Monitoring'),
    '#description' => t('<span>Grants access to "Reports" module. This allows user to view PO Monitoring report.</span>'),
    '#default_value' => _smv_usertype_role_has_permission($perms_array,'smv_reports_po_monitoring'),
  );  

  $form['smv_reports_payment_list'] = array(
    '#type' => 'checkbox',
    '#title' => t('Reports - Payment List'),
    '#description' => t('<span>Grants access to "Reports" module. This allows user to view Payment List report.</span>'),
    '#default_value' => _smv_usertype_role_has_permission($perms_array,'smv_reports_payment_list'),
  );

  $form['smv_reports_pending_or'] = array(
    '#type' => 'checkbox',
    '#title' => t('Reports - Pending OR'),
    '#description' => t('<span>Grants access to "Reports" module. This allows user to view Pending OR report.</span>'),
    '#default_value' => _smv_usertype_role_has_permission($perms_array,'smv_reports_pending_or'),
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
  );  

  $form['cancel'] = array(
    '#markup' => l(t('Cancel'), 'manage-usertype'),
  );  

  return $form;
}

function smv_usertype_form_validate($form, &$form_state){

  // if new and is duplicate
  if (empty($form_state['values']['rid']) && user_role_load_by_name($form_state['values']['name'])) {
    form_set_error('name', t('%role role already exists.', array('%role' => $form_state['values']['name'])));
  }

  // if not new, is duplicate
  if (!empty($form_state['values']['rid']) && ($role = user_role_load_by_name($form_state['values']['name']))) {
    if($form_state['values']['rid'] != $role->rid) {
      form_set_error('name', t('%role role already exists.', array('%role' => $form_state['values']['name'])));
    }
  }

  $po_viewall = $form_state['values']['smv_po_view_all'];
  $po_byorg = $form_state['values']['smv_po_view_by_po_org'];
  $po_statuschange = $form_state['values']['smv_po_change_status'];
  $nonpo = $form_state['values']['smv_nonpo_view_all'];
  $notif = $form_state['values']['smv_notifications_view_own'];
  $payment_voucher = $form_state['values']['smv_payment_voucher_view_all'];
  $bir = $form_state['values']['smv_bir_view_all'];

  $archive_po = $form_state['values']['smv_archive_po_view_all'];
  $archive_nonpo = $form_state['values']['smv_archive_nonpo_view_all'];
  $archive_pv = $form_state['values']['smv_archive_pv_view_all'];
  $archive_bir = $form_state['values']['smv_archive_bir_view_all'];
  
  $reports_pomonitoring = $form_state['values']['smv_reports_po_monitoring'];
  $reports_paymentlist = $form_state['values']['smv_reports_payment_list'];
  $reports_pendingor = $form_state['values']['smv_reports_pending_or'];

  if($po_statuschange == 1){
    $po_statuschange_org_selected = array();

    foreach($form_state['values']['smv_po_change_status_org'] as $key => $value){
      if($value != 0){
        $po_statuschange_org_selected[] = $key;
      }else{ continue; }    
    }

    if(empty($po_statuschange_org_selected)){
      form_set_error('name', t('Please select PO Organization.'));
    }  
  }

  if($po_viewall == 0 && $po_byorg == 0 && $po_statuschange == 1){
    form_set_error('name', t('Access to "Purchase Order - View All" or "Purchase Order - View by PO Organization" is required to allow "Purchase Order - Change Status".'));
  }

  if($po_viewall == 0 && $po_byorg == 0 && $po_statuschange == 0 && $nonpo == 0 && $notif == 0 && $payment_voucher == 0 && $bir == 0 && $archive_po == 0 && $archive_nonpo == 0 && $archive_pv == 0 && $archive_bir == 0 && $reports_pomonitoring == 0 && $reports_paymentlist == 0 && $reports_pendingor == 0){
    form_set_error('name', t('Please select user access.'));
  }

}

function _smv_usertype_role_has_permission($roleperms = array(), $data){

  if(!empty($roleperms)){
    if(array_key_exists($data,$roleperms)){
      return 1;
    }else{
      return 0;
    }
  
  }else{
    return 0;
  }

}

function smv_usertype_form_submit($form, &$form_state){

  $rolename  = $form_state['values']['name'];

  if(!isset($rolename))
    return false;

  //Selected PO Organization
  $po_statuschange_org_selected = array();
  foreach($form_state['values']['smv_po_change_status_org'] as $key => $value){
    if($value != 0){
      $po_statuschange_org_selected[] = $key;
    }else{continue;}
  }  

  if(!empty($po_statuschange_org_selected)){
    $po_orgs = implode(',', $po_statuschange_org_selected);
  }else{
    $po_orgs = '';
  }
  
  //New Record
  if(empty($form_state['values']['rid'])){
    $role = new stdClass();
    $role->name = $rolename;
    $newrole = user_role_save($role);

    $record = user_role_load_by_name($rolename); 
    $rid = $record->rid;   

    db_insert('smv_usertype_org_status_change', array())->fields(array('rid'=> $rid,'org_tids' => $po_orgs))->execute();      
  }

  if(!empty($form_state['values']['rid'])){

    $rid = $form_state['values']['rid'];

    $role = new stdClass();
    $role->name = $rolename;
    $role->rid  = $rid;
    $updatedrole = user_role_save($role);

    $record = user_role_load_by_name($rolename); 

    $orgs_status = db_query('SELECT id FROM {smv_usertype_org_status_change}
      WHERE rid = :rid', array(':rid' => $rid))->fetchField();    

    if(!$orgs_status){
      db_insert('smv_usertype_org_status_change', array())->fields(array('rid'=> $rid,'org_tids' => $po_orgs))->execute(); 
    }else{
      db_update('smv_usertype_org_status_change')->fields(array('org_tids' => $po_orgs))->condition('rid', $rid)->execute();
    } 
  }

  // Define role permissions
  $permissions = array(
    'smv_po_view_all' => ($form_state['values']['smv_po_view_all'] == 1) ? TRUE : FALSE, 
    'smv_po_view_by_po_org' => ($form_state['values']['smv_po_view_by_po_org'] == 1) ? TRUE : FALSE, 
    'smv_inlineupdate administer status' => ($form_state['values']['smv_po_change_status'] == 1) ? TRUE : FALSE, 
    'smv_nonpo_view_all' => ($form_state['values']['smv_nonpo_view_all'] == 1) ? TRUE : FALSE, 
    'smv_notifications_view_own' => ($form_state['values']['smv_notifications_view_own'] == 1) ? TRUE : FALSE, 
    //'smv_notifications_view_all' => ($form_state['values']['smv_notifications_view_all'] == 1) ? TRUE : FALSE, 
    'smv_payment_voucher_view_all' => ($form_state['values']['smv_payment_voucher_view_all'] == 1) ? TRUE : FALSE, 
    'smv_bir_view_all' => ($form_state['values']['smv_bir_view_all'] == 1) ? TRUE : FALSE, 
    //'smv_archive_view_all' => ($form_state['values']['smv_archive_view_all'] == 1) ? TRUE : FALSE,
    'smv_archive_po_view_all' => ($form_state['values']['smv_archive_po_view_all'] == 1) ? TRUE : FALSE,
    'smv_archive_nonpo_view_all' => ($form_state['values']['smv_archive_nonpo_view_all'] == 1) ? TRUE : FALSE,
    'smv_archive_pv_view_all' => ($form_state['values']['smv_archive_pv_view_all'] == 1) ? TRUE : FALSE,
    'smv_archive_bir_view_all' => ($form_state['values']['smv_archive_bir_view_all'] == 1) ? TRUE : FALSE,
    'smv_reports_po_monitoring' => ($form_state['values']['smv_reports_po_monitoring'] == 1) ? TRUE : FALSE,
    'smv_reports_payment_list' => ($form_state['values']['smv_reports_payment_list'] == 1) ? TRUE : FALSE,
    'smv_reports_pending_or' => ($form_state['values']['smv_reports_pending_or'] == 1) ? TRUE : FALSE,
  );

  user_role_change_permissions($rid, $permissions);

  if (!isset($form_state['values']['rid'])) {
    drupal_set_message(t('Successfully added %role role.', array('%role' => $record->name)));
  } else {
    drupal_set_message(t('Successfully updated %role role.', array('%role' => $record->name)));
  } 

  $form_state['redirect'] = 'manage-usertype';
}

/**
 * Delete role form
 */
function smv_usertype_delete_form($form, &$form_state) {
  
  $rid = $form_state['build_info']['args'][0];
  $role = user_role_load($rid);


  $user_role = db_query('SELECT uid from {users_roles} where rid = :rid', array('rid'=>$rid))->fetchField();

  if($user_role){
    drupal_access_denied();
  }

  $restricted = smv_usertype_restricted_role_ids();

  if (empty($role) || array_key_exists($rid,$restricted) ) {
    drupal_not_found();
    drupal_exit();
  }

  $manage_link = url('manage-usertype', array('absolute'=>TRUE));

  $form['rid_to_delete'] = array('#type' => 'value', '#value' => $role->rid);
  $form['name_to_delete'] = array('#type' => 'value', '#value' => $role->name);

  return confirm_form($form, 
    t('Are you sure you want to delete user type %role?', array('%role' => $role->name)),
    $manage_link,
    t('This action cannot be undone.'),
    t('Delete'),
    t('Cancel')
  );

  return $form;
}

/**
 * Implements hook_form_submit().
 */
function smv_usertype_delete_form_submit($form, &$form_state) {

  $rid = $form_state['values']['rid_to_delete'];
  $name = $form_state['values']['name_to_delete'];

  if (isset($rid)) {
    // delete role
    $result = user_role_delete((int) $rid);
    
    $orgs_status = db_query('SELECT org_tids FROM {smv_usertype_org_status_change}
      WHERE rid = :rid', array(':rid' => $rid))->fetchField();    
    if($orgs_status){
      db_delete('smv_usertype_org_status_change')->condition('rid', $rid)->execute();
    }
  }

  $form_state['redirect'] = 'manage-usertype';
  drupal_set_message(t('Successfully deleted %role user type.', array('%role' => $name)));
}


function smv_usertype_restricted_role_ids(){

  $result = array(
    1 => 'anonymous user',
    2 => 'authenticated user',
    3 => 'administrator',
    5 => 'vendor',
    12 => 'siteadmin',
    13 => 'require-agreement',
    19 => 'internal-users',
  );

  return $result;
}