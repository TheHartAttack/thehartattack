/*Comments*/

class Comments {

  //1. Create object
  constructor(){
    this.document = jQuery(document);
    this.window = jQuery(window);
    this.html = jQuery('html');
    this.commentsContainer = jQuery('#single-post-comments');
    this.comments = jQuery('#comments');
    this.commentsForm = jQuery('#comments-form');
    this.commentInput = jQuery('#comment-input');
    this.commentPost = jQuery('#post-comment');
    this.loadMoreWrapper = jQuery('#load-more-wrapper');
    this.loadMore = jQuery('#load-more-comments');
    this.originalcomment;
    this.events();
  }

  //2. Define events
  events(){
    this.commentPost.on('click', this.commentPostFunction.bind(this));
    this.comments.on('click', this.commentsClickDispatcher.bind(this));
    this.loadMore.on('click', this.loadComments.bind(this));
    this.commentInput.on('keyup', this.keypressDispatcher.bind(this));
    this.comments.on('keyup', this. replyKeypressDispatcher.bind(this));
  }

  //3. Define methods

  commentsClickDispatcher(e){
    e.preventDefault();
    let target = jQuery(e.target);
    if (target.hasClass('comment-edit-button')){
      this.commentEditFunction(e);
    } else if (target.hasClass('comment-cancel-button')){
      this.commentCancelFunction(e);
    } else if (target.hasClass('comment-save-button')){
      this.commentSaveFunction(e);
    } else if (target.hasClass('comment-reply-button')){
      this.commentShowReply(e);
    } else if (target.hasClass('comment-show-replies')){
      this.commentShowReplies(e);
    } else if (target.hasClass('comment-hide-replies')){
      this.commentHideReplies(e);
    } else if (target.hasClass('comment-reply-submit')){
      this.commentReplyFunction(e);
    }
  }

  keypressDispatcher(e){
    if (this.commentInput.val() == ''){
      this.commentPost.attr('disabled', 'disabled');
    } else {
      this.commentPost.removeAttr('disabled');
    }
  }

  replyKeypressDispatcher(e){
    let target = jQuery(e.target);
    if (target.hasClass('comment-reply-input')){
      let button = target.next();
      if (target.val() == ''){
        button.attr('disabled', 'disabled');
      } else {
        button.removeAttr('disabled');
      }
    }
  }

  commentShowReplies(e){
    e.preventDefault();
    let targetComment = jQuery(e.target).closest('.comment');
    targetComment.find('.comment-show-replies').html(targetComment.find('.comment-show-replies').html().replace('See', 'Hide')).toggleClass('comment-show-replies').toggleClass('comment-hide-replies');
    targetComment.find('.comment-replies').show();
  }

  commentHideReplies(e){
    e.preventDefault();
    let targetComment = jQuery(e.target).closest('.comment');
    targetComment.find('.comment-hide-replies').html(targetComment.find('.comment-hide-replies').html().replace('Hide', 'See')).toggleClass('comment-show-replies').toggleClass('comment-hide-replies');
    targetComment.find('.comment-replies').hide();
  }

  commentEditFunction(e){
    let targetComment = jQuery(e.target).closest('.comment, .subcomment');
    let targetCommentContent = jQuery(e.target).closest('.comment-body, .subcomment-body').find('.comment-content');
    let targetCommentContentInner = jQuery(e.target).closest('.comment-body, .subcomment-body').find('.comment-content-inner');
    this.originalComment = targetCommentContentInner.html();
    targetComment.attr('data-edit', '1');
    let editTextArea = '<textarea class="edit-text-area">'+this.originalComment+'</textarea>';
    targetCommentContent.append(editTextArea);
    targetCommentContentInner.hide();
  }

  commentCancelFunction(e){
    let targetComment = jQuery(e.target).closest('.comment, .subcomment');
    let targetCommentContentInner = jQuery(e.target).closest('.comment-body, .subcomment-body').find('.comment-content-inner');
    let replyInput = targetComment.find('.comment-reply');
    let editBox = jQuery(e.target).closest('.comment-body, .subcomment-body').find('.edit-text-area');
    targetComment.attr('data-edit', '0').attr('data-reply', '0');
    targetCommentContentInner.html(this.originalComment);
    editBox.remove();
    targetCommentContentInner.show();
    replyInput.hide();
  }

  commentSaveFunction(e){
    let targetComment = jQuery(e.target).closest('.comment, .subcomment');
    let targetCommentContentInner = jQuery(e.target).closest('.comment-body, .subcomment-body').find('.comment-content-inner');
    let editBox = jQuery(e.target).closest('.comment-body, .subcomment-body').find('.edit-text-area');
    jQuery.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-NONCE', theHartAttackData.nonce)
      },
      url: theHartAttackData.root_url + '/wp-json/thehartattack/v1/manageComment',
      type: 'POST',
      data: {
        'comment': editBox.val(),
        'commentId': jQuery(e.target).closest('.comment, .subcomment').attr('data-comment')
      },
      success: (response) => {
        editBox.remove();
        targetComment.attr('data-edit', '0');
        targetCommentContentInner.html(response.updatedContent);
        targetCommentContentInner.show();
      },
      error: (response) => {
        console.log(response);
      }
    });
  }

  commentShowReply(e){
    e.preventDefault();
    let targetComment = jQuery(e.target).closest('.comment');
    let replyInput = targetComment.find('.comment-reply');
    targetComment.attr('data-reply', '1');
    replyInput.show();
  }

  commentReplyFunction(e){
    let target = jQuery(e.target);
    let subcommentContent = target.closest('form').find('.comment-reply-input').val();
    let parentId = target.closest('.comment').data('comment');
    let postId = this.commentsContainer.data('post');
    target.closest('.comment-reply').find('.comment-error').remove();
    if (subcommentContent != ''){
      jQuery.ajax({
        beforeSend: (xhr) => {
          xhr.setRequestHeader('X-WP-NONCE', theHartAttackData.nonce)
        },
        url: theHartAttackData.root_url + '/wp-json/thehartattack/v1/manageComment',
        type: 'POST',
        data: {
          'comment': subcommentContent,
          'postId': postId,
          'parentCommentId': parentId
        },
        success: (response) => {
          if (response.status == 1){
            eval(response.action);
          } else if (response.status == 0){
            target.closest('.comment-reply').append(`<div class="comment-error">${response.message}</div>`);
          }
        },
        error: (response) => {
          target.closest('.comment-reply').append(`<div class="comment-error">${response.message}</div>`);
        }
      });
    }
  }

  commentPostFunction(e){
    e.preventDefault();
    this.commentsForm.find('.comment-error').remove();
    if (this.commentInput.val() != ''){
      jQuery.ajax({
        beforeSend: (xhr) => {
          xhr.setRequestHeader('X-WP-NONCE', theHartAttackData.nonce)
        },
        url: theHartAttackData.root_url + '/wp-json/thehartattack/v1/manageComment',
        type: 'POST',
        data: {
          'comment': this.commentInput.val(),
          'postId': this.commentsContainer.data('post'),
          'parentCommentId': this.quotedCommentId
        },
        success: (response) => {
          if (response.status == 1){
            eval(response.action);
          } else if (response.status == 0){
            this.commentsForm.append(`<span class="comment-error">${response.message}</span>`);
          }
        },
        error: (response) => {
          this.commentsForm.append(`<span class="comment-error">${response.message}</span>`);
        }
      });
    } else {
      this.commentsForm.append('<div class="comment-error">You cannot submit a blank comment.</div>');
    }
  }

  loadComments(){
    this.loadMoreWrapper.addClass('loading').append('<div class="loader-spinner"></div>');
    jQuery.ajax({
        url: theHartAttackData.ajaxurl,
        data: {
            'id': this.commentsContainer.attr('data-post'),
            'offset': this.comments.find('.comment').length,
            'action': 'load_comments'
        },
        type: 'POST',
        success: (response) => {
            response = JSON.parse(response);
            console.log(response);
            if (response.status == 1){
                this.loadMoreWrapper.removeClass('loading').find('.loader-spinner').remove();
                let loadedComments = '';
                for (let i = 0; i < response.data.length; i++){
                    let subcomments = '';
                    for (let j = 0; j < response.data[i].subcomments.length; j++){
                      subcomments += `
                        <div class="subcomment comment-id" data-comment="${response.data[i].subcomments[j].id}">
                          <div class="subcomment-user">
                            <a href="${response.data[i].subcomments[j].author.url}"><h4 class="username">${response.data[i].subcomments[j].author.username}</h4></a>
                            <a href="${response.data[i].subcomments[j].author.url}" class="comment-user-image"><img class="user-image" src="${response.data[i].subcomments[j].author.image}" alt=""></a>
                            <div class="user-comments-likes">
                              <div class="user-comments">
                                <span class="post-count">${response.data[i].subcomments[j].author.comments}</span>
                                <svg viewBox="0 0 64 64" class="comment-icon">
                                  <path d="M56,0H8C3,0,0,3,0,8v32c0,5,3,8,8,8v16l16-16h32c5,0,8-3,8-8V8C64,3,61,0,56,0z"/>
                                </svg>
                              </div>
                              <div class="user-likes">
                                <span class="user-likes-count">${response.data[i].subcomments[j].author.likes}</span>
                                <svg viewBox="0 0 64 64" class="like-icon">
                                  <path d="M58.5,6L58.5,6C51.2-2,39.3-2,32,6C24.7-2,12.8-2,5.5,6h0c-7.3,8-7.3,21,0,29L32,64l26.5-29
                                  C65.8,27,65.8,14,58.5,6z"/>
                                </svg>
                              </div>
                            </div>
                          </div>
                          <div class="subcomment-body">
                          
                            <div class="comment-time-like">
                              <span class="comment-timedate">Posted <span class="comment-date">${response.data[i].subcomments[j].postedDay}</span> at <span class="comment-time">${response.data[i].subcomments[j].time}</span></span>
                              <div class="comment-like" data-login="${response.data[i].subcomments[j].userStatus}" ${response.user != response.data[i].subcomments[j].author.id ? `data-like="${response.data[i].subcomments[j].likeId}"` : ``}>
                                <span class="comment-like-count">${response.data[i].subcomments[j].likeCount}</span>
                                <svg viewBox="0 0 64 64" class="like-icon">
                                  <path d="M58.5,6L58.5,6C51.2-2,39.3-2,32,6C24.7-2,12.8-2,5.5,6h0c-7.3,8-7.3,21,0,29L32,64l26.5-29
                                  C65.8,27,65.8,14,58.5,6z"/>
                                </svg>
                              </div>
                            </div>
                            <div class="comment-content">
                              <div class="comment-content-inner">${response.data[i].subcomments[j].content}</div>
                            </div>
                            <div class="comment-actions">
                              ${response.user == response.data[i].subcomments[j].author.id ? `<a href="#" class="comment-save-button">Save</a>` : ``}
                              <a href="#" class="comment-cancel-button">Cancel</a>
                              ${response.user == response.data[i].subcomments[j].author.id ? `<a href="#" class="comment-edit-button">Edit</a>` : ``}
                            </div>
                          </div>
                        </div>
                      `;
                    }
                    loadedComments += `
                      
                    <div class="comment comment-id" data-comment="${response.data[i].id}"${response.user == response.data[i].author.id ? `data-edit="0"` : ``} data-reply="0">
                      <div class="comment-inner">
                        <div class="comment-user">
                          <a href="${response.data[i].author.url}"><h4 class="username">${response.data[i].author.username}</h4></a>
                          <a href="${response.data[i].author.url}" class="comment-user-image"><img class="user-image" src="${response.data[i].author.image}" alt=""></a>
                          <div class="user-comments-likes">
                            <div class="user-comments">
                              <span class="post-count">${response.data[i].author.comments}</span>
                              <svg viewBox="0 0 64 64" class="comment-icon">
                                <path d="M56,0H8C3,0,0,3,0,8v32c0,5,3,8,8,8v16l16-16h32c5,0,8-3,8-8V8C64,3,61,0,56,0z"/>
                              </svg>
                            </div>
                            <div class="user-likes">
                              <span class="user-likes-count">${response.data[i].author.likes}</span>
                              <svg viewBox="0 0 64 64" class="like-icon">
                                <path d="M58.5,6L58.5,6C51.2-2,39.3-2,32,6C24.7-2,12.8-2,5.5,6h0c-7.3,8-7.3,21,0,29L32,64l26.5-29
                                C65.8,27,65.8,14,58.5,6z"/>
                              </svg>
                            </div>
                          </div>
                        </div>
                        <div class="comment-body">
                          <div class="comment-time-like">
                            <span class="comment-timedate">Posted <span class="comment-date">${response.data[i].postedDay}</span> at <span class="comment-time">${response.data[i].time}</span></span>
                            <div class="comment-like" data-login="${response.data[i].userStatus}" ${response.user != response.data[i].author.id ? `data-like="${response.data[i].likeId}` : ``}">
                              <span class="comment-like-count">${response.data[i].likeCount}</span>
                              <svg viewBox="0 0 64 64" class="like-icon">
                                <path d="M58.5,6L58.5,6C51.2-2,39.3-2,32,6C24.7-2,12.8-2,5.5,6h0c-7.3,8-7.3,21,0,29L32,64l26.5-29
                                C65.8,27,65.8,14,58.5,6z"/>
                              </svg>
                            </div>
                          </div>
                          <div class="comment-content">
                            <div class="comment-content-inner">${response.data[i].content}</div>
                          </div>
                          <div class="comment-actions">
                            <div class="comment-actions-left">
                              ${response.data[i].subcomments.length == 0 ? `` : response.data[i].subcomments.length == 1 ? `<a href="#" class="comment-show-replies">See ${response.data[i].subcomments.length} reply</a>` : `<a href="#" class="comment-show-replies">See ${response.data[i].subcomments.length} replies</a>`}
                            </div>
                            <div class="comment-actions-right">
                              ${response.user == response.data[i].author.id ? `<a href="#" class="comment-save-button">Save</a>`: ``}
                              <a href="#" class="comment-cancel-button">Cancel</a>
                              ${response.user == response.data[i].author.id ? `<a href="#" class="comment-edit-button">Edit</a>`: ``}
                              <a href="#" class="comment-reply-button">Reply</a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="comment-reply">
                        <form>
                          <textarea class="comment-reply-input" placeholder="Write a reply..."></textarea>
                          <button class="comment-reply-submit" disabled="disabled">Post</button>
                        </form>
                      </div>
                      ${subcomments != '' ? `<div class="comment-replies">${subcomments}</div>` : ``}            
                    </div>

                    `;
                }
                this.comments.append(loadedComments);
                if (response.isLast == 1){
                  this.loadMoreWrapper.hide();
                }
            } else {
              this.loadMoreWrapper.removeClass('loading').find('.loader-spinner').remove();
              this.loadMoreWrapper.append(`<div class="comment-error">${response.message}</div>`);
            }
        },
        error: (response) => {
            response = JSON.parse(response);
            console.log(response);
            this.loadMoreWrapper.removeClass('loading').find('.loader-spinner').remove();
            this.loadMoreWrapper.append(`<div class="comment-error">${response.message}</div>`);
        }
    })
  }

};

let comments = new Comments();

export default Comments;