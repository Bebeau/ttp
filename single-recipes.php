<?php get_header('single'); ?>

<section id="top" class="outer" data-parallax='{"y" : 500, "smoothness": 1}'>
	<?php
		echo '<div class="inner">';
			echo '<img class="svg" src="'.get_bloginfo('template_directory').'/assets/images/logo.svg" alt="" />';
		echo '</div>';
		echo '<div id="instafeed"></div>';
	?>
</section>

<?php

echo '<section id="about">';

		echo '<div class="wrap">';
		
			echo '<div class="tagline">';
				echo '"<span class="red">Good</span> <span class="green">food</span> = <span class="red">Good</span> <span class="green">mood</span>. Gotta feed the people."';
			echo '</div>';

			$args = array(
				'post_type' => 'page',
				'post_in' => '19'
			);
			query_posts( $args );

			if (have_posts()) : 
				echo '<div class="half">';
				while (have_posts()) : 
					the_post();
					echo '<article class="image">';
						the_post_thumbnail();
					echo '</article>';
					echo '<article class="copy blue">';
						the_content();
					echo '</article>';
				endwhile;
				echo '</div>';
			endif;

			wp_reset_query();

		echo '</div>';

	echo '</section>';

	?>

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
					echo '<button class="btn btn-modal" data-modal="categories"><i class="fa fa-filter"></i> Category</button>';
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
								echo '<article class="image" style="background: url('.$images[0].') no-repeat scroll center / cover"></article>';
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

<?php get_footer('single'); ?>