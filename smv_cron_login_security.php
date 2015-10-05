<?php

/**
* System cron script - Remove expired login logs
* @author Jen Andes
* @copyright Primesoft Phils. Inc
*/
error_reporting(E_ALL^ E_WARNING);
define('DRUPAL_ROOT', getcwd());
include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

_smv_loginsecurity_remove_events();
smv_loginsecurity_unlock_account();
 
?>