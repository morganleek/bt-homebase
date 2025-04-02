<?php
	$terms = get_terms( array(
		'taxonomy'   => 'post_tag',
		'hide_empty' => false,
	) );

	print "<div class=\"wp-block-tags-selector\">";
		print "<ul>";
			foreach( $terms as $term ) {	
				print "<li>";
					print "<a href=\"#\" data-term_id=\"{$term->term_id}\">";
						print $term->name;
					print "</a>";
				print "</li>";
			}
		print "</ul>";
	print "</div>";