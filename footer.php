<?php 
		echo '<footer></footer>';

	// end bodyWrap
	echo '</div>';

	echo '<section id="contact" class="btn-modal" data-modal="contact">';
		echo '<i class="fa fa-envelope-o"></i>';
	echo '</section>';

	echo '<section class="modal" data-modal="contact">';
		echo '<div class="half image">';
		echo '</div>';
		echo '<div class="half">';
			echo '<i class="close"></i>';
			echo '<div class="outer">';
				echo '<div class="inner">';
					echo '<form method="GET" id="contactForm" class="modalCopy">';
						echo '<h3 class="modalTitle">Let&#39;s Chat</h3>';
						if(!is_smartphone()) {
							echo '<p>Our purpose is to Feed The People. Feed the people both literally and figuratively with food and knowledge to create a community of optimal health. And that means sharing, uniting, and working together! So if you want to tell your story, collaborate on a project or just send us some feedback, don’t be shy...</p>';
						} else {
							echo '<p>If you want to tell your story, collaborate on a project or just send us some feedback, don’t be shy...</p>';
						}
						echo '<div class="field">';
							echo '<label for="fname">First Name</label>';
							echo '<input type="text" name="fname" placeholder="Jane" />';
						echo '</div>';
						echo '<div class="field">';
							echo '<label for="lname">Last Name</label>';
							echo '<input type="text" name="lname" placeholder="Doe" />';
						echo '</div>';
						echo '<div class="field">';
							echo '<label for="email">Email Address</label>';
							echo '<input type="email" name="email" placeholder="email@address.."/>';
						echo '</div>';
						echo '<div class="field">';
							echo '<label for="message">Friendly Message</label>';
							echo '<textarea type="text" placeholder="What&#39;s up?"></textarea>';
						echo '</div>';
						echo '<button type="submit" class="btn-contact">Send</button>';
					echo '</form>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</section>';

	echo '<section class="modal" data-modal="ingredients">';
		echo '<div class="half image">';
		echo '</div>';
		echo '<div class="half">';
			echo '<i class="close"></i>';
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
		echo '<div class="half image">';
		echo '</div>';
		echo '<div class="half">';
			echo '<i class="close"></i>';
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
									echo '<a href="'.get_term_link($term->term_taxonomy_id).'" data-term="'.$term->term_taxonomy_id.'" >'.$term->name.'</a>';
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

	echo '<section class="modal" data-modal="rating">';
		echo '<i class="close"></i>';
		echo '<div class="outer">';
			echo '<div class="inner">';
				echo '<article id="rateRecipe">';
					echo '<ul>';
			            echo '<li><i class="fa fa-star" data-star="1"></i></li>';
			            echo '<li><i class="fa fa-star" data-star="2"></i></li>';
			            echo '<li><i class="fa fa-star" data-star="3"></i></li>';
			            echo '<li><i class="fa fa-star" data-star="4"></i></li>';
			            echo '<li><i class="fa fa-star" data-star="5"></i></li>';
			        echo '</ul>';
				echo '</article>';
			echo '</div>';
		echo '</div>';
	echo '</section>';

	if(is_singular('recipes')) {
		echo '<section class="modal single-recipes in">';
			
			if (have_posts()) :

		        echo '<section itemscope itemtype="http://schema.org/Recipe" id="recipeWrap">';

		        while (have_posts()) : the_post();

		            $images = get_post_meta($post->ID, 'recipe_images', true);
		            $count = 0;
		            echo '<div id="recipeImages">';
		                foreach($images as $image) {
		                    if($count === 0) {
		                        echo '<article class="featureImage"><img itemprop="image" src="'.wp_get_attachment_image_src($image, 'feature')[0].'" alt="'.get_the_title().'" /></article>';
		                    } elseif($count < 4) {
		                        echo '<article class="thumbnail" data-image="'.wp_get_attachment_image_src($image, 'feature')[0].'"><span style="background:url('.wp_get_attachment_image_src($image, 'medium')[0].') no-repeat scroll center / cover"></span></article>';
		                    }
		                    $count++;
		                }
		                if(!is_smartphone()) {
		                    relatedRecipe();
		                }
		            echo '</div>';

		            echo '<div id="recipeCopy">';
		            	
		            	echo '<i class="close"></i>';

		                the_title('<h1 itemprop="name">','</h1>');
		                echo '<span class="line">';
		                    echo '<span></span>';
		                    echo '<span></span>';
		                    echo '<span></span>';
		                    echo '<span></span>';
		                    echo '<span></span>';
		                echo '</span>';

		                recipe_rating();
		                
		                echo '<div class="copy">';
		                	echo '<div itemprop="description">';
		                		the_content();
		                	echo '</div>';
		                    listIngredients();
		                    listInstructions();
		                    socialShare();
		                    if(is_smartphone()) {
			                    relatedRecipe();
			                }
		                    echo '<div id="dishpicsWrap">';
			                    echo '<div id="dishpicsTitle">';
			                    	echo '<h4>#dishpics</h4>';
			                    echo '</div>';
			                    echo '<div id="dishpics"></div>';
			                echo '</div>';
		                    
		                echo '</div>';

		            echo '</div>';
		        
		        endwhile;

		        echo '</section>';

		    endif;

		echo '</section>';
	} else {
		echo '<section class="modal single-recipes">';
			
		echo '</section>';
	}
    
	echo '</body>';
echo '</html>';

wp_footer(); ?>  