<?php if (is_user_logged_in()){
    $user = wp_get_current_user();
    wp_redirect(site_url('/user/'.$user->data->user_nicename));
    exit;
} else {
    
    get_header(); ?>

    <div id="col-left">

        <div id="user-container">

            <div id="user-login" class="user-login-register">
                <h3>Login</h3>
                <form>
                    <input type="text" id="user-login-username" placeholder="Username">
                    <input type="password" id="user-login-password" placeholder="Password">
                    <label>Keep me logged in&nbsp;&nbsp;<input type="checkbox" id="user-login-remember"></label>
                    <?php wp_nonce_field('ajax-login-nonce', 'user-login-security'); ?>
                    <button id="user-login-submit" disabled>Login</button>
                </form>
                <div id="reset-resend">
                    <a href="" id="reset-password-link">Forgotten password?</a> | <a href="" id="resend-activation-link">Resend account activation link</a>
                </div>
            </div>

            <div id="user-register" class="user-login-register">
                <h3>Register</h3>
                <form>
                    <input type="text" id="user-register-username" placeholder="Username">
                    <input type="email" id="user-register-email" placeholder="Email">
                    <input type="password" id="user-register-password" placeholder="Password">
                    <?php wp_nonce_field('ajax-register-nonce', 'user-register-security'); ?>
                    <button id="user-register-submit"  disabled>Register</button>
                </form>
            </div>

        </div>

    </div>

<?php }

include 'sidebar.php';

get_footer(); ?>

<div id="reset-password-overlay">
    <button id="reset-password-close" class="overlay-close">✖</button>
    <div id="reset-container">
        <form id="reset-password-form">
            <h4>Reset Password</h4>
            <input type="text" id="reset-password-input" placeholder="Username or email">
            <?php wp_nonce_field('ajax-reset-nonce', 'reset-password-security'); ?>
            <button id="reset-password-submit" disabled></button>
        </form>
    </div>
</div>
<div id="resend-activation-overlay">
    <button id="resend-activation-close" class="overlay-close">✖</button>
    <div id="resend-container">
        <form id="resend-activation-form">
            <h4>Resend Activation</h4>
            <input type="text" id="resend-activation-input" placeholder="Username or email">
            <?php wp_nonce_field('ajax-resend-nonce', 'resend-activation-security'); ?>
            <button id="resend-activation-submit" disabled></button>
        </form>
    </div>
</div>