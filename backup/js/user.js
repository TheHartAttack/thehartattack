/*user*/

class User {

  //1. Create object
  constructor(){
    this.document = jQuery(document);
    this.openUser = jQuery('#header-hex-user');
    this.closeUser = jQuery('#user-close');
    this.userOverlay = jQuery('#user-overlay');
    this.userLogin = jQuery('#user-login');
    this.loginForm = jQuery('#login-form');
    this.loginName = jQuery('#login-name');
    this.loginPassword = jQuery('#login-password');
    this.loginRemember = jQuery('#login-remember');
    this.loginSubmit = jQuery('#login-submit');
    this.loginResponse = jQuery('#login-response');
    this.userRegister= jQuery('#user-register');
    this.registerForm = jQuery('#register-form');
    this.registerName = jQuery('#register-name');
    this.registerPassword = jQuery('#register-password');
    this.registerEmail = jQuery('#register-email');
    this.registerSubmit = jQuery('#register-submit');
    this.registerResponse = jQuery('#register-response');
    this.userReset = jQuery('#user-reset');
    this.resetForm = jQuery('#reset-form');
    this.resetEmail = jQuery('#reset-email');
    this.resetSubmit = jQuery('#reset-submit');
    this.resetResponse = jQuery('#reset-response');
    this.toggleLinks = jQuery('.toggle-links');
    this.toggleToReset = jQuery('.toggle-to-reset');
    this.toggleToRegister = jQuery('.toggle-to-register');
    this.toggleToLogin = jQuery('.toggle-to-login');
    this.loginSecurity = jQuery('#login-security');
    this.registerSecurity = jQuery('#register-security');
    this.resetSecurity = jQuery('#reset-security');
    this.userProfile = jQuery('#user-profile');
    this.updateEmail = jQuery('#user-profile-update-email');
    this.updateEmailSave = jQuery('#user-profile-update-email-save');
    this.updateEmailCancel = jQuery('#user-profile-update-email-cancel');
    this.updateEmailContainer = jQuery('.user-profile-email');
    this.updateEmailSecurity = jQuery('#update-email-security');
    this.updatePassword = jQuery('#user-profile-update-password');
    this.updatePasswordContainer = jQuery('.user-profile-password');
    this.updatePasswordSave = jQuery('#user-profile-update-password-save');
    this.updatePasswordCancel = jQuery('#user-profile-update-password-cancel');
    this.updatePasswordSecurity = jQuery('#update-password-security');
    this.profileImage = jQuery('#user-profile-image');
    this.profileImageUpload = jQuery('#profile-image-upload-svg-container');
    this.profileImageUploadForm = jQuery('#user-image-upload-form');
    this.profileImageUploadInput = jQuery('#user-image-upload');
    this.profileImageUploadSecurity = jQuery('#image-security');
    this.userLogout = jQuery('#user-profile-logout');
    this.events();
    this.isOverlayOpen = false;
    this.timer;
  }

  //2. Define events
  events(){
    this.openUser.on('click', this.openOverlay.bind(this));
    this.closeUser.on('click', this.closeOverlay.bind(this));
    this.document.on('keydown', this.keyPressDispatcher.bind(this));
    this.loginForm.find('input').on('keyup', this.loginValidator.bind(this));
    this.registerForm.find('input').on('keyup', this.registerValidator.bind(this));
    this.resetForm.find('input').on('keyup', this.resetValidator.bind(this));
    this.loginSubmit.on('click', this.userLoginFunction.bind(this));
    this.registerSubmit.on('click', this.userRegisterFunction.bind(this));
    this.resetSubmit.on('click', this.userResetFunction.bind(this));
    this.toggleToReset.on('click', this.toggleToResetFunction.bind(this));
    this.toggleToRegister.on('click', this.toggleToRegisterFunction.bind(this));
    this.toggleToLogin.on('click', this.toggleToLoginFunction.bind(this));
    this.updateEmail.on('click', this.updateEmailFunction.bind(this));
    this.updateEmailSave.on('click', this.updateEmailSaveFunction.bind(this));
    this.updateEmailCancel.on('click', this.updateEmailCancelFunction.bind(this));
    this.updatePassword.on('click', this.updatePasswordFunction.bind(this));
    this.updatePasswordSave.on('click', this.updatePasswordSaveFunction.bind(this));
    this.updatePasswordCancel.on('click', this.updatePasswordCancelFunction.bind(this));
    this.profileImageUpload.on('click', this.profileImageUploadFunction.bind(this));
    this.profileImageUploadInput.on('change', this.profileImageUploadHandle.bind(this));
    this.userLogout.on('click', this.userLogoutFunction.bind(this));
  }

  //3. Define methods
  openOverlay(){
    this.userOverlay.addClass('user-active'); //Opens user overlay
    jQuery('html').addClass('noscroll'); //Removes scroll from body
    this.isOverlayOpen = true; //Updates overlay variable status
    return false;
  }

  closeOverlay(){
    this.userOverlay.removeClass('user-active'); //Closes user overlay
    jQuery('html').removeClass('noscroll'); //Adds scrolling back to body
    this.loginResponse.html('');
    this.loginName.val('');
    this.loginPassword.val('');
    this.loginRemember.attr('checked', false);
    clearTimeout(this.timer);
    this.isOverlayOpen = false; //Updates overlay variable status
  }

  keyPressDispatcher(e){
    //Opens user with U key
    if (e.keyCode == 85 && !this.isOverlayOpen && !jQuery("input, textarea, div[contenteditable]").is(':focus')) {
      this.openOverlay();
    }
    //Closes user with ESC key
    if (e.keyCode == 27 && this.isOverlayOpen) {
      this.closeOverlay();
    }
    //Submits login form with ENTER key
    if (e.keyCode == 13 && this.isOverlayOpen && !this.loginSubmit.hasClass('submit-disabled') && (this.loginName.is(':focus') || this.loginPassword.is(':focus'))) {
      this.loginSubmit.click();
    }
  }

  toggleToRegisterFunction(){
    this.userLogin.removeClass('active');
    this.userReset.removeClass('active');
    this.userRegister.addClass('active');
    this.registerName.val('');
    this.registerEmail.val('');
    this.registerPassword.val('');
    this.registerResponse.html('');
    this.loginSubmit.addClass('submit-disabled');
    this.registerSubmit.addClass('submit-disabled');
    this.resetSubmit.addClass('submit-disabled');
  }

  toggleToResetFunction(){
    this.userLogin.removeClass('active');
    this.userReset.addClass('active');
    this.userRegister.removeClass('active');
    this.resetEmail.val('');
    this.resetResponse.html('');
    this.loginSubmit.addClass('submit-disabled');
    this.registerSubmit.addClass('submit-disabled');
    this.resetSubmit.addClass('submit-disabled');
  }

  toggleToLoginFunction(){
    this.userLogin.addClass('active');
    this.userReset.removeClass('active');
    this.userRegister.removeClass('active');
    this.loginName.val('');
    this.loginPassword.val('');
    this.loginRemember.attr('checked', false);
    this.loginResponse.html('');
    this.loginSubmit.addClass('submit-disabled');
    this.registerSubmit.addClass('submit-disabled');
    this.resetSubmit.addClass('submit-disabled');
  }

  loginValidator(){
    //Checks input fields for values
    if (this.userOverlay.hasClass('user-active')){
      var username = this.loginName.val();
      var password = this.loginPassword.val();
      if (username && password){
        this.loginSubmit.removeClass('submit-disabled');
      } else {
        this.loginSubmit.addClass('submit-disabled');
      }
    }
  }

  registerValidator(){
    //Checks input fields for values
    if (this.userOverlay.hasClass('user-active')){
      var username = this.registerName.val();
      var password = this.registerPassword.val();
      var email = this.registerEmail.val();
      if (username && password && email){
        this.registerSubmit.removeClass('submit-disabled');
      } else {
        this.registerSubmit.addClass('submit-disabled');
      }
    }
  }

  resetValidator(){
    //Checks input fields for values
    if (this.userOverlay.hasClass('user-active')){
      var email = this.resetEmail.val();
      if (email){
        this.resetSubmit.removeClass('submit-disabled');
      } else {
        this.resetSubmit.addClass('submit-disabled');
      }
    }
  }

  userLoginFunction(){
    this.loginResponse.html('');
    jQuery.ajax({
      url: theHartAttackData.ajaxurl,
      data: {
        'username': this.loginName.val(),
        'password': this.loginPassword.val(),
        'remember': this.loginRemember.attr('is:checked'),
        'action': 'custom_login',
        'security': this.loginSecurity.val()
      },
      type: 'POST',
      success: (response) => {
        response = JSON.parse(response);
        if(response.status == 1){
          this.loginResponse.html('Login successful!');
          this.timer = setTimeout(() => eval(response.action), 1000);
        } else if (response.status == 0) {
          this.loginResponse.html(response.message);
        }
      },
      error: (response) => {
        response = JSON.parse(response);
        this.loginResponse.html('An unknown error occurred.');
      }
    })
  }

  userRegisterFunction(){
    this.registerResponse.html('');
    jQuery.ajax({
      url: theHartAttackData.ajaxurl,
      data: {
        'username': this.registerName.val(),
        'password': this.registerPassword.val(),
        'email': this.registerEmail.val(),
        'action': 'custom_register',
        'security': this.registerSecurity.val()
      },
      type: 'POST',
      success: (response) => {
        response = JSON.parse(response);
        if (response.status == 1){
          this.toggleLinks.hide();
          this.registerForm.hide();
          this.registerResponse.html(response.message);
          this.timer = setTimeout(() => eval(response.action), 5000);
        } else if (response.status == 0){
          this.registerResponse.html(response.message);
        }
      },
      error: (response) => {
        response = JSON.parse(response);
        this.registerResponse.html('An unknown error occurred, please try again later.');
      }
    })
  }

  userResetFunction(){
    this.resetResponse.html('');
    jQuery.ajax({
      url: theHartAttackData.ajaxurl,
      data: {
        'user': this.resetEmail.val(),
        'action': 'custom_reset',
        'security': this.resetSecurity.val()
      },
      type: 'POST',
      success: (response) => {
        response = JSON.parse(response);
        if (response.status == 1){
          this.resetResponse.html(response.message);
          this.timer = setTimeout(() => eval(response.action), 10000);
        } else if (response.status == 0){
          this.resetResponse.html(response.message);
        }
      },
      error: (response) => {
        response = JSON.parse(response);
        this.resetResponse.html('An unknown error occurred, please try again later.');
      }
    })
  }

  updateEmailFunction(){
    if (this.updateEmailContainer.attr('data-update') == 'false'){
      this.updateEmailContainer.attr('data-update', 'true');
      var currentEmail = jQuery('#user-profile-email-address').text();
      this.updateEmailContainer.find('strong').after('<input id="user-profile-email-input" type="text" value="'+currentEmail+'">');
      this.updateEmailContainer.find('span').hide();
      this.updateEmail.hide();
      this.updateEmailSave.show();
      this.updateEmailCancel.show();
    }
  }

  updateEmailSaveFunction(){
    if (this.updateEmailContainer.attr('data-update') == 'true'){
      jQuery.ajax({
        url: theHartAttackData.ajaxurl,
        data: {
          'user': this.userProfile.attr('data-id'),
          'email': jQuery('#user-profile-email-input').val(),
          'action': 'custom_update_email',
          'security': this.updateEmailSecurity.val()
        },
        type: 'POST',
        success: (response) => {
          response = JSON.parse(response);
          console.log(response.message);
          this.updateEmailContainer.attr('data-update', 'false');
          this.updateEmailContainer.find('#user-profile-email-input').remove();
          this.updateEmailContainer.find('span').text(response.email).show();
          this.updateEmail.show();
          this.updateEmailSave.hide();
          this.updateEmailCancel.hide();
        },
        error: (response) => {
          //response = JSON.parse(response);
        }
      })
    }
  }

  updateEmailCancelFunction(){
    if (this.updateEmailContainer.attr('data-update') == 'true'){
      this.updateEmailContainer.attr('data-update', 'false');
      this.updateEmailContainer.find('#user-profile-email-input').remove();
      this.updateEmailContainer.find('span').show();
      this.updateEmail.show();
      this.updateEmailSave.hide();
      this.updateEmailCancel.hide();
    }
  }

  updatePasswordFunction(){
    if (this.updatePasswordContainer.attr('data-update') == 'false'){
      this.updatePasswordContainer.attr('data-update', 'true');
      this.updatePasswordContainer.prepend('<span class="new-password-label">New Password:</span><input id="user-profile-password-input" type="password">');
      this.updatePassword.hide();
      this.updatePasswordSave.show();
      this.updatePasswordCancel.show();
    }
  }

  updatePasswordSaveFunction(){
    if (this.updatePasswordContainer.attr('data-update') == 'true'){
      jQuery.ajax({
        url: theHartAttackData.ajaxurl,
        data: {
          'user': this.userProfile.attr('data-id'),
          'password': jQuery('#user-profile-password-input').val(),
          'action': 'custom_update_password',
          'security': this.updatePasswordSecurity.val()
        },
        type: 'POST',
        success: (response) => {
          response = JSON.parse(response);
          this.updatePasswordContainer.attr('data-update', 'false');
          this.updatePasswordContainer.find('.new-password-label').remove();
          this.updatePasswordContainer.find('#user-profile-password-input').remove();
          this.updatePasswordSave.hide();
          this.updatePasswordCancel.hide();
          this.updatePasswordContainer.append('<span class="response-message">'+response.message+'</span>');
          this.timer = setTimeout(() => eval(response.action), 5000);
        },
        error: (response) => {
          //response = JSON.parse(response);
        }
      })
    }
  }

  updatePasswordCancelFunction(){
    if (this.updatePasswordContainer.attr('data-update') == 'true'){
      this.updatePasswordContainer.attr('data-update', 'false');
      this.updatePasswordContainer.find('.new-password-label').remove();
      this.updatePasswordContainer.find('#user-profile-password-input').remove();
      this.updatePassword.show();
      this.updatePasswordSave.hide();
      this.updatePasswordCancel.hide();
    }
  }

  profileImageUploadFunction(){
    this.profileImageUploadInput.click();
  }

  profileImageUploadHandle(){
    var form = document.getElementById('user-image-upload-form');
    var fd = new FormData(form);
    fd.append('action', 'custom_file_upload');
    jQuery.ajax({
      url: theHartAttackData.ajaxurl,
      type: 'POST',
      contentType: false,
      processData: false,
      enctype: 'multipart/form-data',
      data: fd,
      success: (response) => {
        response = JSON.parse(response);
        this.profileImage.attr('src', response.url);
      },
      error: (response) => {
      }
    })
  }

  userLogoutFunction(){
    jQuery.ajax({
      url: theHartAttackData.ajaxurl,
      type: 'POST',
      data: {
        'action': 'custom_logout'
      },
      success: (response) => {
        response = JSON.parse(response);
        console.log(response);
        eval(response.action);
      },
      error: (response) => {
      }
    })
  }

};

var user = new User();

export default User;
