<?php get_header(); 

	global $post;
	$catID = get_query_var('cat');

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
			'order' => 'DESC',
			'tax_query' => array(
				array(
				    'taxonomy' => 'category',
				    'field' => 'id',
				    'terms' => $catID
			     )
			)
		);

		$category = new WP_Query($args);

		if ($category->have_posts()) : 
			echo '<h1 id="listingTitle">'.get_cat_name($catID).'</h1>';
			echo '<section id="filter">';
				echo '<button class="btn btn-modal" data-modal="category"><i class="fa fa-filter"></i> Category</button>';
				echo '<button class="btn btn-modal" data-modal="ingredients"><i class="fa fa-filter"></i> Ingredient</button>';
			echo '</section>';

			$count = 1;
			echo '<div id="listingWrap" data-term="'.$catID.'" data-perPage="'.get_option('posts_per_page').'">';
				while ($category->have_posts()) : 
					$category->the_post();
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

<?php get_footer(); ?>