/*Likes*/

class CommentLike {

  constructor(){
    this.events();
  }

  events(){
    jQuery('.comment-like').on('click', this.clickDispatcher.bind(this));
  }

  //Methods
  clickDispatcher(e){
    var currentLikeBox = jQuery(e.target).closest('.comment-like');
    var currentComment = jQuery(e.target).closest('.comment');
    if (currentLikeBox.attr('data-status') == 'true'){
      this.deleteLike(currentLikeBox);
    } else {
      this.createLike(currentLikeBox, currentComment);
    }
  }

  createLike(currentLikeBox, currentComment){
    jQuery.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-NONCE', theHartAttackData.nonce)
      },
      url: theHartAttackData.root_url + '/wp-json/thehartattack/v1/manageCommentLike',
      type: 'POST',
      data: {
        'commentId': currentComment.data('comment'),
        'postId': jQuery('#single-post-comments').data('post')
      },
      success: (response) => {
        currentLikeBox.attr('data-status', 'true');
        var likeCount = parseInt(currentLikeBox.find('.comment-like-count').html(), 10);
        likeCount++;
        currentLikeBox.find('.comment-like-count').html(likeCount);
        currentLikeBox.attr('data-like', response);
        console.log(response);
      },
      error: (response) => {
        console.log(response);
      }
    });
  }

  deleteLike(currentLikeBox){
    jQuery.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-NONCE', theHartAttackData.nonce)
      },
      url: theHartAttackData.root_url + '/wp-json/thehartattack/v1/manageCommentLike',
      data: {
        'likeId': currentLikeBox.attr('data-like')
      },
      type: 'DELETE',
      success: (response) => {
        currentLikeBox.attr('data-status', 'false');
        var likeCount = parseInt(currentLikeBox.find('.comment-like-count').html(), 10);
        likeCount--;
        currentLikeBox.find('.comment-like-count').html(likeCount);
        currentLikeBox.attr('data-like', '');
        console.log(response);
      },
      error: (response) => {
        console.log(response);
      }
    });
  }

};

var commentLike = new CommentLike();

export default CommentLike;
