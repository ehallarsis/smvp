<?php
/**
 * A Views field handler for editing a node status.
 *
*/
class smv_inlineupdate_field_handler_status_edit extends views_handler_field {

  function construct() {
    parent::construct();
    $this->additional_fields = array(   
      'nid' => array(
        'table' => 'field_data_field_po_status',
        'field' => 'entity_id',
      ),
      'field_po_status' => array(
        'table' => 'field_data_field_po_status',
        'field' => 'field_po_status_value',
      ),
    );
  }  

  function query() {
    $this->ensure_my_table();
    $this->add_additional_fields();
  }

  function render($values) {
    // Render a Views form item placeholder.
    // This causes Views to wrap the View in a form.
    // Render a Views form item placeholder.
    return '<!--form-item-' . $this->options['id'] . '--' . $this->view->row_index . '-->';
  }  


  /**
   * Add to and alter the form created by Views.
   */
  function views_form(&$form, &$form_state) {
    global $user;

    // Create a container for our replacements
    $form[$this->options['id']] = array(
      '#type' => 'container',
      '#tree' => TRUE,
    );

    $form['dialog-box'] = array(    
      '#markup' => '<div id="dialog-confirm"></div>',    
    );    

    // Iterate over the result and add our replacement fields to the form.
    foreach($this->view->result as $row_index => $row) {
      
      $nid   = $row->{$this->aliases['nid']};
      
      //get current status of po
      $node = node_load($nid);
      $field_info = field_get_items('node', $node, 'field_po_status');
      $field_org_info = field_get_items('node', $node, 'field_po_organization');
      
      $postatus = check_plain($field_info[0]['value']);    
      $org_tid = check_plain($field_org_info[0]['tid']);   

      if(in_array('internal-users', $user->roles)){
        $allowed  = array('New','Viewed');
        if(in_array($postatus, $allowed)){
          $options = array($postatus => $postatus,'Cancelled' => 'Cancelled', 'Revised'=>'Revised');
        }else{
          $options = array($postatus => $postatus);
        }        
      }

      if(in_array('siteadmin', $user->roles)){      
        if($postatus == 'For Revision'){
          $options = array('Viewed' => 'Viewed',$postatus => $postatus);
        }elseif($postatus == 'Cancelled'){
          $options = array('Viewed' => 'Viewed',$postatus => $postatus);
        }else{
          $options = array($postatus => $postatus);  
        }

        //$options = array('New' => 'New', 'Open' => 'Open', 'Paid' => 'Paid', 'Cancelled' => 'Cancelled', 'Revised'=>'Revised');
      }

      $rid = smv_usertype_internaluser_assigned_rid($user->uid);
      $rid_orgs = smv_usertype_internaluser_org_change_status($rid);

      if($rid_orgs && $rid_orgs != ''){
        $orgs = explode(',',$rid_orgs);
      }else{
        $orgs = '';
      }

      //display status dropdown form to permitted usertype
      if(user_access('smv_inlineupdate administer status') && 
        ((in_array('internal-users', $user->roles) && $orgs != '' && in_array($org_tid, $orgs)) ||
        in_array('siteadmin', $user->roles))){

        $ponumber = db_query('SELECT field_po_number_value FROM {field_data_field_po_number} 
          WHERE entity_id = :nid', 
          array(':nid' => $nid))->fetchField();

        $postatus = str_replace(' ', '_', $row->{$this->aliases['field_po_status']});

        $statusId = $nid . '-' . $postatus . '-' . $ponumber;

        $form[$this->options['id']][$row_index][$nid] = array(
          '#type' => 'select',
          '#options' => $options,
          '#default_value' => $row->{$this->aliases['field_po_status']},
          '#multiple' => FALSE,
          '#attributes' => array('id'=>$statusId, 'class' => array('po-status-change')),  
        );
      }else{
        $form[$this->options['id']][$row_index][$nid] = array(    
          '#markup' => $postatus,    
        );    
      }
    }
  }

  /**
   * Form submit method. 
   */
  function views_form_submit($form, &$form_state) {

    // Determine which nodes we need to update.
    /*$updates = array();
    foreach($this->view->result as $row_index => $row) {
      $nid   = $row->{$this->aliases['nid']}; 
      $value = $form_state['values'][$this->options['id']][$row_index][$nid];
      if ($row->{$this->aliases['field_po_status']} != $value) {
        $updates[$row->{$this->aliases['nid']}] = $value;
      }
    }

    // Grab the nodes we need to update and update them.
    $nodes = node_load_multiple(array_keys($updates));  
    foreach($nodes as $nid => $node) {
      //$node->status = $updates[$nid];
      $node->field_po_status[LANGUAGE_NONE][0]['value'] = $updates[$nid];
      node_save($node);
    }*/
  } 

}