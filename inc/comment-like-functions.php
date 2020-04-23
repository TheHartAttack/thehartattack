<?php

  function theHartAttackCommentLikes(){
    register_rest_route('thehartattack/v1', 'manageCommentLike', array(
      'methods' => 'POST',
      'callback' => 'createCommentLike'
    ));

    register_rest_route('thehartattack/v1', 'manageCommentLike', array(
      'methods' => 'DELETE',
      'callback' => 'deleteCommentLike'
    ));
  }

  function createCommentLike($data){
    if (is_user_logged_in()){
      $commentId = sanitize_text_field($data['commentId']);
      $postId = sanitize_text_field($data['postId']);

      $userLikeQuery = new WP_Query(array(
        'author' => get_current_user_id(),
        'post_type' => 'comment-like',
        'meta_query' => array(
          array(
            'key' => 'liked_comment_id',
            'compare' => '=',
            'value' => $commentId
          )
        )
      ));

      $commentAuthor = get_comment($commentId)->user_id;
      $currentPost = get_post($postId);

      if ($userLikeQuery->found_posts == 0 AND $commentAuthor != get_current_user_id()){
        return wp_insert_post(array(
          'post_type' => 'comment-like',
          'post_status' => 'publish',
          'post_title' => wp_get_current_user()->user_login . ' liked a comment by ' . get_comment($commentId)->comment_author . ' on '. $currentPost->post_title,
          'meta_input' => array(
            'liked_comment_id' => $commentId,
          )
        ));
      } else if ($commentAuthor == get_current_user_id()){
        die('You cannot like your own comment.');
      } else {
        die('Invalid post ID.');
      }
    } else {
      die('Only logged in users can like posts.');
    }

  }

  function deleteCommentLike($data){
    $likeId = sanitize_text_field($data['likeId']);
    if (get_current_user_id() == get_post_field('post_author', $likeId) AND get_post_type($likeId) == 'comment-like'){
      wp_delete_post($likeId, true);
      return 'Like deleted.';
    } else {
      die('You do not have permission to delete this like.');
    }
  }

  add_action('rest_api_init', 'theHartAttackCommentLikes');
