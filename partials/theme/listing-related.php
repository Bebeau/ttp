<?php 

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

				echo '<a href="'.get_the_permalink().'" class="recipe">';
					$images = get_post_meta($post->ID,'recipe_images',true);
					if(!empty($images)) {
						echo '<img class="image" src="'.$images[0].'" alt="" />';
					}
					the_title("<h3>","</h3>");
				echo '</a>'; 

			}

		echo '</section>';

	}

	$related->reset_postdata();

?>