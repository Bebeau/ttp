<?php 
		echo '<footer></footer>';

	// end bodyWrap
	echo '</div>';

	echo '<section id="contact" class="btn-modal" data-modal="contact">';
		echo '<i class="fa fa-envelope-o"></i>';
	echo '</section>';

	echo '<section class="modal" data-modal="contact">';
		echo '<i class="fa fa-close"></i>';
		echo '<div class="half image">';
		echo '</div>';
		echo '<div class="half">';
			echo '<div class="outer">';
				echo '<div class="inner">';
					echo '<form method="GET" id="contactForm">';
						echo '<h3 class="modalTitle">Let&#39;s Chat</h3>';
						if(!is_smartphone()) {
							echo '<p>Our purpose is to Feed The People. Feed the people both literally and figuratively with food and knowledge to create a community of optimal health. And that means sharing, uniting, and working together! So if you want to tell your story, collaborate on a project or just send us some feedback, don’t be shy...</p>';
						} else {
							echo '<p>If you want to tell your story, collaborate on a project or just send us some feedback, don’t be shy...</p>';
						}
						echo '<div class="field">';
							echo '<label for="firstname">First Name</label>';
							echo '<input type="text" name="firstname" id="firstname" placeholder="Jane" />';
						echo '</div>';
						echo '<div class="field">';
							echo '<label for="lastname">Last Name</label>';
							echo '<input type="text" name="lastname" id="lastname" placeholder="Doe" />';
						echo '</div>';
						echo '<div class="field">';
							echo '<label for="emailaddress">Email Address</label>';
							echo '<input type="email" name="emailaddress" id="emailaddress" placeholder="email@address.."/>';
						echo '</div>';
						echo '<div class="field">';
							echo '<label for="message">Friendly Message</label>';
							echo '<textarea type="text" placeholder="What&#39;s up?"></textarea>';
						echo '</div>';
						echo '<button type="submit">Send</button>';
					echo '</form>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</section>';

	echo '<section class="modal" data-modal="ingredients">';
		echo '<i class="fa fa-close"></i>';
		echo '<div class="half image">';
		echo '</div>';
		echo '<div class="half">';
			echo '<div class="outer">';
				echo '<div class="inner">';
					echo '<div class="modalCopy">';
						echo '<h3 class="modalTitle">Ingredients</h3>';
						echo '<p>Select the ingredients you wish to cook with from the listing below and click filter to see what recipes we have for you.</p>';
						$args = array(
							'smallest'                  => 10, 
							'largest'                   => 18,
							'unit'                      => 'pt', 
							'number'                    => 45,  
							'format'                    => 'array',
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
						$tags = wp_tag_cloud($args);
						if(!empty($tags)) {
							foreach($tags as $tag) {
								$term = get_term_by('name',$tag, 'ingredients');
								echo '<span data-term="'.$term->term_taxonomy_id.'">'.$tag.'</span>';
							}
							echo '<button class="btn btn-filter">Filter</button>';
						}
					echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</section>';

	echo '<section class="modal" data-modal="category">';
		echo '<i class="fa fa-close"></i>';
		echo '<div class="half image">';
		echo '</div>';
		echo '<div class="half">';
			echo '<div class="outer">';
				echo '<div class="inner">';
					echo '<div class="modalCopy">';
						echo '<h3 class="modalTitle">Categories</h3>';
						echo '<p>Select categories below and click filter to view those specific type of recipes.</p>';
						$args = array(
						    'taxonomy' => 'category',
						    'post_type' => 'recipes',
						    'hide_empty' => true,
						);
						$terms = get_terms($args);
						if(!empty($terms)) {
							$totalTerms = count($terms);
							$count = 1;
							foreach($terms as $term) {
								if($term->count > 0) { 
									echo ' <span><a href="'.get_term_link($term->term_taxonomy_id).'" data-term="'.$term->term_taxonomy_id.'" >'.$term->name.'</a></span>';
									if($count !== $totalTerms) {
										echo ' &bull;</span>';
									} else {
										echo '</span>';
									}
									$count++;
								}
							}
							echo '<button class="btn btn-filter">Filter</button>';
						}
					echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</section>';

	if(is_singular('recipes')) {
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
		                if(!is_smartphone()) {
		                    relatedRecipe();
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
		                    if(is_smartphone()) {
			                    relatedRecipe();
			                }
		                echo '</div>';

		            echo '</div>';
		        
		        endwhile;

		        echo '</section>';

		    endif;

		echo '</section>';
	} else {
		echo '<section class="modal single-recipes">';
			echo '<i class="fa fa-close"></i>';
		echo '</section>';
	}
    
	echo '</body>';
echo '</html>';

wp_footer(); ?>  