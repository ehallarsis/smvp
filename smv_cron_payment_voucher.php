<?php
/**
* System cron script - Payment Voucher
* @author Jen Andes
* @copyright Primesoft Phils. Inc
*/
error_reporting(E_ALL^ E_WARNING);
define('DRUPAL_ROOT', getcwd());
include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$importer_id  = 'pv_feeds';

//Source & destination folder path 
$source       = smv_sitecron_file_folder_directory($importer_id);
$destination  = smv_sitecron_feeds_folder_directory($importer_id);

//Get current number of files inside destination folder
$dest_files = @scandir($destination);
$dest_filecount = count($dest_files);
$num = 0;

$csv_file_array   = array();
$pdf_file_array   = array();
$xls_file_array   = array();

$file_header = array('filename','title','author', 'vendor_no', 'pv_number','date', 'pv_csv_filepath', 'pv_csv', 'pv_pdf_filepath', 'pv_pdf', 'pv_xls_filepath', 'pv_xls', 'sync_status','exception_type', 'mc');  

//Restrict processing of multiple files
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
				}elseif($extension == 'xls' || $extension == 'XLS'){ 
					//add to xls array	
					$xls_file_array[] = array($filename, $file);
					continue;					
				}
				elseif($extension == 'csv' || $extension == 'CSV'){

					//explode value of filename
					$returned_file_info   = explode('_', $filename);

					//initialize file folder path variables
					$exception_path = 'sm_vendor/exception';

					/* 
					* Filename convention: <Vendor Code>_<Document Number>_<Year>_<Payment Type>
					* 0700000001_2100000101_2015_MC
					* Process only if filename has correct number of info based on filename convention (payment type - optional)
					* Acceptable file extension: pdf, csv    
					*/
					$number_returned_info = count($returned_file_info);
					if($number_returned_info <= 2){  
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);	 
						$csv_file_array[] = array($filename, $filename, '', '', '', '', $exception_path,$exception_file, $exception_path,'', $exception_path,'',0,'exception_filename_convention','');
						continue;
					}

					//construct variables from filename info
					$vendor_no = smv_sitecron_remove_special_characters($importer_id,$returned_file_info[0]);
					$pv_number = smv_sitecron_remove_special_characters($importer_id,$returned_file_info[1]);					
					$pv_year	 = $returned_file_info[2];

					//Managers check
					if(isset($returned_file_info[3]) && $returned_file_info[3] != "" && !empty($returned_file_info[3])){
						$filename_mc = strtolower($returned_file_info[3]);
						$ptype = ($filename_mc == 'mc' || $filename_mc == 'c') ? 1 : 0;
					}else{
						$ptype = 0;
					}

					/* 	
					* Process only if it theres existing vendor number        
					*/
					$vendor_uid = smv_sitecron_vendor_uid($vendor_no);
					if(!$vendor_uid){
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);		
						$csv_file_array[] = array($filename, $filename, '', '', '', '', $exception_path,$exception_file, $exception_path,'', $exception_path,'',0,'exception_no_vendorno','');											
						continue;
					}

					/* 
					* Columns: Payment Voucher # | Date | Amount | PO number | Line Number
					* Process only if it has correct number of columns (5)    	    
					*/
					$column_count = smv_sitecron_csv_column_count($file);
					if($column_count != 5){		
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);			
						$csv_file_array[] = array($filename, $filename, '', '', '', '', $exception_path,$exception_file, $exception_path,'', $exception_path,'',0,'exception_column_count','');																
						continue;
					}
					
					//Read contents of csv
				  $csv_lines = smv_sitecron_csv_to_array($file);
				  $line_count = count($csv_lines);

				  if($line_count == 0){
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);
						$csv_file_array[] = array($filename, $filename, '', '', '', '', $exception_path,$exception_file, $exception_path,'', $exception_path,'',0,'exception_empty_csv','');													
						continue;
				  }

					$date  	= smv_sitecron_convert_date_to_timestamp($csv_lines[0][1]);	

					//copy to details feeds folder
					smv_sitecron_copy_to_details_feeds_folder($importer_id,$file,$vendor_no);

				  $new_filename = 'PV_' . $vendor_no . '_' . $pv_number . '_' . $pv_year; 		
					$raw_path 	= 'sm_vendor/vendors/'.$vendor_no.'/active/pv';  		
					$raw_file 	= smv_sitecron_move_to_active_folder($importer_id,$file,$vendor_no,$new_filename);								  

				  $csv_file_array[] = array($filename,$new_filename, $vendor_uid, $vendor_no, $pv_number, $date, $raw_path, $raw_file, $raw_path, '',$raw_path,'', 1,'',$ptype);

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
	if(!empty($pdf_file_array)){
		foreach ($pdf_file_array as $key => $row){
			$csv_array_key = smv_sitecron_recursive_array_search($row[0], $csv_file_array);			
			$pdf_file = $row[1];

			//check sync_status
			$sync_status = $csv_file_array[$csv_array_key][12];
			if($sync_status == 1){
				//$new_filename = 'PV_' . $csv_file_array[$csv_array_key][3] . '_' . $csv_file_array[$csv_array_key][4];
				$new_filename = $csv_file_array[$csv_array_key][1];	
				$pdf = smv_sitecron_move_to_active_folder($importer_id, $pdf_file, $vendor_no,$new_filename);
				$csv_file_array[$csv_array_key][9] = $pdf;
			}else{
				$exception_file = smv_sitecron_move_to_exception_folder($importer_id, $pdf_file);	
				$csv_file_array[$csv_array_key][9] = $exception_file;	
			}
		}
	}

	/*
	* Redo csv array data to add xls files
	*/
	if(!empty($xls_file_array)){
		foreach ($xls_file_array as $xls_key => $xls_row){
			$csv_array_key = smv_sitecron_recursive_array_search($xls_row[0], $csv_file_array);			
			$xls_file = $xls_row[1];

			//check sync_status
			$sync_status = $csv_file_array[$csv_array_key][12];
			if($sync_status == 1){
				//$new_filename = 'PV_' . $csv_file_array[$csv_array_key][3] . '_' . $csv_file_array[$csv_array_key][4]; 	
				$new_filename = $csv_file_array[$csv_array_key][1];	
				$xls = smv_sitecron_move_to_active_folder($importer_id, $xls_file, $vendor_no,$new_filename);
				$csv_file_array[$csv_array_key][11] = $xls;
			}else{
				$exception_file = smv_sitecron_move_to_exception_folder($importer_id, $xls_file);	
				$csv_file_array[$csv_array_key][11] = $exception_file;	
			}
		}
	}

	//output to csv only if file details and file header is not empty
	if($csv_file_array && $file_header){
		$output_csv = smv_sitecron_output_csv($csv_file_array, $file_header, $destination, '/smprime_pv.csv');
	} 	


}


/**
* Feed import process
* Payment Voucher
*/
$pv_feeds_file = smv_sitecron_folder_files_count($destination);
if($pv_feeds_file > 2){
	if(file_exists($destination . '/smprime_pv.csv')){
		smv_sitecron_source_config_path($importer_id);
		$source = feeds_source($importer_id);
		$source->startImport(); 
	}			
}

/**
* Additional feed import process
* PV details feeds
*/
smv_sitecron_line_items_feeds_import('pv_details_feeds');

?>