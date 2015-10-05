<?php
$security_questions = url("global-settings/security-questions", array('absolute' => true));
$ldap = url("settings/ldap", array('absolute' => true));
$announcements = url("node/41/edit", array('absolute' => true));
$terms = url("node/40/edit", array('absolute' => true));
$emails = url("global-settings/email-templates", array('absolute' => true));
$footer = url("global-settings/footer-links", array('absolute' => true));
$contactus = url("node/75/edit", array('absolute' => true));
$quickguide = url("node/74/edit", array('absolute' => true));
$pass_policy = url("admin/config/people/password_policy/1/edit", array('absolute' => true));
$housekeeping = url("global-settings/housekeeping", array('absolute' => true));
$vis = url("vis-results", array('absolute' => true));
?>

<div class="global-settings-wrapper">
	<ul>
	 <li><a href="<?php print $ldap; ?>"><span>AD Settings/Parameters</span></a></li>
	 <li><a href="<?php print $announcements; ?>"><span>Announcements</span></a></li>
	 <li><a href="<?php print $contactus; ?>"><span>Contact Us</span></a></li>
	 <li><a href="<?php print $footer; ?>"><span>Footer Links</span></a></li>	 
	 <li><a href="<?php print $housekeeping; ?>"><span>Housekeeping</span></a></li>
	 <li><a href="<?php print $emails; ?>"><span>Notification Templates</span></a></li>
	 <li><a href="<?php print $pass_policy; ?>"><span>Password Policies</span></a></li>
	 <li><a href="<?php print $quickguide; ?>"><span>Quickguide</span></a></li>	 
	 <li><a href="<?php print $security_questions; ?>"><span>Security Questions</span></a></li>
	 <li><a href="<?php print $terms; ?>"><span>Terms and Conditions</span></a></li>
	 <li><a href="<?php print $vis; ?>"><span>Vendor Information Sheet</span></a></li>
	</ul>
</div>
