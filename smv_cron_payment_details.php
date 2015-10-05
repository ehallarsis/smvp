<?php
/**
* System cron script - Payment Details
* @author Jen Andes
* @copyright Primesoft Phils. Inc
*/
error_reporting(E_ALL^ E_WARNING);
define('DRUPAL_ROOT', getcwd());
include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$importer_id  = 'pd_feeds';

//Source & destination folder path 
$source        = smv_sitecron_file_folder_directory($importer_id);
$destination   = smv_sitecron_feeds_folder_directory($importer_id);

//Check destination path
smv_sitecron_check_destination_folder($destination);

//Get current number of files inside destination folder
$dest_files = @scandir($destination);
$dest_filecount = count($dest_files);
$num = 0;

$csv_file_array   = array();

//construct csv file header
$file_header = array('title', 'author_id', 'docno', 'po_number', 'csv_filepath', 'csv', 'sync_status','exception_type','mc', 'po_nid');

if($dest_filecount <= 2){   

	//File path destination transfer
	if ($handle = opendir($source)) {  
		
		while (false !== ($file_trans = readdir($handle)) && $num < 2000) {

			$file = $source . '/' . $file_trans;

			if (is_file($file)) {
				
				//File info
				$info = pathinfo($file);
				$filename  = $info["filename"];
				$basename  = $info["basename"];
				$extension = $info["extension"];
				$dirname   = $info["dirname"];

				//Remove special character from filename
				if(preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬]/', $filename)){
					$filename = preg_replace('/[^A-Za-z0-9\._ -]/', '', $filename);						
				}					

				if($extension == 'csv' || $extension == 'CSV'){
					
					//initialize variables for failed sync
					$exception_path = 'sm_vendor/exception';

					//explode value of filename
					$returned_file_info   = explode('_', $filename);
					
					/* 
					* Filename convention: <VendorCode>_<PaymentDocNo>_<PONumber>_<Year>_<PaymentType>.csv
					* 0700000001_2100000001_1000000001_2015_MC
					* Process only if filename has correct number of info based on filename convention (4) 
					* Acceptable file extension: csv     
					*/
					$number_returned_info = count($returned_file_info);
					if($number_returned_info <= 3){					
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);				
						$csv_file_array[] = array($filename, 0,'','', $exception_path, $exception_file, 0,'exception_filename_convention','', '');
						continue;
					}

					//construct variables from filename info						
					$vendor_no = $returned_file_info[0];
					$doc_no		 = $returned_file_info[1];
					$po_number = $returned_file_info[2];
					$year 		 = $returned_file_info[3];

					//Managers check
					if(isset($returned_file_info[4]) && $returned_file_info[4] != "" ){
						$filename_mc = strtolower($returned_file_info[4]);
						$ptype = ($filename_mc == 'mc' || $filename_mc == 'c') ? 1 : 0;
					}else{
						$ptype = 0;
					}

					//process only if there's existing po number
					$po_id 	= smv_sitecron_po_number_id($po_number);
					if(!$po_id){
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);			
						$csv_file_array[] = array($filename, 0, '', '', $exception_path, $exception_file, 0,'exception_no_ponumber', '', '');							
						continue;
					}						

					//Process only if it theres existing vendor number        					
					$vendor_uid = smv_sitecron_vendor_uid($vendor_no);
					if(!$vendor_uid){
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);			
						$csv_file_array[] = array($filename, 0, '', '', $exception_path, $exception_file, 0,'exception_no_vendorno', '', '');
						continue;
					}	

					/* 
					* Columns: Doc No | Posting Date | Payment Amount | PO Number | Line Number
					* Process only if it has correct number of columns (5)        
					*/
					$column_count = smv_sitecron_csv_column_count($file);
					if($column_count != 5){
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);			
						$csv_file_array[] = array($filename, 0, '', '', $exception_path, $exception_file, 0,'exception_column_count', '', '');
						continue;
					}

					/*$pd_nid = smv_sitefeeds_paymentdetails_nid($po_number);
					if($pd_nid){								
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);
						$csv_file_array[] = array($filename, 0, '', '', $exception_path, $exception_file, 0,'exception_update_restricted','');
						continue;		
					}*/							

			  	//move to details feeds folder
					smv_sitecron_copy_to_details_feeds_folder($importer_id,$file,$vendor_no);										
					
					$new_filename = 'PD_' . $vendor_no . '_' . $po_number . '_' . $year; 														
					$raw_path = 'sm_vendor/vendors/'.$vendor_no.'/active/pd';  		
					$raw_file = smv_sitecron_move_to_active_folder($importer_id,$file,$vendor_no,$new_filename);										
					// Added by: Emmanuel P. Hallarsis
					$po_nid = smv_sitefeeds_purchaseorder_nid($po_number);

					$csv_file_array[] = array($new_filename, $vendor_uid, $doc_no, $po_number,$raw_path, $raw_file, 1,'',$ptype, $po_nid);

				}else{
					//invalid file type, continue
					continue;
				}

				$num++;
			}
		}
		closedir($handle); 		
	}else{
		echo "$source could not be opened.\n";
	}

	//output to csv only if file details and file header is not empty
	if($csv_file_array && $file_header){
		$output_csv = smv_sitecron_output_csv($csv_file_array, $file_header, $destination, '/smprime_pd.csv');
	} 	
}

/**
* Feed import process
* Payment Details
*/
$pd_feeds_file = smv_sitecron_folder_files_count($destination);
if($pd_feeds_file > 2){
	if(file_exists($destination . '/smprime_pd.csv')){
		smv_sitecron_source_config_path($importer_id);
		$source = feeds_source($importer_id);			
		$source->startImport(); 
	}			
}

/**
* Additional feed import process
* Payment Details items feeds
*/
smv_sitecron_line_items_feeds_import('pd_details_feeds');

?>