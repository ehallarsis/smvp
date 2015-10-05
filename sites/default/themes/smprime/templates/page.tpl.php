<?php 
  //add warning message and redirect incase user used the one-time login link from forgot password page
  $editpath = "user/" . $user->uid . "/edit";
  if(isset($_SESSION['pass_reset_' . $user->uid]) && current_path() != $editpath && in_array('vendor',$user->roles)){
    drupal_set_message(t('You have used a one-time login link to temporarily access this account. Please change your password.'), 'warning'); 
    drupal_goto($editpath);                   
  }  

  //terms and condition
  if(!empty($_POST['terms_accept']) && $_POST['terms_accept'] == 1 && !isset($_SESSION['terms_accept_' . $user->uid])){
    $_SESSION['terms_accept_' . $user->uid] =  1;    
  }

  //announcement
  if(!empty($_POST['announcement_viewed']) && $_POST['announcement_viewed'] == 1 && !isset($_SESSION['announcement_viewed_' . $user->uid])){
    $_SESSION['announcement_viewed_' . $user->uid] =  1;    
  }

  //pending or
  if(!empty($_POST['pendingor_viewed']) && $_POST['pendingor_viewed'] == 1 && !isset($_SESSION['pendingor_viewed_' . $user->uid])){
    $_SESSION['pendingor_viewed_' . $user->uid] =  1;    
  }

?>

<div class="header-line"></div>
<div id="page" class="container">

  <header id="header" role="banner">
    <?php if ($logo): ?>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
      <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo"><img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" /></a>
    </div>
    <?php endif; ?>

    <?php if ($site_name || $site_slogan): ?>
      <hgroup id="name-and-slogan">
        <?php if ($site_name): ?>
          <h1 id="site-name">
            <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><span><?php print $site_name; ?></span></a>
          </h1>
        <?php endif; ?>

        <?php if ($site_slogan): ?>
          <h2 id="site-slogan"><?php print $site_slogan; ?></h2>
        <?php endif; ?>
      </hgroup><!-- /#name-and-slogan -->
    <?php endif; ?>

    <?php print render($page['header']); ?>
  </header>
	<div class="clearfix"></div>
    <div id="navigation">
      
      <?php if(in_array('vendor',$user->roles)) : ?>

        <!-- hide navigation if user use a one time login link from forgot password page -->
        <?php if(!isset($_SESSION['pass_reset_' . $user->uid])) : ?>
          <?php print render($page['navigation']); ?>
        <?php endif; ?>

      <?php else : ?>
        <?php print render($page['navigation']); ?>
      <?php endif; ?>

    </div><!-- /#navigation -->
      
  <div id="main" class="mainHolder">
    <div id="content" class="column" role="main">
      <?php print render($page['highlighted']); ?>
      <?php print $breadcrumb; ?>
      <a id="main-content"></a>
      <div id="poSearch" class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-lg-push-6 col-md-push-6 col-sm-push-6 col-xs-push-6"></div>
     
      <!-- Content title -->
      <?php print render($title_prefix); ?>
      <?php if ($title): ?>
        <h1 class="title col-lg-12 col-md-12 col-sm-12 col-xs-12" id="page-title"><?php print $title; ?></h1>
      <?php endif; ?>
       <div class="clearfix"></div>
      <?php print render($title_suffix); ?>

      <?php print $messages; ?>

      <?php print render($tabs); ?>
      <?php print render($page['help']); ?>
      <?php if ($action_links): ?>
        <ul class="action-links"><?php print render($action_links); ?></ul>
      <?php endif; ?>
      <?php print render($page['content']); ?>
      <?php print $feed_icons; ?>
    </div><!-- /#content -->

    <?php
      // Render the sidebars to see if there's anything in them.
      $sidebar_first  = render($page['sidebar_first']);
      $sidebar_second = render($page['sidebar_second']);
    ?>

    <?php if ($sidebar_first || $sidebar_second): ?>
      <aside class="sidebars">
        <?php print $sidebar_first; ?>
        <?php print $sidebar_second; ?>
      </aside><!-- /.sidebars -->
    <?php endif; ?>

  </div><!-- /#main -->

</div><!-- /#page -->
  <?php print render($page['footer']); ?>
  <div class="notFrontFooterMenu"></div>
  <div class="footer-bg"></div>


<?php print render($page['bottom']); ?>