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
			echo '<div class="recipeWrap">';
				while (have_posts()) : 
					the_post();
					echo '<a href="'.get_the_permalink().'" class="recipe" data-animation="slideUp">';
						the_post_thumbnail();
						the_title("<h3>","</h3>");
					echo '</a>';
				endwhile;
			echo '</div>'; 
		endif;

	echo '</div>';

echo '</section>';

get_footer(); 

?>