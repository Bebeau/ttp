<?php get_header();
	
	echo '<div class="tagline">';
		echo '"<span class="red">Good</span> <span class="green">food</span> = <span class="red">Good</span> <span class="green">mood</span>. Gotta feed the people."';
	echo '</div>';

	if (have_posts()) : while (have_posts()) : the_post();

		$prepHour = get_post_meta( get_the_ID(), 'recipePrep_hours', true );
        $prepMinute = get_post_meta( get_the_ID(), 'recipePrep_minutes', true );
        $cookHour = get_post_meta( get_the_ID(), 'recipeTime_hours', true );
        $cookMinute = get_post_meta( get_the_ID(), 'recipeTime_minutes', true );
        $servingSize = get_post_meta( get_the_ID(), 'recipeServing', true );

        echo '<div class="recipe-image">';
        	the_post_thumbnail();
        echo '</div>';

		echo '<div id="recipe">';

			echo '<div class="recipe-top">';
				the_title('<h1>','</h1>');
				echo '<span class="line"><span></span><span></span><span></span><span></span><span></span></span>';
				echo '<div class="stat">'.$prepHour.':'.$prepMinute.'/'.$cookHour.':'.$cookMinute.'/'.$servingSize.'</div>';
			echo '</div>';

			listIngredients($post->ID);
			listInstructions($post->ID);

			if(has_post_thumbnail()) {
				$postImage = wp_get_attachment_image_url(get_post_thumbnail_id($post->ID), 'large', false ); 
			} else { 
				$postImage = get_bloginfo('template_directory').'/assets/images/default_facebook.jpg'; 
			};

			?>
	    	<div class="social-share">
				<!-- Facebook (url) -->
				<a target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" class="facebook">
				    <i class="fa fa-facebook"></i>
				</a>
				<!-- Twitter (url, text, @mention) -->
				<a class="twitter" target="_blank" href="http://twitter.com/share?url=<?php the_permalink(); ?>&text=Good food = good mood. Gotta feed the people. - &via=thetoastedpost">
				    <i class="fa fa-twitter"></i>
				</a>
				<!-- Pinterest (url) -->
				<a target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php echo $postImage; ?>&description=<?php echo strip_tags(get_the_excerpt()); ?>" class="pinterest" count-layout="horizontal">
				    <i class="fa fa-pinterest"></i>
				</a>
			</div>
	    	<?php echo '<div class="fb-comments" data-href="'.get_the_permalink().'" data-width="100%" data-num-posts="6"></div>';
	    
	    echo '</div>';
	
	endwhile; else:

	    echo '<p>Sorry, no posts matched your criteria.</p>';
	
	endif;

get_footer(); ?>