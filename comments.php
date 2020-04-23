<?php $id = get_the_ID(); ?>
<div id="single-post-comments" data-post="<?php echo $id; ?>">

  <h3>Comments</h3>

  <?php
  if (is_user_logged_in()){ ?>
    <form id="comments-form">
      <textarea id="comment-input" placeholder="Leave a comment..."></textarea>
      <button id="post-comment" disabled="disabled">Post</button>
    </form>
  <?php } else { ?>
    <span id="comments-login">Please <a href="<?php echo site_url('/user'); ?>">log in</a> to post a comment.</span>
  <?php } ?>

  <?php
    $numberOfComments = get_option('comments_per_page');
    $comments = get_comments(array(
        'orderby' => 'comment_date',
        'order' => 'DESC',
        'post_id' => $id,
        'status' => 'approve',
        'parent' => 0
    ));
    $commentCount = count($comments);

    if ($comments){
      $comments = sort_comments_by_likes($comments);
      $comments = array_slice($comments, 0, get_option('comments_per_page'));
      ?>

      <div id="comments">

      <?php

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

        ?>

        <div class="comment comment-id" data-comment="<?php echo $comment->comment_ID; ?>"<?php if ($comment->user_id == get_current_user_id()){ ?> data-edit="0"<?php } ?> data-reply="0">
          <div class="comment-inner">
            <div class="comment-user">
              <a href="<?php echo site_url('/user/'.$author->user_nicename) ?>"><h4 class="username"><?php echo $author->user_login ?></h4></a>
              <a href="<?php echo site_url('/user/'.$author->user_nicename) ?>" class="comment-user-image"><img class="user-image" src="<?php echo get_user_meta($author->ID, 'user-image', true); ?>" alt=""></a>
              <div class="user-comments-likes">
                <div class="user-comments">
                  <span class="post-count"><?php echo shorthandNumber($authorComments); ?></span>
                  <?php include 'svg/64/comment.php'; ?>
                </div>
                <div class="user-likes">
                  <span class="user-likes-count"><?php echo shorthandNumber($authorLikes); ?></span>
                  <?php include 'svg/64/like.php'; ?>
                </div>
              </div>
            </div>
            <div class="comment-body">
              <div class="comment-time-like">
                <span class="comment-timedate">Posted <span class="comment-date"><?php echo $postedDay; ?></span> at <span class="comment-time"><?php echo get_comment_time('H:i'); ?></span></span>
                <div class="comment-like" data-login="<?php echo $userStatus; ?>" <?php if ($comment->user_id != get_current_user_id()){ ?>data-like="<?php echo $likeId; ?>"<?php } ?>>
                  <span class="comment-like-count"><?php echo shorthandNumber($likeCount); ?></span>
                  <?php include 'svg/64/like.php'; ?>
                </div>
              </div>
              <div class="comment-content">
                <div class="comment-content-inner"><?php echo $comment->comment_content; ?></div>
              </div>
              <div class="comment-actions">
                <div class="comment-actions-left">
                  <?php if ($subcommentCount){ ?><a href="#" class="comment-show-replies">See <?php echo $subcommentCount; if ($subcommentCount == 1){echo ' reply';} else {echo ' replies';}; ?></a><?php } ?>
                </div>
                <div class="comment-actions-right">
                  <?php if ($comment->user_id == get_current_user_id()){ ?>
                    <a href="#" class="comment-save-button">Save</a>
                  <?php } ?>
                    <a href="#" class="comment-cancel-button">Cancel</a>
                  <?php if ($comment->user_id == get_current_user_id()){ ?>
                    <a href="#" class="comment-edit-button">Edit</a>
                  <?php } ?>
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

          <?php if ($subcomments){
            $subcomments = sort_comments_by_likes($subcomments); ?>
            <div class="comment-replies">
              <?php foreach ($subcomments as $subcomment){ 

                $commentId = $subcomment->comment_ID;
                $author = get_user_by('id', $subcomment->user_id);
                $postedDay = get_posted_day($commentId);
                $likeCount = get_comment_like_count($commentId);
                $likeId = has_current_user_liked_comment($commentId);
                $authorCommentsLikes = get_author_comments_likes_counts($author->ID);
                $authorComments = $authorCommentsLikes['comments'];
                $authorLikes = $authorCommentsLikes['likes'];
                $userStatus = is_user_logged_in_and_comment_author($author->ID);
                
                ?>
                <div class="subcomment comment-id" data-comment="<?php echo $subcomment->comment_ID; ?>">
                  <div class="subcomment-user">
                    <a href="<?php echo site_url('/user/'.$author->user_nicename) ?>"><h4 class="username"><?php echo $author->user_login ?></h4></a>
                    <a href="<?php echo site_url('/user/'.$author->user_nicename) ?>" class="comment-user-image"><img class="user-image" src="<?php echo get_user_meta($author->ID, 'user-image', true); ?>" alt=""></a>
                    <div class="user-comments-likes">
                      <div class="user-comments">
                        <span class="post-count"><?php echo shorthandNumber($authorComments); ?></span>
                        <?php include 'svg/64/comment.php'; ?>
                      </div>
                      <div class="user-likes">
                        <span class="user-likes-count"><?php echo shorthandNumber($authorLikes); ?></span>
                        <?php include 'svg/64/like.php'; ?>
                      </div>
                    </div>
                  </div>
                  <div class="subcomment-body">
                  
                    <div class="comment-time-like">
                      <span class="comment-timedate">Posted <span class="comment-date"><?php echo $postedDay; ?></span> at <span class="comment-time"><?php echo get_comment_date('H:i', $subcomment->comment_ID); ?></span></span>
                      <div class="comment-like" data-login="<?php echo $userStatus; ?>" <?php if ($comment->user_id != get_current_user_id()){ ?>data-like="<?php echo $likeId; ?>"<?php } ?>>
                        <span class="comment-like-count"><?php echo shorthandNumber($likeCount); ?></span>
                        <?php include 'svg/64/like.php'; ?>
                      </div>
                    </div>
                    <div class="comment-content">
                      <div class="comment-content-inner"><?php echo $subcomment->comment_content; ?></div>
                    </div>
                    <div class="comment-actions">
                      <?php if ($subcomment->user_id == get_current_user_id()){ ?>
                        <a href="#" class="comment-save-button">Save</a>
                      <?php } ?>
                        <a href="#" class="comment-cancel-button">Cancel</a>
                      <?php if ($subcomment->user_id == get_current_user_id()){ ?>
                        <a href="#" class="comment-edit-button">Edit</a>
                      <?php } ?>
                    </div>
                  
                  </div>
                </div>
              <?php } ?>
            </div>
          <?php } ?>

        </div>

      <?php } ?>

      </div>

      <?php if ($commentCount > $numberOfComments){ ?>
        <div id="load-more-wrapper">
          <button id="load-more-comments">Load<br>more</button>
        </div>
      <?php } ?>
 
    <?php } else { ?>
      <span class="no-comments">No comments posted yet.</span>
    <?php } ?>

</div>
