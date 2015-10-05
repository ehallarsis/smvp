<?php
$retails = url("admin/structure/menu/manage/menu-footer-retails", array('absolute' => true));	
$residential = url("admin/structure/menu/manage/menu-footer-residential", array('absolute' => true));	
$hotels = url("admin/structure/menu/manage/menu-footer-hotels", array('absolute' => true));	
$finance = url("admin/structure/menu/manage/menu-footer-finance", array('absolute' => true));	
$csr = url("admin/structure/menu/manage/menu-footer-csr", array('absolute' => true));	
$corporate = url("admin/structure/menu/manage/menu-footer-corporate", array('absolute' => true));	
?>

<div class="footer-links-templates-wrapper">
	<ul>
	 <li><a href="<?php print $retails; ?>"><span>Retails</span></a></li>
	 <li><a href="<?php print $residential; ?>"><span>Residential</span></a></li>
	 <li><a href="<?php print $hotels; ?>"><span>Hotels</span></a></li>
	 <li><a href="<?php print $finance; ?>"><span>Finance</span></a></li>
	 <li><a href="<?php print $csr; ?>"><span>Corporate Social Responsibility</span></a></li>
	 <li><a href="<?php print $corporate; ?>"><span>Corporate</span></a></li>
	</ul>
</div>
