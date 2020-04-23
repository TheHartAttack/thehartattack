<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri() . '/tha.ico'; ?>" />
    <?php wp_head(); ?>
  </head>

	<body id="particles-js">

	<header id="header">

    <div id="header-container">
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <div class="header-hex"></div>
      <a href="<?php echo site_url(); ?>" id="nav-home" class="main-nav"><?php include 'svg/64/home.php'; ?></a>
      <a href="<?php echo site_url('/category/music'); ?>" id="nav-music" class="main-nav"><?php include 'svg/64/music.php'; ?></a>
      <a href="<?php echo site_url('/category/gaming'); ?>" id="nav-gaming" class="main-nav"><?php include 'svg/64/gaming.php'; ?></a>
      <a href="<?php echo site_url('/category/tv-film'); ?>" id="nav-tv-film" class="main-nav"><?php include 'svg/64/tv-film.php'; ?></a>
      <a href="<?php echo site_url('/category/other-stuff'); ?>" id="nav-other-stuff" class="main-nav"><?php include 'svg/64/other-stuff.php'; ?></a>
      <a href="" id="nav-search" class="main-nav"><?php include 'svg/64/search.php'; ?></a>
      <a href="" id="nav-contact" class="main-nav"><?php include 'svg/64/contact.php'; ?></a>
      <a href="<?php if (is_user_logged_in()){$user = wp_get_current_user(); echo site_url('/user/'.$user->data->user_nicename);} else {echo site_url('/user');} ?>" id="nav-user" class="main-nav"><?php include 'svg/64/user.php'; ?></a>
      <a href="https://danhart.uk" target="_blank" id="nav-dev" class="main-nav"><?php include 'svg/64/dev.php'; ?></a>
      <?php

      include 'svg/tha.php';
      include 'svg/pentagram.php';  
      include 'svg/the-hart-attack.php';
      include 'svg/pentagram.php';
      include 'svg/tha.php';
      include 'svg/tha.php';
      include 'svg/tha.php';
      
      ?>
    </div>

    <div id="resp-header">
      <?php include 'svg/the-hart-attack-alt.php';
      include 'svg/tha.php'; ?>
      <button id="menu-button">
        <div class="menu-bar"></div>
        <div class="menu-bar"></div>
        <div class="menu-bar"></div>
      </button>
      <?php include 'svg/tha.php'; ?>
    </div>

    <div id="resp-menu">
      <ul>
          <li><a href="<?php echo site_url(); ?>" id="resp-home"><?php include 'svg/64/home.php'; ?></a></li>
          <li><a href="<?php echo site_url('/category/music'); ?>" id="resp-music"><?php include 'svg/64/music.php'; ?></a></li>
          <li><a href="<?php echo site_url('/category/gaming'); ?>" id="resp-gaming"><?php include 'svg/64/gaming.php'; ?></a></li>
          <li><a href="<?php echo site_url('/category/tv-film'); ?>" id="resp-tv-film"><?php include 'svg/64/tv-film.php'; ?></a></li>
          <li><a href="<?php echo site_url('/category/other-stuff'); ?>" id="resp-other-stuff"><?php include 'svg/64/other-stuff.php'; ?></a></li>
          <li><a href="" id="resp-search"><?php include 'svg/64/search.php'; ?></a></li>
          <li><a href="" id="resp-contact"><?php include 'svg/64/contact.php'; ?></a></li>
          <li><a href="<?php if (is_user_logged_in()){$user = wp_get_current_user(); echo site_url('/user/'.$user->data->user_nicename);} else {echo site_url('/user');} ?>"><?php include 'svg/64/user.php'; ?></a></li>
          <li><a href="https://danhart.uk"><?php include 'svg/64/dev.php'; ?></a></li>
      </ul>
    </div>

	</header>

  <div id="main-container">