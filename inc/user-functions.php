<?php

add_action("wp_ajax_custom_login", "handle_custom_login");
add_action("wp_ajax_nopriv_custom_login", "handle_custom_login");
add_action("wp_ajax_custom_register", "handle_custom_register");
add_action("wp_ajax_nopriv_custom_register", "handle_custom_register");
add_action("wp_ajax_custom_reset", "handle_custom_reset");
add_action("wp_ajax_nopriv_custom_reset", "handle_custom_reset");
add_action("wp_ajax_custom_resend", "handle_custom_resend");
add_action("wp_ajax_nopriv_custom_resend", "handle_custom_resend");
add_action("wp_ajax_custom_update_email", "handle_custom_update_email");
add_action("wp_ajax_custom_change_password", "handle_custom_change_password");
add_action("wp_ajax_custom_file_upload", "handle_custom_file_upload");
add_action("wp_ajax_custom_logout", "handle_custom_logout");
add_action("wp_ajax_custom_update_bio", "handle_custom_bio");
add_action("wp_ajax_load_recent_comments", "handle_load_recent_comments");
add_action("wp_ajax_load_liked_posts", "handle_load_liked_posts");

function handle_custom_login(){
  check_ajax_referer('ajax-login-nonce', 'security');
  $info = array();
  $info['user_login'] = sanitize_text_field($_POST['username']);
  $info['user_password'] = sanitize_text_field($_POST['password']);
  $info['remember'] = sanitize_text_field($_POST['remember']);
  $userID = get_user_by('login', sanitize_text_field($_POST['username']));
  $userID = $userID->ID;
  $userActivated = filter_var(get_user_meta($userID, 'user_activation_status', true), FILTER_VALIDATE_BOOLEAN);

  if ($userID){

    if ($userActivated == 'true'){

      if (!is_wp_error(wp_authenticate($info['user_login'], $info['user_password']))){

        $user_signon = wp_signon($info, false);

        if (is_wp_error($user_signon)){
          echo json_encode(array(
            "status" => 0,
            "message" => 'Server error - please try again later.'
          ));
        } else {
          echo json_encode(array(
            "status" => 1,
            "message" => 'Successfully logged in!',
            "action" => 'window.location.href = "'.site_url('/user/'.get_userdata($userID)->user_nicename).'";'
          ));
        }

      } else {
        echo json_encode(array(
          "status" => 0,
          "message" => 'Incorrect password.'
        ));
      }

    } else {
      echo json_encode(array(
        "status" => 0,
        "message" => 'Please activate your account before logging in.',
      ));
    }

  } else {
    echo json_encode(array(
      "status" => 0,
      "message" => 'User does not exist.',
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
          ), site_url('/user-activate.php'));
          $sendActivationLink = wp_mail($email, 'The Hart Attack account activation', 'Activation link: ' . $activationLink);
          if (!is_wp_error($sendActivationLink)){
            echo json_encode(array(
              "status" => 1,
              "message" => 'Account created! Please check your email for the account activation link.',
            ));
          } else {
            echo json_encode(array(
              'status' => 0,
              'message' => 'Server error - please try again later.'
            ));
          }
        } else {
          echo json_encode(array(
            'status' => 0,
            'message' => 'Server error - please try again later.'
          ));
        }
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
    $user = get_user_by($get_by, $account);
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
        echo json_encode(array(
          'status' => 1,
          'message' => 'Check your email for your new password.'
        ));
      } else {
        echo json_encode(array(
          'status' => 0,
          'message' => 'Server error - please try again later.'
        ));
      }
    }
  } else {
    echo json_encode(array(
      'status' => 0,
      'message' => $error
    ));
  }

  wp_die();
}

function handle_custom_resend(){
  check_ajax_referer('ajax-resend-nonce', 'security');
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
    $user = get_user_by($get_by, $account);
    $email = $user->user_email;

    if (get_user_meta($user->ID, 'user_activation_status', true) != 'true'){
      $activationKey = get_user_meta($user->ID, 'user_activation_key', true);
      $activationLink = add_query_arg(array(
        'user_id' => $user->ID,
        'user_activation_key' => $activationKey
      ), site_url('/user-activate.php'));
      if (wp_mail($email, 'The Hart Attack account activation', 'Activation link: ' . $activationLink)){
        echo json_encode(array(
          "status" => 1,
          "message" => 'Please check your email for the link to activate your account.'
        ));
      } else {
        echo json_encode(array(
          'status' => 0,
          'message' => 'Server error - please try again later.'
        ));
      }
    } else {
      echo json_encode(array(
        'status' => 0,
        'message' => 'That account has already been activated.'
      ));
    }

  } else {
    echo json_encode(array(
      'status' => 0,
      'message' => $error
    ));
  }

  wp_die();

}

function handle_custom_update_email(){
  check_ajax_referer('ajax-update-email-nonce', 'security');
  $userID = $_POST['user'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $user = get_userdata(get_current_user_id());
  $authentication = wp_authenticate($user->user_login, $password);
  $emailInUse = email_exists($email);

  if (!$emailInUse){

    if (!is_wp_error($authentication)){

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
        $message = 'The email for your account on The Hart Attack has been updated to this address.';
        $headers[] = 'MIME-Version: 1.0' . "\r\n";
        $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers[] = "X-Mailer: PHP \r\n";
        $headers[] = $sender;
        $mail = wp_mail($to, $subject, $message, $headers);
    
        if (!is_wp_error($updateUser)){
          echo json_encode(array(
            'status' => 1,
            'message' => "Email updated!",
            'email' => $email
          ));
        } else {
          echo json_encode(array(
            'status' => 0,
            'message' => "Server error - please try again later.",
          ));
        }
    
      } else {
        echo json_encode(array(
          'status' => 0,
          'message' => 'You do not have permission to perform that action.',
        ));
      }
  
    } else {
      echo json_encode(array(
        'status' => 0,
        'message' => "Incorrect password."
      ));
    }

  } else {
    echo json_encode(array(
      'status' => 0,
      'message' => "That email is already in use."
    ));
  }

  wp_die();
}

function handle_custom_change_password(){
  check_ajax_referer('ajax-change-password-nonce', 'security');
  $userID = $_POST['user'];
  $old = $_POST['old'];
  $new = $_POST['new'];
  $user = get_userdata(get_current_user_id());
  $authentication = wp_authenticate($user->user_login, $old);

  if (!is_wp_error($authentication)){

    if (get_current_user_id() == $userID){

      $setPassword = wp_set_password($new, $userID);
  
      if (!is_wp_error($setPassword)){
  
        $user = get_user_by('ID', $userID);
        $info = array(
          'user_login' => $user->user_login,
          'user_password' => $new,
          'remember' => true
        );
        $user_signon = wp_signon($info, false);
  
        if (!is_wp_error($user_signon)){
          echo json_encode(array(
            'status' => 1,
            'message' => 'Password updated!'
          ));
        } else {
          echo json_encode(array(
            'status' => 0,
            'message' => $user_signon->get_error_message()
          ));
        }
  
      } else {
        echo json_encode(array(
          'status' => 0,
          'message' => $setPassword->get_error_message()
        ));
      }
  
    } else {
      echo json_encode(array(
        'status' => 0,
        'message' => 'You do not have permission to perform that action.'
      ));
    }

  } else {
    echo json_encode(array(
      'status' => 0,
      'message' => "Incorrect password."
    ));
  }

  wp_die();

}

function handle_custom_file_upload(){
  check_ajax_referer('ajax-image-nonce', 'image-security');
	$image = $_FILES['user-image-upload'];
  $allowed = array('gif', 'png', 'jpg', 'jpeg');
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

    if ($size > 2000000){
      echo json_encode(array(
        'status' => 0,
        'message' => 'File size too big.',
        'size' => $size,
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
          'size' => $size,
          'ext' => $ext
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
    'action' => 'window.location.href = "'.site_url().'"'
  ));
  wp_die();
}

function handle_custom_bio(){
  $user = sanitize_text_field($_POST['user']);
  $bio = sanitize_text_field($_POST['bio']);

  if(get_current_user_id() == $user){
    update_user_meta($user, 'description', $bio);

    echo json_encode(array(
      'status' => 1,
      'message' => 'Bio updated.',
      'data' => $bio,
    ));
  }

  wp_die();
}
  
function handle_load_recent_comments(){
  $user = $_POST['user'];
  $page = $_POST['page'];
  $number = $_POST['number'];
  $olderNewer = $_POST['olderNewer'];

  $page += $olderNewer;

  $comments = get_comments(array(
      'orderby' => 'post_date',
      'order' => 'DESC',
      'user_id' => $user,
      'number' => $number,
      'paged' => $page
  ));

  $data = array();

  foreach ($comments as $comment){

    //Get comment like count
    $commentLikeCount = new WP_Query(array(
      'post_type' => 'comment-like',
      'meta_query' => array(
        array(
        'key' => 'liked_comment_id',
        'compare' => '=',
        'value' => $comment->comment_ID
        )
      )
    ));

    //Comment date config
    $today = date('jS F Y');
    $yesterday = date('jS F Y', time() - 86400);
    $postedDay = get_comment_date('jS F Y', $comment->comment_ID);
    if ($today == $postedDay){
    $postedDay = 'today';
    } else if ($yesterday == $postedDay){
    $postedDay = 'yesterday';
    }

    //Parent comments
    $parentCommentId = $comment->comment_parent;
    $parentComment = get_comment($parentCommentId);
    $parentPostedDay = get_comment_date('jS F Y', $parentCommentId);
    if ($today == $parentPostedDay){
    $parentPostedDay = 'today';
    } else if ($yesterday == $parentPostedDay){
    $parentPostedDay = 'yesterday';
    }

    $currentComment = array(
      'commentContent' => nl2br($comment->comment_content),
      'permalink' => get_the_permalink($comment->comment_post_ID),
      'time' => get_comment_date('H:i', $comment->comment_ID),
      'postedDay' => $postedDay,
      'likeCount' => $commentLikeCount->found_posts,
      'postTitle' => get_the_title($comment->comment_post_ID),
      'parent' => $parentCommentId,
      'parentAuthor' => $parentComment->comment_author,
      'parentPostedDay' => $parentPostedDay,
      'parentPostedDate' => get_comment_date('H:i', $parentCommentId),
      'parentContent' => $parentComment->comment_content
    );

    array_push($data, $currentComment);

}
  
  $userCommentCount = get_comments(array(
    'user_id' => $user,
    'count' => true
  ));

  if (ceil($userCommentCount/$number) <= $page){
      $isLastPage = 1;
  } else {
      $isLastPage = 0;
  }

  echo json_encode(array(
    'status' => 1,
    'data' => $data,
    'pageNumber' => $page,
    'lastPage' => $isLastPage
  ));

  wp_die();

}

function handle_load_liked_posts(){

  $user = $_POST['user'];
  $page = $_POST['page'];
  $number = $_POST['number'];
  $olderNewer = $_POST['olderNewer'];

  $page += $olderNewer;

  //Get user liked posts
  $likes = new WP_Query(array(
      'post_type' => 'like',
      'post_status' => 'publish',
      'author' => $user,
      'posts_per_page' => $number,
      'paged' => $page
  ));
  if (ceil($likes->found_posts/$number) <= $page){
      $isLastPage = 1;
  } else {
      $isLastPage = 0;
  }

  $likedPostIds = array();
  if ($likes->have_posts()) {
    while ($likes->have_posts()) {
        $likes->the_post();
        array_push($likedPostIds, get_field('liked_post_id', get_the_ID()));
    }
    $likedPosts = new WP_Query(array(
        'post__in' => $likedPostIds,
        'post_type' => 'post',
        'post_status' => 'publish',
        'order' => 'DESC',
        'orderby' => 'post_date'
    ));
  }

  $data = array();

  if ($likedPosts->have_posts()) {
    while ($likedPosts->have_posts()) {
      $likedPosts->the_post();
      
      //Get liked posts comments/likes counts
      $commentCount = get_comments_number(get_the_id());
      $likeCount = new WP_Query(array(
          'post_type' => 'like',
          'meta_query' => array(
              array(
              'key' => 'liked_post_id',
              'compare' => '=',
              'value' => get_the_id()
              )
          )
      ));

      $currentPost = array(
        'permalink' => get_the_permalink(),
        'image' => get_the_post_thumbnail_url(),
        'postTitle' => get_the_title(),
        'postDate' => get_the_date('jS F Y'),
        'commentCount' => $commentCount,
        'likeCount' => $likeCount->found_posts
      );

      array_push($data, $currentPost);

    } 
  }

  echo json_encode(array(
    'status' => 1,
    'data' => $data,
    'pageNumber' => $page,
    'lastPage' => $isLastPage
  ));

  wp_die();

}