<?php get_header(); ?>

	<div id="col-left">

		<?php
		while(have_posts()){
			the_post();

			$commentCount = get_comments_number(get_the_id());
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

			?>

			<a class="post" href="<?php the_permalink(); ?>">

				<?php include 'svg/post-likes.php'; ?>
				<div class="post-likes-count"><?php echo $likeCount->found_posts; ?></div>

				<?php include 'svg/post-comments.php'; ?>
				<div class="post-comments-count"><?php echo $commentCount; ?></div>

				<div class="post-feat-image" style="background-image: url(<?php echo get_the_post_thumbnail_url(); ?>);"></div>
				<div class="post-content">
					<h2 class="post-title"><?php echo get_the_title(); ?></h2>
					<span class="post-date"><?php echo get_the_date('jS F Y'); ?></span>
					<h3 class="post-subtitle"><?php echo get_field('subtitle') ?></h3>
					<span class="read-more">Read post <span>| &raquo;</span></span>
				</div>
			</a><?php
		} ?>

			<?php echo custom_pagination(); ?>

	</div>

	<?php include 'sidebar.php'; ?>

<?php get_footer(); ?>
