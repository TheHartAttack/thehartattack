<div id="single-post-comments" data-post="<?php echo get_the_ID(); ?>">

  <h3>Comments</h3>

  <div id="comments">

    <?php

    define('DEFAULT_COMMENTS_PER_PAGE', 25);
    $id = get_the_ID();
    $page = (get_query_var('page')) ? get_query_var('page') : 1;
    $limit = DEFAULT_COMMENTS_PER_PAGE;
    $offset = ($page * $limit) - $limit;
    $param = array(
      'status' => 'approve',
      'offset' => $offset,
      'post_id' => $id,
      'number' => $limit
    );
    $total_comments = get_comments(array(
      'orderby' => 'post_date',
      'order' => 'DESC',
      'post_id' => $id,
      'status' => 'approve',
      'parent'=> 0
    ));
    $pages = ceil(count($total_comments)/DEFAULT_COMMENTS_PER_PAGE);
    $comments = get_comments($param);

      if ($comments){
        foreach ($comments as $comment){

          $author = get_user_by('id', $comment->user_id);
          $authorCommentsArgs = array (
              'user_id' => $author->ID,
              'count' => true
          );
          $authorComments = get_comments($authorCommentsArgs);
          $postText = ' post';
          if ($authorComments > 1){
            $postText = ' posts';
          }

          $today = date('jS F Y');
          $yesterday = date('jS F Y', time() - 86400);
          $postedDay = get_comment_time('jS F Y');
          if ($today == $postedDay){
            $postedDay = 'today';
          } else if ($yesterday == $postedDay){
            $postedDay = 'yesterday';
          }

          $likeCount = new WP_Query(array(
            'post_type' => 'comment-like',
            'meta_query' => array(
              array(
                'key' => 'liked_comment_id',
                'compare' => '=',
                'value' => $comment->comment_ID
              )
            )
          ));

          $userLikeStatus = 'false';
          if(is_user_logged_in()){
            $userLikeQuery = new WP_Query(array(
              'author' => get_current_user_id(),
              'post_type' => 'comment-like',
              'meta_query' => array(
                array(
                  'key' => 'liked_comment_id',
                  'compare' => '=',
                  'value' => $comment->comment_ID
                )
              )
            ));
          }
          if ($userLikeQuery->found_posts){
            $userLikeStatus = 'true';
          }

          $userComments = get_comments(array(
            'user_id' => $author->ID
          ));

          $commentIds = array();
          foreach ($userComments as $userComment){
            array_push($commentIds, $userComment->comment_ID);
          }

          $userLikedComments = new WP_Query(array(
            'post_type' => 'comment-like',
            'meta_query' => array(
              array(
                'key' => 'liked_comment_id',
                'compare' => 'IN',
                'value' => $commentIds
              )
            )
          ));

          $userLoginStatus = 'false';
          if (is_user_logged_in() AND get_current_user_id() != $author->ID){
            $userLoginStatus = 'true';
          } else if (get_current_user_id() == $author->ID){
            $userLoginStatus = 'current';
          }

          $parentCommentId = $comment->comment_parent;
          $parentComment = get_comment($parentCommentId);
          $parentPostedDay = get_comment_date('jS F Y', $parentCommentId);
          if ($today == $parentPostedDay){
            $parentPostedDay = 'today';
          } else if ($yesterday == $parentPostedDay){
            $parentPostedDay = 'yesterday';
          }

          ?>

          <div class="comment" data-comment="<?php echo $comment->comment_ID; ?>">
            <div class="comment-user">
              <h4 class="username"><?php echo $comment->comment_author ?></h4>
              <img class="user-image" src="<?php echo get_user_meta($author->ID, 'user-image', true); ?>" alt="">
              <span class="post-count"><?php echo $authorComments . $postText; ?></span>
              <div class="user-likes">
                <svg viewBox="0 0 96 96" class="user-likes-svg">
                  <path d="M81.9,51.5L81.9,51.5c9.3-9.3,9.3-24.6,0-33.9l0,0c-9.3-9.3-24.6-9.3-33.9,0
                	c-9.3-9.3-24.6-9.3-33.9,0l0,0c-9.3,9.3-9.3,24.6,0,33.9h0l0,0L48,85.4L81.9,51.5L81.9,51.5z"/>
                </svg>
                <span class="user-likes-count"><?php echo $userLikedComments->found_posts; ?></span>
              </div>
            </div>
            <div class="comment-body">
              <div class="comment-time-edit">
                <span class="comment-timedate">Posted <span class="comment-date"><?php echo $postedDay; ?></span> at <span class="comment-time"><?php echo get_comment_time('H:i'); ?></span></span>
                <?php if ($comment->user_id == get_current_user_id()){ ?>
                  <div class="comment-edit-container">
                    <div class="comment-edit">✎</div>
                    <div class="comment-save">✔</div>
                    <div class="comment-cancel">✖</div>
                  </div>
                <?php } ?>
              </div>
              <div class="comment-content" <?php if ($comment->user_id == get_current_user_id()){ ?>data-edit="false"<?php } ?>>
                <?php if ($comment->comment_parent){ ?>
                  <blockquote class="quoted-comment-block" data-parent="<?php echo $comment->comment_parent; ?>">
                    <span class="quoted-author"><?php echo $parentComment->comment_author ?> posted <?php if ($parentPostedDay != 'today' AND $parentPostedDay != 'yesterday'){echo 'on ';} echo $parentPostedDay; ?> at <?php echo get_comment_date('H:i', $parentCommentId); ?>:</span>
                    <span class="quoted-comment"><?php echo $parentComment->comment_content; ?></span>
                  </blockquote>
                <?php } ?>
                <div class="comment-content-inner"><?php echo $comment->comment_content; ?></div>
              </div>
              <div class="comment-quote-like">
                <?php if (is_user_logged_in()){ ?>
                <svg viewBox="0 0 96 96" class="comment-quote">
                  <g id="XMLID_2_">
                  	<path id="XMLID_4_" d="M24.5,35.8c0-5.5,2.1-10.9,6.3-15.1c1.2-1.2,2.5-2.2,3.8-3c-9-0.7-18.3,2.4-25.2,9.3
                  		c-12.3,12.3-12.5,32-0.7,44.5c3.9,4.3,9.5,7,15.8,7c11.8,0,21.3-9.6,21.3-21.3C45.8,45.3,36.3,35.8,24.5,35.8z"/>
                  	<path id="XMLID_1_" d="M74.7,35.8c0-5.5,2.1-10.9,6.3-15.1c1.2-1.2,2.5-2.2,3.8-3c-9-0.7-18.3,2.4-25.2,9.3
                  		c-12.3,12.3-12.5,32-0.7,44.5c3.9,4.3,9.5,7,15.8,7c11.8,0,21.3-9.6,21.3-21.3C96,45.3,86.4,35.8,74.7,35.8z"/>
                  </g>
                </svg>
              <?php } ?>
                <div class="comment-like" data-status="<?php echo $userLikeStatus; ?>" data-login="<?php echo $userLoginStatus; ?>" data-like="<?php echo $userLikeQuery->posts[0]->ID; ?>">
                  <span class="comment-like-count"><?php echo $likeCount->found_posts; ?></span>
                  <?php if ($userLoginStatus != 'current'){?>
                    <svg viewBox="0 0 96 96" class="comment-like-svg">
                      <path d="M81.9,51.5L81.9,51.5c9.3-9.3,9.3-24.6,0-33.9l0,0c-9.3-9.3-24.6-9.3-33.9,0
                  	c-9.3-9.3-24.6-9.3-33.9,0l0,0c-9.3,9.3-9.3,24.6,0,33.9h0l0,0L48,85.4L81.9,51.5L81.9,51.5z"/>
                    </svg>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>

        <?php }

        $paginationArgs = array(
          'base'         => @add_query_arg('page','%#%'),
          'format'       => '?page=%#%',
          'total'        => $pages,
          'current'      => $page,
          'show_all'     => false,
          'end_size'     => 1,
          'mid_size'     => 2,
          'prev_next'    => true,
          'prev_text'    => __('<svg viewBox="0 0 96 96" class="comment-pagination-hex">
          <polygon id="XMLID_55_" class="st0" points="72,90 96,48 72,6 24,6 0,48 24,90 "/>
          </svg><span class="pagination-prevnext">&laquo;</span>'),
          'next_text'    => __('<svg viewBox="0 0 96 96" class="comment-pagination-hex">
          <polygon id="XMLID_55_" class="st0" points="72,90 96,48 72,6 24,6 0,48 24,90 "/>
          </svg><span class="pagination-prevnext">&raquo;</span>'),
          'before_page_number' => '<svg viewBox="0 0 96 96" class="comment-pagination-hex">
          <polygon id="XMLID_55_" class="st0" points="72,90 96,48 72,6 24,6 0,48 24,90 "/>
          </svg><span>',
          'after_page_number' => '</span>',
          'type'         => 'list'
        );
        echo paginate_links($paginationArgs);

      } else { ?>
        <span class="no-comments">No comments posted yet.</span>
      <?php } ?>

  </div>

  <?php
  if (is_user_logged_in()){ ?>
    <form id="comments-form">
      <textarea id="comment-input" placeholder="Leave a comment..."></textarea>
      <div id="post-comment">
        <svg viewBox="0 0 96 96">
          <polygon id="XMLID_55_" class="post-comment-hex" points="72,90 96,48 72,6 24,6 0,48 24,90 	"/>
          <span>Post</span>
        </svg>
      </div>
    </form>
  <?php } ?>

</div>
