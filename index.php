<?php 

get_header(); ?>

	<section id="listing" class="outer" data-parallax='{"y" : -100, "smoothness": 1}'>

	<?php

		echo '<div class="wrap">';

			echo '<div class="statsWrap half">';
				echo '<article></article>';
				echo '<article>';
					echo '<div class="stats">';
						echo '<span class="green">health</span> / <span class="orange">heart</span> / <span class="blue">healing</span>';
					echo '</div>';
				echo '</article>';
			echo '</div>';

			echo '<span class="line">';
				echo '<span></span>';
				echo '<span></span>';
				echo '<span></span>';
				echo '<span></span>';
				echo '<span></span>';
				echo '<span></span>';
				echo '<span></span>';
				echo '<span></span>';
				echo '<span></span>';
				echo '<span></span>';
			echo '</span>';

			$args = array(
				'post_type' => 'recipes',
				'post_status' => 'publish',
				'order' => 'DESC'
			);
			query_posts( $args );

			if (have_posts()) : 
				echo '<h1 id="listingTitle">All Recipes</h1>';
				echo '<section id="filter">';
					echo '<button class="btn btn-modal" data-modal="category"><i class="fa fa-filter"></i> Category</button>';
					echo '<button class="btn btn-modal" data-modal="ingredients"><i class="fa fa-filter"></i> Ingredient</button>';
				echo '</section>';

				$count = 1;
				echo '<div id="listingWrap" data-cat="" data-tag="" data-perPage="'.get_option('posts_per_page').'">';
					while (have_posts()) : 
						the_post();
						echo '<a href="'.get_the_permalink().'" class="recipe" data-color="color'.$count++.'" data-post="'.$post->ID.'" data-animation="slideUp">';
							$images = get_post_meta($post->ID,'recipe_images',true);
							if(!empty($images)) {
								echo '<article class="image" style="background: url('.wp_get_attachment_image_src($images[0], 'listing')[0].') no-repeat scroll center / cover"></article>';
							}
							the_title("<h3>&bull; <span>","</span> &bull;</h3>");
						echo '</a>';
						if($count > 5) {
							$count = 1;
						}
					endwhile;
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
			                        echo '<div class="socialLinks">';
			                            echo '<h3>Or, follow...</h3>';
			                            echo '<a href="https://facebook.com/thetoastedpost" target="_blank"><i class="fa fa-facebook"></i></a>';
			                            echo '<a href="https://twitter.com/thetoastedpost" target="_blank"><i class="fa fa-twitter"></i></a>';
			                            echo '<a href="https://pinterest.com/thetoastedpost" target="_blank"><i class="fa fa-pinterest"></i></a>';
			                        echo '</div>';
			                    echo '</div>';
			                echo '</div>';
			            echo '</article>';
			        echo '</section>';
				echo '</div>'; 
			endif;

			wp_reset_query();

		echo '</div>';

	?>

	</section>

<?php

get_footer(); 

?>