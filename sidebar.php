<?php $dan = get_user_meta('1'); ?>

<div id="col-right">

  <div id="profile">
    <div id="profile-image-text" <?php if ($dan['description'][0] == ''){ ?>class="no-margin" <?php } ?>>
      <div class="glitch-container">
        <div class="glitch-img" style="background-image: url('<?php echo get_user_meta(1, 'user-image', true);?>');"></div>
      </div>
      <?php if ($dan['description'][0] != ''){ ?>
      <p><?php echo nl2br($dan['description'][0]); ?></p><?php } ?>
    </div>

    <div id="social-links">
      <a href="https://facebook.com/thehartattack">
        <?php include 'svg/64/facebook.php'; ?>
      </a>
      <a href="https://instagram.com/danhart1990/">
        <?php include 'svg/64/instagram.php'; ?>
      </a>
      <a href="https://twitter.com/thehartattack">
        <?php include 'svg/64/twitter.php'; ?>
      </a>
      <a href="https://youtube.com/channel/UCzfKsgVN42euEo4t3YoLGtQ">
        <?php include 'svg/64/youtube.php'; ?>
      </a>
      <a href="https://last.fm/user/thehartattack">
        <?php include 'svg/64/lastfm.php'; ?>
      </a>
      <a href="https://my.playstation.com/profile/TheHartAttack">
        <?php include 'svg/64/playstation.php'; ?>
      </a>
      <a href="https://steampowered.com/id/danhart1990">
        <?php include 'svg/64/steam.php'; ?>
      </a>
      <a href="https://linkedin.com/in/danielrussellhart">
        <?php include 'svg/64/linkedin.php'; ?>
      </a>
    </div>

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
  
</div>
