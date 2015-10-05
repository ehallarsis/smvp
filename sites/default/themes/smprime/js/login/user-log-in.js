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
		detachHeader();
		$("#user-login #login-form input, .page-user-password #login-form input[type='text']").addClass("form-control");
		$("body.page-user-password").removeClass("not-front");
		$("#user-login #login-form input#edit-submit").addClass("btn-primary");
		$("#user-login #edit-actions, #user-login .login-reset-pass, .page-user-password #edit-actions, .page-user-password .back").addClass("no-padding col-lg-6 col-md-6 col-sm-6 col-xs-6");
		$("footer #block-menu-menu-footer-csr").prepend("<h2 class='block-title'>CSR</h2>");
		$(".not-logged-in #footer .block-menu > ul.menu").addClass("loginDropDown");
		$(".not-front #footer .block-menu > ul.menu").addClass("notFrontLoginDropDown");
		$("<div class='clearfix'></div><div class='login-border'></div>").insertAfter(".page-user-password #login-form");
		$("<div class='login-footer-note col-lg-4 col-lg-offset-7 col-md-4 col-md-offset-7 col-sm-4 col-sm-offset-7 col-xs-4 col-xs-offset-7 no-padding'><p class='forgotpass-note'>Because you're accessing sensitive information, you need to verify your User ID, e-mail Address and answer to your security question correctly to access your account.<br><br>Reset password instruction will be sent to your registered email address.</p></div>").insertAfter(".page-user-password .login-border");
		$("body.page-user-login").removeClass("not-front").addClass("front");
		footerIconHover();
		
		//Announcement
		var goDaddyLogo = $(".not-logged-in #user-login h1.goDaddyLogo");
		if ($(".not-logged-in #login-form div#announce-icon").length == 0){
			goDaddyLogo.css('margin-top','109px');
		}
	});
	
	function detachHeader(){
		var toBeDetach_footer = $(".not-logged-in > footer.region-footer").detach();
		var toBeDetach_footerNotFront = $(".not-front > footer.region-footer").detach();
		var toBeDetach_securityQuestion = $(".page-user-password #security_question_wrapper").detach();
		var toBeDetach_back = $(".page-user-password .back").detach();
		toBeDetach_footer.appendTo(".footerMenu");
		toBeDetach_footerNotFront.appendTo(".notFrontFooterMenu");
		toBeDetach_securityQuestion.insertAfter(".page-user-password #login-form div.form-item-name");
		toBeDetach_back.insertAfter(".page-user-password #edit-actions");
	}
	
	function footerIconHover(){
		$('.block:has(ul.menu)').mouseenter(function(){
			$(this).find('.loginDropDown').slideDown(300);
			$(this).find('.notFrontLoginDropDown').slideDown(100);
		});
		$('.block:has(ul.menu)').mouseleave(function(){
			$(this).find('.loginDropDown').slideUp(100);
			$(this).find('.notFrontLoginDropDown').slideUp(0);
		});
	}

})(jQuery, Drupal, this, this.document);
