<?php
	global $post;

	$display_number = get_field('display_number', $post->ID);
	$display_grid_ref = get_field('display_grid_ref', $post->ID);


	print "<div class=\"display-location\">";
		print "<ul>";
		if( is_admin() && !$display_number ) {
			print "<li class=\"display-number\">000</li>";
		}	
		if($display_number) {
			print "<li class=\"display-number\">$display_number</li>";
		}
		if($display_grid_ref) {
			print "<li class=\"display-grid-ref\">View Map ($display_grid_ref)</li>";
		}
		print "</ul>";
	print "</div>";