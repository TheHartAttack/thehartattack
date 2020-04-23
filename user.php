<div id="user-overlay">
  <div id="user-close">✖</div>
  <div id="user-container">

    <?php
    if (!is_user_logged_in()){

    ?>

      <div id="user-login" class="user-container-inner active">

        <h2>Log In</h2>
        <div class="toggle-links">
          <span id="login-toggle-to-reset" class="user-toggle toggle-to-reset">Forgotten your password?</span><span>|</span><span id="login-toggle-to-register" class="user-toggle toggle-to-register">Don't have an account yet?</span>
        </div>
        <form id="login-form" class="user-form">
          <input type="text" name="login-name" id="login-name" class="login-input user-form-input" placeholder="Username">
          <input type="password" name="login-password" id="login-password" class="login-input user-form-input" placeholder="Password">
          <label>Remember me&nbsp;<input type="checkbox" name="login-remember" id="login-remember"></label>
          <div id="login-submit" class="user-form-submit submit-disabled">
            <svg viewBox="0 0 96 96">
              <polygon id="XMLID_55_" class="post-comments-hex" points="72,90 96,48 72,6 24,6 0,48 24,90 	"/>
              <span>Submit</span>
            </svg>
          </div>
          <?php wp_nonce_field( 'ajax-login-nonce', 'login-security' ); ?>
        </form>

        <span id="login-response" class="user-response"></span>

      </div>

      <div id="user-register" class="user-container-inner">

        <h2>Register</h2>
        <div class="toggle-links">
          <span id="register-toggle-to-login" class="user-toggle toggle-to-login">Already have an account?</span><span>|</span><span id="register-toggle-to-reset" class="user-toggle toggle-to-reset">Forgotten your password?</span>
        </div>
        <form id="register-form" class="user-form">
          <input type="text" name="register-name" id="register-name" class="register-input user-form-input" placeholder="Username">
          <input type="email" name="register-email" id="register-email" class="register-input user-form-input" placeholder="Email">
          <input type="password" name="register-password" id="register-password" class="register-input user-form-input" placeholder="Password">
          <div id="register-submit" class="user-form-submit submit-disabled">
            <svg viewBox="0 0 96 96">
              <polygon id="XMLID_55_" class="post-comments-hex" points="72,90 96,48 72,6 24,6 0,48 24,90 	"/>
              <span>Submit</span>
            </svg>
          </div>
          <?php wp_nonce_field( 'ajax-register-nonce', 'register-security' ); ?>
        </form>

        <span id="register-response" class="user-response"></span>

      </div>

      <div id="user-reset" class="user-container-inner">

        <h2>Password Reset</h2>
        <div class="toggle-links">
          <span id="reset-toggle-to-login" class="user-toggle toggle-to-login">Don't need to reset your password?</span><span>|</span><span id="reset-toggle-to-register" class="user-toggle toggle-to-register">Don't have an account yet?</span>
        </div>
        <form id="reset-form" class="user-form">
          <input type="email" name="reset-email" id="reset-email" class="reset-input user-form-input" placeholder="Email">
          <div id="reset-submit" class="user-form-submit submit-disabled">
            <svg viewBox="0 0 96 96">
              <polygon id="XMLID_55_" class="password-reset-hex" points="72,90 96,48 72,6 24,6 0,48 24,90 	"/>
              <span>Submit</span>
            </svg>
          </div>
          <?php wp_nonce_field( 'ajax-reset-nonce', 'reset-security' ); ?>
        </form>

        <span id="reset-response" class="user-response"></span>

      </div>

    <?php } else {

      $user = wp_get_current_user();

      $authorCommentsArgs = array (
          'user_id' => $user->ID,
          'count' => true
      );
      $authorComments = get_comments($authorCommentsArgs);

      $userImage = get_user_meta($user->ID, 'user-image', true);

      ?>

      <div id="user-profile" data-id="<?php echo $user->ID; ?>">

        <div id="profile-image-container">
          <img class="profile-image" id="user-profile-image" src="<?php echo $userImage; ?>" alt="">
          <form enctype="multipart/form-data" id="user-image-upload-form">
            <input type="file" name="user-image-upload" id="user-image-upload">
            <?php wp_nonce_field('ajax-image-nonce', 'image-security'); ?>
          </form>
          <div id="profile-image-upload-svg-container">
            <svg viewBox="0 0 96 96" id="profile-image-upload-svg">
              <polygon id="XMLID_55_" class="profile-image-upload-hex" points="72,90 96,48 72,6 24,6 0,48 24,90 	"/>
              <span>Upload<br>Image</span>
            </svg>
          </div>
        </div>

        <div class="user-profile-head-right">

          <h2 class="user-profile-name"><?php echo $user->display_name; ?></h2>
          <p class="user-profile-registered"><strong>Registered: </strong> <?php echo date("jS F Y", strtotime($user->user_registered)); ?></p>
          <div class="user-profile-email" data-update="false">
            <strong>Email: </strong> <span id="user-profile-email-address"><?php echo $user->user_email; ?></span>
            <div class="user-profile-update-email-buttons">
              <a id="user-profile-update-email">✎</a>
              <a id="user-profile-update-email-save">✔</a>
              <a id="user-profile-update-email-cancel">✖</a>
              <?php wp_nonce_field( 'ajax-update-email-nonce', 'update-email-security' ); ?>
            </div>
          </div>
          <div class="user-profile-password" data-update="false">
            <a id="user-profile-update-password">Update password</a>
            <a id="user-profile-update-password-save">✔</a>
            <a id="user-profile-update-password-cancel">✖</a>
            <?php wp_nonce_field( 'ajax-update-password-nonce', 'update-password-security' ); ?>
          </div>
          <a id="user-profile-logout">Logout</a>

        </div>

      </div>

    <?php } ?>

  </div>
</div>
