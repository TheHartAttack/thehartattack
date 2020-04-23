/*Likes*/

class CommentLike {

  constructor(){
    this.comments = jQuery('#comments');
    this.events();
  }

  events(){
    this.comments.on('click', this.clickDispatcher.bind(this));
  }

  //Methods
  clickDispatcher(e){
    let currentLikeBox = jQuery(e.target).closest('.comment-like');
    if (currentLikeBox.attr('data-like')){
      let currentComment = jQuery(e.target).closest('.comment-id[data-comment]');
      if (currentLikeBox.attr('data-like') != '0'){
        this.deleteLike(currentLikeBox);
      } else {
        if (currentLikeBox.attr('data-login') == 1)
        this.createLike(currentLikeBox, currentComment);
      }
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
        let likeCount = parseInt(currentLikeBox.find('.comment-like-count').html(), 10);
        likeCount++;
        currentLikeBox.find('.comment-like-count').html(likeCount);
        currentLikeBox.attr('data-like', response);
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
        //currentLikeBox.attr('data-status', 'false');
        let likeCount = parseInt(currentLikeBox.find('.comment-like-count').html(), 10);
        likeCount--;
        currentLikeBox.find('.comment-like-count').html(likeCount);
        currentLikeBox.attr('data-like', 0);
      },
      error: (response) => {
        console.log(response);
      }
    });
  }

};

let commentLike = new CommentLike();

export default CommentLike;
