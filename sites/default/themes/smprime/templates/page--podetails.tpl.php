<!-- Content title -->
<?php print render($title_prefix); ?>
<?php if ($title): ?>
  <h1 class="title col-lg-12 col-md-12 col-sm-12 col-xs-12" id="page-title"><?php print $title; ?></h1>
<?php endif; ?>
<div class="clearfix"></div>
<?php print render($title_suffix); ?>
<?php print render($page['content']); ?>