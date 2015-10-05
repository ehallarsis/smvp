<?php
/**
* System cron script - Purchase Order
* @author Jen Andes
* @copyright Primesoft Phils. Inc
*/
error_reporting(E_ALL^ E_WARNING);
define('DRUPAL_ROOT', getcwd());
include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$importer_id  = 'po_feeds';

//Source & destination folder path 
$source       = smv_sitecron_file_folder_directory($importer_id);
$destination  = smv_sitecron_feeds_folder_directory($importer_id);

//Check destination path
smv_sitecron_check_destination_folder($destination);

//Get current number of files inside destination folder
$dest_files = @scandir($destination);
$dest_filecount = count($dest_files);
$num = 0;

$csv_file_array   = array();
$pdf_file_array   = array();

$file_header = array('nid','filename','title','author_id','vendor_no','po_number','po_org', 'po_maker','po_status', 'po_sync_status','po_csv_filepath','po_csv', 'po_pdf_filepath','po_pdf','exception_type', 'po_revised', 'po_date');

//Restrict processing of multiple files
if($dest_filecount <= 2){   

	//File path destination transfer
	if ($handle = opendir($source)) {  
		
		while (false !== ($file_trans = readdir($handle)) && $num < 2000) {
			
			$file = $source . '/' . $file_trans;

			if (is_file($file)) {
								
				/* 
				* Get file info
				* Sample result: 
				*   ["dirname"]=> "E:\xampp\htdocs\smvendor\sites\default\private_files\sm_files\po"
				*   ["basename"]=> "PO_1000000001_0700000001_P100_purchasing_Open_20141201.csv"          
				*   ["extension"]=> "csv"
				*   ["filename"]=> "PO_1000000001_0700000001_P100_purchasing_Open_20141201"  
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

					$pdf_file_array[] = array($filename, $file);
					continue;

				}elseif($extension == 'csv' || $extension == 'CSV'){

					/* 
					* Filename convention v1.4: PO_<PO Number>_<Vendor Code>_<POrg>_<User>_<Status>_<Date> 
					* PO_1000000001_0700000001_P100_purchasing_Open_20141201.csv
					* Process only if filename has correct number of info based on filename convention (7)
					* Allow additional character after filename convention 
					*/					
					$returned_file_info   = explode('_', $filename);
					$number_returned_info = count($returned_file_info);

					//initialize exception file folder path variables
					$exception_path = 'sm_vendor/exception';

					//Filename convention checking
					if($number_returned_info <= 6){	
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);
						$csv_file_array[] = array('',$filename,$filename,'','','','','','',0, $exception_path, $exception_file,$exception_path,'','exception_filename_convention',0,'');
						continue;
					}

					//Filename date format						
					if(!smv_sitecron_validate_date_format($returned_file_info[6])){	
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);
						$csv_file_array[] = array('',$filename,$filename,'','','','','','',0, $exception_path, $exception_file,$exception_path,'','exception_invalid_date',0,'');
						continue;
					}											

					//construct variables from filename info
					$po_number = smv_sitecron_remove_special_characters($importer_id,$returned_file_info[1]);
					$vendor_no = smv_sitecron_remove_special_characters($importer_id,$returned_file_info[2]);						
					$po_org    = smv_sitecron_remove_special_characters($importer_id,$returned_file_info[3]);
					$po_maker  = smv_sitecron_remove_special_characters($importer_id,$returned_file_info[4]);   
					$po_status = strtolower(smv_sitecron_remove_special_characters($importer_id,$returned_file_info[5])); 
					$po_date	 = smv_sitecron_convert_date_to_timestamp($returned_file_info[6]);	

					/* 
					* Process only if it theres existing vendor number        
					*/
					$vendor_uid = smv_sitecron_vendor_uid($vendor_no);
					if(!$vendor_uid){
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);
						$csv_file_array[] = array('',$filename,$filename,'',$vendor_no,'',$po_org, $po_maker, '',0,$exception_path, $exception_file,$exception_path,'','exception_no_vendorno',0,''); 
						continue;
					}			

					/* 
					* Columns: PO No | Line No | Material No | Material Desc | Quantity | UOM | Unit Price | Total Price
					* Process only if it has correct number of columns (8)        
					*/
					$column_count = smv_sitecron_csv_column_count($file);
					if($column_count != 8){
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);					
						$csv_file_array[] = array('',$filename,$filename,'',$vendor_no,'',$po_org, $po_maker, '',0,$exception_path, $exception_file,$exception_path,'','exception_column_count', 0,''); 
						continue;
					} 

					/* 
					* Process only if submitted po status in filename is "Revised" or "New" 
					*/
					if($po_status != 'new' && $po_status != 'revised'){
						$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);					
						$csv_file_array[] = array('',$filename,$filename,'',$vendor_no,'', $po_org, $po_maker, '',0,$exception_path, $exception_file,$exception_path,'','exception_invalid_status', 0,''); 
						continue;							
					}

					/*  
					* Purchase Order validation 
					* Po Maker, Status        
					*/
					$nid = smv_sitefeeds_purchaseorder_nid($po_number);

					//File already exist
					if($nid){

						//get the current po status
						$current_po_status = smv_sitecron_purchase_order_status($nid);

						//allow revised po only if current status was tag by po maker as For Revision
						if($current_po_status == 'Revised' && $po_status == 'revised'){

							$status = 'Revised';					

							$active_filepath = 'sm_vendor/vendors/'.$vendor_no.'/active/po'; 
							$new_filename = 'PO_' . $vendor_no . '_' . $po_number; 	

							//move to details feeds folder
							smv_sitecron_copy_to_details_feeds_folder($importer_id,$file,$vendor_no);

							$csv_file = smv_sitecron_move_to_active_folder($importer_id,$file,$vendor_no,$new_filename);
							$csv_file_array[] = array($nid,$filename,$new_filename,$vendor_uid,$vendor_no,$po_number,$po_org,$po_maker,$status, 1, $active_filepath,$csv_file, $active_filepath,'','',1, $po_date);								

						}else{
							$exception_file  	= smv_sitecron_move_to_exception_folder($importer_id, $file);					
							$csv_file_array[] = array('',$filename,$filename,'',$vendor_no,'',$po_org, $po_maker, '',0,$exception_path, $exception_file,$exception_path,'','exception_update_restricted', 0,''); 
							continue;		
						}

					}else{

						if($po_status == 'new'){
							//New PO
							$active_filepath = 'sm_vendor/vendors/'.$vendor_no.'/active/po'; 
							$new_filename = 'PO_' . $vendor_no . '_' . $po_number; 	 	

							$status = 'New';				

							//move to details feeds folder
							smv_sitecron_copy_to_details_feeds_folder($importer_id,$file,$vendor_no);

							$csv_file = smv_sitecron_move_to_active_folder($importer_id,$file,$vendor_no,$new_filename);
							$csv_file_array[] = array('',$filename,$new_filename,$vendor_uid,$vendor_no,$po_number,$po_org,$po_maker,$status, 1, $active_filepath,$csv_file, $active_filepath,'','',0,$po_date);							
						}
					}
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
		$sync_status = $csv_file_array[$csv_array_key][9];
		if($sync_status == 1){
			$new_filename = $csv_file_array[$csv_array_key][2]; 	 	
			$pdf = smv_sitecron_move_to_active_folder($importer_id, $pdf_file, $vendor_no, $new_filename);
			$csv_file_array[$csv_array_key][13] = $pdf;
		}else{
			$exception_file = smv_sitecron_move_to_exception_folder($importer_id, $pdf_file);	
			$csv_file_array[$csv_array_key][13] = $exception_file;	
		}
	}

	//output to csv only if file details and file header is not empty
	if($csv_file_array && $file_header){
		$output_csv = smv_sitecron_output_csv($csv_file_array, $file_header, $destination, '/smprime_po.csv');
	}

}	

/**
* Feed import process
* PO feeds
*/
$po_feeds_file = smv_sitecron_folder_files_count($destination);
if($po_feeds_file > 2){
	if(file_exists($destination . '/smprime_po.csv')){
		smv_sitecron_source_config_path($importer_id);
		$source = feeds_source($importer_id);
		$source->startImport(); 
	}			
}

/**
* Additional feed import process
* PO details feeds
*/
smv_sitecron_line_items_feeds_import('po_details_feeds');

?>