<?php

function _smv_global_settings_cron_links($job){
  if(!isset($job))
    return false;

  $cronurl = "admin/config/system/cron/jobs/list/".$job . "/edit";
  $output = url($cronurl, array('absolute' => true));

  $output = "<a href='".$output."'><span>Update</span></a>";
  return $output;
}

?>

<table class="views-table cols-3 smTable">
  <thead>
    <tr>
      <th>Document</th>
      <th>Scheduled Sync</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <tr class="odd">
      <td class="views-field">Vendor Account</td>
      <td class="views-field"><?php print smv_global_settings_schedule_sync('smv_sitecron_vendors'); ?></td>
      <td class="views-field"><?php print _smv_global_settings_cron_links('smv_sitecron_vendors'); ?></td>
    </tr>
    <tr class="even">
      <td class="views-field">Purchase Order</td>
      <td class="views-field"><?php print smv_global_settings_schedule_sync('smv_sitecron_po'); ?></td>      
      <td class="views-field"><?php print _smv_global_settings_cron_links('smv_sitecron_po'); ?></td>
    </tr>  
    <tr class="odd">
      <td class="views-field">Goods Receipt</td>
      <td class="views-field"><?php print smv_global_settings_schedule_sync('smv_sitecron_gr'); ?></td>      
      <td class="views-field"><?php print _smv_global_settings_cron_links('smv_sitecron_gr'); ?></td>
    </tr>      
    <tr class="even">
      <td class="views-field">Invoice Receipt</td>
      <td class="views-field"><?php print smv_global_settings_schedule_sync('smv_sitecron_ir'); ?></td>      
      <td class="views-field"><?php print _smv_global_settings_cron_links('smv_sitecron_ir'); ?></td>
    </tr>  
    <tr class="odd">
      <td class="views-field">Payment Details</td>
      <td class="views-field"><?php print smv_global_settings_schedule_sync('smv_sitecron_pd'); ?></td>      
      <td class="views-field"><?php print _smv_global_settings_cron_links('smv_sitecron_pd'); ?></td>
    </tr>  
    <tr class="even">
      <td class="views-field">Non PO</td>
      <td class="views-field"><?php print smv_global_settings_schedule_sync('smv_sitecron_nonpo'); ?></td>      
      <td class="views-field"><?php print _smv_global_settings_cron_links('smv_sitecron_nonpo'); ?></td>
    </tr>  
    <tr class="odd">
      <td class="views-field">Payment Voucher</td>
      <td class="views-field"><?php print smv_global_settings_schedule_sync('smv_sitecron_pv'); ?></td>      
      <td class="views-field"><?php print _smv_global_settings_cron_links('smv_sitecron_pv'); ?></td>
    </tr>
    <tr class="even">
      <td class="views-field">BIR Form 2307</td>
      <td class="views-field"><?php print smv_global_settings_schedule_sync('smv_sitecron_bir'); ?></td>      
      <td class="views-field"><?php print _smv_global_settings_cron_links('smv_sitecron_bir'); ?></td>
    </tr>  
    <tr class="odd">
      <td class="views-field">OR Monitoring</td>
      <td class="views-field"><?php print smv_global_settings_schedule_sync('smv_sitecron_pendingor'); ?></td>      
      <td class="views-field"><?php print _smv_global_settings_cron_links('smv_sitecron_pendingor'); ?></td>
    </tr>                  
  </tbody>
</table>

