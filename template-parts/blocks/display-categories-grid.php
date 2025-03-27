<?php
	
	$terms = get_terms( array(
    'taxonomy'   => 'display-category',
    'hide_empty' => false,
	) );

	print "<div class=\"wp-block-display-categories-grid\">";
		print "<ul>";
			foreach( $terms as $term ) {
				$link = get_term_link( $term );
				print "<li>";
					print "<div class=\"wp-block-group\">";
						print "<figure style=\"aspect-ratio:16/9;\" class=\"wp-block-post-featured-image\">";
							print "<a href=\"$link\" target=\"_self\">";
								// Image
							print "</a>";
						print "</figure>";
						print "<h3><a href=\"$link\" target=\"_self\">{$term->name}</a></h3>";
					print "</div>";
				
				print "</li>";
			}
		print "</ul>";
	print "</div>";