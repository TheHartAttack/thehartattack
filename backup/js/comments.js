/*Comments*/

class Comments {

  //1. Create object
  constructor(){
    this.document = jQuery(document);
    this.comments = jQuery('#single-post-comments');
    this.commentsForm = jQuery('#comments-form');
    this.commentInput = jQuery('#comment-input');
    this.commentPost = jQuery('#post-comment');
    this.commentQuote = jQuery('.comment-quote');
    this.commentLike = jQuery('.comment-like');
    this.commentEdit = jQuery('.comment-edit');
    this.commentSave = jQuery('.comment-save');
    this.commentCancel = jQuery('.comment-cancel');
    this.originalcomment;
    this.quotedCommentId;
    this.cancelCommentQuote;
    this.events();
  }

  //2. Define events
  events(){
    this.commentPost.on('click', this.commentPostFunction.bind(this));
    this.commentEdit.on('click', this.commentEditFunction.bind(this));
    this.commentSave.on('click', this.commentSaveFunction.bind(this));
    this.commentCancel.on('click', this.commentCancelFunction.bind(this));
    this.commentQuote.on('click', this.commentQuoteFunction.bind(this));
  }

  //3. Define methods

  commentQuoteFunction(e){
    var quotedComment = jQuery(e.target).closest('.comment-body').find('.comment-content').html();
    var quotedAuthor = jQuery(e.target).closest('.comment').find('.username').html();
    var quotedDate = jQuery(e.target).closest('.comment-body').find('.comment-date').html();
    var quotedTime = jQuery(e.target).closest('.comment-body').find('.comment-time').html();
    quotedComment = `
    <div class="quoted-comment-container">
      <span class="quote-head">Quoted comment:</span>
      <blockquote class="quoted-comment-block">
        <span class="quoted-author">${quotedAuthor} posted ${quotedDate} at ${quotedTime}: </span>
        <span class="quoted-comment">${quotedComment}</span>
      </blockquote>
      <div id="cancel-quote">✖</div>
    </div>`;
    this.commentsForm.find('.quoted-comment-container').remove();
    this.commentsForm.prepend(quotedComment);
    this.quotedCommentId = jQuery(e.target).closest('.comment').data('comment');
    document.getElementById('cancel-quote').addEventListener('click', () => {
      this.commentsForm.find('.quoted-comment-container').remove();
      quotedCommentId = '';
    });
  }

  commentEditFunction(e){
    var targetComment = jQuery(e.target).closest('.comment-body').find('.comment-content');
    var targetCommentContent = jQuery(e.target).closest('.comment-body').find('.comment-content-inner');
    var targetCommentEdit = jQuery(e.target).closest('.comment-edit-container').find('.comment-edit');
    var targetCommentSave = jQuery(e.target).closest('.comment-edit-container').find('.comment-save');
    var targetCommentCancel = jQuery(e.target).closest('.comment-edit-container').find('.comment-cancel');
    this.originalComment = targetCommentContent.html();
    targetCommentEdit.hide();
    targetCommentSave.show();
    targetCommentCancel.show();
    targetComment.attr('data-edit', 'true');
    var editTextArea = '<textarea class="edit-text-area">'+this.originalComment+'</textarea>';
    targetComment.append(editTextArea);
    targetCommentContent.hide();
  }

  commentCancelFunction(e){
    var targetComment = jQuery(e.target).closest('.comment-body').find('.comment-content');
    var targetCommentContent = jQuery(e.target).closest('.comment-body').find('.comment-content-inner');
    var targetCommentEdit = jQuery(e.target).closest('.comment-edit-container').find('.comment-edit');
    var targetCommentSave = jQuery(e.target).closest('.comment-edit-container').find('.comment-save');
    var targetCommentCancel = jQuery(e.target).closest('.comment-edit-container').find('.comment-cancel');
    var editBox = jQuery(e.target).closest('.comment-body').find('.edit-text-area');
    targetCommentContent.html(this.originalComment);
    editBox.remove();
    targetCommentContent.show();
    targetCommentEdit.show();
    targetCommentSave.hide();
    targetCommentCancel.hide();
  }

  commentSaveFunction(e){
    var targetComment = jQuery(e.target).closest('.comment-body').find('.comment-content');
    var targetCommentContent = jQuery(e.target).closest('.comment-body').find('.comment-content-inner');
    var targetCommentEdit = jQuery(e.target).closest('.comment-edit-container').find('.comment-edit');
    var targetCommentSave = jQuery(e.target).closest('.comment-edit-container').find('.comment-save');
    var targetCommentCancel = jQuery(e.target).closest('.comment-edit-container').find('.comment-cancel');
    var editBox = jQuery(e.target).closest('.comment-body').find('.edit-text-area');
    jQuery.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-NONCE', theHartAttackData.nonce)
      },
      url: theHartAttackData.root_url + '/wp-json/thehartattack/v1/manageComment',
      type: 'POST',
      data: {
        'comment': editBox.val(),
        'commentId': jQuery(e.target).closest('.comment').attr('data-comment')
      },
      success: (response) => {
        console.log(response);
        editBox.remove();
        targetCommentContent.html(response.updatedContent);
        targetCommentContent.show();
        targetCommentEdit.show();
        targetCommentSave.hide();
        targetCommentCancel.hide();
      },
      error: (response) => {
        console.log(response);
      }
    });
  }

  commentPostFunction(){
    jQuery('.comment-error').remove();
    jQuery.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-NONCE', theHartAttackData.nonce)
      },
      url: theHartAttackData.root_url + '/wp-json/thehartattack/v1/manageComment',
      type: 'POST',
      data: {
        'comment': this.commentInput.val(),
        'postId': this.comments.data('post'),
        'quotedCommentId': this.quotedCommentId
      },
      success: (response) => {
        console.log(response);
        if (response.status == 1){
          eval(response.action);
        } else if (response.status == 0){
          console.log(response.message);
          this.commentsForm.append('<span class="comment-error">'+response.message+'</span>');
        }
      },
      error: (response) => {
        console.log(response);
      }
    });
  }

};

var comments = new Comments();

export default Comments;
