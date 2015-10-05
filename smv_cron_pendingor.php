<?php
/**
* System cron script - OR Monitoring
* @author Jen Andes
* @copyright Primesoft Phils. Inc
*/
error_reporting(E_ALL^ E_WARNING);
define('DRUPAL_ROOT', getcwd());
include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$importer_id  = 'pending_or_items';

//Source & destination folder path 
$source       = smv_sitecron_file_folder_directory($importer_id);
$destination  = smv_sitecron_feeds_folder_directory($importer_id);

//Get current number of files inside destination folder
$dest_files = @scandir($destination);
$dest_filecount = count($dest_files);
$num = 0;

//Restrict processing of multiple files
if($dest_filecount <= 2){   

	//File path destination transfer
	if ($handle = opendir($source)) {  
		
		while (false !== ($file_trans = readdir($handle)) && $num < 2000) {
			
			$file = $source . '/' . $file_trans;

			if (is_file($file)) {
								     
				$info = pathinfo($file);
				$filename  = $info["filename"];
				$basename  = $info["basename"];
				$extension = $info["extension"];
				$dirname   = $info["dirname"];				

				if($extension == 'csv' || $extension == 'CSV'){
					smv_sitecron_move_to_feeds_folder($importer_id, $dirname, $basename);
					$num++; 
				}else{
					//invalid file type, continue
					continue;
				}					
			}        
		}
		closedir($handle);    
	}else{
		echo "$source could not be opened.\n";
	}
}

/**
* Feeds Import
*/
smv_sitecron_line_items_feeds_import($importer_id);

?>