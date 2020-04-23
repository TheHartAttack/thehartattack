<?php

$path = preg_replace('/wp-content.*$/', '', __DIR__);
require_once($path."wp-load.php");

$linkUser = $_GET['user_id'];
$linkActivationKey = $_GET['user_activation_key'];
$dbUserStatus = get_user_meta($linkUser, 'user_activation_status');
$dbActivationKey = get_user_meta($linkUser, 'user_activation_key');

if ($linkActivationKey == $dbActivationKey AND $dbUserStatus != 'true'){
  $activated = update_user_meta($linkUser, 'user_activation_status', 'true');
  if ($activated){

  }
}

wp_redirect(get_site_url());
