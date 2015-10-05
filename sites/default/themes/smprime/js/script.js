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

  jQuery.noConflict();
  jQuery(document).ready(function () {
		
		checkIE()
		activeTab();
		detachHeader();
		tableAccordion();

		//---- quickguides -------
		$(document).ajaxSuccess(function() {			
			//anchor link
			$('#quickquide_shortcuts ul li a').click(function(){
				var a_class = $(this).attr('class');
				$('#cboxLoadedContent').animate({
					scrollTop: $("#cboxLoadedContent div." + a_class).offset().top - 100
				}, 1000);
			});			
			//add back on top 
			$('body.not-logged-in #cboxClose').before('<a href="javascript:void(0)" id="backTop">Back To Top</a>');
			$('body.not-logged-in a#backTop').click(function(){
				$('#cboxLoadedContent').animate({
					scrollTop: 0 }, 1000);
			});
		});

  	//----terms and conditions-------
		$(".not-front #page #header > div.region-header").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-6 userInfo");
		$(".not-front #page #navigation #block-menu-menu-vendor-menu > ul").addClass("nav nav-tabs");
		
		//------PO--------------
		$(".page-manage-purchase-order #content .views-exposed-widgets, .page-purchase-order #content .views-exposed-widgets").addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12");
		$(".not-front #content .views-exposed-widgets input, .not-front #content .views-exposed-widgets select").addClass("form-control");
		$(".page-list-purchase-order-org #content #edit-datefrom-wrapper, .page-list-purchase-order-org #content #edit-dateto-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".not-front #content #edit-date-from-wrapper, .not-front #content #edit-date-to-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".not-front #content #edit-po-from-wrapper, .not-front #content #edit-po-to-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".not-front #content #edit-po-status-wrapper, .not-front #content #edit-po-org-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-purchase-order #content .views-submit-button, .page-purchase-order #content .views-reset-button").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 no-padding'>");
		$(".page-list-purchase-order-org #content .views-submit-button, .page-list-purchase-order-org #content .views-reset-button").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 no-padding'>");
		$(".page-purchase-order #content #poSearch").addClass("no-padding");
		$(".page-purchase-order #content .view-display-id-page, .page-manage-purchase-order #content .view-display-id-page, .page-purchase-order #content #block-views-purchase-order-search").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-6 no-padding");
		$(".page-archive-purchase-order #content #block-views-purchase-order-archive-search, .page-archive-purchase-order #content .view-display-id-page").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-6 no-padding");
		$(".page-archive-purchase-order #content .view-display-id-page").after("<div class='clearfix'></div>");
		$(".page-purchase-order #content .view-display-id-page > .view-content, .page-manage-purchase-order #content .view-display-id-page > .view-content, .page-archive-purchase-order #content .view-display-id-page > .view-content").addClass("view-purchase-order-container");
		$(".page-purchase-order #content .view-display-id-page").after("<div class='clearfix'></div>");
		$(".page-purchase-order #content #block-views-purchase-order-search > .view-display-id-search > .view-header  > div").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-6 accordHeader");
		$(".page-purchase-order #content #block-views-purchase-order-search > .view-display-id-search > .ui-accordion").addClass("clearfix");
		$(".page-purchase-order #content #block-views-purchase-order-search > .view-display-id-search > .ui-accordion > h3 > a > div").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-6")
		$(".page-purchase-order #content #block-views-purchase-order-search > .view-display-id-search > .ui-accordion > h3 > a").append("<div class='clearfix'></div>")
		$(".page-manage-purchase-order #content > #block-views-784ddd18cbfc36fcc97011e306b4e5dd").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-6");		
		$(".page-manage-purchase-order #content > .view-display-id-page").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-6");
		$(".page-manage-purchase-order #content .view-display-id-page").after("<div class='clearfix'></div>");
		$("#views-form-purchase-order-internalusers-search .poHeader select").addClass("form-control");
		$(".not-front #content form table tbody > tr.showHide > td > table ").wrap("<div class='scrollable'>");
		$(".page-manage-purchase-order #content .view-admin-manage-files .views-exposed-widgets #edit-title-wrapper").prepend("<label for='edit-search'>Search</label>");
		$(".page-manage-purchase-order #content .view-admin-manage-files form > div > fieldset > div.fieldset-wrapper input[type='submit']").addClass('btn btn-primary btn-sm');
		$(".page-list-purchase-order-org #content #edit-datefrom-wrapper > label").text("Date From");
		$(".page-list-purchase-order-org #content #edit-dateto-wrapper > label").text("Date To");
		$(".page-list-purchase-order-org #content #edit-po-from-wrapper > label").text("PO Number From");
		$(".page-list-purchase-order-org #content #edit-po-to-wrapper > label").text("PO Number To");
		$(".page-list-purchase-order #content .views-submit-button, .page-list-purchase-order #content .views-reset-button").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 no-padding'>");
		$(".page-manage-files-purchase-order #content .views-submit-button, .page-manage-files-purchase-order #content .views-reset-button").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 no-padding'>");
		$(".page-manage-files-purchase-order #content .view-admin-manage-files .views-exposed-widgets #edit-search-wrapper").prepend("<label for='edit-search'>Search</label>");
		$(".page-list-purchase-order #content div.even, .page-archive-list-purchase-order #content div.even, .page-list-purchase-order-org #content div.even").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-6 no-padding");
		$(".page-list-purchase-order #content > div.view-purchase-order-internalusers, .page-archive-list-purchase-order #content > div.view-purchase-order-archive-internal-users, .page-list-purchase-order-org #content > div.view-purchase-order-org-internal-users").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-6 no-padding");
		$(".page-list-purchase-order #content > div.view-purchase-order-internalusers > .view-content, .page-archive-list-purchase-order #content > div.view-purchase-order-archive-internal-users > .view-content, .page-list-purchase-order-org #content > div.view-purchase-order-org-internal-users > .view-content").addClass("view-purchase-order-container");
		$(".page-list-purchase-order #content > div.view-purchase-order-internalusers, .page-archive-list-purchase-order #content > div.view-purchase-order-archive-internal-users, .page-list-purchase-order-org #content > div.view-purchase-order-org-internal-users").after("<div class='clearfix'></div>");
		$(".page-list-purchase-order  #content div.even").prepend("<span class='list-header'>LIST</span>");
		$("div.po-status").removeClass("col-lg-6 col-md-6 col-sm-6 col-xs-6");
		$("<div class='col-lg-12 no-padding purchaseOrderHeader'><div class='col-lg-5 col-md-5 col-sm-5 col-xs-5'>PO Number</div><div class='col-lg-4 col-md-4 col-sm-4 col-xs-4'>Date</div><div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>Status</div></div>").insertBefore(".page-purchase-order #content #block-views-purchase-order-search .view-purchase-order > .view-content");
		$("<div class='col-lg-12 no-padding purchaseOrderHeader'><div class='col-lg-5 col-md-5 col-sm-5 col-xs-5'>PO Number</div><div class='col-lg-4 col-md-4 col-sm-4 col-xs-4'>Date</div><div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>Status</div></div>").insertBefore(".page-archive-purchase-order #content #block-views-purchase-order-archive-search .view-purchase-order-archive > .view-content");
		$("h3.poAccordHeader div.po-status:contains('For Revision')").css("color","red");
		
		//-------Payment Voucher---------
		$(".page-payment-voucher #content .view-content").addClass("clearfix");
		$(".page-list-payment-voucher #content #edit-datefrom-wrapper, .page-list-payment-voucher #content #edit-dateto-wrapper, .page-archive-payment-voucher .view-filters #edit-datefrom-wrapper, .page-archive-payment-voucher .view-filters #edit-dateto-wrapper, .page-archive-list-payment-voucher .view-filters #edit-datefrom-wrapper, .page-archive-list-payment-voucher .view-filters #edit-dateto-wrapper").wrap("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-payment-voucher #content #edit-datefrom-wrapper, .page-payment-voucher #content #edit-dateto-wrapper, .page-payment-voucher #content #edit-docno-wrapper").wrap("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-archive-payment-voucher #content .views-exposed-widgets, .page-payment-voucher #content .views-exposed-widgets").prepend("<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2'><h3>Voucher Date</h3></div>")
		$(".not-front #content #edit-datefrom-wrapper .views-widget, .not-front #content #edit-dateto-wrapper .views-widget").addClass("upperBox")
		$(".page-list-payment-voucher #content .views-submit-button, .page-list-payment-voucher #content .views-reset-button, .page-archive-list-payment-voucher #content .views-submit-button, .page-archive-list-payment-voucher #content .views-reset-button").wrapAll("<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 no-padding'>");
		$(".page-payment-voucher #content .views-submit-button, .page-payment-voucher #content .views-reset-button").wrapAll("<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 no-padding'>");
		$(".page-list-payment-voucher .views-exposed-widgets #edit-pvno-wrapper, .page-archive-list-payment-voucher .views-exposed-widgets #edit-pvno-wrapper, .page-archive-payment-voucher .views-exposed-widgets #edit-pvno-wrapper, .page-payment-voucher .views-exposed-widgets #edit-pvno-wrapper").wrap("<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2'>")
		$(".page-list-payment-voucher #content .views-exposed-widgets, .page-archive-list-payment-voucher #content .views-exposed-widgets").prepend("<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2'><h3>Voucher Date</h3></div>")
		
		//-------BIR Form-------------
		$(".page-bir-form-2307 #content .view-content").addClass("clearfix");
		$(".not-front #content #edit-yearfrom-wrapper, .not-front #content #edit-yearto-wrapper").wrapAll("<div class='col-lg-6 col-md-6 col-sm-6 col-xs-6 bir-search'>");
		$(".not-front #content #edit-quarterfrom-wrapper, .not-front #content #edit-quarterto-wrapper").wrapAll("<div class='col-lg-6 col-md-6 col-sm-6 col-xs-6 bir-search'>");
		$(".not-front #content .view-bir .views-exposed-widgets .bir-search").wrapAll("<div class='col-lg-6 col-md-6 col-sm-6 col-xs-6 no-padding'>");
		$(".page-list-bir-form #content .views-submit-button, .page-list-bir-form #content .views-reset-button, .page-bir-form-2307 #content .views-submit-button, .page-bir-form-2307 #content .views-reset-button, .page-archive-list-bir-form #content .views-submit-button, .page-archive-list-bir-form #content .views-reset-button").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 no-padding'>");
		
		//---------Non-PO-----
		$(".page-non-po #content .view-display-id-page .view-filters > form").append("<div class='clearfix'></div>");
		$(".page-list-non-po #content .views-exposed-widgets, .page-non-po #content .views-exposed-widgets, .page-archive-non-po #content .views-exposed-widgets, .page-archive-list-non-po #content .views-exposed-widgets").prepend("<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2'><h3>Invoice Date</h3></div>")
		$(".page-list-non-po #content .views-exposed-widgets #edit-datefrom-wrapper, .page-list-non-po #content .views-exposed-widgets #edit-dateto-wrapper, .page-non-po #content .views-exposed-widgets #edit-datefrom-wrapper, .page-non-po #content .views-exposed-widgets #edit-dateto-wrapper, .page-archive-non-po .view-filters #edit-datefrom-wrapper, .page-archive-non-po .view-filters #edit-dateto-wrapper, .page-archive-list-non-po .view-filters #edit-datefrom-wrapper, .page-archive-list-non-po .view-filters #edit-dateto-wrapper").wrap("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>")
		$(".page-list-non-po #content .views-exposed-widgets #edit-docno-wrapper, .page-non-po #content .views-exposed-widgets #edit-docno-wrapper, .page-archive-non-po .view-filters #edit-docno-wrapper, .page-archive-list-non-po .view-filters #edit-docno-wrapper").wrap("<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2'>")
		$(".page-list-non-po #content .views-submit-button, .page-list-non-po #content .views-reset-button, .page-non-po #content .views-submit-button, .page-non-po #content .views-reset-button, .page-archive-non-po #content .views-submit-button, .page-archive-non-po #content .views-reset-button, .page-archive-list-non-po #content .views-submit-button, .page-archive-list-non-po #content .views-reset-button").wrapAll("<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 no-padding'>");
		
		//------Notification-----
		$(".page-notifications #content .views-exposed-widgets").addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12");
		$(".page-notifications #content .view-content").addClass("clearfix");
		$(".page-notifications .views-exposed-widgets #edit-search-wrapper").wrap("<div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'>");
		$(".page-list-notifications .views-exposed-widgets #edit-search-wrapper").wrap("<div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'>");
		$(".page-notifications #content .view-display-id-page .view-filters").append("<div class='clearfix'></div>");
		$(".page-notifications #content .view-notifications:has(div.view-footer)").after("<div class='clearfix'></div>");//Has view-footer
		$(".page-notifications #content .view-notifications:not(:has(div.view-footer)) .view-content").after("<div class='clearfix'></div>"); //No view-footer
		$(".page-list-notifications #content > .view-user-notifications > .view-content, .page-list-notifications #content > .view-user-notifications > .view-footer").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-6 no-padding");
		$(".page-notifications #content .view-user-notifications > .view-content").addClass("no-padding");
		$(".page-list-notifications #content .view-user-notifications:has(div.view-footer)").after("<div class='clearfix'></div>");//Has view-footer
		$(".page-list-notifications #content .view-user-notifications:not(:has(div.view-footer)) .view-content").after("<div class='clearfix'></div>"); //No view-footer
		$(".page-notifications #content .view-display-id-page > .view-content, .page-notifications #content .view-display-id-page > .view-footer").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-6");
		$(".page-notifications #content .view-user-notifications:has(div.view-footer)").after("<div class='clearfix'></div>");//Has view-footer
		$(".page-notifications #content .view-user-notifications:not(:has(div.view-footer)) .view-content").after("<div class='clearfix'></div>"); //No view-footer
		$(".page-list-notifications #content .views-submit-button, .page-list-notifications #content .views-reset-button").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 no-padding'>");
		//$(".page-list-notifications #content .view-user-notifications .views-exposed-widgets #edit-search-wrapper").prepend("<label for='edit-search'>Search</label>");
		//$(".page-notifications #content .view-user-notifications .views-exposed-widgets #edit-search-wrapper").prepend("<label for='edit-search'>Search</label>");
		$(".page-notifications .views-submit-button, .page-notifications #content .views-reset-button").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 no-padding'>");
		
		//-----Vendor Information Sheet----
		$(".section-vendor-information-sheet #content form > div").prepend("<h3>Vendor Information Sheet</h3>")
		$(".section-vendor-information-sheet #content form > div > div > input[type='text'], .section-vendor-information-sheet #content form > div > div > input[type='email']").addClass("form-control")
		$(".section-vendor-information-sheet #content form > div > div > div#edit-submitted-business-organization-type > div").addClass("col-lg-2 col-md-2 col-sm-2 col-xs-2 no-margin");
		$(".section-vendor-information-sheet #content form > div > div > div#edit-submitted-business-organization-type").append("<div class='clearfix'></div>");
		$(".section-vendor-information-sheet #content form > div > div > div.required-docs-left-wrapper, .section-vendor-information-sheet #content form > div > div > div.required-docs-right-wrapper").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-6");
		$(".section-vendor-information-sheet #content form > div > div.webform-component--required-documents-text").append("<div class='clearfix'></div>");
		$(".webform-component--required-business-documents .webform-component-markup > div, .webform-component--required-attachments .webform-component-markup > div").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-6");
		$(".section-vendor-information-sheet #content form > div > fieldset.webform-component--questionnaire > div > fieldset > div.fieldset-wrapper > div").addClass("col-lg-4 col-md-4 col-sm-4 col-xs-4");
		$(".section-vendor-information-sheet #content form > div > fieldset.webform-component--questionnaire > div > fieldset > div.fieldset-wrapper > div > input").addClass("form-control");
		$(".section-vendor-information-sheet #webform-client-form-39 > div > .webform-component--questionnaire > div > div > div > div").addClass("col-lg-2 textInline");
		$(".section-vendor-information-sheet #webform-client-form-39 > div > fieldset > div > div > div").append("<div class='clearfix'></div>");
		$(".section-vendor-information-sheet #content form div div.webform-component--landline-number, .section-vendor-information-sheet #content form div div.webform-component--mobile-number, .section-vendor-information-sheet #content form div div.webform-component--fax-number, .section-vendor-information-sheet #content form div div.webform-component--e-mail-address").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-6 no-padding");
		$(".section-vendor-information-sheet #content form div div.webform-component--landline-number label, .section-vendor-information-sheet #content form div div.webform-component--mobile-number label, .section-vendor-information-sheet #content form div div.webform-component--fax-number label, .section-vendor-information-sheet #content form div div.webform-component--e-mail-address label").addClass("form-label");
		$(".section-vendor-information-sheet #content form div div.webform-component--landline-number input, .section-vendor-information-sheet #content form div div.webform-component--mobile-number input, .section-vendor-information-sheet #content form div div.webform-component--fax-number input, .section-vendor-information-sheet #content form div div.webform-component--e-mail-address input").addClass("form-input");
		
		//-----Archive
		$(".page-archive #content #edit-pofrom-wrapper, .page-archive #content #edit-poto-wrapper").wrapAll("<div class='col-lg-9 col-md-9 col-sm-9 col-xs-9 no-padding'>");
		$(".page-archive #content #edit-pofrom-wrapper, .page-archive #content #edit-poto-wrapper").wrap("<div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'>");
		
		//-----User Profile----
		$(".page-user-edit #content #user-profile-form > div > div > div > div > input, .page-user-edit #content #user-profile-form > div > div > div > div > select, .page-user-edit #content #user-profile-form > div > div > div > div > div > input, .page-user-edit #content #user-profile-form > div > div > div > div > div > div > input").addClass("form-control textInput");
		$(".page-user #user-login > div > div > h3").addClass("loginTitle");
		$(".page-user #content #user-profile-form .otheraccounts a").addClass("btn btn-primary btn-sm");
		
		//-----Vendor Other Account-----
		// $(".page-other-accounts #content .view-vendor-other-accounts .views-exposed-widgets #edit-search-wrapper").prepend("<label for='edit-search'>Search</label>");
		$(".page-other-accounts .views-exposed-widgets #edit-search-wrapper").wrap("<div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'>");
		$(".page-other-accounts #content #poSearch .view-header a").wrap("<div class='alias'>");
		$(".page-vendor-account #content #smv-vendor-accounts-form > div > input[type='submit'], .page-vendor-account #content #smv-vendor-accounts-form > div > a").wrapAll("<div class='form-actions'>");
		$(".page-vendor-account #content #smv-vendor-accounts-form div.otheraccounts a, .page-vendor-account #content #smv-vendor-accounts-edit-form div.otheraccounts a ").addClass("btn btn-primary btn-sm");
		$(".page-vendor-account #content #smv-vendor-accounts-edit-form > div > div > input[type='text'], .page-vendor-account #content #smv-vendor-accounts-form > div > div > input[type='text']").addClass("form-control");
		$(".page-other-accounts .view-vendor-other-accounts .views-submit-button, .page-other-accounts .view-vendor-other-accounts .views-reset-button").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 no-padding'>");
		
		//----User Forgot Password-----
		$(".page-user-password > #page > div.clearfix").removeClass("clearfix");
		$(".page-user-password > #page > #main > #content > form > div ").prepend('<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><div class="user-logo"></div><h3>Vendor Portal</h3><div class="log-in-footer"></div></div>')
		$(".page-user-password #content > form > div > .form-type-textfield, .page-user-password #content > form > div > #edit-actions,.page-user-password #content > form > div > input ").wrapAll('<div id="login-form" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 no-padding">')
		$(".page-user-password #user-pass > div > div > h3").addClass("loginTitle");
		$(".page-user-password #user-pass > div > #login-form #edit-name").addClass("form-control");
		
		//-----table----
		$("table").addClass("smTable");
		
		//----Tab Set Height----
		var biggestHeight = "0";
		$(".po-tabs *").each(function(){
			if ($(this).height() > biggestHeight ) {
				biggestHeight = $(this).height()+22;
			}
		});
		$(".po-tabs").height(biggestHeight);
		
		//-----Navigation Bar ------
		$("#block-menu-menu-internal-users-menu > ul").addClass("nav nav-tabs");
		$(".not-front #navigation ul.nav-tabs > li > span").wrap("<a>");
		
		//-----Reports-----
		$(".page-reports-payment-list #content .view-filters #edit-datefrom-wrapper, .page-reports-payment-list #content .view-filters #edit-dateto-wrapper, ").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-reports-payment-list #content .view-filters #edit-pofrom-wrapper, .page-reports-payment-list #content .view-filters #edit-poto-wrapper,").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-reports-payment-list #content .view-filters #edit-vendorno-wrapper, .page-reports-payment-list #content .view-filters #edit-company-wrapper,").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-reports-payment-list #content .view-filters .views-submit-button, .page-reports-payment-list #content .view-filters .views-reset-button,").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-reports-po-monitoring #content #edit-vendorno-wrapper, .page-po-monitoring-report #content #edit-vendorno-wrapper, .page-reports-po-monitoring #content .po-monitoring-search").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-reports-po-monitoring #content .view-filters #edit-datefrom-wrapper, .page-reports-po-monitoring #content .view-filters #edit-dateto-wrapper, .page-po-monitoring-report #content .view-filters #edit-datefrom-wrapper, .page-po-monitoring-report #content .view-filters #edit-dateto-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-reports-po-monitoring #content .view-filters #edit-pofrom-wrapper, .page-reports-po-monitoring #content .view-filters #edit-poto-wrapper, .page-po-monitoring-report #content .view-filters #edit-pofrom-wrapper, .page-po-monitoring-report #content .view-filters #edit-poto-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-reports-po-monitoring #content .view-filters #edit-status-wrapper, .page-reports-po-monitoring #content .view-filters #edit-org-wrapper, .page-po-monitoring-report #content .view-filters #edit-status-wrapper, .page-po-monitoring-report #content .view-filters #edit-org-wrapper").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-reports-po-monitoring #content .view-filters .views-submit-button, .page-reports-po-monitoring #content .view-filters .views-reset-button, .page-po-monitoring-report #content .view-filters .views-submit-button, .page-po-monitoring-report #content .view-filters .views-reset-button").wrapAll("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-reports-po-monitoring #content .view-filters #edit-docno-wrapper, .page-reports-po-monitoring #content .view-filters #edit-date-wrapper, .page-reports-po-monitoring #content .view-filters #edit-lineno-wrapper").wrap("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
		$(".page-po-monitoring-report div.views-exposed-form #edit-docno-wrapper, .page-po-monitoring-report div.views-exposed-form #edit-date-wrapper, .page-po-monitoring-report div.views-exposed-form #edit-lineno-wrapper").wrap("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>");
	
		//vendor
		$(".not-front #content .field-items #rw-terms-accept a").addClass("btn btn-primary btn-sm");
		$(".not-front #content .field-items a[href*='logout']").addClass("btn btn-primary btn-sm");
		
	});
	
	function checkIE(){
		if($("html").hasClass("lt-ie9")){
			//Notification
			$(".page-notifications #content div.view-content thead tr th:nth-child(1), .page-list-notifications #content div.view-content thead tr th:nth-child(1)").css({'width':'64%', 'border':'none'});
			$(".page-notifications #content div.view-content thead tr th:nth-child(2), .page-list-notifications #content div.view-content thead tr th:nth-child(2)").css({'width':'35%', 'border':'none'});
			$(".page-notifications #content div.view-content thead tr th:nth-child(3), .page-list-notifications #content div.view-content thead tr th:nth-child(3)").css({'width':'10%', 'border':'none'});
			$(".page-notifications #content div.view-content tbody tr td:nth-child(1), .page-list-notifications #content div.view-content tbody tr td:nth-child(1)").css({'width':'64%', 'border':'none'});
			$(".page-notifications #content div.view-content tbody tr td:nth-child(2), .page-list-notifications #content div.view-content tbody tr td:nth-child(2)").css({'width':'36%', 'border':'none'});
			$(".page-notifications #content div.view-content tbody tr td:nth-child(3), .page-list-notifications #content div.view-content tbody tr td:nth-child(3)").css({'width':'10%', 'border':'none'});
		
			//Purchase Order Table
			$(".page-list-purchase-order #content div.view-purchase-order-internalusers form table.views-table > thead tr th:nth-child(1), .page-list-purchase-order-org #content div.view-purchase-order-org-internal-users form table.views-table > thead tr th:nth-child(1), .page-archive-list-purchase-order #content div.even div.view-purchase-order-archive-internal-users table.views-table > thead tr th:nth-child(1)").css('width','28%');
			$(".page-list-purchase-order #content div.view-purchase-order-internalusers form table.views-table > thead tr th:nth-child(2), .page-list-purchase-order-org #content div.view-purchase-order-org-internal-users form table.views-table > thead tr th:nth-child(2), .page-archive-list-purchase-order #content div.even div.view-purchase-order-archive-internal-users table.views-table > thead tr th:nth-child(2)").css('width','44%');
			$(".page-list-purchase-order #content div.view-purchase-order-internalusers form table.views-table > thead tr th:nth-child(3), .page-list-purchase-order-org #content div.view-purchase-order-org-internal-users form table.views-table > thead tr th:nth-child(3), .page-archive-list-purchase-order #content div.even div.view-purchase-order-archive-internal-users table.views-table > thead tr th:nth-child(3)").css('width','29%');
			$(".page-list-purchase-order #content div.view-purchase-order-internalusers form table.views-table > thead tr th:nth-child(4), .page-list-purchase-order-org #content div.view-purchase-order-org-internal-users form table.views-table > thead tr th:nth-child(4), .page-archive-list-purchase-order #content div.even div.view-purchase-order-archive-internal-users table.views-table > thead tr th:nth-child(4)").css('width','10%');
			
			$(".page-list-purchase-order #content div.view-purchase-order-internalusers form table.views-table > tbody tr td:nth-child(1), .page-list-purchase-order-org #content div.view-purchase-order-org-internal-users form table.views-table > tbody tr td:nth-child(1), .page-archive-list-purchase-order #content div.even div.view-purchase-order-archive-internal-users table.views-table > tbody tr td:nth-child(1)").css('width','25%');
			$(".page-list-purchase-order #content div.view-purchase-order-internalusers form table.views-table > tbody tr td:nth-child(2), .page-list-purchase-order-org #content div.view-purchase-order-org-internal-users form table.views-table > tbody tr td:nth-child(2), .page-archive-list-purchase-order #content div.even div.view-purchase-order-archive-internal-users table.views-table > tbody tr td:nth-child(2)").css('width','40%');
			$(".page-list-purchase-order #content div.view-purchase-order-internalusers form table.views-table > tbody tr td:nth-child(3), .page-list-purchase-order-org #content div.view-purchase-order-org-internal-users form table.views-table > tbody tr td:nth-child(3), .page-archive-list-purchase-order #content div.even div.view-purchase-order-archive-internal-users table.views-table > tbody tr td:nth-child(3)").css('width','25%');
			$(".page-list-purchase-order #content div.view-purchase-order-internalusers form table.views-table > tbody tr td:nth-child(4), .page-list-purchase-order-org #content div.view-purchase-order-org-internal-users form table.views-table > tbody tr td:nth-child(4), .page-archive-list-purchase-order #content div.even div.view-purchase-order-archive-internal-users table.views-table > tbody tr td:nth-child(4)").css('width','10%');
			
			//Purchase Order Tab
			var setTab = $("div#po-tab1").find("div.po-content").css("z-index", "1");
			var forFirstTab = $("div#po-tab2, div#po-tab3");
			var forSecondTab = $("div#po-tab1, div#po-tab3");
			var forThirdTab = $("div#po-tab1, div#po-tab2");
			
			//Set Active Tab onClick
			$("div#po-tab1").live( "click", function() {
				forFirstTab.find("div.po-content").removeAttr("style");
				forFirstTab.removeClass("ie8ActiveTab");
				$("div#po-tab1").find("div.po-content").css("z-index", "1");
				$(this).addClass("ie8ActiveTab");
			});
			
			$("div#po-tab2").live( "click", function() {
				forSecondTab.find("div.po-content").removeAttr("style");
				forSecondTab.removeClass("ie8ActiveTab");
				$("div#po-tab2").find("div.po-content").css("z-index", "1");
				$(this).addClass("ie8ActiveTab");
			});
			
			$("div#po-tab3").live( "click", function() {
				forThirdTab.find("div.po-content").removeAttr("style");
				forThirdTab.removeClass("ie8ActiveTab");
				$("div#po-tab3").find("div.po-content").css("z-index", "1");
				$(this).addClass("ie8ActiveTab");
			});	
			
			//Set Active Tab onLoad
			if (window.location.href.indexOf("#po-tab2") > -1) {
				forSecondTab.find("div.po-content").removeAttr("style");
				forSecondTab.removeClass("ie8ActiveTab");
				$("div#po-tab2").find("div.po-content").css("z-index", "1");
				$("div#po-tab2").addClass("ie8ActiveTab");
			} else if (window.location.href.indexOf("#po-tab3") > -1) {
				forThirdTab.find("div.po-content").removeAttr("style");
				forThirdTab.removeClass("ie8ActiveTab");
				$("div#po-tab3").find("div.po-content").css("z-index", "1");
				$("div#po-tab3").addClass("ie8ActiveTab");
			} 
		
		}
	}
	
	function detachHeader(){
		var toBeDetach_header = $("#content > .view > div.view-header").detach(); //OLD(.view = .view-display-id-page)
		var toBeDetach_detailHeader = $(".page-list-purchase-order-org #content > div.view-purchase-order-org-internal-users > .view-content span.details-header").detach();
		var toBeDetach_purchaseDetailHeader = $(".page-list-purchase-order #content > div.view-purchase-order-internalusers > .view-content span.details-header").detach();
		var toBeDetach_archivePurchaseDetailHeader = $(".page-archive-purchase-order #content > div.view-purchase-order-archive > .view-content span.details-header").detach();
		var toBeDetach_purchasingDetailHeader = $(".page-purchase-order #content > div.view-purchase-order > .view-content span.details-header").detach();
		var toBeDetach_purchasingListDetailHeader = $(".page-archive-list-purchase-order #content > div.view-purchase-order-archive-internal-users > .view-content span.details-header").detach();
		var toBeDetach_verificationMessage= $(".section-terms-and-conditions #content div.messages").detach();
		var detach_itemList = $(".page-notifications #content .view-display-id-page > .item-list").detach();
		toBeDetach_header.appendTo("#poSearch");
		toBeDetach_detailHeader.prependTo(".page-list-purchase-order-org #content > div.view-purchase-order-org-internal-users");
		toBeDetach_purchaseDetailHeader.prependTo(".page-list-purchase-order #content > div.view-purchase-order-internalusers");
		toBeDetach_archivePurchaseDetailHeader.prependTo(".page-archive-purchase-order #content > div.view-purchase-order-archive");
		toBeDetach_purchasingDetailHeader.prependTo(".page-purchase-order #content > div.view-purchase-order");
		toBeDetach_purchasingListDetailHeader.prependTo(".page-archive-list-purchase-order #content > div.view-purchase-order-archive-internal-users");
		toBeDetach_verificationMessage.prependTo(".section-terms-and-conditions .container div#navigation");
		detach_itemList.appendTo(".page-notifications #content .view-display-id-page > .view-content");
		
		if($("div.group-vendor-password div#edit-account div.form-type-password-confirm").length == 0){
			$("div.group-vendor-password > h2").css('display','none');
		}
		
		if ($(".logged-in div.group-user-vendorinfo-contactperson h3").length == 0){
			var toBeDetach_UserAndEadd = $(".logged-in div.group-vendor-password div#edit-account > div.form-item-name, .logged-in div.group-vendor-password div#edit-account > div.form-item-mail").detach();
			toBeDetach_UserAndEadd.appendTo("div.group-user-information");
		}
		
		if ($(".logged-in div.group-user-vendorinfo-contactperson h3").length != 0){
			var detach_email = $(".page-user-edit #content #edit-account > .form-item-mail").detach();
			detach_email.insertBefore(".page-user-edit #content div.group-user-vendorinfo-contactperson div#edit-field-vendor-email2");
			$(".not-front #content #user-profile-form  #edit-field-vendor-email2, .not-front #content #user-profile-form  .form-item-mail").wrapAll("<div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'>");
		}
		
		$(".not-front #content #user-profile-form  #edit-field-vendor-contactname, .not-front #content #user-profile-form  #edit-field-vendor-contactno").wrapAll("<div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'>");
		$(".not-front #content #user-profile-form .group-user-vendorinfo-contactperson input").addClass("form-control");
		$(".not-front #content #user-profile-form .group-user-vendorinfo-contactperson").append("<div class='clearfix'></div>");
	}	
	
	function tableAccordion (){
		//Display Body
		$('tr.poHeader > td:not(:last-child)').click(function(){
			var closest = $(this).parent().next('tr.showHide');
			if($("html").hasClass("lt-ie9")){
				closest.show();
				$(this).parent().siblings(".showHide").not(closest).hide();
				return false;
			} else {
				closest.toggle();
				$(this).parent().siblings(".showHide").not(closest).hide();
				return false;
			}
		});
		
		//Resize Status column for alignment
		var poStatus = $('div.po-status');
		if($('.poAccordBody').css('display') == 'block'){
			poStatus.addClass('openBody');
			$("h3.poAccordHeader").live( "click", function() {
				poStatus.toggleClass('openBody');
			});
		} 
	}
	
	function activeTab(){
		var firstTab = $("div#po-tab1").addClass("active-tab");
		$("div#po-tab2, div#po-tab3").live( "click", function() {
			firstTab.removeClass("active-tab");
		});
		
		if (window.location.href.indexOf("#po-tab2") > -1) {
			firstTab.removeClass("active-tab");
		} else if (window.location.href.indexOf("#po-tab3") > -1) {
			firstTab.removeClass("active-tab");
		} 
	}
	
	jQuery( window ).load(function() {
		var value = window.location.href.substring(window.location.href.lastIndexOf('/') - 10);
		var url_split = value.split("/");
		var loc = $('tr.showHide > td > table > tbody > tr > td > a').val();
		var p = $("a:contains("+ value +")");
		if(("td:contains("+ url_split[0] +")")){
			$("td:contains("+ url_split[0] +")").trigger("click");
		}
	});
})(jQuery, Drupal, this, this.document);
