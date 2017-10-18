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
// ajax response to save download track
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
    exit;
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
    exit;
}
// ajax response to save download track
add_action('wp_ajax_loadRecipes', 'loadRecipes');
add_action('wp_ajax_nopriv_loadRecipes', 'loadRecipes');
function loadRecipes() {
    $termID = (isset($_GET['termID'])) ? $_GET['termID'] : 0;
    $pageNumber = (isset($_GET['pageNumber'])) ? $_GET['pageNumber'] : 0;

    if($termID !== "0") {
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
                    'terms' => $termID
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
            'order'         => 'DESC'
        );
    }

    $recipes = new WP_Query($args);

    if ($recipes->have_posts()) :
    while ($recipes->have_posts()) : $recipes->the_post();

        global $post;
        echo '<a href="'.get_the_permalink().'" class="recipe" data-animation="slideUp">';
            $images = get_post_meta($post->ID,'recipe_images',true);
            if(!empty($images)) {
                echo '<article class="image" style="background: url('.$images[0].') no-repeat scroll center / cover"></article>';
            }
            the_title("<h3>","</h3>");
        echo '</a>';

    endwhile;
    endif;

    wp_reset_query();

    exit;
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
    $random = rand(0,count($images));
    echo '<section id="socialShare">';
        echo '<a target="_blank" href="http://www.facebook.com/sharer/sharer.php?u='.get_permalink().'" class="facebook">';
            echo '<i class="fa fa-facebook"></i>';
        echo '</a>';
        echo '<a class="twitter" target="_blank" href="http://twitter.com/share?url='.get_permalink().'&text=Check out this '.get_the_title().' recipe! Good food = good mood. Gotta feed the people. - &via=thetoastedpost">';
            echo '<i class="fa fa-twitter"></i>';
        echo '</a>';
        echo '<a target="_blank" href="http://pinterest.com/pin/create/button/?url='.get_permalink().'&media='.$images[$random].'&description='.strip_tags(get_the_excerpt()).'" class="pinterest" count-layout="horizontal">';
            echo '<i class="fa fa-pinterest"></i>';
        echo '</a>';
    echo '</section>';
}
?>