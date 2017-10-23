<?php 
		echo '<footer></footer>';

	// end bodyWrap
	echo '</div>';

	echo '<section id="contact" class="btn-modal" data-modal="contact">';
		echo '<i class="fa fa-envelope-o"></i>';
	echo '</section>';

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
						$total = count($tags);
						$count = 1;
						foreach($tags as $tag) {
							$term = get_term_by('name',$tag, 'ingredients');
							echo '<a href="'.get_term_link($term->term_taxonomy_id).'" data-term="'.$term->term_taxonomy_id.'" >'.$term->name.'</a>';
							if($count !== $total) {
								echo ' &bull; ';
							}
							$count++;
						}
					}
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</section>';

	echo '<section class="modal" data-modal="category">';
		echo '<i class="fa fa-close"></i>';
		echo '<div class="outer">';
			echo '<div class="inner">';
				$args = array(
				    'taxonomy' => 'category',
				    'post_type' => 'recipes',
				    'hide_empty' => false,
				);
				$terms = get_terms($args);
				if(!empty($terms)) {
					echo '<a href="'.get_site_url().'" data-term="0">All Recipes</a>';
					foreach($terms as $term) {
						if($term->count > 0) { 
							echo '<a href="'.get_term_link($term->term_taxonomy_id).'" data-term="'.$term->term_taxonomy_id.'" >'.$term->name.'</a>';
						}
					}
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