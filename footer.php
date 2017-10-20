<?php 
		echo '<footer>';
			echo '<section id="contact" class="btn-modal" data-modal="contact">';
				echo '<i class="fa fa-envelope-o"></i>';
			echo '</section>';
		echo '</footer>';

	// end bodyWrap
	echo '</div>';

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

	echo '<section class="modal" data-modal="categories">';
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

	echo '<section class="modal single-recipes">';
		echo '<i class="fa fa-close"></i>';
	echo '</section>';
    
	echo '</body>';
echo '</html>';

wp_footer(); ?>  