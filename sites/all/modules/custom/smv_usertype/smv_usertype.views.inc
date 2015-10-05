<?php
/**
 * Implements hook_views_data()
 *
 */
function smv_usertype_views_data() {

 $table = array(
    'role' => array(  
      'table' => array(
        'group' => 'Roles', 
            'base' => array(
              'field' => 'rid', 
              'title' => 'Roles',
              'help' => 'Role database table'
            ),
        ),
      
        'rid' => array(  
          'title' => t('Role Id'),
          'help' => t('Role Id field'),     
          'field' => array(
            'click sortable' => TRUE,  
          ),      
          'filter' => array(
            'handler' => 'views_handler_filter_numeric'   
          ),  
          'sort' => array(
            'handler' => 'views_handler_sort'       
          )
        ),

        'name' => array(      
          'title' => t('Role Title'),
          'help' => t('Role title field'),
          'field' => array(
            'click sortable' => TRUE,
          ),
          'filter' => array(
            'handler' => 'views_handler_filter_string'
          ),
          'sort' => array(
            'handler' => 'views_handler_sort'
          )
        ),

        'weight' => array(      
          'title' => t('Role Weight'),
          'help' => t('Role Weight field'),
          'field' => array(
            'click sortable' => TRUE,
          ),
          'filter' => array(
            'handler' => 'views_handler_filter_numeric'
          ),
          'sort' => array(
            'handler' => 'views_handler_sort'
          )
        )  
    )
  );
  
  return $table;
}