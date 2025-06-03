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
			
			$map_pdf = get_field( 'map_pdf', 'option' );
			print "<li class=\"display-grid-ref\"><span>$display_grid_ref</span><span>View Map</span><a href=\"{$map_pdf['url']}\" target=\"_blank\"></a></li>";
		}
		print "</ul>";
	print "</div>";