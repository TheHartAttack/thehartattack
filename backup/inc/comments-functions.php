<?php

function theHartAttackComments(){
  register_rest_route('thehartattack/v1', 'manageComment', array(
    'methods' => 'POST',
    'callback' => 'manageComment'
  ));
}
add_action('rest_api_init', 'theHartAttackComments');

function manageComment($data){

$commentId = $data['commentId'];
  if ($commentId){
    editComment($data);
  } else {
    addComment($data);
  }
}

function editComment($data){

  $commentContent = sanitize_textarea_field($data['comment']);
  $commentId = $data['commentId'];
  $commentAuthor = get_comment($commentId)->user_id;
  if ($commentAuthor == get_current_user_id()){

    $comment = array(
      'comment_ID' => $commentId,
      'comment_content' => $commentContent
    );
    $updateComment = wp_update_comment($comment);

    if ($updateComment){
      echo json_encode(array(
        'status' => 1,
        'updatedContent' => sanitize_textarea_field($data['comment'])
      ));
    } else {
      echo json_encode(array(
        'status' => 0
      ));
    }

  }

  die();

}

function addComment($data){

  $user = wp_get_current_user();

  $userLastPost = get_comments(array(
    'user_id' => $user->ID,
    'orderby' => 'post_date',
    'order' => 'DESC',
    'number' => 1
  ));

  $userLastPostDate = $userLastPost[0]->comment_date;
  if (!strlen($userLastPostDate)){
    $userLastPostDate = '2000-01-01 00:00:00';
  }
  $currentDate = current_time('mysql');
  $datetime1 = date_format(date_create($userLastPostDate), 'U');
  $datetime2 = date_format(date_create($currentDate), 'U');
  $interval = $datetime2 - $datetime1;

  if ($interval >= 120){
    $newComment = wp_insert_comment(array(
      'comment_post_ID' => sanitize_text_field($data['postId']),
      'comment_author' => $user->display_name,
      'comment_author_email' => $user->user_email,
      'user_id' => $user->ID,
      'comment_content' => sanitize_textarea_field($data['comment']),
      'comment_approved' => 1,
      'comment_date' => $currentDate,
      'comment_parent' => sanitize_text_field($data['quotedCommentId'])
    ));
    if ($newComment){
      echo json_encode(array(
        'status' => 1,
        'comment_id' => $newComment,
        'action' => 'window.location.reload(true);',
      ));
    } else {
      echo json_encode(array(
        'status' => 0,
        'message' => 'Comment not posted.'
      ));
    }
  } else {
    echo json_encode(array(
      'status' => 0,
      'message' => 'Too soon to post another comment. Please try again in '.(120 - $interval).' seconds.',
      'lastPostTime' => $userLastPostDate,
      'currentTime' => $currentDate,
      'time_difference' => $interval
    ));
  }


  die();

}
