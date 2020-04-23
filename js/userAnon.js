/*userAnon*/

class UserAnon {

    //1. Create object
    constructor(){
        //Login elements
        this.loginContainer = jQuery('#user-login');
        this.loginForm = jQuery('#user-login > form');
        this.loginUsername = jQuery('#user-login-username');
        this.loginPassword = jQuery('#user-login-password');
        this.loginRemember = jQuery('#user-login-remember');
        this.loginSecurity = jQuery('#user-login-security');
        this.loginSubmit = jQuery('#user-login-submit');
        //Reset elements
        this.resetButton = jQuery('#reset-password-link');
        this.resetContainer = jQuery('#reset-container');
        this.resetOverlay = jQuery('#reset-password-overlay');
        this.closeResetOverlayButton = jQuery('#reset-password-close');
        this.resetForm = jQuery('#reset-password-form');
        this.resetInput = jQuery('#reset-password-input');
        this.resetSecurity = jQuery('#reset-password-security');
        this.resetSubmit = jQuery('#reset-password-submit');
        //Resend elements
        this.resendButton = jQuery('#resend-activation-link');
        this.resendContainer = jQuery('#resend-container');
        this.resendOverlay = jQuery('#resend-activation-overlay');
        this.closeResendOverlayButton = jQuery('#resend-activation-close');
        this.resendForm = jQuery('#resend-activation-form');
        this.resendInput = jQuery('#resend-activation-input');
        this.resendSecurity = jQuery('#resend-activation-security');
        this.resendSubmit = jQuery('#resend-activation-submit');
        //Register elements
        this.registerContainer = jQuery('#user-register');
        this.registerForm = jQuery('#user-register > form');
        this.registerUsername = jQuery('#user-register-username');
        this.registerPassword = jQuery('#user-register-password');
        this.registerEmail = jQuery('#user-register-email');
        this.registerSecurity = jQuery('#user-register-security');
        this.registerSubmit = jQuery('#user-register-submit');
        //Misc elements
        this.body = jQuery('body');
        this.resetOverlayOpen = false;
        this.resendOverlayOpen = false;
        this.events();
    }
  
    //2. Define events
    events(){
        this.loginForm.on('keyup', this.loginValidator.bind(this));
        this.loginSubmit.on('click', this.userLogin.bind(this));
        this.resetButton.on('click', this.openResetOverlay.bind(this));
        this.closeResetOverlayButton.on('click', this.closeResetOverlay.bind(this));
        this.resetForm.on('keyup', this.resetValidator.bind(this));
        this.resetSubmit.on('click', this.resetPassword.bind(this));
        this.resendButton.on('click', this.openResendOverlay.bind(this));
        this.closeResendOverlayButton.on('click', this.closeResendOverlay.bind(this));
        this.resendForm.on('keyup', this.resendValidator.bind(this));
        this.resendSubmit.on('click', this.resendActivation.bind(this));
        this.registerForm.on('keyup', this.registerValidator.bind(this));
        this.registerSubmit.on('click', this.userRegister.bind(this));
    }
  
    //3. Define methods
    loginValidator(){
        let username = this.loginUsername.val();
        let password = this.loginPassword.val();
        if (username && password){
        this.loginSubmit.removeAttr('disabled');
        } else {
        this.loginSubmit.attr('disabled', 'disabled');
        }
    }

    userLogin(e){
        e.preventDefault();
        this.loginForm.addClass('loading hidden');
        this.loginForm.append('<div class="loader-spinner"></div>');
        jQuery.ajax({
            url: theHartAttackData.ajaxurl,
            data: {
                'username': this.loginUsername.val(),
                'password': this.loginPassword.val(),
                'remember': this.loginRemember.prop('checked'),
                'security': this.loginSecurity.val(),
                'action': 'custom_login'
            },
            type: 'POST',
            success: (response) => {
                console.log(response);
                response = JSON.parse(response);
                if (response.status == 1){
                    eval(response.action);
                } else {
                    this.loginForm.find('.loader-spinner').remove();
                    this.loginForm.append(`<span class="response-message">${response.message}</span>`);
                    this.loginUsername.val('');
                    this.loginPassword.val('');
                    this.loginRemember.prop('checked', false);
                    this.loginSubmit.attr('disabled', 'disabled');
                    setTimeout(() => {
                        this.loginForm.find('.response-message').fadeOut('250');
                        this.loginForm.removeClass('hidden');
                    }, 3000);
                    setTimeout(() => {
                        this.loginForm.find('.response-message').remove();
                        this.loginForm.removeClass('loading');
                    }, 3500);
                }
            },
            error: (response) => {
                console.log(response);
                response = JSON.parse(response);
                this.loginForm.find('.loader-spinner').remove();
                this.loginForm.append(`<span class="response-message">${response.message}</span>`);
                setTimeout(() => {
                    this.loginForm.find('.response-message').fadeOut('250');
                }, 3000);
                setTimeout(() => {
                    this.loginForm.removeClass('loading');
                }, 3500);
            }
          })
    }

    openResetOverlay(e){
        e.preventDefault();
        if (this.resetOverlayOpen == false){
            this.resetContainer.find('.response').remove();
        this.resetContainer.find('.loader-spinner').remove();
        this.resetForm.removeClass('hidden').show();
        this.resetInput.val('');
        this.body.addClass('overlayed');
        this.resetOverlay.fadeIn();
        this.resetOverlayOpen = true;
        setTimeout(() => this.resetInput.focus(), 250)
        }   
    }

    closeResetOverlay(e){
        e.preventDefault();
        if (this.resetOverlayOpen == true){
            this.body.removeClass('overlayed');
        this.resetOverlay.fadeOut();
        this.resetOverlayOpen = false;
        }
    }

    resetValidator(){
        let input = this.resetInput.val();
        if (input){
        this.resetSubmit.removeAttr('disabled');
        } else {
        this.resetSubmit.attr('disabled', 'disabled');
        }
    }

    resetPassword(e){
        e.preventDefault();
        this.resetForm.addClass('hidden').hide();
        this.resetContainer.append('<div class="loader-spinner"></div>');
        jQuery.ajax({
            url: theHartAttackData.ajaxurl,
            type: 'POST',
            data: {
                'user': this.resetInput.val(),
                'security': this.resetSecurity.val(),
                'action': 'custom_reset'
            },
            success: (response) => {
                response = JSON.parse(response);
                if (response.status == 1){
                    this.resetContainer.find('.loader-spinner').remove();
                    this.resetContainer.append('<span class="response">'+response.message+'</span>');
                    setTimeout(() => this.closeResetOverlay(e), 3000);
                } else {
                    this.resetContainer.find('.loader-spinner').remove();
                    this.resetContainer.append('<span class="response">'+response.message+'</span>');
                    setTimeout(() => {
                        this.resetContainer.find('.response').fadeOut(250, () => this.resetContainer.find('.response').remove());
                        this.resetForm.fadeIn(250).removeClass('hidden');
                    }, 3000);
                } 
            },
            error: (response) => {
                response = JSON.parse(response);
                this.resetContainer.find('.loader-spinner').remove();
                this.resetContainer.append('<span class="response">'+response.message+'</span>');
                setTimeout(() => this.closeResetOverlay(e), 3000);

            }
        });
    }

    openResendOverlay(e){
        e.preventDefault();
        if (this.resendOverlayOpen == false){
            this.resendContainer.find('.response').remove();
        this.resendForm.removeClass('hidden').show();
        this.resendContainer.find('.loader-spinner').remove();
        this.resendInput.val('');
        this.body.addClass('overlayed');
        this.resendOverlay.fadeIn();
        this.resendOverlayOpen = true;
        setTimeout(() => this.resendInput.focus(), 250)
        }
    }

    closeResendOverlay(e){
        e.preventDefault();
        if (this.resendOverlayOpen == true){
            this.body.removeClass('overlayed');
        this.resendOverlay.fadeOut();
        this.resendOverlayOpen = false;
        }
    }

    resendValidator(){
        let input = this.resendInput.val();
        if (input){
        this.resendSubmit.removeAttr('disabled');
        } else {
        this.resendSubmit.attr('disabled', 'disabled');
        }
    }

    resendActivation(e){
        e.preventDefault();
        this.resendForm.addClass('hidden').hide();
        this.resendContainer.append('<div class="loader-spinner"></div>');
        jQuery.ajax({
            url: theHartAttackData.ajaxurl,
            type: 'POST',
            data: {
                'user': this.resendInput.val(),
                'security': this.resendSecurity.val(),
                'action': 'custom_resend'
            },
            success: (response) => {
                response = JSON.parse(response);
                if (response.status == 1){
                    this.resendContainer.find('.loader-spinner').remove();
                    this.resendContainer.append('<span class="response">'+response.message+'</span>');
                    setTimeout(() => this.closeResendOverlay(e), 3000);
                } else {
                    this.resendContainer.find('.loader-spinner').remove();
                    this.resendContainer.append('<span class="response">'+response.message+'</span>');
                    setTimeout(() => {
                        this.resendContainer.find('.response').fadeOut(250, () => this.resendContainer.find('.response').remove());
                        this.resendForm.fadeIn(250).removeClass('hidden');
                    }, 3000);
                } 
            },
            error: (response) => {
                response = JSON.parse(response);
                this.resendContainer.find('.loader-spinner').remove();
                this.resendContainer.append('<span class="response">'+response.message+'</span>');
                setTimeout(() => this.closeResendOverlay(e), 3000);

            }
        });
    }

    registerValidator(){
        let username = this.registerUsername.val();
        let email = this.registerEmail.val();
        let password = this.registerPassword.val();
        if (username && email && password){
        this.registerSubmit.removeAttr('disabled');
        } else {
        this.registerSubmit.attr('disabled', 'disabled');
        }
    }

    userRegister(e){
        e.preventDefault();
        this.registerForm.addClass('loading hidden');
        this.registerForm.append('<div class="loader-spinner"></div>');
        jQuery.ajax({
            url: theHartAttackData.ajaxurl,
            data: {
                'username': this.registerUsername.val(),
                'email': this.registerEmail.val(),
                'password': this.registerPassword.val(),
                'security': this.registerSecurity.val(),
                'action': 'custom_register'
            },
            type: 'POST',
            success: (response) => {
                response = JSON.parse(response);
                if (response.status == 1){
                    this.registerForm.find('.loader-spinner').remove();
                    this.registerForm.append(`<span class="response-message">${response.message}</span>`);
                } else {
                    this.registerForm.find('.loader-spinner').remove();
                    this.registerForm.append(`<span class="response-message">${response.message}</span>`);
                    setTimeout(() => {
                        this.registerForm.find('.response-message').fadeOut('250');
                        this.registerForm.removeClass('hidden');
                    }, 3000);
                    setTimeout(() => {
                        this.registerForm.find('.response-message').remove();
                        this.registerForm.removeClass('loading');
                    }, 3500);
                }
            },
            error: (response) => {
                response = JSON.parse(response);
                this.registerForm.find('.loader-spinner').remove();
                this.registerForm.append(`<span class="response-message">${response.message}</span>`);
                setTimeout(() => {
                    this.registerForm.find('.response-message').fadeOut('250');
                }, 3000);
                setTimeout(() => {
                    this.registerForm.removeClass('loading');
                }, 3500);
            }
          })
    }
  
};

let userAnon = new UserAnon();

export default UserAnon;
