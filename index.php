<?php 

get_header();

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
				echo '<div class="dropdown">';
					echo '<button class="btn btn-filter"><i class="fa fa-filter"></i> Category</button>';
					echo '<nav>';
						$args = array(
						    'taxonomy' => 'category',
						    'post_type' => 'recipes',
						    'hide_empty' => false,
						);
						$terms = get_terms($args);
						foreach($terms as $term) {
							echo '<a href="'.get_term_link($term->term_taxonomy_id).'">'.$term->name.'</a>';
						}
					echo '</nav>';
				echo '</div>';
				echo '<div class="dropdown">';
					echo '<button class="btn btn-filter"><i class="fa fa-filter"></i> Ingredient</button>';
					echo '<nav>';
						$args = array(
							'smallest'                  => 8, 
							'largest'                   => 22,
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
						wp_tag_cloud( $args );
					echo '</nav>';
				echo '</button>';
			echo '</section>';

			$termID = get_queried_object_id();

			echo '<div id="recipeWrap" data-term="'.$termID.'" data-perPage="'.get_option('posts_per_page').'">';
				while (have_posts()) : 
					the_post();
					echo '<a href="'.get_the_permalink().'" class="recipe" data-animation="slideUp">';
						$images = get_post_meta($post->ID,'recipe_images',true);
						if(!empty($images)) {
							echo '<article class="image" style="background: url('.$images[0].') no-repeat scroll center / cover"></article>';
						}
						the_title("<h3>","</h3>");
					echo '</a>';
				endwhile;
			echo '</div>'; 
		endif;

	echo '</div>';

echo '</section>';

get_footer(); 

?>