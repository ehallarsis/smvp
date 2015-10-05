<?php
/**
 * Implements hook_views_data()
 *
 */
function smv_global_settings_views_data() {

 $table = array(
    'smv_global_settings' => array(  
      'table' => array(
        'group' => 'Global Settings', 
            'base' => array(
              'field' => 'id', 
              'title' => 'Global Settings',
              'help' => 'Global settigs database table'
            ),
        ),
        'id' => array(  
          'title' => t('Settings Id'),
			    'help' => t('The primary key of the global settings table.'),
			    'field' => array(
			      'handler' => 'views_handler_field',
			      'click sortable' => TRUE,
			    ),
			    'sort' => array(
			      'handler' => 'views_handler_sort',
			    ),
			    'filter' => array(
			      'handler' => 'views_handler_filter_string',
			    ),
			    'argument' => array(
			      'handler' => 'views_handler_argument_string',
			    )
        ),
        'setup_key' => array(      
          'title' => t('Settings Key'),
          'help' => t('Settings key field'),
			    'field' => array(
			      'handler' => 'views_handler_field',
			      'click sortable' => TRUE,
			    ),
			    'sort' => array(
			      'handler' => 'views_handler_sort',
			    ),
			    'filter' => array(
			      'handler' => 'views_handler_filter_string',
			    ),
			    'argument' => array(
			      'handler' => 'views_handler_argument_string',
			    )
        ),
        'setup_value' => array(      
          'title' => t('Settings value'),
          'help' => t('Settings value field'),
			    'field' => array(
			      'handler' => 'views_handler_field',
			      'click sortable' => TRUE,
			    ),
			    'sort' => array(
			      'handler' => 'views_handler_sort',
			    ),
			    'filter' => array(
			      'handler' => 'views_handler_filter_string',
			    ),
			    'argument' => array(
			      'handler' => 'views_handler_argument_string',
			    )
        ),       
        'description' => array(      
          'title' => t('Settings description'),
          'help' => t('Settings description field'),
			    'field' => array(
			      'handler' => 'views_handler_field',
			      'click sortable' => TRUE,
			    ),
			    'sort' => array(
			      'handler' => 'views_handler_sort',
			    ),
			    'filter' => array(
			      'handler' => 'views_handler_filter_string',
			    ),
			    'argument' => array(
			      'handler' => 'views_handler_argument_string',
			    )
        ),          
    )
  );
  
  return $table;
}