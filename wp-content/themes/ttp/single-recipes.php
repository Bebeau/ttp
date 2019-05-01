<?php get_header(); ?>

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

					$termID = get_queried_object_id();
					$count = 1;
					echo '<div id="listingWrap" data-term="'.$termID.'" data-perPage="'.get_option('posts_per_page').'">';
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
					echo '</div>'; 
				endif;

				wp_reset_query();

			echo '</div>';

		?>

	</section>

<?php get_footer(); ?>