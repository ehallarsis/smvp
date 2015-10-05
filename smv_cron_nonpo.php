<?php
/**
* System cron script - NON PO
* @author Jen Andes
* @copyright Primesoft Phils. Inc
*/
error_reporting(E_ALL^ E_WARNING);
define('DRUPAL_ROOT', getcwd());
include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$importer_id  = 'nonpo_feeds';

//Source & destination folder path 
$source       = smv_sitecron_file_folder_directory($importer_id);
$destination  = smv_sitecron_feeds_folder_directory($importer_id);

//Get current number of files inside destination folder
$dest_files = @scandir($destination);
$dest_filecount = count($dest_files);
$num = 0;

$csv_file_array   = array();
$pdf_file_array   = array();

$file_header 	= array('title','author','docno','vendor_no','date', 'amount', 'csv_filepath', 'csv', 'pdf_filepath', 'pdf','sync_status','exception_type');

if($dest_filecount <= 2){   

	//File path destination transfer
	if ($handle = opendir($source)) {  
		
		while (false !== ($file_trans = readdir($handle)) && $num < 2000) {
						 
			if (is_file($source . '/' . $file_trans)) {
				
				$file = $source . '/' . $file_trans;
        
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

					//add to pdf array	
					$pdf_file_array[] = array($filename, $file);
					continue;

				}elseif($extension == 'csv' || $extension == 'CSV'){

					//explode value of filename
					$returned_file_info   = explode('_', $filename);

					//initialize file folder path variables
					$exception_path = 'sm_vendor/exception';														
				
					/* 
					* Filename convention: VendorNo_DocumentNumber 
					* Process only if filename has correct number of info based on filename convention (2) 
					*/
					$number_returned_info = count($returned_file_info);
					if($number_returned_info <= 1){			
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);				
						$csv_file_array[] = array($filename, '','','','','',$exception_path, $exception_file, $exception_path,'',0,'exception_filename_convention');
						continue;
					}

					//construct variables from filename info
					$vendor_no = $returned_file_info[0];
					$docno = $returned_file_info[1];						

					/* 
					* Process only if it theres existing vendor number        
					*/
					$vendor_uid = smv_sitecron_vendor_uid($vendor_no);
					if(!$vendor_uid){
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);
						$csv_file_array[] = array($filename, '','','','','',$exception_path,$exception_file,$exception_path,'',0,'exception_no_vendorno');
						continue;
					}						

					/* 
					* Columns: Document No | Invoice Date | Payment Amount
					* Process only if it has correct number of columns (3)        
					*/
					$column_count = smv_sitecron_csv_column_count($file);
					if($column_count != 3){
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);
						$csv_file_array[] = array($filename, '','','','','',$exception_path,$exception_file,$exception_path,'',0,'exception_column_count');
						continue;
					} 

					//Read contents of csv
				  $csv_lines = smv_sitecron_csv_to_array($file);
				  $line_count = count($csv_lines);

				  if($line_count == 0){
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);
						$csv_file_array[] = array($filename, '','','','','',$exception_path,$exception_file,$exception_path,'',0,'exception_empty_csv');
						continue;		
				  }
				 	
				  $date   = smv_sitecron_convert_date_to_timestamp($csv_lines[0][1]);
				  $amount_csv = $csv_lines[0][2];

				  if($amount_csv != '' || $amount_csv !=0){
				  	$amount = str_replace(",", "", $amount_csv);
				  }else{
				  	$amount = $amount_csv;
				  }
				  
					$raw_path = 'sm_vendor/vendors/'.$vendor_no.'/active/nonpo'; 			
					$raw_file = smv_sitecron_move_to_active_folder($importer_id,$file,$vendor_no);							  

					$csv_file_array[] = array($filename,$vendor_uid, $docno, $vendor_no, $date,$amount,$raw_path,$raw_file,$raw_path, '', 1,'');			
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


	/*
	* Redo csv array data to add pdf files
	*/
	foreach ($pdf_file_array as $key => $row){
		$csv_array_key = smv_sitecron_recursive_array_search($row[0], $csv_file_array);			
		$pdf_file = $row[1];

		//check sync_status
		$sync_status = $csv_file_array[$csv_array_key][10];
		if($sync_status == 1){
			$pdf = smv_sitecron_move_to_active_folder($importer_id, $pdf_file, $vendor_no);
			$csv_file_array[$csv_array_key][9] = $pdf;
		}else{
			$exception_file = smv_sitecron_move_to_exception_folder($importer_id, $pdf_file);	
			$csv_file_array[$csv_array_key][9] = $exception_file;	
		}
	}

	//output to csv only if file details and file header is not empty
	if($csv_file_array && $file_header){
		$output_csv = smv_sitecron_output_csv($csv_file_array, $file_header, $destination, '/smprime_nonpo.csv');
	}
}

/**
* Feed import process
* Non PO feeds
*/
$nonpo_feeds_file = smv_sitecron_folder_files_count($destination);
if($nonpo_feeds_file > 2){
	if(file_exists($destination . '/smprime_nonpo.csv')){
		smv_sitecron_source_config_path($importer_id);
		$source = feeds_source($importer_id);
		$source->startImport(); 
	}			
}
?>