/*Search*/

class Search {

  //1. Create object
  constructor(){
    this.body = jQuery('body');
    this.respMenu = jQuery('#resp-menu');
    this.resultsDiv = jQuery('#search-results');
    this.openSearch = jQuery('#nav-search');
    this.respOpenSearch = jQuery('#resp-search');
    this.closeSearch = jQuery('#search-close');
    this.searchOverlay = jQuery('#search-overlay');
    this.searchField = jQuery('#search-input');
    this.events();
    this.isOverlayOpen = false;
    this.isSpinnerVisible = false;
    this.previousValue;
    this.typingTimer;
  }

  //2. Define events
  events(){
    this.openSearch.on('click', this.openOverlay.bind(this));
    this.respOpenSearch.on('click', this.openOverlay.bind(this));
    this.closeSearch.on('click', this.closeOverlay.bind(this));
    jQuery(document).on("keydown", this.keyPressDispatcher.bind(this));
    this.searchField.on("keyup", this.typingLogic.bind(this));
  }

  //3. Define methods
  openOverlay(){
    this.respMenu.removeClass('open');
    this.searchOverlay.addClass('search-active'); //Opens search overlay
    this.body.addClass('overlayed'); //Removes scroll from body
    this.searchField.val(''); //Clears search input field
    this.resultsDiv.html('');
    setTimeout(() => this.searchField.focus(), 251); //Focuses search field once overlay open
    this.isOverlayOpen = true; //Updates overlay variable status
    return false;
  }

  closeOverlay(){
    this.resultsDiv.html('');
    this.searchOverlay.removeClass('search-active'); //Closes search overlay
    this.body.removeClass('overlayed'); //Adds scrolling back to body
    this.isOverlayOpen = false; //Updates overlay variable status
    this.previousValue = '';
  }

  keyPressDispatcher(e) {
    //Opens search with S key
    if (e.keyCode == 83 && !this.isOverlayOpen && !jQuery('input, textarea').is(':focus') && !this.body.hasClass('overlayed')) {
      this.openOverlay();
    }
    //Closes search with ESC key
    if (e.keyCode == 27 && this.isOverlayOpen) {
      this.closeOverlay();
    }
  }

  typingLogic() {
    if (this.searchField.val() != this.previousValue) { //When search field value changes
      clearTimeout(this.typingTimer); //Clear existing timer
      if (this.searchField.val()) { //Check if search field has a value
        if (!this.isSpinnerVisible) { //Check if spinner visible
          this.resultsDiv.html('<svg class="spinner-loader" viewBox="0 0 96 96"><polygon points="72,90 96,48 72,6 24,6 0,48 24,90 "/></svg>'); //Make spinner visible
          this.isSpinnerVisible = true; //Update spinner status
        }
        this.typingTimer = setTimeout(this.getResults.bind(this), 750); //Get results after .75 second timer
      } else {
        this.resultsDiv.html(''); //Clear results
        this.isSpinnerVisible = false; //Update spinner status

      }
    }
    this.previousValue = this.searchField.val(); //Updates previous value variable
  }

  getResults() {
    jQuery.getJSON(theHartAttackData.root_url + '/wp-json/thehartattack/v1/search?term=' + this.searchField.val(), (results) => {
      if (results.postsPages.length){
        this.resultsDiv.html(`<div id="search-results-inner">
          ${results.postsPages.map(item => `
          <a href="${item.permalink}" class="search-result">
            <div class="search-result-feat-img" style="background-image: url('${item.postFeatImg}')"></div>
            <div class="search-result-content">
              <h2 class="search-result-title">${item.title}</h2>
              <span class="search-result-date">${item.postDate}</span>
              <h3 class="search-result-subtitle">${item.subtitle}</h3>
              <span class="search-result-read-more">Read post <span>| &raquo;</span></span>
            </div>
            <svg viewBox="0 0 64 64" class="comment-icon">
              <path d="M56,0H8C3,0,0,3,0,8v32c0,5,3,8,8,8v16l16-16h32c5,0,8-3,8-8V8C64,3,61,0,56,0z"/>
            </svg>
            <span class="search-result-comment-count">${item.commentCount}</span>
            <svg viewBox="0 0 64 64" class="like-icon">
              <path d="M58.5,6L58.5,6C51.2-2,39.3-2,32,6C24.7-2,12.8-2,5.5,6h0c-7.3,8-7.3,21,0,29L32,64l26.5-29 C65.8,27,65.8,14,58.5,6z"/>
            </svg>
            <span class="search-result-like-count">${item.likeCount}</span>
          </a>
          `).join('')}</div>`);
      } else {
        this.resultsDiv.html('No results found.');
      }
      this.isSpinnerVisible = false;
    });
  }

};

var search = new Search();

export default Search;
