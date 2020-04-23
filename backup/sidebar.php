<div id="col-right">

  <div id="profile">
    <div id="profile-image-text">
      <!--<img src="<?php echo get_theme_file_uri('/img/dan.jpg');?>">-->
      <div class="glitch-container">
        <div class="glitch-img" style="background-image: url('<?php echo get_theme_file_uri('/img/dan.jpg');?>');"></div>
      </div>
      <p><?php
        $dan = get_user_meta('1');
        echo $dan['description'][0];
      ?></p>
    </div>

    <svg id="social-links" viewBox="0 0 598 126">
      <a href="https://facebook.com/thehartattack">
        <?php include 'svg/social-facebook.php'; ?>
      </a>
      <a href="https://instagram.com/danhart1990/">
        <?php include 'svg/social-instagram.php'; ?>
      </a>
      <a href="https://twitter.com/thehartattack">
        <?php include 'svg/social-twitter.php'; ?>
      </a>
      <a href="https://youtube.com/channel/UCzfKsgVN42euEo4t3YoLGtQ">
        <?php include 'svg/social-youtube.php'; ?>
      </a>
      <a href="https://last.fm/user/thehartattack">
        <?php include 'svg/social-lastfm.php'; ?>
      </a>
      <a href="https://my.playstation.com/profile/TheHartAttack">
        <?php include 'svg/social-playstation.php'; ?>
      </a>
      <a href="https://steampowered.com/id/danhart1990">
        <?php include 'svg/social-steam.php'; ?>
      </a>
      <a href="https://linkedin.com/in/danielrussellhart">
        <?php include 'svg/social-linkedin.php'; ?>
      </a>
    </svg>

  </div>

  <?php
    $date = date('F');
    if ($date != 'December' AND $date != 'October'){
      include 'svg/crystal-shelf.php';
      include 'svg/incense-burner.php';
    } else if ($date == 'October'){
      include 'svg/halloween-moon.php';
      include 'svg/halloween-tree.php';
      include 'svg/halloween-tombstone.php';
    }
  ?>

  <!--<div id="instafeed"></div>-->

  <!--<a href="#" id="work-with-me">
    <h2>Looking for a
      <span>web developer?</span>
    </h2>
    <p>Check out my portfolio and CV.</p>
  </a>-->

</div>
