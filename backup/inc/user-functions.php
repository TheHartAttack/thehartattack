<?php

add_action("wp_ajax_custom_login", "handle_custom_login");
add_action("wp_ajax_nopriv_custom_login", "handle_custom_login");
add_action("wp_ajax_custom_register", "handle_custom_register");
add_action("wp_ajax_nopriv_custom_register", "handle_custom_register");
add_action("wp_ajax_custom_reset", "handle_custom_reset");
add_action("wp_ajax_nopriv_custom_reset", "handle_custom_reset");
add_action("wp_ajax_custom_update_email", "handle_custom_update_email");
add_action("wp_ajax_custom_update_password", "handle_custom_update_password");
add_action("wp_ajax_custom_file_upload", "handle_custom_file_upload");
add_action("wp_ajax_custom_logout", "handle_custom_logout");

function handle_custom_login(){

  check_ajax_referer('ajax-login-nonce', 'security');

  $info = array();
  $info['user_login'] = sanitize_text_field($_POST['username']);
  $info['user_password'] = sanitize_text_field($_POST['password']);
  $info['remember'] = sanitize_text_field($_POST['remember']);
  $userID = get_user_by('login', sanitize_text_field($_POST['username']));
  $userID = $userID->ID;
  $userActivationStatus = get_user_meta($userID, 'user_activation_status');

  if($userActivationStatus){
    $user_signon = wp_signon($info, false);

    if (is_wp_error($user_signon)){
      echo json_encode(array(
        "status" => 0,
        "message" => $user_signon
      ));
    } else {
      echo json_encode(array(
        "status" => 1,
        "message" => $user_signon,
        "action" => 'window.location.reload(true);'
      ));
    }
  } else {
    echo json_encode(array(
      "status" => 0,
      "message" => 'Please activate your account before logging in.',
      "action" => ''
    ));
  }

  wp_die();

}

function handle_custom_register(){

  check_ajax_referer('ajax-register-nonce', 'security');

  $username = sanitize_text_field($_POST['username']);
  $email = strtolower(sanitize_text_field($_POST['email']));
  $password = sanitize_text_field($_POST['password']);
  $usernameInUse = username_exists($username);
  $emailInUse = email_exists($email);

  if (!$usernameInUse){
    if (!$emailInUse){
      if ($username != '' AND $email != '' AND $password != ''){
        $newUser = wp_create_user($username, $password, $email);

        if ($newUser && !is_wp_error($newUser)) {
          $userActivationKey = md5($newUser . time());
          update_user_meta($newUser, 'user_activation_status', 'false');
          update_user_meta($newUser, 'user_activation_key', $userActivationKey);
          update_user_meta($newUser, 'user-image', get_theme_file_uri('img/default-user-image.jpg'));
          $activationLink = add_query_arg(array(
            'user_id' => $newUser,
            'user_activation_key' => $userActivationKey
          ), /*get_permalink(get_page_by_path('activate'))*/site_url('/user-activate.php'));
          $sendActivationLink = wp_mail($email, 'The Hart Attack account activation', 'Activation link: ' . $activationLink);
          if (!is_wp_error($sendActivationLink)){
            echo json_encode(array(
              "status" => 1,
              "message" => 'Please check your email for the link to activate your account.',
              "action" => 'window.location.reload(true);'
            ));
          }
        }

        /*if (!is_wp_error($newUser)){
          $info = array();
          $info['user_login'] = $username;
          $info['user_password'] = $password;
          $newUserSignOn = wp_signon($info, false);
          if (is_wp_error($newUserSignOn)){
            echo json_encode(array(
              "status" => 0,
              "message" => $newUserSignOn
            ));
          } else {
            echo json_encode(array(
              "status" => 1,
              "message" => $newUserSignOn,
              "action" => 'window.location.reload(true);'
            ));
          }
        };*/
      }
    } else {
      echo json_encode(array(
        "status" => 0,
        "message" => 'This email address has already been registered.',
        "action" => ''
      ));
    }
  } else {
    echo json_encode(array(
      "status" => 0,
      "message" => 'This username has already been registered.',
      "action" => ''
    ));
  }

  wp_die();

}

function handle_custom_reset(){

  check_ajax_referer('ajax-reset-nonce', 'security');

  global $wpdb;

  $account = $_POST['user'];

  if (empty($account)) {
    $error = 'Enter an username or e-mail address.';
  } else if (is_email($account)) {
    if (email_exists($account)){
      $get_by = 'email';
    } else {
      $error = 'There is no user registered with that email address.';
    }
  } else if (validate_username($account)) {
    if (username_exists($account)){
      $get_by = 'login';
    } else {
      $error = 'There is no user registered with that username.';
    }
  } else {
    $error = 'Invalid username or e-mail address.';
  }

  if(empty($error)) {
    $random_password = wp_generate_password();
    $user = get_user_by( $get_by, $account );
    $update_user = wp_update_user(array(
      'ID' => $user->ID,
      'user_pass' => $random_password
      )
    );

    if($update_user) {
      $from = 'admin@thehartattack.com';
      if(!(isset($from) && is_email($from))) {
        $sitename = strtolower($_SERVER['SERVER_NAME']);
        if (substr($sitename, 0, 4) == 'www.') {
          $sitename = substr($sitename, 4 );
        }
        $from = 'admin@'.$sitename;
      }

      $to = $user->user_email;
      $subject = 'The Hart Attack - your new password';
      $sender = 'From: '.get_option('name').' <'.$from.'>' . "\r\n";

      $message = 'Your new password is: '.$random_password;

      $headers[] = 'MIME-Version: 1.0' . "\r\n";
      $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      $headers[] = "X-Mailer: PHP \r\n";
      $headers[] = $sender;

      $mail = wp_mail($to, $subject, $message, $headers);
      if($mail){
        $success = 'Check your email address for you new password.';
      } else {
        $error = 'System is unable to send you a new password.';
      }
    }
  }

  if(!empty($error)){
    echo json_encode(array(
      'loggedin' => false,
      'message' => $error,
      'status' => 0
    ));
  }

  if(!empty($success)){
    echo json_encode(array(
      'loggedin' => false,
      'message' => $success,
      'status' => 1
    ));
  }

  wp_die();

}

function handle_custom_update_email(){

  check_ajax_referer('ajax-update-email-nonce', 'security');

  $userID = $_POST['user'];
  $email = $_POST['email'];

  if (get_current_user_id() == $userID){

    $updateUser = wp_update_user(array(
      'ID' => $userID,
      'user_email' => $email
    ));

    $from = 'admin@thehartattack.com';
    if(!(isset($from) && is_email($from))) {
      $sitename = strtolower($_SERVER['SERVER_NAME']);
      if (substr($sitename, 0, 4) == 'www.') {
        $sitename = substr($sitename, 4 );
      }
      $from = 'admin@'.$sitename;
    }
    $user = get_user_by('ID', $userID);
    $to = $user->user_email;
    $subject = 'The Hart Attack - your email address has been updated';
    $sender = 'From: '.get_option('name').' <'.$from.'>' . "\r\n";
    $message = 'The email address for your account on The Hart Attack has been updated to: '.$email;
    $headers[] = 'MIME-Version: 1.0' . "\r\n";
    $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers[] = "X-Mailer: PHP \r\n";
    $headers[] = $sender;
    $mail = wp_mail($to, $subject, $message, $headers);

    $from = 'admin@thehartattack.com';
    if(!(isset($from) && is_email($from))) {
      $sitename = strtolower($_SERVER['SERVER_NAME']);
      if (substr($sitename, 0, 4) == 'www.') {
        $sitename = substr($sitename, 4 );
      }
      $from = 'admin@'.$sitename;
    }
    $to = $email;
    $subject = 'The Hart Attack - your email address has been updated';
    $sender = 'From: '.get_option('name').' <'.$from.'>' . "\r\n";
    $message = 'The email address for your account on The Hart Attack has been updated to this address.';
    $headers[] = 'MIME-Version: 1.0' . "\r\n";
    $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers[] = "X-Mailer: PHP \r\n";
    $headers[] = $sender;
    $mail = wp_mail($to, $subject, $message, $headers);

    if (!is_wp_error($updateUser)){
      echo json_encode(array(
        'status' => 1,
        'message' => $updateUser,
        'email' => $email
      ));
    } else {
      echo json_encode(array(
        'status' => 0,
        'message' => $updateUser,
      ));
    }

  } else {
    echo json_encode(array(
      'status' => 0,
      'message' => 'User ID does not match.',
    ));
  }

  wp_die();

}

function handle_custom_update_password(){

  check_ajax_referer('ajax-update-password-nonce', 'security');

  $userID = $_POST['user'];
  $password = $_POST['password'];

  if (get_current_user_id() == $userID){

    $setPassword = wp_set_password($password, $userID);

    if (!is_wp_error($setPassword)){

      $user = get_user_by('ID', $userID);
      $info = array(
        'user_login' => $user->user_login,
        'user_password' => $password,
        'remember' => true
      );
      $user_signon = wp_signon($info, false);

      if (!is_wp_error($user_signon)){
        echo json_encode(array(
          'status' => 1,
          'message' => 'Password updated!',
          'action' => 'window.location.reload(true);'
        ));
      } else {
        echo json_encode(array(
          'status' => 0,
          'message' => 'Error.',
        ));
      }

    } else {
      echo json_encode(array(
        'status' => 0,
        'message' => 'Error.',
      ));
    }

  }

  wp_die();

}

function handle_custom_file_upload(){

  check_ajax_referer('ajax-image-nonce', 'image-security');
	$image = $_FILES['user-image-upload'];
  $allowed =  array('gif', 'png', 'jpg', 'jpeg');
  $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
  $ext = strtolower($ext);
  $size = $image['size'];
  $user = wp_get_current_user();
  if (!in_array($ext, $allowed)){
      echo json_encode(array(
        'message' => 'Invalid file type.',
        'ext' => $ext
      ));
  } else {

    if ($size > 1000000){
      echo json_encode(array(
        'message' => 'File size too big.',
        'ext' => $ext
      ));
    } else {

      $uploaded_file = wp_handle_upload($image, array('test_form' => false));

      if ($uploaded_file){
        echo json_encode(array(
          'status' => 1,
          'url' => $uploaded_file['url'],
          'size' => $size,
          'ext' => $ext
        ));

        update_user_meta($user->ID, 'user-image', $uploaded_file['url']);

      } else {
        echo json_encode(array(
          'status' => 0,
          'message' => 'Error',
        ));
      }

    }

  }

  wp_die();

}

function handle_custom_logout(){
  wp_logout();
  echo json_encode(array(
    'status' => 1,
    'message' => 'Logged out.',
    'action' => 'window.location.reload(true);'
  ));
  wp_die();
}
