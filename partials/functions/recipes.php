<?php

// create custom post type for recipes
add_action( 'init', 'init_recipes' );
function init_recipes() {
    $recipe_type_labels = array(
        'name' => _x('Recipes', 'post type general name'),
        'singular_name' => _x('Recipe', 'post type singular name'),
        'add_new' => _x('Add New Recipe', 'video'),
        'add_new_item' => __('Add New Recipe'),
        'edit_item' => __('Edit Recipe'),
        'new_item' => __('Add New Recipe'),
        'all_items' => __('View Recipes'),
        'view_item' => __('View Recipe'),
        'search_items' => __('Search Recipes'),
        'not_found' =>  __('No Recipes found'),
        'not_found_in_trash' => __('No Recipes found in Trash'), 
        'parent_item_colon' => '',
        'menu_name' => 'Recipes'
    );
    $recipe_type_args = array(
        'labels' => $recipe_type_labels,
        'public' => true,
        'publicly_queryable' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'recipes' ),
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true, 
        'hierarchical' => true,
        'map_meta_cap' => true,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'thumbnail'),
        'taxonomies' => array('category')
    );
    register_post_type('recipes', $recipe_type_args);
}
// add custom taxonomy for ingredients
add_action( 'init', 'init_ingredients' );
function init_ingredients() {
    $labels = array(
        'name' => _x( 'Ingredients', 'taxonomy general name' ),
        'singular_name' => _x( 'Ingredient', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Ingredients' ),
        'all_items' => __( 'All Ingredients' ),
        'parent_item' => __( 'Parent Ingredient' ),
        'parent_item_colon' => __( 'Parent Ingredient:' ),
        'edit_item' => __( 'Edit Ingredient' ), 
        'update_item' => __( 'Update Ingredient' ),
        'add_new_item' => __( 'Add New Ingredient' ),
        'new_item_name' => __( 'New Ingredient Name' ),
        'menu_name' => __( 'Ingredients' ),
    );
    register_taxonomy(
        'ingredients',
        'recipes',
        array(
            'labels' => $labels,
            'rewrite' => array( 'slug' => 'ingredients' ),
            'public' => false
        )
    );
}
// MailChimp call
function ttp_mailchimp_curl_connect($url, $request_type, $api_key, $data = array()) {
    if( $request_type == 'GET' ) {
        $url .= '?' . http_build_query($data);
    }
 
    $mch = curl_init();
    $headers = array(
        'Content-Type: application/json',
        'Authorization: Basic '.base64_encode( 'user:'. $api_key )
    );
    curl_setopt($mch, CURLOPT_URL, $url );
    curl_setopt($mch, CURLOPT_HTTPHEADER, $headers);
    //curl_setopt($mch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
    curl_setopt($mch, CURLOPT_RETURNTRANSFER, true); // do not echo the result, write it into variable
    curl_setopt($mch, CURLOPT_CUSTOMREQUEST, $request_type); // according to MailChimp API: POST/GET/PATCH/PUT/DELETE
    curl_setopt($mch, CURLOPT_TIMEOUT, 10);
    curl_setopt($mch, CURLOPT_SSL_VERIFYPEER, false); // certificate verification for TLS/SSL connection
 
    if( $request_type != 'GET' ) {
        curl_setopt($mch, CURLOPT_POST, true);
        curl_setopt($mch, CURLOPT_POSTFIELDS, json_encode($data) ); // send data in json
    }

    return curl_exec($mch);
}
// sync submissions to MailChimp list
add_action('wp_ajax_mailchimpSubscribe', 'mailchimpSubscribe');
add_action('wp_ajax_nopriv_mailchimpSubscribe', 'mailchimpSubscribe');
function mailchimpSubscribe() {

    check_ajax_referer('subscribe_ajax_nonce','security');

    $userIP = (isset($_POST['userIP'])) ? $_POST['userIP'] : 0;
    $firstname = (isset($_POST['fname'])) ? $_POST['fname'] : 0;
    $lastname = (isset($_POST['lname'])) ? $_POST['lname'] : 0;
    $emailaddress = (isset($_POST['email'])) ? $_POST['email'] : 0;
    
    // get ping info
    $location = json_decode(file_get_contents('http://freegeoip.net/json/'.$userIP));
    $ipAddress = $location->ip;
    $city = $location->city;
    $state = $location->region_code;
    $country = $location->country_name;
    $countryCode = $location->country_code;
    $zip = $location->zip_code;
    $lat = $location->latitude;
    $lng = $location->longitude;
    $timezone = $location->time_zone;

    // MailChimp Data
    require('MailChimpConfig.php');
    $list = (isset($_POST['list'])) ? $_POST['list'] : 0;
    $data = array(
        'apikey'        => $key,
        'email_address' => $emailaddress,
        'email_type'    => "html",
        'status'        => 'subscribed',
        'merge_fields'  => array(
            'FNAME' => $firstname,
            'LNAME' => $lastname
        ),
        'ip_signup' => $ipAddress,
        'timestamp_signup' => date("D M d, Y G:i"),
        'location' => array(
            'latitude' => $lat,
            'longitude' => $lng,
            'gmtoff' => 0,
            'dstoff' => 0,
            'country_code' => $countryCode,
            'timezone' => $timezone
        )
    );
    // MailChimp API URL
    $url = 'https://'.substr($key, strpos($key,'-')+1).'.api.mailchimp.com/3.0/lists/'.$list.'/members/';
    // Send data to MailChimp
    $success = json_decode( ttp_mailchimp_curl_connect($url, 'POST', $key, $data) );

    if($success) {
        echo "success";
    } else {
        echo "error";
    }

    exit();

}
// Add custom meta boxes to display recipe specs
add_action( 'add_meta_boxes', 'recipe_meta_box', 1 );
function recipe_meta_box( $post ) {
    add_meta_box(
        'ingredient-listing', 
        'Ingredients', 
        'recipe_ingredients',
        'recipes', 
        'normal', 
        'low'
    );
    add_meta_box(
        'instruction-listing', 
        'Instructions', 
        'recipe_instructions',
        'recipes', 
        'normal', 
        'low'
    );
    add_meta_box(
        'recipe-images', 
        'Images', 
        'recipe_images',
        'recipes', 
        'side', 
        'low'
    );
    add_meta_box(
        'recipe-rating', 
        'Rating', 
        'recipe_rating',
        'recipes', 
        'side', 
        'high'
    );
}
function recipe_ingredients() { 
    global $post;
    $ingredients = get_post_meta($post->ID,'ingredients', true);
    if(is_array($ingredients) ) {
        $count = count($ingredients);
    } else {
        $count = 0;
    }
    echo '<section id="ingredientListing" class="sortable" data-post="'.$post->ID.'" data-type="ingredients">';
        if ( !empty($ingredients) && is_array($ingredients) ) {
            foreach( $ingredients as $key => $ingredient ) {
                echo '<article class="ingredient" data-order="'.$key.'">';
                    echo '<div class="move"><i class="fa fa-bars"></i></div>';
                    echo '<input type="text" class="measurement" name="ingredients['.$key.'][measure]" value="'.$ingredient["measure"].'" placeholder="measurement" />';
                    echo '<span>-</span>';
                    echo '<input type="text" class="ingredientTitle" name="ingredients['.$key.'][ingredient_title]" value="'.$ingredient["ingredient_title"].'" placeholder="ingredient" />';
                    echo '<div class="remove" data-key="'.$key.'"><i class="fa fa-close"></i></div>';
                echo '</article>';
            }
        }
    echo '</section>';
    echo '<span class="button button-primary button-large btn-ingredient" data-count="'.$count.'">Add Ingredient</span>';
}
function recipe_instructions() { 
    global $post;
    $instructions = get_post_meta($post->ID,'instructions', true);
    echo '<section id="instructionListing" class="sortable" data-post="'.$post->ID.'" data-type="instructions">';
    if ( is_array($instructions) && !empty($instructions) ) {
        foreach( $instructions as $key => $instruction ) {
            echo '<article class="instruction" data-order="'.$instruction.'">';
                echo '<div class="move"><i class="fa fa-bars"></i></div>';
                echo '<input type="text" name="instructions[]" value="'.$instruction.'" placeholder="cooking step" />';
                echo '<div class="remove" data-key="'.$key.'"><i class="fa fa-close"></i></div>';
            echo '</article>';
        }
    }
    echo '</section>';
    echo '<span class="button button-primary button-large btn-instruction">Add Instruction</span>';
}
function recipe_images() {
    global $post;
    $photos = get_post_meta($post->ID,'recipe_images', true);
    echo '<div data-post="'.$post->ID.'" data-type="recipe_images">';
        echo '<p>Use the button below to upload your recipe photos. After upload, you can drag and drop the photos to change the display order.</p>';
        if ( !empty($photos) ) {
            echo '<section class="photoWrap sortable" data-post="'.$post->ID.'" data-type="recipe_images">';
                foreach( $photos as $key => $photo ) {
                    echo '<article class="photo ui-state-default" data-order="'.$photo.'">';
                        echo '<img src="'.$photo.'" alt="" />';
                        echo '<div class="remove" data-key="'.$key.'"><i class="fa fa-close"></i></div>';
                    echo '</article>';
                }
            echo '</section>';
        } else {
            echo '<section class="photoWrap sortable" data-post="'.$post->ID.'" data-type="recipe_images"></section>';
        }
        echo '<a href="#" class="button upload-image btn-photo">Add Recipe Image</a>';
    echo '</div>';
}
function recipe_rating() {
    global $post;
    $ratings = get_post_meta($post->ID,'recipe_rating',true);
    if(!empty($ratings)) {
        $averageRating = array_sum($ratings) / count($ratings);
    } else {
        $averageRating = 0;
    }
    echo '<article id="starRating" data-post="'.$post->ID.'">';
        echo '<i class="fa fa-star" data-star="1"></i>';
        echo '<i class="fa fa-star" data-star="2"></i>';
        echo '<i class="fa fa-star" data-star="3"></i>';
        echo '<i class="fa fa-star" data-star="4"></i>';
        echo '<i class="fa fa-star" data-star="5"></i>';
    echo '</article>';
    echo '<input type="hidden" name="recipe_rating[]" id="recipe_rating" value="'.round($averageRating, 0, PHP_ROUND_HALF_UP).'" />';
}
// ajax response to save recipe image
add_action('wp_ajax_setImage', 'setImage');
add_action('wp_ajax_nopriv_setImage', 'setImage');
function setImage() {
    // get response variables
    $postID = (isset($_GET['postID'])) ? $_GET['postID'] : 0;
    $imageURL = (isset($_GET['fieldVal'])) ? $_GET['fieldVal'] : 0;
    // get saved photos
    $photos = get_post_meta($postID, 'recipe_images', true);
    // save photos
    if(!empty($imageURL)) {
        if(!empty($photos)) {
            $image[] = $imageURL;
            $photos = array_merge($photos, $image);
            update_post_meta( $postID, 'recipe_images', $photos);
        } else {
            $new[] = $imageURL;
            update_post_meta( $postID, 'recipe_images', $new);
        } 
    }
    exit();
}
// ajax response to set order
add_action('wp_ajax_setOrder', 'setOrder');
add_action('wp_ajax_nopriv_setOrder', 'setOrder');
function setOrder() {
    $order = str_replace( array( '[', ']','"' ),'', $_GET['order'] );
    $postID = (isset($_GET['postID'])) ? $_GET['postID'] : 0;
    $type = (isset($_GET['type'])) ? $_GET['type'] : 0;
    if($type == "ingredients") {
        $ingredients = get_post_meta($postID,'ingredients', true);
        $reordered = array();
        foreach($order as $item) {
            $reordered[$item] = $ingredients[$item];
        }
        update_post_meta($postID, $type, $reordered);
    } else {
       update_post_meta($postID, $type, $order); 
    }
    exit();
}
// ajax response to save download track
add_action('wp_ajax_removeItem', 'removeItem');
add_action('wp_ajax_nopriv_removeItem', 'removeItem');
function removeItem() {
    $postID = (isset($_GET['postID'])) ? $_GET['postID'] : 0;
    $key = (isset($_GET['key'])) ? $_GET['key'] : 0;
    $type = (isset($_GET['type'])) ? $_GET['type'] : 0;

    if(isset($key)) {
        $array = get_post_meta($postID, $type, true );
        if(count($array) > 1) {
            unset($array[$key]);
            update_post_meta($postID, $type, $array);
        } else {
            update_post_meta($postID, $type, "");
        }
    }
    exit();
}
// ajax response to save download track
add_action('wp_ajax_loadListing', 'loadListing');
add_action('wp_ajax_nopriv_loadListing', 'loadListing');
function loadListing() {

    check_ajax_referer('listing_ajax_nonce','security');
    
    $categories = (isset($_POST['categories'])) ? $_POST['categories'] : "";
    $catArray = explode( ',', $categories );
    $ingredients = (isset($_POST['ingredients'])) ? $_POST['ingredients'] : "";
    $tagArray = explode( ',', $ingredients );
    $pageNumber = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 0;
    $trigger = (isset($_POST['trigger'])) ? $_POST['trigger'] : 0;
    $count = (isset($_POST['count'])) ? $_POST['count'] : 1;

    if(empty(array_filter($catArray)) && empty(array_filter($tagArray)) ) {
        $args = array(
            'paged'         => $pageNumber,
            'posts_per_page'=> get_option('posts_per_page'),
            'post_type'     => 'recipes',
            'post_status'   => 'publish',
            'orderby'       => 'date',
            'order'         => 'DESC'
        );
    } elseif(empty(array_filter($tagArray)) && !empty(array_filter($catArray)) ) {
        $args = array(
            'paged'         => $pageNumber,
            'posts_per_page'=> get_option('posts_per_page'),
            'post_type'     => 'recipes',
            'post_status'   => 'publish',
            'orderby'       => 'date',
            'order'         => 'DESC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'id',
                    'terms' => $catArray,
                    'operator' => 'IN'
                )
            )
        );
    } elseif(empty(array_filter($catArray)) && !empty(array_filter($tagArray)) ) {
        $args = array(
            'paged'         => $pageNumber,
            'posts_per_page'=> get_option('posts_per_page'),
            'post_type'     => 'recipes',
            'post_status'   => 'publish',
            'orderby'       => 'date',
            'order'         => 'DESC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'ingredients',
                    'field' => 'id',
                    'terms' => $tagArray,
                    'operator' => 'IN'
                )
            )
        );
    } else {
        $args = array(
            'paged'         => $pageNumber,
            'posts_per_page'=> get_option('posts_per_page'),
            'post_type'     => 'recipes',
            'post_status'   => 'publish',
            'orderby'       => 'date',
            'order'         => 'DESC',
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'category',
                    'field' => 'id',
                    'terms' => $catArray,
                    'operator' => 'IN'
                ),
                array(
                    'taxonomy' => 'ingredients',
                    'field' => 'id',
                    'terms' => $tagArray,
                    'operator' => 'IN'
                )
            )
        );
    }

    $recipes = new WP_Query($args);

    if($recipes->have_posts()) :
        $count++;
        
        while ($recipes->have_posts()) : 
            $recipes->the_post();
            global $post;
            echo '<a href="'.get_the_permalink().'" class="recipe" data-color="color'.$count++.'" data-post="'.$post->ID.'" data-animation="slideUp">';
                $images = get_post_meta($post->ID,'recipe_images',true);
                if(!empty($images)) {
                    echo '<article class="image" style="background: url('.$images[0].') no-repeat scroll center / cover"></article>';
                }
                the_title("<h3>&bull; <span>","</span> &bull;</h3>");
            echo '</a>';
            if($count > 5) {
                $count = 1;
            }
        
        endwhile;

    endif;

    if($pageNumber === "2" || $pageNumber % 3 == 0) {
        if($trigger % 2 == 0) {
            echo '<section class="cta" data-animation="slideUp">';
                if(is_smartphone()) {
                    echo '<article class="half" style="background: url('.get_bloginfo('template_directory').'/assets/images/tote.jpg) no-repeat scroll center / cover"></article>';
                }
                echo '<article class="half">';
                    echo '<div class="outer">';
                        echo '<div class="inner">';
                            echo '<form id="wishlistForm" data-list="bc9392d4ad">';
                                echo '<h3>Are You Totes Cool, or What?</h3>';
                                echo '<p>Fill out the form below to join our wishlist and be the first to get your hands on The Toasted Post products while feeding our ever growing hunger to cook and create.</p>';
                                echo '<div class="field">';
                                    echo '<div class="half">';
                                        echo '<label for="fname">First Name</label>';
                                        echo '<input type="text" name="fname" placeholder="jane" />';
                                    echo '</div>';
                                    echo '<div class="half">';
                                        echo '<label for="fname">Last Name</label>';
                                        echo '<input type="text" name="lname" placeholder="doe" />';
                                    echo '</div>';
                                echo '</div>';
                                echo '<div class="field">';
                                    echo '<label for="fname">Email</label>';
                                    echo '<input type="email" name="email" placeholder="email@address..." />';
                                echo '</div>';
                                echo '<button class="btn btn-newsletter">Join Us</button>';
                            echo '</form>';
                        echo '</div>';
                    echo '</div>';
                echo '</article>';
                if(!is_smartphone()) {
                    echo '<article class="half" style="background: url('.get_bloginfo('template_directory').'/assets/images/tote.jpg) no-repeat scroll center / cover"></article>';
                }
            echo '</section>';
        } else {
            echo '<section class="cta" data-animation="slideUp">';
                echo '<article class="half" style="background: url('.get_bloginfo('template_directory').'/assets/images/email.jpg) no-repeat scroll top left / cover"></article>';
                echo '<article class="half">';
                    echo '<div class="outer">';
                        echo '<div class="inner">';
                            echo '<form id="newsletterForm" data-list="74c64c90b0">';
                                echo '<h3>Hungry for more?</h3>';
                                echo '<p>Fill out the form below to join our newsletter and be automatically emailed new recipes fresh out the kitchen.</p>';
                                echo '<div class="field">';
                                    echo '<div class="half">';
                                        echo '<label for="fname">First Name</label>';
                                        echo '<input type="text" name="fname" placeholder="jane" />';
                                    echo '</div>';
                                    echo '<div class="half">';
                                        echo '<label for="fname">Last Name</label>';
                                        echo '<input type="text" name="lname" placeholder="doe" />';
                                    echo '</div>';
                                echo '</div>';
                                echo '<div class="field">';
                                    echo '<label for="fname">Email</label>';
                                    echo '<input type="email" name="email" placeholder="email@address..." />';
                                echo '</div>';
                                echo '<button class="btn btn-newsletter">Join Us</button>';
                            echo '</form>';
                        echo '</div>';
                    echo '</div>';
                echo '</article>';
            echo '</section>';
        }
    }

    wp_reset_query();

    exit();
}
// ajax response to save download track
add_action('wp_ajax_loadFilter', 'loadFilter');
add_action('wp_ajax_nopriv_loadFilter', 'loadFilter');
function loadFilter() {

    check_ajax_referer('filter_ajax_nonce','security');

    $categories = (isset($_POST['categories'])) ? $_POST['categories'] : 0;
    $ingredients = (isset($_POST['ingredients'])) ? $_POST['ingredients'] : 0;

    if(empty($categories) && empty($ingredients)) {
        $args = array(
            'posts_per_page'=> get_option('posts_per_page'),
            'post_type'     => 'recipes',
            'post_status'   => 'publish',
            'orderby'       => 'date',
            'order'         => 'DESC'
        );
    } elseif(empty($ingredients) && !empty($categories)) {
        $args = array(
            'posts_per_page'=> get_option('posts_per_page'),
            'post_type'     => 'recipes',
            'post_status'   => 'publish',
            'orderby'       => 'date',
            'order'         => 'DESC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'id',
                    'terms' => $categories,
                    'operator' => 'IN'
                )
            )
        );
    } elseif(empty($categories) && !empty($ingredients)) {
        $args = array(
            'posts_per_page'=> get_option('posts_per_page'),
            'post_type'     => 'recipes',
            'post_status'   => 'publish',
            'orderby'       => 'date',
            'order'         => 'DESC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'ingredients',
                    'field' => 'id',
                    'terms' => $ingredients,
                    'operator' => 'IN'
                )
            )
        );
    } else {
        $args = array(
            'posts_per_page'=> get_option('posts_per_page'),
            'post_type'     => 'recipes',
            'post_status'   => 'publish',
            'orderby'       => 'date',
            'order'         => 'DESC',
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'category',
                    'field' => 'id',
                    'terms' => $categories,
                    'operator' => 'IN'
                ),
                array(
                    'taxonomy' => 'ingredients',
                    'field' => 'id',
                    'terms' => $ingredients,
                    'operator' => 'IN'
                )
            )
        );
    }

    $recipes = new WP_Query($args);

    if ($recipes->have_posts()) :
    $count = 1;
    while ($recipes->have_posts()) : $recipes->the_post();

        global $post;
        echo '<a href="'.get_the_permalink().'" class="recipe" data-color="color'.$count++.'" data-post="'.$post->ID.'" data-animation="slideUp">';
            $images = get_post_meta($post->ID,'recipe_images',true);
            if(!empty($images)) {
                echo '<article class="image" style="background: url('.$images[0].') no-repeat scroll center / cover"></article>';
            }
            the_title("<h3>&bull; <span>","</span> &bull;</h3>");
        echo '</a>';
        if($count > 5) {
            $count = 1;
        }
    endwhile;
    endif;

    wp_reset_query();

    exit();
}
// ajax response to save download track
add_action('wp_ajax_loadRecipe', 'loadRecipe');
add_action('wp_ajax_nopriv_loadRecipe', 'loadRecipe');
function loadRecipe() {

    check_ajax_referer('recipe_ajax_nonce','security');

    $postID = (isset($_POST['postID'])) ? $_POST['postID'] : 0;

    $args = array(
            'p' => $postID,
            'post_type' => 'recipes',
            'posts_per_page' => 1
        );

    $recipe = new WP_Query($args);

    if ($recipe->have_posts()) :

        echo '<section id="recipeWrap">';

        while ($recipe->have_posts()) : $recipe->the_post();

        global $post;

            $images = get_post_meta($post->ID, 'recipe_images', true);
            $count = 0;
            echo '<div id="recipeImages">';
                foreach($images as $image) {
                    if($count === 0) {
                        echo '<article class="featureImage"><img src="'.$image.'" alt="'.get_the_title().'" /></article>';
                    } else {
                        echo '<article class="thumbnail" data-image="'.$image.'"><span style="background:url('.$image.') no-repeat scroll center / cover"></span></article>';
                    }
                    $count++;
                }
                if(!is_smartphone()) {
                    relatedRecipe();
                    $recipe->reset_postdata();
                }
            echo '</div>';

            echo '<div id="recipeCopy">';
                
                the_title('<h1>','</h1>');
                echo '<span class="line">';
                    echo '<span></span>';
                    echo '<span></span>';
                    echo '<span></span>';
                    echo '<span></span>';
                    echo '<span></span>';
                echo '</span>';

                recipe_rating();
                
                echo '<div class="copy">';
                    listIngredients($post->ID);
                    listInstructions($post->ID);
                    the_content();
                    socialShare();
                    echo '<h4 id="dishpicsTitle">#dishpics</h4>';
                    echo '<div id="dishpics"></div>';
                echo '</div>';

                if(is_smartphone()) {
                    relatedRecipe();
                    $recipe->reset_postdata();
                }

            echo '</div>';
        
        endwhile;

        echo '</section>';

    endif;

    // wp_reset_query();

    exit();
}
// ajax response to save download track
add_action('wp_ajax_setRating', 'setRating');
add_action('wp_ajax_nopriv_setRating', 'setRating');
function setRating() {

    check_ajax_referer('rating_ajax_nonce','security');
    // get response variables
    $postID = (isset($_POST['postID'])) ? $_POST['postID'] : 0;
    $rating = (isset($_POST['rating'])) ? $_POST['rating'] : 0;

    $ratings = get_post_meta($postID, 'recipe_rating', true);

    if(!empty($rating)) {
        if(!empty($ratings)) {
            $new[] = $rating;
            $recipe_ratings = array_merge($ratings, $new);
            update_post_meta($postID,'recipe_rating',$recipe_ratings);
        } else {
            $new[] = $rating;
            update_post_meta($postID,'recipe_rating',$new);
        }
    }

    exit();
}
// Set content type of email
function set_html_content_type() {
    return 'text/html';
}
// Set from name of email sent
add_filter( 'wp_mail_from_name', 'custom_wp_mail_from_name' );
function custom_wp_mail_from_name( $original_email_from ) {
    $blog_title = htmlspecialchars_decode(get_bloginfo('name'));
    return $blog_title;
}
// Set from email of email sent
add_filter('wp_mail_from', 'custom_wp_mail_from_email');
function custom_wp_mail_from_email( $email_address ) {
    if($email_address === "wordpress@thetoastedpost.com") {
        return 'tiki@thetoastedpost.com';
    } else {
        return $email_address;
    }
}
// sync submissions to MailChimp list
add_action('wp_ajax_contactEmail', 'contactEmail');
add_action('wp_ajax_nopriv_contactEmail', 'contactEmail');
function contactEmail() {

    check_ajax_referer('contact_ajax_nonce','security');

    $firstname = (isset($_POST['fname'])) ? $_POST['fname'] : 0;
    $lastname = (isset($_POST['lname'])) ? $_POST['lname'] : 0;
    $emailaddress = (isset($_POST['email'])) ? $_POST['email'] : 0;

    add_filter( 'wp_mail_content_type', 'set_html_content_type' );

    // structure autoresponder
    ob_start();
    $message = (isset($_POST['message'])) ? $_POST['message'] : 0;
    $person = '<a href="mailto:'.$emailaddress.'">'.$firstname.' '.$lastname.'</a>';
    $subject = "The Toasted Post Contact Form";
    require("includes/emails/contact.php");
    $body = ob_get_clean();

    // construct email header.
    $headers = array(
        'From' => $emailaddress. "\r\n",
        'To' => 'The Toasted Post <kyle@thetoastedpost.com>'. "\r\n",
        'MIME-Version'  => '1.0\r\n'."\r\n",
        'Content-Type'  => 'text/html'."\r\n",
        'charset'       => 'UTF-8'."\r\n"
    );

    // send email
    $success = wp_mail( 'kyle@thetoastedpost.com', $subject, $body, $headers );

    remove_filter( 'wp_mail_content_type', 'set_html_content_type' );

    if($success) {
        echo 'success';
    } else {
        echo 'error';
    }

    exit();

}
// add function to save recipe meta on post save
add_action( 'save_post', 'save_recipe' );
function save_recipe( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return;
    if(isset($_POST['ingredients'])) {
        $cookinfo = $_POST['ingredients'];
        $new = array();
        if (is_array($cookinfo)) {
            foreach( $cookinfo as $key => $ingredient ) {
                $new[$key] = $ingredient['ingredient_title'];
            }
        }
        wp_set_post_terms($post_id, $new, 'ingredients', false);
        update_post_meta($post_id,'ingredients',$cookinfo);
    }
    if(isset($_POST['instructions'])) {
        $instructions = $_POST['instructions'];
        update_post_meta($post_id,'instructions',$instructions);
    }
}
// list ingredients
function listIngredients($pid) {
    //get the saved meta as an arry
    $ingredients = get_post_meta($pid,'ingredients', true );
    echo '<div class="ingredient-listing">';
        echo '<h3>Ingredients</h3>';
        if(!empty($ingredients)) {
            echo '<ul>';
                foreach( $ingredients as $ingredient ) {
                    echo '<li class="ingredient">';
                        echo '<span class="single-measurement">'.$ingredient['measure'].'</span>';
                        echo ' - ';
                        echo '<span class="single-ingredient">'.$ingredient['ingredient_title'].'</span>';
                    echo '</li>';
                }
            echo '</ul>';
        } else {
            echo '<p class="alert">There are no ingredients listed for this recipe.</p>';
        }
    echo '</div>';
}
// list instructions
function listInstructions($pid) {
    //get the saved meta as an arry
    $instructions = get_post_meta($pid,'instructions', true);
    echo '<div class="instruction-listing">';
        echo '<h3>Instructions</h3>';
        if(!empty($instructions)) {
            echo '<ol>';
                foreach( $instructions as $instruction ) {
                    echo '<li class="instruction">';
                        echo '<span class="single-instruction">'.$instruction.'</span>';
                    echo '</li>';
                }
            echo '</ol>';
        } else {
            echo '<p class="alert">There are no instructions listed for this recipe.</p>';
        }
    echo '</div>';
}
// add social share to single recipes/posts/pages
function socialShare() {
    global $post;
    $images = get_post_meta($post->ID, 'recipe_images', true);
    $random = rand(0,count($images)-1);
    echo '<section id="socialShare">';
        echo '<h4>Feed The People.</h4>';
        echo '<a target="_blank" href="http://www.facebook.com/sharer/sharer.php?u='.get_permalink().'" class="facebook">';
            echo '<i class="fa fa-facebook"></i>';
        echo '</a>';
        echo '<a class="twitter" target="_blank" href="http://twitter.com/share?url='.get_permalink().'&text='.get_the_title().' because good food = good mood. Gotta feed the people. - &via=thetoastedpost">';
            echo '<i class="fa fa-twitter"></i>';
        echo '</a>';
        echo '<a target="_blank" href="http://pinterest.com/pin/create/button/?url='.get_permalink().'&media='.$images[$random].'&description='.strip_tags(get_the_title()).'" class="pinterest" count-layout="horizontal">';
            echo '<i class="fa fa-pinterest"></i>';
        echo '</a>';
    echo '</section>';
}
function relatedRecipe() {
    global $post;

    if(get_the_terms($post->ID, 'category')) {
        foreach((get_the_terms($post->ID, 'category')) as $term) { 
            $termID = $term->term_id;
        }
    } else {
        $termID = 0;
    }

    $args = array(
        'post_type' => 'recipes',
        'post_status' => 'publish',
        'order' => 'DESC',
        'post__not_in' => array($post->ID),
        'posts_per_page' => 1,
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field' => 'id',
                'terms' => $termID
             )
        )
    );

    $related = new WP_Query($args);

    if($related->have_posts()) {

        echo '<section id="relatedRecipes">';
            
            echo '<h2>Second Servings?</h2>';

            while ($related->have_posts()) { 

                $related->the_post();

                echo '<a href="'.get_the_permalink().'" data-post="'.$post->ID.'" class="relatedRecipe">';
                    $images = get_post_meta($post->ID,'recipe_images',true);
                    if(!empty($images)) {
                        echo '<img class="image" src="'.$images[0].'" alt="" />';
                    }
                    the_title("<h3>","</h3>");
                echo '</a>'; 

            }

        echo '</section>';

    }
    wp_reset_query();
}
?>