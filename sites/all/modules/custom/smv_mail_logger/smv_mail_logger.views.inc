<?php
/**
 * Implements hook_views_data()
 *
 */
function smv_mail_logger_views_data() {

 $table = array(
    'mail_logger' => array(  
      'table' => array(
        'group' => 'Maillogger', 
            'base' => array(
              'field' => 'mlid', 
              'title' => 'Maillogger',
              'help' => 'Mail logger database table'
            ),
        ),

        'mlid' => array(  
          'title' => t('Mail Id'),
			    'help' => t('The primary key of the maillog table.'),
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

        'mailkey' => array(      
          'title' => t('Mail Key'),
          'help' => t('Mail key field'),
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

        'mailto' => array(      
          'title' => t('Mail To'),
			    'help' => t("The 'To' field of the e-mail address."),
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

        'subject' => array(      
          'title' => t('Subject'),
			    'help' => t("The 'Subject' field of the e-mail."),
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

        'body' => array(      
          'title' => t('Body'),
			    'help' => t("The 'Body' field of the e-mail."),
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
        'viewed' => array(      
          'title' => t('Viewed'),
			    'help' => t("Tag if notification was viewed."),
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
        'date_sent' => array(      
          'title' => t('Date Sent'),
			    'help' => t("The 'Date' field of the e-mail."),
			    'field' => array(
			      'handler' => 'views_handler_field_date',
			      'click sortable' => TRUE,
			    ),
			    'sort' => array(
			      'handler' => 'views_handler_sort_date',
			    ),
			    'filter' => array(
			      'handler' => 'views_handler_filter_date',
			    ),
			    'argument' => array(
			      'handler' => 'views_handler_argument_string',
			    )
        ),        

    )
  );
  
  return $table;
}


