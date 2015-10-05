<?php
/**
* Implements hook_views_data_alter().
*
* @param $data
*   Information about Views' tables and fields.
*/
function smv_inlineupdate_views_data_alter(&$data) {
  // Add the Status form field
  $data['node']['field_po_status_edit'] = array(
    'field' => array(
      'title' => t('PO status form field'),
      'help' => t('Edit the node status'),
      'handler' => 'smv_inlineupdate_field_handler_status_edit',
    ),
  );
}
