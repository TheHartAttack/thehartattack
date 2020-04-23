<?php

  function theHartAttackLikes(){
    register_rest_route('thehartattack/v1', 'manageLike', array(
      'methods' => 'POST',
      'callback' => 'createLike'
    ));

    register_rest_route('thehartattack/v1', 'manageLike', array(
      'methods' => 'DELETE',
      'callback' => 'deleteLike'
    ));
  }

  function createLike($data){
    if (is_user_logged_in()){
      $postId = sanitize_text_field($data['postId']);

      $userLikeQuery = new WP_Query(array(
        'author' => get_current_user_id(),
        'post_type' => 'like',
        'meta_query' => array(
          array(
            'key' => 'liked_post_id',
            'compare' => '=',
            'value' => $postId
          )
        )
      ));

      if ($userLikeQuery->found_posts == 0 AND get_post_type($postId) == 'post'){
        return wp_insert_post(array(
          'post_type' => 'like',
          'post_status' => 'publish',
          'post_title' => wp_get_current_user()->user_login . ' liked ' . get_post_field('post_title', $postId),
          'meta_input' => array(
            'liked_post_id' => $postId
          )
        ));
      } else {
        die('Invalid post ID.');
      }
    } else {
      die('Only logged in users can like posts.');
    }

  }

  function deleteLike($data){
    $likeId = sanitize_text_field($data['likeId']);
    if (get_current_user_id() == get_post_field('post_author', $likeId) AND get_post_type($likeId) == 'like'){
      wp_delete_post($likeId, true);
      return 'Like deleted.';
    } else {
      die('You do not have permission to delete this like.');
    }
  }

  add_action('rest_api_init', 'theHartAttackLikes');
