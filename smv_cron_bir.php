<?php

/**
* System cron script - BIR FORM 2307
* @author Jen Andes
* @copyright Primesoft Phils. Inc
*/
error_reporting(E_ALL^ E_WARNING);
define('DRUPAL_ROOT', getcwd());
include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$importer_id  = 'bir_feeds';

//Source & destination folder path 
$source       = smv_sitecron_file_folder_directory($importer_id);
$destination  = smv_sitecron_feeds_folder_directory($importer_id);

//Get current number of files inside destination folder
$dest_files = @scandir($destination);
$dest_filecount = count($dest_files);
$num = 0;

$csv_file_array  = array();
$file_header 		= array('title','date','author', 'vendor_no', 'quarter','quarter_yr','bir_pdf_filepath','bir_pdf','sync_status','exception_type');

//Restrict processing of multiple files
if($dest_filecount <= 2){   

	//File path destination transfer
	if ($handle = opendir($source)) {  
		
		while (false !== ($file_trans = readdir($handle)) && $num < 2000) {				

			if (is_file($source . '/' . $file_trans)) {

				$file = $source . '/' . $file_trans;
				
				/* 
				* Get file info
				* Sample result: 
				*   ["dirname"]=> "E:\xampp\htdocs\smvendor\sites\default\private_files\sm_files\po"
				*   ["basename"]=> "0700000002_1000000001_P200_scmc.mtn.csv"          
				*   ["extension"]=> "csv"
				*   ["filename"]=> "0700000002_1000000001_P200_scmc.mtn"  
				*/          
				$info = pathinfo($file);
				$filename  = $info["filename"];
				$basename  = $info["basename"];
				$extension = $info["extension"];
				$dirname   = $info["dirname"];

				//Remove special character from filename
				if(preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬]/', $filename)){
					$filename = preg_replace('/[^A-Za-z0-9\._ -]/', '', $filename);						
				}

				if($extension == 'pdf' || $extension == 'PDF'){ 

					//explode value of filename
					$returned_file_info   = explode('_', $filename);			
					
					//initialize file folder path variables
					$exception_path = 'sm_vendor/exception';				

					/* 
					* Filename convention: VendorNo_Date_CompanyCode.pdf
					* 0700000001_20151031_200
					* Process only if filename has correct number of info based on filename convention (3) 
					* Acceptable file extension: pdf     
					*/
					$number_returned_info = count($returned_file_info);
					if($number_returned_info <= 2){						
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);	
						$csv_file_array[] = array($filename,'','', '', '', '', $exception_path, $exception_file,0,'exception_filename_convention');   
						continue;
					}				

					//Filename date format						
					if(!smv_sitecron_validate_date_format($returned_file_info[1])){	
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);
						$csv_file_array[] = array($filename,'','', '', '', '', $exception_path, $exception_file,0,'exception_invalid_date'); 
						continue;
					}																
					
					//construct variables from filename info
					$vendor_no 			 = $returned_file_info[0];
					$file_date			 = $returned_file_info[1];
					$bir_date				 = smv_sitecron_convert_date_to_timestamp($file_date);	

					//get readable date
					$dtime = DateTime::createFromFormat("Ymd", $file_date);
					$month = $dtime->format('n');
					$quarter_yr = $dtime->format('Y');   
					$quarter_no = smv_sitecron_date_quarterno($month);				
					
					/* 
					* Process only if it theres existing vendor number        
					*/
					$vendor_uid = smv_sitecron_vendor_uid($vendor_no);
					if(!$vendor_uid){
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);	
						$csv_file_array[] = array($filename,'','', '', '', '', $exception_path, $exception_file,0,'exception_no_vendorno');  
						continue;
					}

					//construct title of node       
					$raw_file = smv_sitecron_move_to_active_folder($importer_id,$file,$vendor_no);
					$raw_path = 'sm_vendor/vendors/'.$vendor_no.'/active/bir';  	

					$csv_file_array[] = array($filename,$bir_date,$vendor_uid, $vendor_no, $quarter_no, $quarter_yr, $raw_path, $raw_file,1,'');	

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
		$output_csv = smv_sitecron_output_csv($csv_file_array, $file_header, $destination, '/smprime_bir.csv');
	} 
}

/**
* Feed import process
* BIR feeds
*/
$bir_feeds_file = smv_sitecron_folder_files_count($destination);
if($bir_feeds_file > 2){
	if(file_exists($destination . '/smprime_bir.csv')){
		$path = $destination . '/smprime_bir.csv';
		smv_sitecron_source_config_path($importer_id);
		$source = feeds_source($importer_id);
		$config['FeedsFileFetcher']['source'] = $path; 
		$source->setConfig($config);
		$source->save();  
		
		$source = feeds_source($importer_id);
		$source->startImport(); 
	}			
}

?>