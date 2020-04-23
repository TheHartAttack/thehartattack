/*Likes*/

class Like {

  constructor(){
    this.events();
  }

  events(){
    jQuery('.single-post-likes').on('click', this.clickDispatcher.bind(this));
  }

  //Methods
  clickDispatcher(e){
    var currentLikeBox = jQuery(e.target).closest('.single-post-likes');
    if (currentLikeBox.attr('data-status') == 'true'){
      this.deleteLike(currentLikeBox);
    } else {
      this.createLike(currentLikeBox);
    }
  }

  createLike(currentLikeBox){
    jQuery.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-NONCE', theHartAttackData.nonce)
      },
      url: theHartAttackData.root_url + '/wp-json/thehartattack/v1/manageLike',
      type: 'POST',
      data: {
        'postId': currentLikeBox.data('post')
      },
      success: (response) => {
        currentLikeBox.attr('data-status', 'true');
        var likeCount = parseInt(currentLikeBox.find('.single-post-likes-count').html(), 10);
        likeCount++;
        currentLikeBox.find('.single-post-likes-count').html(likeCount);
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
      url: theHartAttackData.root_url + '/wp-json/thehartattack/v1/manageLike',
      data: {
        'likeId': currentLikeBox.attr('data-like')
      },
      type: 'DELETE',
      success: (response) => {
        currentLikeBox.attr('data-status', 'false');
        var likeCount = parseInt(currentLikeBox.find('.single-post-likes-count').html(), 10);
        likeCount--;
        currentLikeBox.find('.single-post-likes-count').html(likeCount);
        currentLikeBox.attr('data-like', '');
        console.log(response);
      },
      error: (response) => {
        console.log(response);
      }
    });
  }

};

var like = new Like();

export default Like;
