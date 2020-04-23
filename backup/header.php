<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
  </head>

	<body id="particles-js">

	<header id="header">

		<svg id="header-svg" viewBox="0 0 2208 338">
			<?php
      $date = date('F');
			include 'svg/header-hexes.php';
			include 'svg/header-tha-logo.php';
      if ($date == 'October'){
        include 'svg/halloween-header.php';
      } else if ($date == 'December'){
        include 'svg/xmas-lights.php';
        include 'svg/xmas-tree.php';
      } else {
        include 'svg/header-tha-icons.php';
      }
      include 'svg/header-hex-buttons.php';
			?>

	</svg>

	</header>

  <div id="main-container">
