<?php
$rulespath 	= "admin/config/workflow/rules/reaction/manage/";
$po_new 		= url($rulespath."rules_purchase_order_new/edit/5", array('absolute' => true));
$po_revised = url($rulespath."rules_purchase_order_revised/edit/5", array('absolute' => true));	
$po_viewed 	= url($rulespath."rules_purchase_order_viewed/edit/8", array('absolute' => true));	
//$po_revision = url($rulespath."rules_purchase_order_for_revision/edit/5", array('absolute' => true));	
$po_cancel  = url($rulespath."rules_purchase_order_cancelled/edit/7", array('absolute' => true));	
$po_revert  = url($rulespath."rules_purchase_order_revert_to_open/edit/7", array('absolute' => true));	

$pd_new = url($rulespath."rules_payment_details_new/edit/5", array('absolute' => true));	
$pv_new = url($rulespath."rules_payment_voucher_new/edit/5", array('absolute' => true));	
$nonpo_new = url($rulespath."rules_non_po_new/edit/5", array('absolute' => true));	
$bir_new = url($rulespath."rules_bir_new/edit/5", array('absolute' => true));	

$account_creation = url("admin/config/workflow/rules/components/manage/rules_account_creation_email/edit/2", array('absolute' => true));
$account_alias_creation = url("admin/config/workflow/rules/components/manage/rules_account_alias_creation_email/edit/2", array('absolute' => true));
$account_confirmation = url("admin/config/workflow/rules/components/manage/rules_acccount_confirmation_email/edit/2", array('absolute' => true));
$account_verified = url("admin/config/workflow/rules/components/manage/rules_account_verified_email/edit/2", array('absolute' => true));
?>

<div class="email-templates-wrapper">
	<ul>
	 <li><a href="<?php print $account_creation; ?>"><span>Vendor - Account Creation</span></a></li>
	 <li><a href="<?php print $account_alias_creation; ?>"><span>Vendor - Alias Creation</span></a></li>
	 <li><a href="<?php print $account_confirmation; ?>"><span>Vendor - Account Confirmation</span></a></li>
	 <li><a href="<?php print $account_verified; ?>"><span>Vendor - Account Verified</span></a></li>
	 <li><a href="<?php print $po_new; ?>"><span>Purchase Order - New</span></a></li>
	 <li><a href="<?php print $po_revised; ?>"><span>Purchase Order - Revised</span></a></li>
	 <li><a href="<?php print $po_viewed; ?>"><span>Purchase Order - Viewed</span></a></li>
	 <li><a href="<?php print $po_cancel; ?>"><span>Purchase Order - Cancelled</span></a></li>
	 <li><a href="<?php print $po_revert; ?>"><span>Purchase Order - Revert to Open</span></a></li>
	 <li><a href="<?php print $pd_new; ?>"><span>New Payment Details</span></a></li>
	 <li><a href="<?php print $pv_new; ?>"><span>New Payment Voucher</span></a></li>
	 <li><a href="<?php print $nonpo_new; ?>"><span>New Non PO</span></a></li>
	 <li><a href="<?php print $bir_new; ?>"><span>New BIR Form</span></a></li>
	</ul>
</div>
