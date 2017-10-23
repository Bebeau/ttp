<?php 
		echo '<footer></footer>';

	// end bodyWrap
	echo '</div>';

	echo '<section id="contact" class="btn-modal" data-modal="contact">';
		echo '<i class="fa fa-envelope-o"></i>';
	echo '</section>';

	echo '<section class="modal" data-modal="contact">';
		echo '<i class="fa fa-close"></i>';
		echo '<div class="outer">';
			echo '<div class="inner">';
				echo '<form>';
					echo '<input type="">';
				echo '</form>';
			echo '</div>';
		echo '</div>';
	echo '</section>';

	echo '<section class="modal" data-modal="ingredients">';
		echo '<i class="fa fa-close"></i>';
		echo '<div class="outer">';
			echo '<div class="inner">';
				echo '<div id="tagCloud">';
					$args = array(
						'smallest'                  => 10, 
						'largest'                   => 36,
						'unit'                      => 'pt', 
						'number'                    => 45,  
						'format'                    => 'flat',
						'separator'                 => "\n",
						'orderby'                   => 'name', 
						'order'                     => 'ASC',
						'exclude'                   => null, 
						'include'                   => null, 
						'link'                      => 'view', 
						'taxonomy'                  => 'ingredients', 
						'echo'                      => true,
						'child_of'                  => null,
					);
					wp_tag_cloud($args);
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</section>';

	echo '<section class="modal" data-modal="category">';
		echo '<i class="fa fa-close"></i>';
		echo '<div class="outer">';
			echo '<div class="inner">';
				$args = array(
				    'taxonomy' => 'category',
				    'post_type' => 'recipes',
				    'hide_empty' => false,
				);
				$terms = get_terms($args);
				foreach($terms as $term) {
					echo '<a href="'.get_term_link($term->term_taxonomy_id).'">'.$term->name.'</a>';
				}
			echo '</div>';
		echo '</div>';
	echo '</section>';

	echo '<section class="modal single-recipes in">';
		echo '<i class="fa fa-close"></i>';
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
						echo '<h4 id="dishpicsTitle">#dishpics</h4>';
						echo '<div id="dishpics"></div>';
					echo '</div>';

			    echo '</div>';
			
			endwhile;

			echo '</section>';

		endif;
	echo '</section>';
    
	echo '</body>';
echo '</html>';

wp_footer(); ?>  