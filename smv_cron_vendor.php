<?php
/**
* System cron script - Vendor Accounts
* @author Jen Andes
* @copyright Primesoft Phils. Inc
*/
error_reporting(E_ALL^ E_WARNING);
define('DRUPAL_ROOT', getcwd());
include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$importer_id 	= 'vendor_feeds';

//Source & destination folder path 
$source 		= smv_sitecron_file_folder_directory($importer_id);
$destination 	= smv_sitecron_feeds_folder_directory($importer_id);

//Check destination path
smv_sitecron_check_destination_folder($destination);

//Get current number of files inside destination folder
$dest_files = @scandir($destination);
$dest_filecount = count($dest_files);
$num = 0;

//Restrict processing of multiple files
if($dest_filecount <= 2){
	if ($handle = opendir($source)) {
		while (FALSE !== ($file_trans = readdir($handle))) {

		  if (is_file($source . '/' . $file_trans)) {

		  	$file = $source . '/' . $file_trans;

		  	//create exception file
		  	/*$dateprocessed = date("Ymd" ,time());
		  	$exception_filename = $dateprocessed . '_invalid_vendors.dat';
		  	$exception_file = drupal_realpath('private://sm_vendor/exception/'.$exception_filename);
		  	$fp_exceptionfile = fopen($exception_file, "a") or die ("Coudn't open or write $exceptionfile");	

		  	//create a new file
		  	$valid_file = $destination . '/users_list_vendor.dat';
				$valid_data 	= array();

				$lines = file($file);
				$rows = array();
				foreach ($lines as $line) {				
				    //temporarily re-create as comma delimited
				    $data = explode(',', trim($line));
				    $rows[] = $data;
				}

				//Loop through comma delimited data TODO!!!!!!!!!!!!
				$i = 1;
				foreach ($rows as $r) {
					$data = implode("\t", $r)."\n";					
					if($i==2){					  	
				  	fputs($fp_exceptionfile, "".$data."");
				  }else{
				  	$valid_data[] = $data;
				  }
				  $i++;
				}

				file_put_contents($valid_file, $valid_data);
				fclose($fp_exceptionfile);*/

				$info = pathinfo($file);
				$filename  = $info["filename"];
				$basename  = $info["basename"];
				$extension = $info["extension"];
				$dirname   = $info["dirname"];				

				if($extension == 'dat' || $extension == 'DAT'){
					$newbasename = "users_list_vendor.dat";					

					$current_path = $dirname . '/' . $basename;	
					$new_path 		= $destination . '/' . $newbasename;

					file_unmanaged_move($current_path, $new_path, FILE_EXISTS_REPLACE);

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

// Execute the import.
if(is_file($destination . '/users_list_vendor.dat')){		
	smv_sitecron_source_config_path($importer_id);
	$source = feeds_source($importer_id);
	$source->startImport();		
}	


function smv_sitecron_vendors_validate_status($status){
	if($status == 0 || $status == 1){
		return true;
	}else{
		return false;
	}
}

?>