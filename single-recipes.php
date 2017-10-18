<?php get_header();

	if (have_posts()) : 

		while (have_posts()) : the_post();

			$images = get_post_meta($post->ID, 'recipe_images', true);

	        echo '<div class="recipeImages">';
	        	foreach($images as $image) {
	        		echo '<img src="'.$image.'" alt="" />';
	        	}
	        echo '</div>';

			echo '<div id="recipeWrap">';

				the_title('<h1>','</h1>');
				the_content();
				listIngredients($post->ID);
				listInstructions($post->ID);
				socialShare();
				
				echo '<div class="fb-comments" data-href="'.get_the_permalink().'" data-width="100%" data-num-posts="6"></div>';
		    
		    echo '</div>';
		
		endwhile;

	endif;

get_footer(); ?>