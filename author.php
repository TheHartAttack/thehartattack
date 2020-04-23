<?php get_header();

//Get user object
$user = get_queried_object();

//Get user comment count
$userCommentCount = get_comments(array(
    'user_id' => $user->ID,
    'status' => 'approve',
    'count' => true
));

//Get user like count
$userComments = get_comments(array(
    'user_id' => $user->ID
  ));
$commentIds = array();
foreach ($userComments as $userComment){
array_push($commentIds, $userComment->comment_ID);
}
$userLikedComments = new WP_Query(array(
'post_type' => 'comment-like',
'meta_query' => array(
    array(
    'key' => 'liked_comment_id',
    'compare' => 'IN',
    'value' => $commentIds
    )
)
));

//User bio
$userBio = get_user_meta($user->ID)['description'][0];

//Check if user's own page
if ($user->ID == get_current_user_id()){
    $ownPage = true;
} else {
    $ownPage = false;
}

//Get comments
$commentsNumber = 5;
$pageNumber = 1;
$comments = get_comments(array(
    'orderby' => 'post_date',
    'order' => 'DESC',
    'user_id' => $user->ID,
    'number' => $commentsNumber,
    'paged' => $pageNumber
));
if (ceil($userCommentCount/$commentsNumber) <= $pageNumber){
    $isLastPage = 1;
} else {
    $isLastPage = 0;
}

//Get user liked posts
$likedPostsNumber = 3;
$likedPostsPage = 1;
$likes = new WP_Query(array(
    'post_type' => 'like',
    'post_status' => 'publish',
    'author' => $user->ID,
    'posts_per_page' => $likedPostsNumber,
    'paged' => $likedPostsPage
));
if (ceil($likes->found_posts/$likedPostsNumber) <= $likedPostsPage){
    $likedPostsIsLastPage = 1;
} else {
    $likedPostsIsLastPage = 0;
}

$likedPostIds = array();
if ($likes->have_posts()) {
    while ($likes->have_posts()) {
        $likes->the_post();
        array_push($likedPostIds, get_field('liked_post_id', get_the_ID()));
    }
    $likedPosts = new WP_Query(array(
        'post__in' => $likedPostIds,
        'post_type' => 'post',
        'post_status' => 'publish',
        'order' => 'DESC',
        'orderby' => 'post_date'
    ));
}

?>

<div id="col-left">

    <div id="user-container">
        
        <div id="user-profile" data-id="<?php echo $user->ID; ?>">
            <div id="user-profile-left" class="user-profile-inner">
                <div id="user-image-container" <?php if ($ownPage){?>class="user-own-page"<?php }; ?>>
                    <img src="<?php echo get_user_meta($user->ID, 'user-image', true); ?>" alt="" id="user-profile-image">
                    <?php if ($ownPage){ ?>
                        <form enctype="multipart/form-data" id="user-image-upload-form">
                            <input type="file" name="user-image-upload" id="user-image-upload">
                            <?php wp_nonce_field('ajax-image-nonce', 'image-security'); ?>
                        </form>
                        <div id="user-image-upload-button"><span>Upload image</span></div>
                        <!--<div class="loader-spinner"></div>-->
                    <?php } ?>
                </div>
            </div>
            <div id="user-profile-center" class="user-profile-inner">
                <h1><?php echo $user->user_login; ?></h1>
                <div id="user-info">
                    <span id="user-reg-date"><strong>Registered:</strong> <?php echo date("j<\s\up>S</\s\up> F Y", strtotime($user->user_registered)); ?></span>
                    <div id="user-info-comments-likes">
                        <div id="user-comment-count"><?php include 'svg/64/comment.php'; ?><span><?php echo shorthandNumber($userCommentCount); ?></span></div>
                    <div id="user-like-count"><?php include 'svg/64/like.php'; ?><span><?php echo shorthandNumber($userLikedComments->found_posts); ?></span></div>
                    </div>
                    <?php print_r(get_user_meta($user->ID, 'user_activation_status', true)); ?>
                </div>
                <!--<a href="#" id="user-private-message">✉</a>-->
            </div>
            <div id="user-profile-right" class="user-profile-inner" data-edit="false">
                <div id="user-bio" <?php if ($ownPage){?>class="user-own-page"<?php }; ?>>
                    <div id="user-bio-text">
                        <?php if ($userBio != ""){echo $userBio;}
                        if (!$userBio && $ownPage){?><em>Click here to write your bio.</em><?php } ?>
                    </div>
                    <?php if ($ownPage){ ?>
                        <button id="user-bio-edit">✎</button>
                    <?php } ?> 
                </div>
                <?php if ($ownPage){ ?>
                    <form id="user-bio-form">
                        <textarea id="user-bio-textarea" maxlength="512"><?php echo $userBio; ?></textarea>
                        <button id="user-bio-cancel">✖</button>
                        <button id="user-bio-save">✔</button>
                        <?php wp_nonce_field('ajax-bio-nonce', 'bio-security'); ?>
                    </form>
                <?php } ?>
            </div>
        </div>

        <?php if ($ownPage){ ?>
            <div id="user-actions">
                <a href="" id="update-email-button">Update email</a>
                <a href="" id="change-password-button">Change password</a>
                <a href="" id="user-logout-button">Logout</a>
                <!--<a href="#" id="inbox">Inbox</a>-->
            </div>
        <?php } ?>

        <div id="user-comments" class="user-sections" data-current-page="<?php echo $pageNumber; ?>" data-last-page="<?php echo $isLastPage; ?>" data-number="<?php echo $commentsNumber; ?>">
            <h2>Recent Comments</h2>
            <?php if ($comments){ ?>

                <ul id="comments-ul">

                <?php foreach ($comments as $comment){

                    //Get comment like count
                    $commentLikeCount = new WP_Query(array(
                        'post_type' => 'comment-like',
                        'meta_query' => array(
                            array(
                            'key' => 'liked_comment_id',
                            'compare' => '=',
                            'value' => $comment->comment_ID
                            )
                        )
                    ));
                    
                    //Comment date config
                    $today = date('jS F Y');
                    $yesterday = date('jS F Y', time() - 86400);
                    $postedDay = get_comment_time('jS F Y');
                    if ($today == $postedDay){
                    $postedDay = 'today';
                    } else if ($yesterday == $postedDay){
                    $postedDay = 'yesterday';
                    }

                    //Parent comments
                    $parentCommentId = $comment->comment_parent;
                    $parentComment = get_comment($parentCommentId);
                    $parentPostedDay = get_comment_date('jS F Y', $parentCommentId);
                    if ($today == $parentPostedDay){
                    $parentPostedDay = 'today';
                    } else if ($yesterday == $parentPostedDay){
                    $parentPostedDay = 'yesterday';
                    }
                    
                    ?>

                    <li class="user-page-comment">
                        <div class="user-page-comment-header">
                            <span>Posted <?php echo $postedDay; ?> at <?php echo get_comment_time('H:i'); ?> in <a href="<?php echo get_the_permalink($comment->comment_post_ID); ?>"><?php echo get_the_title($comment->comment_post_ID); ?></a></span>
                            <div class="user-page-comment-likes">
                                <span><?php echo shorthandNumber($commentLikeCount->found_posts); ?></span>
                                <?php include 'svg/64/like.php'; ?>
                            </div>
                        </div>
                        <div class="user-page-comment-body">
                            <p><?php echo nl2br($comment->comment_content); ?></p>
                        </div>
                    </li>

                <?php } ?>

                </ul>

                <div id="comment-buttons" class="comments-posts-buttons">
                        <button id="load-newer-comments" class="load-newer">⮜</button>
                        <button id="load-older-comments" class="load-older">⮞</button>
                </div>

            <?php } else { ?>
                <span class="no-comments">No comments posted yet.</span>
            <?php }; ?>

        </div>
        
        <div id="user-liked-posts" class="user-sections" data-current-page="<?php echo $likedPostsPage; ?>" data-last-page="<?php echo $likedPostsIsLastPage; ?>" data-number="<?php echo $likedPostsNumber; ?>">
            <h2>Liked Posts</h2>
            <?php if ($likedPosts){ ?>

                <ul id="liked-posts-ul">

                <?php if ($likedPosts->have_posts()) {
                    while ($likedPosts->have_posts()) {
                        $likedPosts->the_post();
                        
                        //Get liked posts comments/likes counts
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
                        <li><a href="<?php echo get_the_permalink(); ?>" class="user-profile-liked-post">
                            <div class="user-profile-liked-post-image" style="background-image: url(<?php echo get_the_post_thumbnail_url(); ?>"></div>
                            <div class="user-profile-liked-post-inner">
                                <h3 class="post-title"><?php echo get_the_title(); ?></h3>
                                <span class="post-date"><?php echo get_the_date('jS F Y'); ?></span>
                                <span class="read-post">Read post <span>| &raquo;</span></span>
                            </div>
                            <?php include 'svg/64/comment.php'; ?>
                            <span class="user-profile-liked-post-comments"><?php echo shorthandNumber($commentCount); ?></span>
                            <?php include 'svg/64/like.php'; ?>
                            <span class="user-profile-liked-post-likes"><?php echo shorthandNumber($likeCount->found_posts); ?></span>
                        </a></li>
                    <?php } ?>

                    </ul>

                    <div id="liked-post-buttons" class="comments-posts-buttons">
                        <button id="load-newer-posts" class="load-newer">⮜</button>
                        <button id="load-older-posts" class="load-older">⮞</button>
                    </div>

                <?php } ?>

            <?php } else { ?>
                <span class="no-liked-posts">This user has not liked any posts yet.</span>
            <?php } ?>
        </div>

    </div>

</div>

<?php include 'sidebar.php';

get_footer(); 

if ($ownPage){ ?>
    <div id="update-email-overlay">
        <button id="update-email-close" class="overlay-close">✖</button>
        <form id="update-email">
            <h4>Update Email</h4>
            <input type="text" id="update-email-email" placeholder="New email">
            <input type="password" id="update-email-pw" placeholder="Password">
            <?php wp_nonce_field('ajax-update-email-nonce', 'update-email-security'); ?>
            <div><button id="update-email-submit"></button></div>
        </form>
    </div>
    <div id="change-password-overlay">
        <button id="change-password-close" class="overlay-close">✖</button>
        <form id="change-password">
            <h4>Change Password</h4>
            <input type="password" id="change-password-current" placeholder="Current password">
            <input type="password" id="change-password-new" placeholder="New password">
            <?php wp_nonce_field('ajax-change-password-nonce', 'change-password-security'); ?>
            <div><button id="change-password-submit"></button></div>
        </form>
    </div>
<?php } ?>