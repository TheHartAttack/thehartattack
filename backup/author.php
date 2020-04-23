<?php get_header(); ?>

<?php

    $user = get_queried_object();


?>

<div id="col-left">

    <div class="single-post-container">
        <h1><?php echo $user->user_nicename; ?></h1>
    </div>

</div>

<?php include 'sidebar.php';

get_footer(); ?>