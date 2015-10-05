<?php
/**
 * @file
 * Contains the theme's functions to manipulate Drupal's default markup.
 *
 */

function smprime_preprocess_page(&$variables, $hook) {
	global $user;

	//Vendor - Purchase Order Page
	if(strstr(current_path(), 'purchase-order')){
		$jq = 'jQuery(document).ready(function () {
							jQuery(".po-viewing").bind("click", function(event){	
								var node_nid = jQuery(this).attr("id");					
								jQuery.ajax({
										type: "GET",
						        url: Drupal.settings.basePath + "po/vendor-view",
						        data: {nid: node_nid},
						        cache: false, 
						        success: function(response) {		
						        	var obj = jQuery.parseJSON(response);	
						        	jQuery("po-node-"+obj.nid).html(obj.po_status);	          	         
						        }    
								});
							});
						})';
		drupal_add_js($jq, array('type' => 'inline', 'scope' => 'header'));
	}

	//Notification
	if(strstr(current_path(), 'notifications')){
		$jq = 'jQuery(document).ready(function () {
							jQuery(".notif-viewing").bind("click", function(event){	
								var notif_mlid = jQuery(this).attr("id"); 
								jQuery.ajax({
										type: "GET",
						        url: Drupal.settings.basePath + "notification/view",
						        data: {mlid: notif_mlid},
						        cache: false, 
						        success: function(response) {
						        	//alert(response);          	         
						        }    
								});
							});
						})';
		drupal_add_js($jq, array('type' => 'inline', 'scope' => 'header'));
	}

	//PO Maker - Purchase Order change status
	if(strstr(current_path(), 'list-purchase-order') || strstr(current_path(), 'list-purchase-order-org') || strstr(current_path(), 'manage-files/purchase-order') || strstr(current_path(), 'archive/list-purchase-order-org') || strstr(current_path(), 'archive/list-purchase-order')){    
		$jq = 'jQuery(document).ready(function () {

							jQuery(".po-status-change").bind("change", function(event){	
								var details = jQuery(this).attr("id");
								var selected_status = this.value;
								var arr = details.split("-");
								var po_nid = arr[0];
								var current_status = arr[1].replace("_", " ");
								var ponumber = arr[2];
	
								jQuery("#dialog-confirm").html("Are you sure you want to change Purchase Order #" + ponumber + " status from \'" + current_status + "\' to \'" + selected_status + "\'?");
							  jQuery("#dialog-confirm").dialog({
							        resizable: false,
							        modal: true,
							        height: 200,
							        width: 400,
							        dialogClass: "no-close",
							        buttons: {
							          "Yes": function(){
							          	jQuery("#"+details+" > option:not([value^=\""+selected_status+"\"])").hide();
													jQuery.ajax({
															type: "GET",
											        url: Drupal.settings.basePath + "po/change-status",
											        data: {nid: po_nid, status: selected_status},
											        cache: false, 
											        success: function(response) {									        	
											        	//alert(response);          	         
											        }    
													});
							            jQuery(this).dialog("close");
							            callback(true);
							          },
							          "No": function () {														            
							            //revert the status
							            jQuery("select#"+details+" option").each(function() { this.selected = (this.text == current_status); });
							            jQuery(this).dialog("close");		
							            return false;              							               
							          }
							        }
							    });
							});
						})';
		drupal_add_js($jq, array('type' => 'inline', 'scope' => 'header'));
	}

	//Added by Emmanuel Hallarsis
	if(strstr(current_path(), 'purchase-order') || strstr(current_path(), 'archive/purchase-order') || strstr(current_path(), 'payment-voucher') || strstr(current_path(), 'archive/payment-voucher') || strstr(current_path(), 'list-payment-voucher') || strstr(current_path(), 'archive/list-payment-voucher') || strstr(current_path(), 'bir-form-2307') || strstr(current_path(), 'archive/bir-form-2307') || strstr(current_path(), 'po-monitoring-report') || strstr(current_path(), 'list-bir-form') || strstr(current_path(), 'archive/list-bir-form') || strstr(current_path(), 'list-purchase-order-org') || strstr(current_path(), 'archive/list-purchase-order-org') || strstr(current_path(), 'list-purchase-order') || strstr(current_path(), 'archive/list-purchase-order')){     

		$jq = 'jQuery(document).ready(function () {

						 function removePopup() {
						 	jQuery("#popup-message-window").remove();
						 	jQuery("#popup-message-background").remove();
						 }

						 function updateMethod(method) {         
							  var allVals = [];
							  var url = window.location.href.match(/^[^\#\?]+/)[0];

							  jQuery(".page-views .view-display-id-page .form-type-checkbox :checked").each(function() {
							    
							    if(jQuery(this).val() != 1) {
							    	allVals.push(jQuery(this).val());
							    }
							    
							  });

						    switch (method) {
						      case "xml":
										if(allVals.length != 0) {
											jQuery("#xml_view").attr("href", url + "/" + method + "/" + allVals);
										}
										else {
											jQuery("#xml_view").attr("href", "#");
										}
						        break;

						      case "batch-csv":
										if(allVals.length != 0) {
											var _href = location.protocol + "//" + location.host;
											jQuery("#csv_view").attr("href", _href + "/" + method + "/" + allVals);
										}
										else {
											jQuery("#csv_view").attr("href", "#");
										}
						        break;

						      case "batch-download":
										if(allVals.length != 0) {
											var _href = location.protocol + "//" + location.host;
											jQuery("#pdf_view").attr("href", _href + "/" + method + "/" + allVals);
										}
										else {
											jQuery("#pdf_view").attr("href", "#");
										}
						        break;

						      case "batch-excel":
										if(allVals.length != 0) {
											var _href = location.protocol + "//" + location.host;
											jQuery("#excel_view").attr("href", _href + "/" + method + "/" + allVals);
										}
										else {
											jQuery("#excel_view").attr("href", "#");
										}
						        break;
						    }
						  }
						  
						  function updatePrint() {
						  	var allVals = [];

						  	jQuery(".page-views .view-display-id-page .form-type-checkbox :checked").each(function() {

						  		if(jQuery(this).parents("tr").find(".cboxElement").attr("rel")) {
						  			allVals.push("[" + jQuery(this).parents("tr").find(".cboxElement").attr("rel") + "]");
						  		}
						  		
								});

								if(allVals.length != 0) {

									var b_url = location.protocol + "//" + location.host;
									var _href = "javascript:printWithParams(\'" + b_url + "/silent_print_pdf.php?DOC_LIST=" + allVals + "&amp;STATUS_UPDATE_ENABLED=true&amp;SHOW_PRINT_ERROR_DIALOG=true\');";

									jQuery("#print_view").attr("href", _href);
								}
								else {
									jQuery("#print_view").attr("href", "#");
								}

						  }

							jQuery("#block-views-exp-purchase-order-page").hide();
							jQuery("#block-views-868e572a260bf9b206edd38b4450a70a").hide();
							jQuery("#block-views-6dfb00ce7321246c6478d4842c498f99").hide();
							jQuery("#block-views-738101ae8c972ba2d34f040a5821d3bc").hide();
							jQuery("#block-views-c6fdb8befb9529da71405b3c8d1fa146").hide();
							jQuery("#block-views-693b547328afeab25059889134804b8d").hide();
							jQuery("#block-views-exp-bir-internal-user-page").hide();
							jQuery("#block-views-exp-payment-voucher-page").hide();
							jQuery("#block-views-ca1aea4df2ed752f808b761f896c3221").hide();
							jQuery("#block-views-exp-reports-vendor-page").hide();
							jQuery("#block-views-dd3be61bbbc92b6d63f9444b825d9cc0").hide();
							jQuery("#block-views-c18c62acb3a53017a53b786c6b1b87de").hide();
							jQuery("#block-views-3697950d25ce866451be85668af11295").hide();
							jQuery("#block-views-exp-bir-page").hide();
							jQuery("#block-views-exp-bir-archived-page").hide();
							jQuery(".page-views .view-display-id-page fieldset.container-inline").hide();
							jQuery(".views-field-field-pv-pdf").hide();
							jQuery(".views-field-field-bir-pdf").hide();

							jQuery(document).delegate("#edit-reset","click",function(event) {
							    event.preventDefault();
							    jQuery("form").each(function(){
							    	jQuery("form select option").removeAttr("selected");
							    	this.reset();
							    });
							    return false;
							});
							
							jQuery(document).delegate("#search_po","click",function(event) {
									jQuery.colorbox({inline:true,href:"#block-views-exp-purchase-order-page",
						            onCleanup: function() {
						                 jQuery("#block-views-exp-purchase-order-page").hide();
						            },
						            onOpen: function() {
						                 jQuery("#block-views-exp-purchase-order-page").show();
						            }
						      });
							});

							jQuery(document).delegate("#search_po_org","click",function(event) {
									jQuery.colorbox({inline:true,href:"#block-views-868e572a260bf9b206edd38b4450a70a",
						            onCleanup: function() {
						                 jQuery("#block-views-868e572a260bf9b206edd38b4450a70a").hide();
						            },
						            onOpen: function() {
						                 jQuery("#block-views-868e572a260bf9b206edd38b4450a70a").show();
						            }
						      });
							});

							jQuery(document).delegate("#search_po_internal","click",function(event) {
									jQuery.colorbox({inline:true,href:"#block-views-c6fdb8befb9529da71405b3c8d1fa146",
						            onCleanup: function() {
						                 jQuery("#block-views-c6fdb8befb9529da71405b3c8d1fa146").hide();
						            },
						            onOpen: function() {
						                 jQuery("#block-views-c6fdb8befb9529da71405b3c8d1fa146").show();
						            }
						      });
							});

							jQuery(document).delegate("#search_po_internal_archive","click",function(event) {
									jQuery.colorbox({inline:true,href:"#block-views-693b547328afeab25059889134804b8d",
						            onCleanup: function() {
						                 jQuery("#block-views-693b547328afeab25059889134804b8d").hide();
						            },
						            onOpen: function() {
						                 jQuery("#block-views-693b547328afeab25059889134804b8d").show();
						            }
						      });
							});

							jQuery(document).delegate("#search_po_org_archive","click",function(event) {
									jQuery.colorbox({inline:true,href:"#block-views-6dfb00ce7321246c6478d4842c498f99",
						            onCleanup: function() {
						                 jQuery("#block-views-6dfb00ce7321246c6478d4842c498f99").hide();
						            },
						            onOpen: function() {
						                 jQuery("#block-views-6dfb00ce7321246c6478d4842c498f99").show();
						            }
						      });
							});

							jQuery(document).delegate("#search_po_archive","click",function(event) {
									jQuery.colorbox({inline:true,href:"#block-views-738101ae8c972ba2d34f040a5821d3bc",
						            onCleanup: function() {
						                 jQuery("#block-views-738101ae8c972ba2d34f040a5821d3bc").hide();
						            },
						            onOpen: function() {
						                 jQuery("#block-views-738101ae8c972ba2d34f040a5821d3bc").show();
						            }
						      });
							});

							jQuery(document).delegate("#search_pv","click",function(event) {
									jQuery.colorbox({inline:true,href:"#block-views-exp-payment-voucher-page",
						            onCleanup: function() {
						            		 removePopup();
						                 jQuery("#block-views-exp-payment-voucher-page").hide();
						            },
						            onOpen: function() {
						                 jQuery("#block-views-exp-payment-voucher-page").show();
						            }
						      });
							});

							jQuery(document).delegate("#search_pv_archive","click",function(event) {
									jQuery.colorbox({inline:true,href:"#block-views-ca1aea4df2ed752f808b761f896c3221",
						            onCleanup: function() {
						                 jQuery("#block-views-ca1aea4df2ed752f808b761f896c3221").hide();
						            },
						            onOpen: function() {
						                 jQuery("#block-views-ca1aea4df2ed752f808b761f896c3221").show();
						            }
						      });
							});

							jQuery(document).delegate("#search_pv_internal","click",function(event) {
									jQuery.colorbox({inline:true,href:"#block-views-dd3be61bbbc92b6d63f9444b825d9cc0",
						            onCleanup: function() {
						                 jQuery("#block-views-dd3be61bbbc92b6d63f9444b825d9cc0").hide();
						            },
						            onOpen: function() {
						                 jQuery("#block-views-dd3be61bbbc92b6d63f9444b825d9cc0").show();
						            }
						      });
							});

							jQuery(document).delegate("#search_pv_internal_archive","click",function(event) {
									jQuery.colorbox({inline:true,href:"#block-views-c18c62acb3a53017a53b786c6b1b87de",
						            onCleanup: function() {
						                 jQuery("#block-views-c18c62acb3a53017a53b786c6b1b87de").hide();
						            },
						            onOpen: function() {
						                 jQuery("#block-views-c18c62acb3a53017a53b786c6b1b87de").show();
						            }
						      });
							});

							jQuery(document).delegate("#search_bir","click",function(event) {
									jQuery.colorbox({inline:true,href:"#block-views-exp-bir-page",
						            onCleanup: function() {
						            		 removePopup();
						                 jQuery("#block-views-exp-bir-page").hide();
						            },
						            onOpen: function() {
						                 jQuery("#block-views-exp-bir-page").show();
						            }
						      });
							});

							jQuery(document).delegate("#search_bir_internal","click",function(event) {
									jQuery.colorbox({inline:true,href:"#block-views-exp-bir-internal-user-page",
						            onCleanup: function() {
						            		 removePopup();
						                 jQuery("#block-views-exp-bir-internal-user-page").hide();
						            },
						            onOpen: function() {
						                 jQuery("#block-views-exp-bir-internal-user-page").show();
						            }
						      });
							});

							jQuery(document).delegate("#search_bir_archive","click",function(event) {
									jQuery.colorbox({inline:true,href:"#block-views-exp-bir-archived-page",
						            onCleanup: function() {
						                 jQuery("#block-views-exp-bir-archived-page").hide();
						            },
						            onOpen: function() {
						                 jQuery("#block-views-exp-bir-archived-page").show();
						            }
						      });
							});

							jQuery(document).delegate("#search_bir_archive_internal","click",function(event) {
									jQuery.colorbox({inline:true,href:"#block-views-3697950d25ce866451be85668af11295",
						            onCleanup: function() {
						                 jQuery("#block-views-3697950d25ce866451be85668af11295").hide();
						            },
						            onOpen: function() {
						                 jQuery("#block-views-3697950d25ce866451be85668af11295").show();
						            }
						      });
							});

							jQuery(document).delegate("#search_po_report","click",function(event) {
									jQuery.colorbox({inline:true,href:"#block-views-exp-reports-vendor-page",
						            onCleanup: function() {
						                 jQuery("#block-views-exp-reports-vendor-page").hide();
						            },
						            onOpen: function() {
						                 jQuery("#block-views-exp-reports-vendor-page").show();
						            }
						      });
							});

							jQuery(document).delegate(".page-views .view-display-id-page input","click",function(event) {
							    updateMethod("batch-download");
							    updateMethod("xml");
							    updateMethod("batch-csv");
							    updateMethod("batch-excel");
							    updatePrint();
							});

							jQuery(document).delegate("#xml_view","click",function(event) {
							    var url = jQuery(this).attr("href");
							    if(url == "#"){
							    	event.preventDefault();
							    	alert("Please select at least one item.");
							    	return false;
							    }
							});

							jQuery(document).delegate("#csv_view","click",function(event) {
							    var url = jQuery(this).attr("href");
							    if(url == "#"){
							    	event.preventDefault();
							    	alert("Please select at least one item.");
							    	return false;
							    }
							});

							jQuery(document).delegate("#pdf_view","click",function(event) {
							    var url = jQuery(this).attr("href");
							    if(url == "#"){
							    	event.preventDefault();
							    	alert("Please select at least one item.");
							    	return false;
							    }
							});

							jQuery(document).delegate("#excel_view","click",function(event) {
							    var url = jQuery(this).attr("href");
							    if(url == "#"){
							    	event.preventDefault();
							    	alert("Please select at least one item.");
							    	return false;
							    }
							});

							jQuery(document).delegate("#print_view","click",function(event) {
							    var url = jQuery(this).attr("href");
							    if(url == "#"){
							    	event.preventDefault();
							    	alert("No file to print.");
							    	return false;
							    }
							});

						})';

		drupal_add_js($jq, array('type' => 'inline', 'scope' => 'header'));

	}	

	//Added by Emmanuel Hallarsis   
	if(strstr(current_path(), 'purchase-order/details') || strstr(current_path(), 'archive/purchase-order/details') || strstr(current_path(), 'list-purchase-order-org/details') || strstr(current_path(), 'archive/list-purchase-order-org/details') || strstr(current_path(), 'list-purchase-order/details') || strstr(current_path(), 'archive/list-purchase-order/details')){
		$jq = 'jQuery(document).ready(function () {
							  parent.jQuery.colorbox.resize({
							    innerWidth:jQuery("body").width(),
							    innerHeight:jQuery("body").height(),
							  });
						})';

		drupal_add_js($jq, array('type' => 'inline', 'scope' => 'footer'));
		$variables['theme_hook_suggestion'] = 'page__podetails';
	}	

	if(strstr(current_path(), 'archive/list-purchase-order-org')){
		$jq = 'jQuery(document).ready(function () {
							  jQuery("#block-menu-menu-internal-users-menu a").each(function() {
								    if(jQuery(this).attr("href") === "/archive/list-purchase-order") {
								        jQuery(this).parents(".leaf").hide();
								    }
								});
						})';

		drupal_add_js($jq, array('type' => 'inline', 'scope' => 'footer'));
	}	

}

function smprime_theme(&$existing, $type, $theme, $path) {
  $hooks['user_login'] = array(
    'template' => 'templates/user-login',
    'render element' => 'form',
  );

  $hooks['user_pass'] = array(
    'template' => 'templates/forgot-pass',
    'render element' => 'form',
  );  

  return $hooks;
}

function smprime_preprocess_user_login(&$variables) {
  $variables['intro_text'] = t('Administrator Login');
  $variables['rendered'] = drupal_render_children($variables['form']);
}

function smprime_preprocess_user_pass(&$variables) {
  $variables['rendered'] = drupal_render_children($variables['form']);
}