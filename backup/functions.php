<?php

require get_theme_file_path('/inc/search-functions.php');
require get_theme_file_path('/inc/like-functions.php');
require get_theme_file_path('/inc/contact-functions.php');
require get_theme_file_path('/inc/user-functions.php');
require get_theme_file_path('/inc/comments-functions.php');
require get_theme_file_path('/inc/comment-like-functions.php');

/*Theme Files*/
  function tha_files() {
    wp_enqueue_script('tha_main_scripts', get_theme_file_uri('/js/scripts.js'), array('jquery'));
    wp_enqueue_script('tha_instafeed', get_theme_file_uri('/js/instafeed.min.js'), NULL, time());
    wp_enqueue_script('tha_mgGlitch', get_theme_file_uri('/js/mgGlitch.min.js'), NULL, time());
    //wp_enqueue_script('tha_glitch', get_theme_file_uri('/js/glitch.js'), NULL, time());
    wp_enqueue_style('tha_main_styles', get_stylesheet_uri());
    wp_localize_script('tha_main_scripts', 'theHartAttackData', array(
      'root_url' => get_site_url(),
      'nonce' => wp_create_nonce('wp_rest'),
      'ajaxurl' => admin_url('admin-ajax.php')
    ));
    $date = date('F');
    if ($date == 'December'){
      wp_enqueue_script('tha_particles', get_theme_file_uri('/js/particles.min.js'), NULL, time());
      wp_enqueue_script('tha_xmas', get_theme_file_uri('/js/xmas.js'), NULL, time());
    } else if ($date == 'October'){
      wp_enqueue_style('tha_halloween', get_theme_file_uri('/css/halloween.css'), NULL, time());
    }
  }
  add_action('wp_enqueue_scripts', 'tha_files');

/*Add Module Attribute To Script Tag*/
  add_filter( 'script_loader_tag', 'add_id_to_script', 10, 3 );
  function add_id_to_script( $tag, $handle, $src ) {
      if (strpos($src, 'js/scripts.js') !== false) {
          $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
      }

      return $tag;
  }

/*Theme Features*/
  function tha_features(){
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));
    add_image_size('banner', 1600, 450, true);
  }
  add_action('after_setup_theme', 'tha_features');

/*Automatically Resize Uploaded Images*/
  function image_crop_dimensions($default, $orig_w, $orig_h, $new_w, $new_h, $crop){
    if ( !$crop ) return null; // let the wordpress default function handle this
      $aspect_ratio = $orig_w / $orig_h;
      $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);
      $crop_w = round($new_w / $size_ratio);
      $crop_h = round($new_h / $size_ratio);
      $s_x = floor( ($orig_w - $crop_w) / 2 );
      $s_y = floor( ($orig_h - $crop_h) / 2 );
      return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
    }
  add_filter('image_resize_dimensions', 'image_crop_dimensions', 10, 6);

/*Add Custom Pagination*/
  function custom_pagination(){
    echo paginate_links(
      array(
        'type'=>'list',
        'prev_text'=> __('<svg viewBox="0 0 96 96" class="pagination-hex">
        <polygon id="XMLID_55_" class="st0" points="72,90 96,48 72,6 24,6 0,48 24,90 "/>
        </svg><span class="pagination-prevnext">&laquo;</span>'),
        'next_text'=> __('<svg viewBox="0 0 96 96" class="pagination-hex">
        <polygon id="XMLID_55_" class="st0" points="72,90 96,48 72,6 24,6 0,48 24,90 "/>
        </svg><span class="pagination-prevnext">&raquo;</span>'),
        'before_page_number' => '<svg viewBox="0 0 96 96" class="pagination-hex">
        <polygon id="XMLID_55_" class="st0" points="72,90 96,48 72,6 24,6 0,48 24,90 "/>
        </svg><span>',
        'after_page_number' => '</span>'
      )
    );
  };

/*Register Likes Post Type*/
  register_post_type('like', array(
    'supports' => array('title'),
    'public' => false,
    'show_ui' => true,
    'labels' => array(
      'name' => 'Likes',
      'add_new_item' => 'Add New Like',
      'edit_item' => 'Edit Like',
      'all_items' => 'All Likes',
      'singular_name' => 'Like'
      ),
    'menu_icon' => 'dashicons-heart'
  ));

  register_post_type('comment-like', array(
    'supports' => array('title'),
    'public' => false,
    'show_ui' => true,
    'labels' => array(
      'name' => 'Comment Likes',
      'add_new_item' => 'Add New Comment Like',
      'edit_item' => 'Edit Comment Like',
      'all_items' => 'All Comment Likes',
      'singular_name' => 'Comment Like'
      ),
    'menu_icon' => 'dashicons-heart'
  ));

/*Remove Admin Bar For Non-Admins*/
  function noSubsAdminBar() {
    $ourCurrentUser = wp_get_current_user();
    if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
      show_admin_bar(false);
    }
  }
  add_action('wp_loaded', 'noSubsAdminBar');

/*Changes author URL base*/
  function new_author_base() {
    global $wp_rewrite;
    $author_slug = 'user';
    $wp_rewrite->author_base = $author_slug;
  }
  add_action('init', 'new_author_base');

/*Prevent non-admins from accessing dashboard*/
function block_wp_admin() {
	if (is_admin() && !current_user_can('administrator') && !(defined('DOING_AJAX') && DOING_AJAX)) {
		wp_redirect(home_url());
		exit;
	}
}
add_action( 'admin_init', 'block_wp_admin' );
