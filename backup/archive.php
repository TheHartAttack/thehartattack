<?php get_header(); ?>

	<div id="col-left">

		<?php $cat = get_queried_object(); ?>
		<div class="archive-title">
			<div class="archive-title-icon">
				<?php include 'svg/cat-'.$cat->slug.'.php'; ?>
			</div>
			<div class="archive-title-text">
					<h1><?php echo $cat->name ?></h1>
			</div>
			<div class="archive-title-icon">
				<?php include 'svg/cat-'.$cat->slug.'.php'; ?>
			</div>
		</div>

		<?php
			if (have_posts()){
				while(have_posts()){
				the_post();
				$commentCount = get_comments_number(get_the_id());
				?>
				<a class="post" href="<?php the_permalink(); ?>">
					<!--<svg class="post-date-svg" viewBox="0 0 96 96">
						<polygon id="XMLID_33_" class="post-date-hex" points="72,90 96,48 72,6 24,6 0,48 24,90 "/>
					</svg>
					<div class="post-date-text">
						<span class="post-date-day"><?php echo get_the_date('jS'); ?></span>
						<span class="post-date-month"><?php echo get_the_date('M'); ?></span>
					</div>-->

					<!--<?php $postcat = get_the_category();
						foreach ($postcat as $cat){
							if ($cat->name == 'Music'){
								include 'svg/cat-music.php';
							} else if ($cat->name == 'Gaming'){
								include 'svg/cat-gaming.php';
							} else if ($cat->name == 'TV/Film'){
								include 'svg/cat-tv-film.php';
							} else if ($cat->name == 'Other Stuff'){
								include 'svg/cat-other-stuff.php';
							}
						} ?>-->

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
			}
		} else { ?>
			<span class="no-posts">No posts found in this category.</span>
		<?php }
		?>

	</div>

	<?php include 'sidebar.php'; ?>

<?php get_footer(); ?>
