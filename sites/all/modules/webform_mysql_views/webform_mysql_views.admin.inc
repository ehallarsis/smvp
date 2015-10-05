<?php

/**
 * Form builder for the Webform MySQL Views admin form.
 *
 * Implemented as its own admin page instead of integrating with the node
 * form because of chicken-and-egg logic hassles.
 *
 * @see webform_mysql_views_admin_form_submit()
 * @ingroup forms
 */
function webform_mysql_views_admin_form($form, &$form_state) {

  $meets_reqs = _webform_mysql_views_check_requirements();
  if ($meets_reqs !== TRUE) {
    $output = '<strong>'. t("This module will not work on this site.") .'</strong>';
    $output .= theme('item_list', array('items' => $meets_reqs), t('Details'));
    return array("error" => array('#markup' => $output));
  }

  $form['pre'] = array('#markup' => '<table>
    <thead>
      <tr>
        <th><input type="checkbox" id="webform-select-all"></th>
        <th>Webform Name</th>
        <th>View Name</th>
      </tr>
    </thead>
    <tbody>');

  // Get list of webform-enabled content types
  $types = webform_variable_get('webform_node_types', array('webform'));

  // Get list of nids that already have views
  $views = variable_get('webform_mysql_views_views', array());
  $existing_view_nids = array_keys($views);
  $form_state['view_nids'] = array();
  // Get list of all webform nodes
  $nodes = db_query('SELECT nid, title FROM {node}
    WHERE type IN (:types) AND status = :status
    ORDER BY nid', array(':status' => NODE_PUBLISHED, ':types' => $types));
  $count = 0;
  foreach ($nodes as $node) {
    $form_state['view_nids'][] = $node->nid;
    $form['webform_enabled_' . $node->nid] = array(
      '#type' => 'checkbox',
      '#title' => '',
      '#default_value' => in_array($node->nid, $existing_view_nids),
      '#prefix' => '<tr class="' . ($count++ % 2 ? 'even' : 'odd') . '"><td>',
      '#suffix' => '</td>',
    );
    $form['webform_title_' . $node->nid] = array('#markup' => "<td>{$node->title}</td>");

    $form['webform_view_name_' . $node->nid] = array(
      '#type' =>'textfield',
      '#default_value' => isset($views[($node->nid)]) ? $views[($node->nid)] : webform_mysql_views_get_view_name($node->title, $node->nid),
      '#prefix' => '<td>',
      '#suffix' => '</td></tr>',
    );
  }
  $form['post'] = array('#markup' => '</tbody></table>');

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Submit'),
  );

  return $form;

}

/**
 * Form submission handler for the admin form.
 *
 * @see webform_mysql_views_admin_form()
 */
function webform_mysql_views_admin_form_validate($form, &$form_state) {

  $name_changed = $submitted_nids = array();
  $views = variable_get('webform_mysql_views_views', array());
  foreach ($form_state['view_nids'] as $nid) {
    if (!empty($form_state['values']['webform_enabled_' . $nid])) {
      $view_name = $form_state['values']["webform_view_name_$nid"];
      $count = 0;
      preg_replace('/[^a-z0-9_]/', '', $view_name, -1, $count);
      if ($count) {
        form_set_error("webform_view_name_$nid", t('The custom view name -- %table -- can only contain underscores and alphanumeric characters.', array('%table' => $view_name)));
      }
      elseif (strlen($view_name) == 0) {
        form_set_error("webform_view_name_$nid", t('Please enter a value for all your views.'));
      }
      elseif (strlen($view_name) > 64) {
        form_set_error("webform_view_name_$nid", t('The custom view name -- %table -- is too long. Please shorten it to be less than 64 characters.', array('%table' => $view_name)));
      }
      elseif ((empty($views[$nid]) || $views[$nid] != $view_name) &&
        db_table_exists($view_name)) {
        form_set_error("webform_view_name_$nid", t('The custom view name -- %table -- already exists in your Drupal database. Please rename the view.', array('%table' => $view_name)));
      }
    }
  }
}

/**
 * Form submission handler for the admin form.
 *
 * @see webform_mysql_views_admin_form()
 */
function webform_mysql_views_admin_form_submit($form, &$form_state) {
  global $db_prefix;

  // Load existing view info
  $webform_views = $previous_webform_views = variable_get('webform_mysql_views_views', array());
  $webform_view_nids = array_keys($webform_views);

  // Parse submitted settings;
  $submitted_nids = array();
  foreach ($form_state['view_nids'] as $nid) {
    if (!empty($form_state['values']['webform_enabled_' . $nid])) {
      $submitted_nids[] = $nid;
      drupal_set_message($nid);
    }
  }

  // drop all submitted and old views
  foreach (array_merge($submitted_nids, $webform_view_nids) as $nid) {
    if (!isset($webform_views[$nid])) {
      continue;
    }
    // (Drop the prefix from the stored view name and let the DB API handle it)
    $query = "DROP VIEW IF EXISTS {" . substr($webform_views[$nid], strlen($db_prefix)) . "}";
    db_query($query);
    unset($webform_views[$nid]);
  }
  if (count($drop_nids)) {
    drupal_set_message(
      format_plural(count($drop_nids), t('Dropped 1 view.'), t('Dropped @count views.'),
      array('@count' => count($drop_nids))));
  }

  foreach ($submitted_nids as $nid) {
    webform_mysql_views_rebuild($nid, TRUE, $form_state['values']["webform_view_name_$nid"]);
    $webform_views[$nid] = $form_state['values']["webform_view_name_$nid"];
  }

  if (count($new_nids)) {
    drupal_set_message(
      format_plural(count($new_nids),
      t('Created 1 view.'),
      t('Created @count views.'),
      array('@count' => count($drop_nids)))
    );
  }

  // save the webform_views variable
  variable_set('webform_mysql_views_views', $webform_views);
}
