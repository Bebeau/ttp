<?php

/*
Plugin Name: TTP Recipes
Plugin URI: http://theinitgroup.com
Description: This plugin creates custom post types for recipes.
Author: Kyle Bebeau
Version: 1.2
Author URI: http://theinitgroup.com
*/

// Load all styles and scripts for the site
add_action( 'wp_enqueue_scripts', 'load_recipe_scripts' );
function load_recipe_scripts() {
    global $post;
    if(is_page_template('templates/form.php')) {
        // Load custom scripts
        wp_enqueue_script('jquery_ui', 'https://code.jquery.com/ui/1.11.4/jquery-ui.js', array('jquery'), null, false);
        wp_enqueue_style( 'recipe-styles', get_bloginfo('template_directory').'/assets/css/form.css', false, '1.0.0' );
    }
    // wp_enqueue_style( 'recipe-pages', plugin_dir_url( __FILE__  ) . 'assets/css/pages.css', false, '1.0.0' );
}
// Add admin styles for login page customization
add_action( 'admin_enqueue_scripts', 'load_recipes_admin_scripts' );
function load_recipes_admin_scripts() {
    global $typenow;
    if( $typenow == 'recipes' ) {
        wp_enqueue_script('jquery_ui', 'https://code.jquery.com/ui/1.11.4/jquery-ui.js', array('jquery'), null, true);
        wp_localize_script( 'my-ajax-request', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    }
}
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
            'rewrite' => array( 'slug' => 'ingredients' )
        )
    );
}
add_action( 'init', 'init_ingredients' );

// Add custom meta boxes to display recipe specs
add_action( 'add_meta_boxes', 'recipe_meta_box', 1 );
function recipe_meta_box( $post ) {
    add_meta_box(
        'ingredient-listing', 
        'Ingredients', 
        'recpie_ingredients',
        'recipes', 
        'normal', 
        'low'
    );
    add_meta_box(
        'instruction-listing', 
        'Step by Step Instructions', 
        'recpie_instructions',
        'recipes', 
        'normal', 
        'low'
    );
}
function recpie_ingredients() { 
    global $post;
    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'ingredients_noncename' );

    //get the saved meta as an arry
    $ingredients = get_post_meta($post->ID,'ingredients', true);

    $c = 0;

    echo '<section id="sortable">';
        if ( is_array($ingredients) ) {
            foreach( $ingredients as $ingredient ) {
                printf( '
                    <div class="single-ingredient">
                        <span class="moveable">
                            <span class="bar"></span>
                            <span class="bar"></span>
                            <span class="bar"></span>
                        </span>
                        <article class="measurement">
                            <input type="text" name="ingredients[%1$s][measure]" value="%2$s" placeholder="measurement" />
                        </article>
                        -
                        <article class="ingredient-title">
                            <input type="text" name="ingredients[%1$s][ingredient_title]" value="%3$s" placeholder="ingredient" />
                        </article>
                        <span class="button-remove"><i class="fa fa-close"></i></span>
                    </div>', 
                    $c, 
                    $ingredient['measure'], 
                    $ingredient['ingredient_title']
                );
                $c = $c + 1;
            }
        } 
    echo '</section>'; ?>
    <span class="add button button-primary button-large">+ <?php _e('Add Ingredient'); ?></span>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            removeBtn = function() {
                jQuery(".button-remove").on('click', function(e) {
                    e.preventDefault();
                    jQuery(this).parent().remove();
                });
            }
            sortable = function() {
                jQuery( "#sortable" ).sortable({
                    placeholder: "ui-state-highlight"
                });
                jQuery( "#sortable" ).disableSelection();
            }
            var count = <?php echo $c; ?>;
            jQuery(".add").click(function(e) {
                count = count + 1;
                e.preventDefault();
                jQuery('#sortable').append('<div class="single-ingredient"><span class="moveable"><span class="bar"></span><span class="bar"></span><span class="bar"></span></span><article class="measurement"> <input type="text" name="ingredients['+count+'][measure]" value="" placeholder="measurement"/></article> - <article class="ingredient-title"><input type="text" name="ingredients['+count+'][ingredient_title]" value="" placeholder="ingredient" /></article><span class="button-remove"><i class="fa fa-close"></i></span></div>' );
                removeBtn();
                sortable();
            });
            removeBtn();
            sortable();
        });
    </script>
<?php }

function recpie_instructions() { 
    global $post;
    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'instructions_noncename' );

    //get the saved meta as an arry
    $instructions = get_post_meta($post->ID,'instructions', true);

    $c = 0;

    echo '<section id="sortable2">';
        if ( is_array($instructions) ) {
            foreach( $instructions as $instruction ) {
                printf( '
                    <div class="single-instruction">
                        <span class="moveable">
                            <span class="bar"></span>
                            <span class="bar"></span>
                            <span class="bar"></span>
                        </span>
                        <article class="step">
                            <input type="text" name="instructions[%1$s][step]" value="%2$s" placeholder="cooking step" />
                        </article>
                        <span class="button-remove"><i class="fa fa-close"></i></span>
                    </div>', 
                    $c, 
                    $instruction['step']
                );
                $c = $c + 1;
            }
        } 
    echo '</section>'; ?>
    <span class="add_instruction button button-primary button-large">+ <?php _e('Add Step'); ?></span>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            removeInstructions = function() {
                jQuery(".button-remove").on('click', function(e) {
                    e.preventDefault();
                    jQuery(this).parent().remove();
                });
            }
            sortableInstructions = function() {
                jQuery( "#sortable2" ).sortable({
                    placeholder: "ui-state-highlight"
                });
                jQuery( "#sortable2" ).disableSelection();
            }
            var count = <?php echo $c; ?>;
            jQuery(".add_instruction").click(function(e) {
                count = count + 1;
                e.preventDefault();
                jQuery('#sortable2').append('<div class="single-instruction"><span class="moveable"><span class="bar"></span><span class="bar"></span><span class="bar"></span></span><article class="step"> <input type="text" name="instructions['+count+'][step]" value="" placeholder="cooking step"/></article><span class="button-remove"><i class="fa fa-close"></i></span></div>' );
                removeInstructions();
                sortableInstructions();
            });
            removeInstructions();
            sortableInstructions();
        });
    </script>
<?php }
/* When the post is saved, saves our custom data */
function dynamic_save_postdata( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return;

    if ( !isset( $_POST['ingredients_noncename'] ) || !wp_verify_nonce( $_POST['ingredients_noncename'], plugin_basename( __FILE__ ) ) )
        return;

    if(isset($_POST['ingredients'])) {
        $cookinfo = $_POST['ingredients'];
        //get the saved meta as an arry
        $new = array();
        if ( is_array($cookinfo) ) {
            foreach( $cookinfo as $key => $ingredient ) {
                $new[$key] = $ingredient['ingredient_title'];
            }
        }
        wp_set_post_terms($post_id, $new, 'ingredients', false);
        update_post_meta($post_id,'ingredients',$cookinfo);
    }

    if ( !isset( $_POST['instructions_noncename'] ) || !wp_verify_nonce( $_POST['instructions_noncename'], plugin_basename( __FILE__ ) ) )
        return;

    if(isset($_POST['instructions'])) {
        $instructions = $_POST['instructions'];
        update_post_meta($post_id,'instructions',$instructions);
    }

    if(isset($_POST['recipePrep_hours'])) {
        update_post_meta($post_id,'recipePrep_hours',$_POST['recipePrep_hours']);
    }
    if(isset($_POST['recipePrep_minutes'])) {
        $prepMin = sprintf("%02d", $_POST['recipePrep_minutes']);
        update_post_meta($post_id,'recipePrep_minutes',$prepMin);
    }
    if(isset($_POST['recipeTime_hours'])) {
        update_post_meta($post_id,'recipeTime_hours',$_POST['recipeTime_hours']);
    }
    if(isset($_POST['recipeTime_minutes'])) {
        $timeMin = sprintf("%02d", $_POST['recipeTime_minutes']);
        update_post_meta($post_id,'recipeTime_minutes',$timeMin);
    }
    if(isset($_POST['recipeServing'])) {
        update_post_meta($post_id,'recipeServing',$_POST['recipeServing']);
    }

    if(isset($_POST['firstname'])) {
        update_post_meta($post_id,'recipeFirstName',$_POST['firstname']);
    }
    if(isset($_POST['lastname'])) {
        update_post_meta($post_id,'recipeLastName',$_POST['lastname']);
    }
    if(isset($_POST['emailaddress'])) {
        update_post_meta($post_id,'recipeEmailaddress',$_POST['emailaddress']);
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
            echo '<ul>';
                foreach( $instructions as $instruction ) {
                    printf( '
                            <li class="instruction">
                                <span class="single-instruction">%1$s</span>
                            </li>', 
                        $instruction['step']
                    );
                }
            echo '</ul>';
        } else {
            echo '<p class="alert">There are no instructions listed for this recipe.</p>';
        }
    echo '</div>';
}
// add feature image attachment
function uploadFeatureImage($id, $image) {
    if(isset($image)) {
        $uploaddir = wp_upload_dir();
        $file = $image;
        $uploadfile = $uploaddir['path'] . '/' . basename( $file["name"] );

        move_uploaded_file( $file["tmp_name"], $uploadfile );
        $filename = basename( $uploadfile );

        $wp_filetype = wp_check_filetype(basename($filename), null );

        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        $attach_id = wp_insert_attachment( $attachment, $uploadfile );
        update_post_meta($id,'_thumbnail_id',$attach_id);
        set_post_thumbnail( $id, $attach_id );
    }
}
// add submission to MailChimp
function AddtoMailchimp($firstname, $lastname, $emailaddress) {
    require_once 'templates/includes/MCAPI.class.php';
    require_once 'templates/includes/config.ini.php';

    // Prep array to send to MailChimp
    $merge_vars = array(
        'FNAME'        => $firstname,
        'LNAME'        => $lastname,
        'EMAIL'        => $emailaddress,
        'GROUPINGS' => array(
                0 => array(
                    'name' => "Source",
                    'groups' => "Recipes"
                )
            )
    );
    // Capture lead
    $api = new MCAPI($api);
    $retval = $api->listSubscribe($list_id,$emailaddress,$merge_vars ,'HTML',false,true,true);
    $retval;
    // Backup capture
    $api2 = new MCAPI($api2);
    $retval2 = $api2->listSubscribe($list_id2,$emailaddress,$merge_vars ,'HTML',false,true,true);
    $retval2;
}
add_filter( 'wp_mail_content_type', 'set_recipes_content_type' );
function set_recipes_content_type( $content_type ) {
    return 'text/html';
}
// send thank you to user
function sendThankYou($emailaddress) {

    add_filter( 'wp_mail_content_type', 'set_recipes_content_type' );

    $facebook = get_option('facebook');
    $twitter = get_option('twitter');
    $instagram = get_option('instagram');
    $pinterest = get_option('pinterest');
    $youtube = get_option('youtube');

    $recipeListingURL = site_url('recipes-listing');

    ob_start();
    $subject2 = "Thank You For Submitting Your Recipe!";
    require("templates/includes/emails/thankyou.php");
    $body2 = ob_get_clean();

    // construct email header.
    $headers2 = array(
        'From' => 'homeandfamilytv@gmail.com'. "\r\n",
        'To' => $emailaddress. "\r\n",
        'MIME-Version'  => '1.0\r\n'."\r\n",
        'Content-Type'  => 'text/html'."\r\n",
        'charset'       => 'UTF-8'
    );

    wp_mail( $emailaddress, $subject2, $body2, $headers2 );

    remove_filter( 'wp_mail_content_type', 'set_recipes_content_type' );
}

?>