<?php


function get_posted_day($id){
  $today = date('jS F Y');
  $yesterday = date('jS F Y', time() - 86400);
  $postedDay = get_comment_date('jS F Y', $id);
  if ($today == $postedDay){
    $postedDay = 'today';
  } else if ($yesterday == $postedDay){
    $postedDay = 'yesterday';
  }
  return $postedDay;
}

function get_comment_like_count($id){
  $likeCount = new WP_Query(array(
    'post_type' => 'comment-like',
    'meta_query' => array(
      array(
        'key' => 'liked_comment_id',
        'compare' => '=',
        'value' => $id
      )
    )
  ));
  return $likeCount->found_posts;
}

function has_current_user_liked_comment($commentId){
  if(is_user_logged_in()){
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
  }
  if ($userLikeQuery->found_posts){
    return $userLikeQuery->posts[0]->ID;
  } else {
    return 0;
  }
}

function get_author_comments_likes_counts($authorId){
  $authorComments = get_comments(array(
    'user_id' => $authorId,
    'status' => 'approve'
  ));
  $commentIds = array();
  foreach ($authorComments as $authorComment){
    array_push($commentIds, $authorComment->comment_ID);
  }
  $authorLikes = new WP_Query(array(
    'post_type' => 'comment-like',
    'meta_query' => array(
      array(
        'key' => 'liked_comment_id',
        'compare' => 'IN',
        'value' => $commentIds
      )
    )
  ));
  return array(
    'comments' => count($authorComments),
    'likes' => $authorLikes->found_posts
  );
}

function is_user_logged_in_and_comment_author($authorId){
  if (is_user_logged_in()){
    $userStatus = 1;
    if (get_current_user_id() == $authorId){
      $userStatus = 2;
    }
  } else {
    $userStatus = 0;
  }
  return $userStatus;
}

function sort_comments_by_likes($comments){
  $reorderedComments = array();
  $i = 0;
  foreach ($comments as $comment){
    $likeCount = get_comment_like_count($comment->comment_ID);
    $reorderedComments[$i] = array(
      'id' => $comment->comment_ID,
      'likes' => $likeCount,
      'date' => $comment->comment_date
    );
    $i++;
  }
  usort($reorderedComments, function ($a, $b) {
    if ($a['likes'] == $b['likes']){
      if ($a['date'] > $b['date']){
        return -1;
      } else {
        return 1;
      }
    } else if ($a['likes'] > $b['likes']){
      return -1;
    } else {          
      return 1;
    }
  });
  $reReorderedComments = array();
  foreach ($reorderedComments as $comment){
    array_push($reReorderedComments, get_comment($comment['id']));
  }
  return $reReorderedComments;
}


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

  if ($interval >= 60){
    $newComment = wp_insert_comment(array(
      'comment_post_ID' => sanitize_text_field($data['postId']),
      'comment_author' => $user->display_name,
      'comment_author_email' => $user->user_email,
      'user_id' => $user->ID,
      'comment_content' => sanitize_textarea_field($data['comment']),
      'comment_approved' => 1,
      'comment_date' => $currentDate,
      'comment_parent' => sanitize_text_field($data['parentCommentId'])
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
      'message' => 'Too soon to post another comment. Please try again in '.(60 - $interval).' seconds.',
      'lastPostTime' => $userLastPostDate,
      'currentTime' => $currentDate,
      'time_difference' => $interval
    ));
  }


  die();

}

function handleLoadComments(){
  $post = $_POST['id'];
  $offset = $_POST['offset'];
  $data = array();

  $comments = get_comments(array(
    'orderby' => 'post_date',
    'status' => 'approve',
    'order' => 'DESC',
    'post_id' => $post,
    'parent' => 0
  ));
  $commentCount = count($comments);
  $comments = sort_comments_by_likes($comments);
  $comments = array_slice($comments, $offset, get_option('comments_per_page'));
  $isLast = 0;
  if ($offset + get_option('comments_per_page') >= $commentCount){
    $isLast = 1;
  }

  foreach ($comments as $comment){

    $commentId = $comment->comment_ID;
    $author = get_user_by('id', $comment->user_id);
    $postedDay = get_posted_day($commentId);
    $likeCount = get_comment_like_count($commentId);
    $likeId = has_current_user_liked_comment($commentId);
    $authorCommentsLikes = get_author_comments_likes_counts($author->ID);
    $authorComments = $authorCommentsLikes['comments'];
    $authorLikes = $authorCommentsLikes['likes'];
    $userStatus = is_user_logged_in_and_comment_author($author->ID);

    //Get replies
    $subcomments = get_comments(array(
      'orderby' => 'comment_date',
      'order' => 'DESC',
      'post_id' => $id,
      'status' => 'approve',
      'parent' => $comment->comment_ID
    ));
    $subcommentCount = count($subcomments);

    $author = array(
      'id' => $comment->user_id,
      'username' => $author->user_login,
      'url' => site_url('/user/'.$author->user_nicename),
      'image' => get_user_meta($comment->user_id, 'user-image', true),
      'comments' => shorthandNumber($authorComments),
      'likes' => shorthandNumber($authorLikes),
    );

    $currentComment = array(
      'id' => $comment->comment_ID,
      'content' => nl2br($comment->comment_content),
      'permalink' => get_the_permalink($comment->comment_post_ID),
      'time' => get_comment_date('H:i', $comment->comment_ID),
      'postedDay' => $postedDay,
      'likeCount' => $likeCount,
      'likeId' => $likeId,
      'userStatus' => $userStatus,
      'author' => $author
    );

    $sc = array();
    if ($subcomments){
      $subcomments = sort_comments_by_likes($subcomments);
      foreach ($subcomments as $subcomment){
        $commentId = $subcomment->comment_ID;
        $author = get_user_by('id', $subcomment->user_id);
        $postedDay = get_posted_day($commentId);
        $likeCount = get_comment_like_count($commentId);
        $likeId = has_current_user_liked_comment($commentId);
        $authorCommentsLikes = get_author_comments_likes_counts($author->ID);
        $authorComments = $authorCommentsLikes['comments'];
        $authorLikes = $authorCommentsLikes['likes'];
        $userStatus = is_user_logged_in_and_comment_author($author->ID);

        $author = array(
          'id' => $subcomment->user_id,
          'username' => $author->user_login,
          'url' => site_url('/user/'.$author->user_nicename),
          'image' => get_user_meta($comment->user_id, 'user-image', true),
          'comments' => shorthandNumber($authorComments),
          'likes' => shorthandNumber($authorLikes),
        );

        $currentSubcomment = array(
          'id' => $subcomment->comment_ID,
          'content' => nl2br($subcomment->comment_content),
          'permalink' => get_the_permalink($subcomment->comment_post_ID),
          'time' => get_comment_date('H:i', $subcomment->comment_ID),
          'postedDay' => $postedDay,
          'likeCount' => $likeCount,
          'likeId' => $likeId,
          'userStatus' => $userStatus,
          'author' => $author
        );

        array_push($sc, $currentSubcomment);

      }
    }

    $currentComment['subcomments'] = $sc;

    array_push($data, $currentComment);

  }

  echo json_encode(array(
    'status' => 1,
    'data' => $data,
    'isLast' => $isLast,
    'total' => $commentCount,
    'user' => get_current_user_id()
  ));

  wp_die();

}
add_action("wp_ajax_load_comments", "handleLoadComments");
add_action("wp_ajax_nopriv_load_comments", "handleLoadComments");
