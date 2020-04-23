<?php 

add_action("wp_ajax_load_posts", "handle_load_posts");
add_action("wp_ajax_nopriv_load_posts", "handle_load_posts");

function handle_load_posts(){
    $page = $_POST['currentPage'];
    $cat = $_POST['category'];
    $olderNewer = $_POST['olderNewer'];
    $pageInput = $_POST['pageInput'];
    if ($pageInput){
        $page = $pageInput;
    } else {
        $page += $olderNewer;
    }
    $totalPosts = new WP_Query(array(
        'post_type' => 'post',
        'status' => 'publish'
    ));
    $totalPosts = $totalPosts->found_posts;
    $postsPerPage = get_option('posts_per_page');
    $totalPages = ceil($totalPosts/$postsPerPage);
    if ($page >= $totalPages){
        $isLastPage = 1;
        $page = $totalPages;
    } else {
        $isLastPage = 0;
    }

    $data = array();

    $posts = new WP_Query(array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'category_name' => $cat,
        'posts_per_page' => get_option('posts_per_page'),
        'paged' => $page
    ));

    if ($posts->have_posts()){
        while ($posts->have_posts()){
            $posts->the_post();

            $postSubtitle = get_post_meta(get_the_id(), 'subtitle', true);
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

            $currentPost = array(
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(),
                'postTitle' => get_the_title(),
                'postSubtitle' => $postSubtitle,
                'postDate' => get_the_date('jS F Y'),
                'commentCount' => $commentCount,
                'likeCount' => $likeCount->found_posts
            );

            array_push($data, $currentPost);

        }
    }

    echo json_encode(array(
        'status' => 1,
        'data' => $data,
        'pageNumber' => $page,
        'isLastPage' => $isLastPage
    ));

    wp_die();

}