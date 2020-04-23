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
            include 'svg/cat-'.$cat->slug.'.php';
            ?></a><?php
          } ?>

        <a href="" class="post-comments-link">
          <?php include 'svg/single-post-comments.php'; ?>
  				<div class="post-comments-count"><?php echo $commentCount; ?></div>
        </a>

        <h2 class="single-post-title"><?php echo get_the_title(); ?></h2>
        <span class="single-post-date"><?php echo get_the_date('jS F Y'); ?></span>
        <h3 class="single-post-subtitle"><?php echo get_field('subtitle'); ?></h3>
        <!--<img class="single-post-feat-img" src="<?php echo the_post_thumbnail_url('banner'); ?>" alt="">-->
      </div>
    </div>

    <?php the_content(); ?>

    <div class="single-post-links-likes">

      <div class="single-post-share-links">

        <svg viewBox="0 0 96 96" class="share-icon">
          <path class="share-icon-icon" d="M60.8,53.5c-2.7,0-5.1,1.2-6.8,3l-13.6-6.4c0.1-0.4,0.1-0.8,0.1-1.2c0-0.6-0.1-1.1-0.2-1.7
  	l14-7.4c1.7,1.6,4,2.7,6.5,2.7c5.1,0,9.2-4.1,9.2-9.2c0-5.1-4.1-9.2-9.2-9.2s-9.2,4.1-9.2,9.2c0,0.6,0.1,1.1,0.2,1.7l-14,7.4
  	c-1.7-1.6-4-2.7-6.5-2.7c-5.1,0-9.2,4.1-9.2,9.2c0,5.1,4.1,9.2,9.2,9.2c2.7,0,5.1-1.2,6.8-3l13.6,6.4c-0.1,0.4-0.1,0.8-0.1,1.2
  	c0,5.1,4.1,9.2,9.2,9.2s9.2-4.1,9.2-9.2C70,57.7,65.9,53.5,60.8,53.5z"/>
        </svg>

        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_the_permalink(); ?>" class="share-icon-facebook" target="_blank" onClick="window.open(this.href,'targetWindow','toolbar=no,location=0,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=250'); return false;">
          <svg viewBox="0 0 96 96">
            <polygon class="share-icon-facebook-hex" points="72,90 96,48 72,6 24,6 0,48 24,90 "/>
            <path class="share-icon-facebook-icon" d="M53,72V51h6.5l1.3-9H53v-5.5c0-2.4,0.7-4.5,4.4-4.5H62v-7.5c0,0-3.9-0.6-7.2-0.6
    C48,23.9,43,28,43,35.5V42h-8v9h8v21H53z"/>
          </svg>
        </a>

        <a href="https://twitter.com/intent/tweet?text=<?php echo get_the_title(); ?>&url=<?php echo get_the_permalink(); ?>" class="share-icon-twitter" target="_blank" onClick="window.open(this.href,'targetWindow','toolbar=no,location=0,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=250'); return false;">
          <svg viewBox="0 0 96 96">
          <polygon class="share-icon-twitter-hex" points="72,90 96,48 72,6 24,6 0,48 24,90 "/>
          <path class="share-icon-twitter-icon" d="M71.9,33.1c-1.8,0.8-3.7,1.3-5.6,1.5c2-1.2,3.6-3.1,4.3-5.4c-1.9,1.1-4,1.9-6.3,2.4
          	c-1.8-1.9-4.3-3.1-7.2-3.1c-5.4,0-9.8,4.4-9.8,9.8c0,0.8,0.1,1.5,0.3,2.2c-8.2-0.4-15.4-4.3-20.3-10.3c-0.9,1.4-1.3,3.1-1.3,4.9
          	c0,3.4,1.7,6.4,4.4,8.2c-1.6-0.1-3.1-0.5-4.5-1.2v0.1c0,4.8,3.4,8.7,7.9,9.7c-0.8,0.2-1.7,0.3-2.6,0.3c-0.6,0-1.2-0.1-1.8-0.2
          	c1.3,3.9,4.9,6.8,9.2,6.8c-3.4,2.6-7.6,4.2-12.2,4.2c-0.8,0-1.6,0-2.3-0.1c4.4,2.8,9.5,4.4,15.1,4.4c18.1,0,28-15,28-28
          	c0-0.4,0-0.8,0-1.3C69,36.9,70.7,35.2,71.9,33.1C72,33.2,71.9,33.1,71.9,33.1z"/>
          </svg>
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

        <svg viewBox="0 0 96 96" class="single-post-likes-svg">
        <path d="M81.9,51.5L81.9,51.5c9.3-9.3,9.3-24.6,0-33.9l0,0c-9.3-9.3-24.6-9.3-33.9,0
        	c-9.3-9.3-24.6-9.3-33.9,0l0,0c-9.3,9.3-9.3,24.6,0,33.9h0l0,0L48,85.4L81.9,51.5L81.9,51.5z"/>
        </svg>

        <span class="single-post-likes-count"><?php echo $likeCount->found_posts; ?></span>
      </div>

    </div>

  </div>

  <?php comments_template(); ?>

</div>

<?php include 'sidebar.php';

}

get_footer();

?>
