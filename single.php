<?php

get_header();

while(have_posts()) {
  the_post();

  $commentCount = get_comments_number(get_the_id());

?>

<div id="col-left">
  <div class="single-post-container">
    <div class="single-post-title-section" style="background-image: url('<?php echo the_post_thumbnail_url(); ?>');">
      <div class="single-post-title-section-inner">
        <?php $postcat = get_the_category();
          foreach ($postcat as $cat){
            ?><a class="post-cat-link" href="<?php echo site_url('/category/'.$cat->slug); ?>"><?php
            include 'svg/64/'.$cat->slug.'.php';
            ?></a><?php
          } ?>

        <a href="" class="post-comments-link">
          <?php include 'svg/64/comment.php'; ?>
  				<div class="post-comments-count"><?php echo shorthandNumber($commentCount); ?></div>
        </a>

        <h2 class="single-post-title"><?php echo get_the_title(); ?></h2>
        <span class="single-post-date"><?php echo get_the_date('jS F Y'); ?></span>
        <h3 class="single-post-subtitle"><?php echo get_field('subtitle'); ?></h3>
      </div>
    </div>

    <?php the_content(); ?>

    <div class="single-post-links-likes">

      <div class="single-post-share-links">

        <?php include 'svg/64/share.php'; ?>

        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_the_permalink(); ?>" class="share-icon-facebook" target="_blank" onClick="window.open(this.href,'targetWindow','toolbar=no,location=0,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=250'); return false;">
          <?php include 'svg/64/facebook.php'; ?>
        </a>

        <a href="https://twitter.com/intent/tweet?text=<?php echo get_the_title(); ?>&url=<?php echo get_the_permalink(); ?>" class="share-icon-twitter" target="_blank" onClick="window.open(this.href,'targetWindow','toolbar=no,location=0,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=250'); return false;">
          <?php include 'svg/64/twitter.php'; ?>
        </a>
      </div>

      <?php

        $likeCount = new WP_Query(array(
          'post_type' => 'like',
          'meta_query' => array(
            array(
              'key' => 'liked_post_id',
              'compare' => '=',
              'value' => get_the_id()
            )
          )
        ));

        $userLikeStatus = 'false';

        if(is_user_logged_in()){
          $userLikeQuery = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => array(
              array(
                'key' => 'liked_post_id',
                'compare' => '=',
                'value' => get_the_id()
              )
            )
          ));
        }

        if ($userLikeQuery->found_posts){
          $userLikeStatus = 'true';
        }

        $userLoginStatus = 'false';

        if(is_user_logged_in()){
          $userLoginStatus = 'true';
        }

      ?>

      <div class="single-post-likes" data-status="<?php echo $userLikeStatus; ?>" data-user-login="<?php echo $userLoginStatus; ?>" data-post="<?php echo get_the_id(); ?>" data-like="<?php echo $userLikeQuery->posts[0]->ID; ?>">
        <span class="single-post-likes-count"><?php echo shorthandNumber($likeCount->found_posts); ?></span>
        <?php include 'svg/64/like.php'; ?>
      </div>

    </div>

  </div>

  <?php comments_template(); ?>

</div>

<?php include 'sidebar.php';

}

get_footer();

?>
