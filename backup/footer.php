	</div>

	<?php
		include 'search.php';
		include 'contact.php';
		include 'user.php';
		$date = date('F');
		if ($date == 'December'){
			include 'svg/xmas-snow.php';
		}
	?>

	<footer>

		<svg id="footer-svg" viewBox="0 0 2208 88">
			<?php include 'svg/footer-hexes.php'; ?>
		</svg>

		<span id="footer-copy"><span>&copy;</span><span>Dan Hart</span><span><?php echo date("Y"); ?></span></span>

		<?php wp_footer(); ?>

	</footer>

</body>

</html>
