<?php function theHartAttackRegisterSearch() {
  register_rest_route('thehartattack/v1', 'search', array(
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'theHartAttackSearchResults'
  ));
}
add_action('rest_api_init', 'theHartAttackRegisterSearch');

function theHartAttackSearchResults($data) {

  $mainQuery = new WP_Query(array(
    'post_type' => array('post'),
    'posts_per_page' => '-1',
    's' => sanitize_text_field($data['term'])
  ));

  $results = array(
    'postsPages' => array()
  );

  while($mainQuery->have_posts()) {
    $mainQuery->the_post();

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
    $likeCount = $likeCount->found_posts;

    if (get_post_type() == 'post' OR get_post_type() == 'page') {
      array_push($results['postsPages'], array(
        'title' => get_the_title(),
        'subtitle' => get_field('subtitle'),
        'permalink' => get_the_permalink(),
        'postType' => get_post_type(),
        'postDate' => get_the_date(),
        'postCat' => get_the_category(),
        'postFeatImg' => get_the_post_thumbnail_url(),
        'commentCount' => get_comments_number(get_the_id()),
        'likeCount' => $likeCount
      ));
    }
  }

  return $results;
}
