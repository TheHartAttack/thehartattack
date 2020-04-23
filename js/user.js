/*user*/

class User {

  //1. Create object
  constructor(){
    this.document = jQuery(document);
    this.html = jQuery('html');
    this.body = jQuery('body');
    this.userProfile = jQuery('#user-profile');
    this.userActions = jQuery('#user-actions');
    //Bio elements
    this.userBioContainer = jQuery('div#user-profile-right');
    this.userBio = jQuery('div#user-bio.user-own-page');
    this.userBioText = jQuery('div#user-bio-text');
    this.userBioForm = jQuery('form#user-bio-form');
    this.userBioTextArea = jQuery('textarea#user-bio-textarea');
    this.userBioSave = jQuery('button#user-bio-save');
    this.userBioCancel = jQuery('button#user-bio-cancel');
    this.userBioSecurity = jQuery('#bio-security');
    //Email elements
    this.updateEmail = jQuery('#update-email-button');
    this.updateEmailOverlay = jQuery('#update-email-overlay');
    this.closeEmailOverlay = jQuery('#update-email-close');
    this.updateEmailForm = jQuery('#update-email');
    this.updateEmailEmail = jQuery('#update-email-email');
    this.updateEmailPassword = jQuery('#update-email-pw');
    this.updateEmailSecurity = jQuery('#update-email-security');
    this.updateEmailSubmit = jQuery('#update-email-submit');
    //Password elements
    this.changePassword = jQuery('#change-password-button');
    this.changePasswordOverlay = jQuery('#change-password-overlay');
    this.closePasswordOverlay = jQuery('#change-password-close');
    this.changePasswordForm = jQuery('#change-password');
    this.changePasswordCurrent = jQuery('#change-password-current');
    this.changePasswordNew = jQuery('#change-password-new');
    this.changePasswordSecurity = jQuery('#change-password-security');
    this.changePasswordSubmit = jQuery('#change-password-submit');
    //Image elements
    this.userImageDiv = jQuery('#user-image-container');
    this.profileImage = jQuery('#user-profile-image');
    this.profileImageUploadButton = jQuery('#user-image-upload-button');
    this.profileImageUploadForm = jQuery('#user-image-upload-form');
    this.profileImageUploadInput = jQuery('#user-image-upload');
    this.profileImageUploadSecurity = jQuery('#image-security');
    this.profileImageUploadLoaderSpinner = jQuery('#loader-spinner');
    //Recent comments elements
    this.olderComments = jQuery('#load-older-comments');
    this.newerComments = jQuery('#load-newer-comments');
    this.userComments = jQuery('#user-comments');
    this.commentsList = jQuery('#comments-ul');
    //Liked posts elements
    this.olderLikedPosts = jQuery('#load-older-posts');
    this.newerLikedPosts = jQuery('#load-newer-posts');
    this.userLikedPosts = jQuery('#user-liked-posts');
    this.likedPostsList = jQuery('#liked-posts-ul');
    //Misc
    this.userLogout = jQuery('#user-logout-button');
    this.events();
    this.emailOverlayOpen = false;
    this.passwordOverlayOpen = false;
    this.timer;
  }

  //2. Define events
  events(){
    this.document.on('keydown', this.keyPressDispatcher.bind(this));
    //Bio events
    this.userBioContainer.on('click', this.userBioDispatcher.bind(this));
    //Email events
    this.updateEmail.on('click', this.openEmailOverlayFunction.bind(this));
    this.closeEmailOverlay.on('click', this.closeEmailOverlayFunction.bind(this));
    this.updateEmailSubmit.on('click', this.updateEmailSaveFunction.bind(this));
    //Password events
    this.changePassword.on('click', this.openPasswordOverlayFunction.bind(this));
    this.closePasswordOverlay.on('click', this.closePasswordOverlayFunction.bind(this));
    this.changePasswordSubmit.on('click', this.changePasswordSaveFunction.bind(this));
    //Image events
    this.profileImageUploadButton.on('click', this.profileImageUploadFunction.bind(this));
    this.profileImageUploadInput.on('change', this.profileImageUploadHandle.bind(this));
    //Recent comments events
    this.olderComments.on('click', this.loadCommentsDispatcher.bind(this));
    this.newerComments.on('click', this.loadCommentsDispatcher.bind(this));
    //Liked posts events
    this.olderLikedPosts.on('click', this.loadPostsDispatcher.bind(this));
    this.newerLikedPosts.on('click', this.loadPostsDispatcher.bind(this));
    //Misc
    this.userLogout.on('click', this.userLogoutFunction.bind(this));
  }

  //3. Define methods

  keyPressDispatcher(e){
    if (e.keyCode == 27 && this.emailOverlayOpen){
      this.closeEmailOverlayFunction(e);
    }
    if (e.keycode == 27 && this.passwordOverlayOpen){
      this.closePasswordOverlayFunction(e);
    }
  }

  loadCommentsDispatcher(e){
    if (e.target == this.olderComments[0]){
      this.loadComments(1);
    }
    if (e.target == this.newerComments[0]){
      this.loadComments(-1);
    }
  }

  loadPostsDispatcher(e){
    if (e.target == this.olderLikedPosts[0]){
      this.loadPosts(1);
    }
    if (e.target == this.newerLikedPosts[0]){
      this.loadPosts(-1);
    }
  }

  loadComments(olderNewer){
    this.html.animate({
      scrollTop: (this.userComments.offset().top - 88)
    }, 250);
    this.userComments.addClass('loading');
    this.commentsList.addClass('loading').html('<div class="loader-spinner"></div>');
    jQuery.ajax({
      url: theHartAttackData.ajaxurl,
      data: {
        'user': this.userProfile.attr('data-id'),
        'page': this.userComments.attr('data-current-page'),
        'number': this.userComments.attr('data-number'),
        'olderNewer': olderNewer,
        'action': 'load_recent_comments'
      },
      type: 'POST',
      success: (response) => {
        response = JSON.parse(response);
        this.userComments.attr('data-current-page', response.pageNumber);
        this.userComments.attr('data-last-page', response.lastPage);
        this.commentsList.find('.loader-spinner').remove();
        this.userComments.removeClass('loading');
        this.commentsList.removeClass('loading')
        for (let i = 0; i < response.data.length; i++){
          let parentComment = '';
          /*if (response.data[i].parent != '0'){
            parentComment = `
              <blockquote class="quoted-comment-block" data-parent="${response.data[i].parent}">
                <span class="quoted-author">${response.data[i].parentAuthor} posted ${response.data[i].parentPostedDay} at ${response.data[i].parentPostedDate}:</span>
                <span class="quoted-comment">${response.data[i].parentContent}</span>
              </blockquote>
            `;
          }*/
          this.commentsList.append(`
            <li class="user-page-comment">
              <div class="user-page-comment-header">
                  <span>Posted ${response.data[i].postedDay} at ${response.data[i].time} in <a href="${response.data[i].permalink}">${response.data[i].postTitle}</a></span>
                  <div class="user-page-comment-likes">
                      <span>${response.data[i].likeCount}</span>
                      <svg viewBox="0 0 42 42" class="icon-likes">
                        <path d="M38.4,23L38.4,23c4.8-5.2,4.8-13.8,0-19l0,0c-4.8-5.2-12.6-5.2-17.4,0c-4.8-5.2-12.6-5.2-17.4,0l0,0c-4.8,5.2-4.8,13.8,0,19l0,0l0,0L21,42L38.4,23L38.4,23z"/>
                      </svg>
                  </div>
              </div>
              <div class="user-page-comment-body">`
                +parentComment+
                `<p>${response.data[i].commentContent}</p>
              </div>
            </li>
          `);
        }
      },
      error: (response) => {
        response = JSON.parse(response);
        this.commentsList.find('.loader-spinner').remove();
      }
    })
  }

  loadPosts(olderNewer){
    this.html.animate({
      scrollTop: (this.userLikedPosts.offset().top - 88)
    }, 250);
    this.userLikedPosts.addClass('loading');
    this.likedPostsList.addClass('loading').html('<div class="loader-spinner"></div>');
    jQuery.ajax({
      url: theHartAttackData.ajaxurl,
      data: {
        'user': this.userProfile.attr('data-id'),
        'page': this.userLikedPosts.attr('data-current-page'),
        'number': this.userLikedPosts.attr('data-number'),
        'olderNewer': olderNewer,
        'action': 'load_liked_posts'
      },
      type: 'POST',
      success: (response) => {
        response = JSON.parse(response);
        if (response.status == 1){
          this.userLikedPosts.attr('data-current-page', response.pageNumber);
          this.userLikedPosts.attr('data-last-page', response.lastPage);
          this.likedPostsList.find('.loader-spinner').remove();
          this.userLikedPosts.removeClass('loading');
          this.likedPostsList.removeClass('loading');
          for (let i = 0; i < response.data.length; i++){
            this.likedPostsList.append(`
              <li>
                <a href="${response.data[i].permalink}" class="user-profile-liked-post">
                  <div class="user-profile-liked-post-image" style="background-image: url(${response.data[i].image}"></div>
                  <div class="user-profile-liked-post-inner">
                    <h3 class="post-title">${response.data[i].postTitle}</h3>
                    <span class="post-date">${response.data[i].postDate}</span>
                    <span class="read-post">Read post <span>| &raquo;</span></span>
                  </div>
                  <svg viewBox="0 0 64 64" class="comment-icon">
                    <path d="M56,0H8C3,0,0,3,0,8v32c0,5,3,8,8,8v16l16-16h32c5,0,8-3,8-8V8C64,3,61,0,56,0z">
                  </svg>
                  <span class="user-profile-liked-post-comments">${response.data[i].commentCount}</span>
                  <svg viewBox="0 0 64 64" class="like-icon">
                    <path d="M58.5,6L58.5,6C51.2-2,39.3-2,32,6C24.7-2,12.8-2,5.5,6h0c-7.3,8-7.3,21,0,29L32,64l26.5-29
                    C65.8,27,65.8,14,58.5,6z">
                  </svg>
                  <span class="user-profile-liked-post-likes">${response.data[i].likeCount}</span>
                </a>
              </li>
            `);
          }
        } else {
          this.userLikedPosts.html(`<span class="error-message">${response.message}</span>`);
        }
      },
      error: (response) => {
        response = JSON.parse(response);
        this.likedPostsList.find('.loader-spinner').remove();
        this.userLikedPosts.html(`<span class="error-message">${response.message}</span>`);
      }
    })
  }

  openEmailOverlayFunction(e){
    e.preventDefault();
    this.body.addClass('overlayed');
    this.updateEmailOverlay.fadeIn();
    this.emailOverlayOpen = true;
    setTimeout(() => this.updateEmailEmail.focus(), 250)
  }

  closeEmailOverlayFunction(e){
    e.preventDefault();
    this.body.removeClass('overlayed');
    this.updateEmailOverlay.fadeOut();
    this.emailOverlayOpen = false;
  }

  updateEmailSaveFunction(e){
    e.preventDefault();
    this.updateEmailForm.children().hide();
    this.updateEmailForm.append(`
      <div class="loader-spinner"></div>
    `);
    if (this.emailOverlayOpen){
      jQuery.ajax({
        url: theHartAttackData.ajaxurl,
        data: {
          'user': this.userProfile.attr('data-id'),
          'email': this.updateEmailEmail.val(),
          'password': this.updateEmailPassword.val(),
          'action': 'custom_update_email',
          'security': this.updateEmailSecurity.val()
        },
        type: 'POST',
        success: (response) => {
          response = JSON.parse(response);
          this.updateEmailForm.find('.loader-spinner').remove();
          this.updateEmailForm.append(`
            <span class="success-message">${response.message}</span>
          `);
          if (response.status == 1){
            setTimeout(() => {
              this.closeEmailOverlayFunction(e)
            }, 3000);
            setTimeout(() => {
              this.updateEmailForm.find('.success-message').remove();
              this.updateEmailForm.children().show().val("");
            }, 3500);
          } else {
            setTimeout(() => {
              this.updateEmailForm.find('.success-message').remove();
              this.updateEmailForm.children().fadeIn();
            }, 3000);
          }
        },
        error: (response) => {
          response = JSON.parse(response);
          this.updateEmailForm.find('.loader-spinner').remove();
          this.updateEmailForm.children().show();
          this.updateEmailForm.append(`
            <span class="error-message">${response.message}</span>
          `);
        }
      })
    }
  }

  openPasswordOverlayFunction(e){
    e.preventDefault();
    this.body.addClass('overlayed');
    this.changePasswordOverlay.fadeIn();
    this.passwordOverlayOpen = true;
    setTimeout(() => this.changePasswordCurrent.focus(), 250)
  }

  closePasswordOverlayFunction(e){
    e.preventDefault();
    this.body.removeClass('overlayed');
    this.changePasswordOverlay.fadeOut();
    this.passwordOverlayOpen = false;
  }

  changePasswordSaveFunction(e){
    e.preventDefault();
    this.changePasswordForm.children().hide();
    this.changePasswordForm.append(`
      <div class="loader-spinner"></div>
    `);
    if (this.passwordOverlayOpen){
      jQuery.ajax({
        url: theHartAttackData.ajaxurl,
        data: {
          'user': this.userProfile.attr('data-id'),
          'old': this.changePasswordCurrent.val(),
          'new': this.changePasswordNew.val(),
          'action': 'custom_change_password',
          'security': this.changePasswordSecurity.val()
        },
        type: 'POST',
        success: (response) => {
          response = JSON.parse(response);
          this.changePasswordForm.find('.loader-spinner').remove();
          this.changePasswordForm.append(`
            <span class="success-message">${response.message}</span>
          `);
          if (response.status == 1){
            setTimeout(() => {
              this.closePasswordOverlayFunction(e)
            }, 3000);
            setTimeout(() => {
              this.changePasswordForm.find('.success-message').remove();
              this.changePasswordForm.children().show().val("");
            }, 3500);
          } else {
            setTimeout(() => {
              this.changePasswordForm.find('.success-message').remove();
              this.changePasswordForm.children().fadeIn();
            }, 3000);
          }
        },
        error: (response) => {
          response = JSON.parse(response);
          this.changePasswordForm.find('.loader-spinner').remove();
          this.changePasswordForm.children().show();
          this.changePasswordForm.append(`
            <span class="error-message">${response.message}</span>
          `);
        }
      })
    }
  }

  userBioDispatcher(e){
    e.preventDefault();
    if (this.userBioContainer.attr('data-edit') == 'false'){
      this.editUserBio();
    }
    if (e.target == this.userBioSave[0]){
      this.saveUserBio();
    }
    if (e.target == this.userBioCancel[0]){
      this.cancelUserBio();
    }
  }

  editUserBio(){
    this.userBioContainer.attr('data-edit', 'true');
    this.userBioForm.show();
    this.userBio.hide();
    let val = this.userBioTextArea.val();
    this.userBioTextArea.focus().val("").val(val);
  }

  cancelUserBio(){
    let val = this.userBioTextArea.val();
    this.userBioTextArea.val(val);
    this.userBioContainer.attr('data-edit', 'false');
    this.userBioForm.hide();
    this.userBio.show();
  }

  saveUserBio(){
    jQuery.ajax({
      url: theHartAttackData.ajaxurl,
      data: {
        'user': this.userProfile.attr('data-id'),
        'bio': this.userBioTextArea.val(),
        'action': 'custom_update_bio',
        'security': this.userBioSecurity.val()
      },
      type: 'POST',
      success: (response) => {
        response = JSON.parse(response);
        this.userBioText.html(response.data);
        this.userBioTextArea.val(response.data);
        this.userBioContainer.attr('data-edit', 'false');
        this.userBioForm.hide();
        this.userBio.show();
      },
      error: (response) => {
        response = JSON.parse(response);
      }
    })
  }

  profileImageUploadFunction(){
    this.profileImageUploadInput.click();
  }

  profileImageUploadHandle(){
    var form = document.getElementById('user-image-upload-form');
    var fd = new FormData(form);
    fd.append('action', 'custom_file_upload');
    this.profileImageUploadForm.hide();
    this.userImageDiv.append('<div class="loader-spinner"></div>');
    jQuery.ajax({
      url: theHartAttackData.ajaxurl,
      type: 'POST',
      contentType: false,
      processData: false,
      enctype: 'multipart/form-data',
      data: fd,
      success: (response) => {
        response = JSON.parse(response);
        console.log(response);
        this.profileImage.attr('src', response.url);
        this.userImageDiv.find('.loader-spinner').remove();
        this.profileImageUploadForm.show();
      },
      error: (response) => {
        response = JSON.parse(response);
        console.log(response)
        this.userImageDiv.find('.loader-spinner').remove();
        this.profileImageUploadForm.show();
      }
    });
  }

  userLogoutFunction(e){
    e.preventDefault();
    console.log('Logging out...');
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
        response = JSON.parse(response);
        console.log(response);
      }
    })
  }

};

var user = new User();

export default User;
