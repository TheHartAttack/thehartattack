<?php

require_once('wp-load.php');

$linkUser = $_GET['user_id'];
$linkActivationKey = $_GET['user_activation_key'];
$dbUserStatus = get_user_meta($linkUser, 'user_activation_status');
$dbActivationKey = get_user_meta($linkUser, 'user_activation_key');

if ($linkActivationKey == $dbActivationKey[0] AND $dbUserStatus[0] != 'true'){
  $activated = update_user_meta($linkUser, 'user_activation_status', 'true');
  if (!is_wp_error($activated)){
    wp_redirect(site_url());
  } else {
    echo 'ERROR';
  }
}

