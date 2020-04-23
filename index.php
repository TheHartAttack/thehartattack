<?php get_header(); ?>

	<div id="col-left">

		<div id="posts">

			<?php

			if (have_posts()){
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

						<?php include 'svg/64/like.php'; ?>
						<div class="post-likes-count"><?php echo shorthandNumber($likeCount->found_posts); ?></div>

						<?php include 'svg/64/comment.php'; ?>
						<div class="post-comments-count"><?php echo shorthandNumber($commentCount); ?></div>

						<div class="post-feat-image" style="background-image: url(<?php echo get_the_post_thumbnail_url(); ?>);"></div>
						<div class="post-content">
							<h2 class="post-title"><?php echo get_the_title(); ?></h2>
							<span class="post-date"><?php echo get_the_date('jS F Y'); ?></span>
							<h3 class="post-subtitle"><?php echo get_field('subtitle') ?></h3>
							<span class="read-more">Read post <span>| &raquo;</span></span>
						</div>
					</a><?php
				}				

			} ?>

		</div>

		<?php 
		$postsPerPage = get_option('posts_per_page');
		global $wp;
		$currentPage = $wp->request;
		if ($currentPage != ''){
			$currentPagePattern = '/[^0-9]/';
			$currentPage = preg_replace($currentPagePattern, '', $currentPage);
		}
		if ($currentPage == ''){
			$currentPage = 1;
		}
		
		$totalPosts = new WP_Query(array(
			'post_type' => 'post',
			'status' => 'publish'
		));
		$totalPosts = $totalPosts->found_posts;
		$totalPages = ceil($totalPosts/$postsPerPage);
		if ($currentPage == $totalPages){
			$lastPage = 1;
		} else {
			$lastPage = 0;
		}
		if ($currentPage > 1){
			$prevMin = $currentPage - 1;
		} else {
			$prevMin = $currentPage;
		}
		if ($currentPage < $totalPages){
			$nextMax = $currentPage + 1;
		} else {
			$nextMax = $currentPage;
		}
		
		if ($totalPosts > $postsPerPage){ ?>

			<div id="posts-pagination" data-page="<?php echo $currentPage; ?>" data-last="<?php echo $lastPage; ?>">
				<a id="previous-posts" href="<?php echo site_url('/page/'.$prevMin); ?>">⮜</a>
				<input type="number" id="posts-page-number" min="1" max="<?php echo $totalPages; ?>" value="<?php echo $currentPage; ?>">
				<a id="next-posts" href="<?php echo site_url('/page/'.$nextMax); ?>">⮞</a>
			</div>

		<?php } ?>

	</div>

	<?php include 'sidebar.php'; ?>

<?php get_footer(); ?>
