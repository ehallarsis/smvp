/**
 * @file
 * A JavaScript file for the theme.
 *
 * In order for this JavaScript to be loaded on pages, see the instructions in
 * the README.txt next to this file.
 */

// JavaScript should be made compatible with libraries other than jQuery by
// wrapping it with an "anonymous closure". See:
// - http://drupal.org/node/1446420
// - http://www.adequatelygood.com/2010/3/JavaScript-Module-Pattern-In-Depth
(function ($, Drupal, window, document, undefined) {
	
  jQuery(document).ready(function () {
		$("#block-menu-menu-sm-admin-menu > ul").addClass("nav nav-tabs");
		$(".not-front #content .view-filters").after("<div class='clearfix'></div>");
		$(".not-front #content #edit-companyname-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-manage-usertype #edit-name-wrapper").wrap("<div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'>");
		$("<span class='form-required' title='This field is required.'>*</span>").appendTo(".page-user-password #login-form .form-item-name > label");
		$(".not-front #navigation ul.nav-tabs > li > span").wrap("<a>");
		$(".not-front input[type='text'], .not-front input[type='password']").addClass("form-control");
		// $("div.poAccordBody table.smTable tbody tr.views-row td:last-child").addClass("tdWrapper");
		
		//Settings
		$(".page-settings-ldap #content #smcfb-ldap-settings-form > div > input").wrapAll("<div class='form-actions'>");
		$(".page-ldap-adduser #content #smv-ldap-adduser-form div > input[type='submit']").wrapAll("<div class='form-actions'>");

		//Vendor List
		$(".page-vendor-list #content .views-exposed-widgets #edit-search-wrapper, .page-vendor-list #content .views-exposed-widgets #edit-created-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-vendor-list #content .views-exposed-widgets #edit-main-wrapper, .page-vendor-list #content .views-exposed-widgets #edit-status-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-vendor-list #content .views-exposed-widgets #edit-verified-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-vendor-list .views-submit-button, .page-vendor-list #content .views-reset-button").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 no-padding'>");
		
		//Company Users
		$(".page-company-users #content #poSearch .view-header a").wrap("<div class='user'>");
		$(".page-manage-usertype #content #poSearch .view-header a").wrap("<div class='user'>");
		$(".page-company-users .views-submit-button, .page-company-users #content .views-reset-button").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 no-padding'>");
		$(".page-company-users #content .views-exposed-widgets #edit-search-wrapper, .page-company-users #content .views-exposed-widgets #edit-created-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-company-users #content .views-exposed-widgets #edit-usertype-wrapper, .page-company-users #content .views-exposed-widgets #edit-org-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-company-users #content .views-exposed-widgets #edit-status-wrapper").wrap("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-ldap-adduser #content form#smv-ldap-adduser-form table.smTable").addClass("ldapTable");
		
		//User Types
		//$(".page-manage-usertype #content .view-admin-manage-roles .views-exposed-widgets #edit-name-wrapper").prepend("<label for='edit-search'>Search</label>");
		$(".page-usertype #content #smv-usertype-form > div > div.form-type-checkbox, .page-usertype #content #smv-usertype-form > div > div.po-org-wrapper").wrapAll("<div class='userAccessContainer'>");
		$(".page-usertype #content #smv-usertype-form > div > input[type='submit'], .page-usertype #content #smv-usertype-form > div > a").wrapAll("<div class='form-actions form-userType'>");
		$(".page-manage-usertype .views-submit-button, .page-manage-usertype  #content .views-reset-button").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 no-padding'>");
		
		//Email Templates
		$(".not-front #rules-form-wrapper form#rules-ui-edit-element > div > input[type='submit'], .not-front #rules-form-wrapper form#rules-ui-edit-element > div > a").wrapAll("<div class='form-actions'>");
		$(".not-front #rules-form-wrapper form#rules-ui-edit-element div.fieldset-wrapper input[type='text'], .not-front #rules-form-wrapper form#rules-ui-edit-element div.fieldset-wrapper textarea").addClass("form-control");
		
		//Manage PO Files
		$(".page-manage-purchase-order .views-submit-button, .page-manage-purchase-order .views-reset-button, .page-archive-list-purchase-order .views-submit-button, .page-archive-list-purchase-order .views-reset-button").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 no-padding'>");
		$(".page-manage-files .view-filters #edit-vendorno-wrapper, .page-manage-files .view-filters #edit-ponumber-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-manage-files-purchase-order .view-filters #edit-date-wrapper, .page-manage-files-purchase-order .view-filters #edit-org-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-manage-files-purchase-order .view-filters #edit-archive-wrapper, .page-manage-files-purchase-order .view-filters #edit-status-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		
		// Manage Goods Receipt, Invoice Receipt, Payment Details
		$(".page-manage-files-goods-receipt .views-submit-button, .page-manage-files-goods-receipt .views-reset-button, .page-manage-files-invoice-receipt .views-submit-button, .page-manage-files-invoice-receipt .views-reset-button, .page-manage-files-payment-details .views-submit-button, .page-manage-files-payment-details .views-reset-button").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 no-padding'>");
		$(".page-manage-files-goods-receipt .view-filters #edit-date-wrapper, .page-manage-files-goods-receipt .view-filters #edit-archive-wrapper, .page-manage-files-invoice-receipt .view-filters #edit-date-wrapper, .page-manage-files-invoice-receipt .view-filters #edit-archive-wrapper, .page-manage-files-payment-details .view-filters #edit-archive-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");

		//Manage Non PO Files
		$(".page-manage-files-nonpo .views-submit-button, .page-manage-files-nonpo .views-reset-button, .page-manage-files-payment-voucher .views-submit-button, .page-manage-files-payment-voucher .views-reset-button").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 no-padding'>");
		$(".page-manage-files-nonpo .view-filters #edit-vendorno-wrapper, .page-manage-files-payment-voucher .view-filters #edit-vendorno-wrapper").unwrap("<div></div>");
		$(".page-manage-files-nonpo .view-filters #edit-docno-wrapper, .page-manage-files-nonpo .view-filters #edit-vendorno-wrapper, .page-manage-files-payment-voucher .view-filters #edit-docno-wrapper, .page-manage-files-payment-voucher .view-filters #edit-vendorno-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-manage-files-nonpo .view-filters #edit-date-wrapper, .page-manage-files-nonpo .view-filters #edit-archive-wrapper, .page-manage-files-payment-voucher .view-filters #edit-date-wrapper, .page-manage-files-payment-voucher .view-filters #edit-archive-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		
		//Manage Pending OR
		$(".page-manage-files-pending-or .view-filters #edit-acctg-docno-wrapper, .page-manage-files-pending-or .view-filters #edit-postingdate-wrapper, .page-reports-pending-or .view-filters #edit-acctg-docno-wrapper, .page-reports-pending-or .view-filters #edit-postingdate-wrapper, .page-reports-pending-or .view-filters #edit-vendorno-wrapper").wrap("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-manage-files-pending-or .view-filters .views-submit-button, .page-manage-files-pending-or .view-filters .views-reset-button, .page-reports-pending-or .view-filters .views-submit-button, .page-reports-pending-or .view-filters .views-reset-button").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		
		//Manage BIR
		$(".page-manage-files-bir .views-submit-button, .page-manage-files-bir .views-reset-button").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 no-padding'>");
		$(".page-manage-files-bir .view-filters #edit-vendorno-wrapper").unwrap("<div></div>");
		$(".page-manage-files-bir .view-filters #edit-vendorno-wrapper, .page-manage-files-bir .view-filters #edit-year-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-manage-files-bir .view-filters #edit-quarterno-wrapper, .page-manage-files-bir .view-filters #edit-archive-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		
		//Exception Management
		$(".page-manage-exception .views-submit-button, .page-manage-exception #content .views-reset-button").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 no-padding'>");
		$(".page-manage-exception #edit-title-wrapper, .page-manage-exception #edit-date-wrapper, .page-manage-exception #edit-folder-wrapper").wrap("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		
		//Archive
		$(".page-archive-purchase-order .views-submit-button, .page-archive-purchase-order  #content .views-reset-button, .page-archive-bir-form-2307 .views-submit-button, .page-archive-bir-form-2307  #content .views-reset-button").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 no-padding'>");
		$(".page-archive-payment-voucher .views-submit-button, .page-archive-payment-voucher  #content .views-reset-button").wrapAll("<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 no-padding'>");
		
		//Housekeeping
		$(".page-global-settings-housekeeping #content #smv-global-settings-setup-edit-form > div > input[type='submit'], .page-global-settings-housekeeping #content #smv-global-settings-setup-edit-form > div > a").wrapAll("<div class='form-actions'>");
		
		//Sync Settings
		$(".page-admin-config-system-cron #content form input[type='submit']").wrapAll("<div class='form-actions'>");
		
		//Settings - Vendor Information Sheet
		if ($(".logged-in.page-node-webform-results #content h1#page-title").length != 0){
			$(".page-node-webform-results #content ul.secondary").css("display","block");
			$(".page-node-webform-results #content table.sticky-table.smTable tbody tr td:nth-child(5)").css("display","none");
		}
		
		//Clone Height Purchase Order
		var height = $("div.view-purchase-order-container").outerHeight() - 40;
		$(".page-purchase-order #content #block-views-purchase-order-search > div.view-purchase-order > div.view-content.ui-accordion").css("max-height",height);
		$(".page-archive-purchase-order #content #block-views-purchase-order-archive-search > div.view-purchase-order-archive > div.view-content.ui-accordion").css("max-height",height);
		$(".page-list-purchase-order-org #content div.view-purchase-order-org-internal-users form table.views-table > tbody").css("max-height",height + 3);
		$(".page-list-purchase-order #content div.view-purchase-order-internalusers form table.views-table > tbody").css("max-height",height + 3);
		
	});
	
})(jQuery, Drupal, this, this.document);
// JavaScript Document