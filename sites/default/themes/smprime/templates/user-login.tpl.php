<?php
global $base_url;

$ldap_login = url("ldap/sso", array('absolute' => true));
$vis_link = url("vendor-information-sheet", array('absolute' => true));
$forgot_pass = url("user/password", array('absolute' => true));

$announcement_link = $base_url . '/announcements?width=50%25&height=60%25&top=50';
$quickguide_link 	 = $base_url . '/quickguide?width=50%25&height=60%25&top=50';
$contactus_link 	 = $base_url . '/contact-us?width=50%25&height=60%25&top=50';

?>

<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
	<div class="user-logo"></div>
    <h3>Vendor Portal</h3>
    <div class="log-in-footer"></div>
</div>
<div id="login-form" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 no-padding">
	
	<!-- Render login form reset link -->
	<?php print $rendered; ?>
	
	<!-- Render password reset link -->
	<div class='login-reset-pass'>  	
		<a href="<?php print $forgot_pass; ?>" title="Reset your password">Forgot Password</a>
	</div>

    <div class="clearfix"></div>	
    <?php if(smv_global_settings_announcement_loginpage_display()): ?>
			<div id="announce-icon"class="user-right-icon" ><a class="colorbox-node" href="<?php print $announcement_link; ?>" >Announcement</a></div>
		<?php endif; ?>

		<div id="quick-icon"class="user-right-icon" ><a class="colorbox-node" href="<?php print $quickguide_link; ?>">Quickguide</a></div>
    <div id="contact-icon"class="user-right-icon" ><a class="colorbox-node" href="<?php print $contactus_link; ?>">Contact us</a></div>

</div>
<div class="clearfix"></div>
<div class="login-border"></div>
<div class="login-footer-note col-lg-4 col-lg-offset-7 col-md-4 col-md-offset-7 col-sm-4 col-sm-offset-7 col-xs-4 col-xs-offset-7 no-padding"><p>*Best viewed with Microsoft Internet Explorer 8 and up, Moziilla Firefox 32+ and Google Chrome 37 and above.</p></div>
<h1 class="goDaddyLogo">GODADDY</h1>
<div class="footerMenu"></div>
<!--<div id="siteseal">
	<script type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=eylds6M8Advyt5RuLXPwZVBPMYMQHMfRBHo48prjLaxF4cAd3xT10yazFlcJ"></script>
</div>-->