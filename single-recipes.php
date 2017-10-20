<?php get_header();

	if (have_posts()) :

		echo '<section id="recipeWrap">'; 

		while (have_posts()) : the_post();

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
	        	// get_template_part( 'partials/theme/listing', 'related' );
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
					the_content();
					listIngredients($post->ID);
					listInstructions($post->ID);
					socialShare();
				echo '</div>';

		    echo '</div>';
		
		endwhile;

		echo '</section>';

	endif;

get_footer(); ?>