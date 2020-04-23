class Contact {

  //1. Create object
  constructor(){
    this.body = jQuery('body');
    this.respMenu = jQuery('#resp-menu');
    this.resultsDiv = jQuery('#contact-results');
    this.openContact = jQuery('#nav-contact');
    this.respOpenContact = jQuery('#resp-contact');
    this.closeContact = jQuery('#contact-close');
    this.contactOverlay = jQuery('#contact-overlay');
    this.contactContainer = jQuery('#contact-container');
    this.contactForm = jQuery('#contact-form');
    this.contactName = jQuery('#contact-name');
    this.contactEmail = jQuery('#contact-email');
    this.contactMessage = jQuery('#contact-message');
    this.contactSubmit = jQuery('#contact-submit');
    this.contactSuccess = jQuery('span.contact-success');
    this.contactFailure = jQuery('span.contact-failure');
    this.events();
    this.isOverlayOpen = false;
    this.isSpinnerVisible = false;
    this.responseTimeout;
  }

  //2. Define events
  events(){
    this.openContact.on('click', this.openOverlay.bind(this));
    this.respOpenContact.on('click', this.openOverlay.bind(this));
    this.closeContact.on('click', this.closeOverlay.bind(this));
    jQuery(document).on('keydown', this.keyPressDispatcher.bind(this));
    jQuery('form#contact-form').on('keyup', this.contactValidator.bind(this));
    this.contactSubmit.on('click', this.sendMail.bind(this));
  }

  //3. Define methods
  openOverlay(){
    this.respMenu.removeClass('open');
    clearTimeout(this.responseTimeout);
    this.contactOverlay.find('.contact-response').remove();
    this.contactName.val('');
    this.contactEmail.val('');
    this.contactMessage.val('');
    this.contactContainer.removeClass('hidden');
    this.contactOverlay.addClass('contact-active'); //Opens contact overlay
    this.body.addClass('overlayed'); //Removes scroll from body
    setTimeout(() => this.contactName.focus(), 250); //Focuses contact field once overlay open
    this.isOverlayOpen = true; //Updates overlay variable status
    return false;
  }

  closeOverlay(){
    this.contactOverlay.removeClass('contact-active'); //Closes contact overlay
    this.contactSubmit.attr('disabled', 'disabled'); //Disables submit button
    this.body.removeClass('overlayed'); //Adds scrolling back to body
    this.isOverlayOpen = false; //Updates overlay variable status
  }

  keyPressDispatcher(e) {
    //Opens search with C key
    if (e.keyCode == 67 && !this.isOverlayOpen && !jQuery("input, textarea, div[contenteditable]").is(':focus') && !this.body.hasClass('overlayed')) {
      this.openOverlay();
    }
    //Closes search with ESC key
    if (e.keyCode == 27 && this.isOverlayOpen) {
      this.closeOverlay();
    }
  }

  contactValidator(){
    //Checks input fields for values
    if (this.contactOverlay.hasClass('contact-active')){
      var name = this.contactName.val();
      var email = this.contactEmail.val();
      var message = this.contactMessage.val();
      if (name && email && message){
        this.contactSubmit.removeAttr('disabled');
      } else {
        this.contactSubmit.attr('disabled', 'disabled');
      }
    }
  }

  sendMail(e){
    e.preventDefault();
    this.contactContainer.addClass('hidden');
    this.contactOverlay.append('<div class="loader-spinner"></div>');
    jQuery.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-NONCE', theHartAttackData.nonce)
      },
      url: theHartAttackData.root_url + '/wp-json/thehartattack/v1/manageContact',
      type: 'POST',
      data: {
        'contactName': this.contactName.val(),
        'contactEmail': this.contactEmail.val(),
        'contactMessage': this.contactMessage.val()
      },
      success: (response) => {
        this.contactOverlay.find('.loader-spinner').remove();
        this.contactOverlay.append('<span class="contact-response">'+response.message+'</span>');
        this.responseTimeout = setTimeout(() => this.closeOverlay(), 5000);
      },
      error: (response) => {
        this.contactOverlay.find('.loader-spinner').remove();
        this.contactOverlay.append('<span class="contact-response">'+response.message+'</span>');
        this.reponseTimeout = setTimeout(() => this.closeOverlay(), 5000);
      }
    });
  }

};

var contact = new Contact();

export default Contact;
