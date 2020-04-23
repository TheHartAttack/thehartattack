/*Posts*/

class Posts {

    //1. Create object
    constructor(){
        this.html = jQuery('html');
        this.posts = jQuery('#posts');
        this.pagination = jQuery('#posts-pagination');
        this.previousButton = jQuery('#previous-posts');
        this.nextButton = jQuery('#next-posts');
        this.postsPageNumber = jQuery('#posts-page-number');
        this.events();
    }
  
    //2. Define events
    events(){
        this.previousButton.on('click', this.loadPostsDispatcher.bind(this));
        this.nextButton.on('click', this.loadPostsDispatcher.bind(this));
        this.postsPageNumber.on('change', this.pageNumberDispatcher.bind(this));
    }
  
    //3. Define methods
    loadPostsDispatcher(e){
        e.preventDefault();
        if (e.target == this.nextButton[0]){
          this.loadPosts(1);
        }
        if (e.target == this.previousButton[0]){
          this.loadPosts(-1);
        }
    }

    pageNumberDispatcher(){
        let regex = /\d/;
        let pageNumber = this.postsPageNumber.val();
        if (regex.test(pageNumber)){
            this.loadPosts(0, pageNumber);
        }
    }

    loadPosts(olderNewer, pageInput){
        let height = this.posts.height();
        this.posts.css('height', height);
        this.html.animate({
            scrollTop: (this.posts.offset().top - 96)
        }, 250);
        this.posts.addClass('loading').html('<div class="loader-spinner"></div>');
        this.pagination.hide();
        jQuery.ajax({
            url: theHartAttackData.ajaxurl,
            data: {
                'category': this.posts.attr('data-cat'),
                'currentPage': this.pagination.attr('data-page'),
                'olderNewer': olderNewer,
                'pageInput': pageInput,
                'action': 'load_posts'
            },
            type: 'POST',
            success: (response) => {
                response = JSON.parse(response);
                console.log(response);
                if (response.status == 1){
                    this.posts.removeClass('loading');
                    this.pagination.show();
                    this.postsPageNumber.val(response.pageNumber);
                    this.pagination.attr('data-page', response.pageNumber);
                    this.pagination.attr('data-last', response.isLastPage);
                    let loadedPosts = '';
                    for (let i = 0; i < response.data.length; i++){
                        loadedPosts += `
                            <a class="post" href="${response.data[i].permalink}">
                            <svg viewBox="0 0 64 64" class="like-icon">
                                <path d="M58.5,6L58.5,6C51.2-2,39.3-2,32,6C24.7-2,12.8-2,5.5,6h0c-7.3,8-7.3,21,0,29L32,64l26.5-29
                                C65.8,27,65.8,14,58.5,6z"/>
                            </svg>
                            <div class="post-likes-count">${response.data[i].likeCount}</div>
                            <svg viewBox="0 0 64 64" class="comment-icon">
                                <path d="M56,0H8C3,0,0,3,0,8v32c0,5,3,8,8,8v16l16-16h32c5,0,8-3,8-8V8C64,3,61,0,56,0z"/>
                            </svg>
                            <div class="post-comments-count">${response.data[i].commentCount}</div>
                            <div class="post-feat-image" style="background-image: url(${response.data[i].image});"></div>
                            <div class="post-content">
                                <h2 class="post-title">${response.data[i].postTitle}</h2>
                                <span class="post-date">${response.data[i].postDate}</span>
                                <h3 class="post-subtitle">${response.data[i].postSubtitle}</h3>
                                <span class="read-more">Read post <span>| &raquo;</span></span>
                            </div>
                            </a>
                        `;
                    }
                    this.posts.html(loadedPosts);
                    this.posts.css('height', 'unset');
                    this.postsPageNumber.blur();
                }
            },
            error: (response) => {
                response = JSON.parse(response);
                console.log(response);
            }
        })
    }

};

var posts = new Posts();
export default Posts;
  