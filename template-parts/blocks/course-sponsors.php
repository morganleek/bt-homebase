<?php
	global $post;

	$sponsors = get_field( 'sponsors', $post->ID ); 

	if( $sponsors ) {
		print "<div class=\"wp-block-course-sponsors wp-block-group alignwide is-layout-flow wp-block-group-is-layout-flow\" style=\"border-radius:16px;margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--30)\">";
			print "<h4 class=\"wp-block-heading\">This course is proudly sponsored by</h4>";
			print "<ul class=\"sponsors-list\">";
				foreach( $sponsors as $sponsor ) {
					print "<li>";	
						print isset( $sponsor['link']['url'] ) ? "<a href=\"" . $sponsor['link']['url'] . "\" target=\"" . $sponsor['link']['target'] . "\">" : "";
							print wp_get_attachment_image( $sponsor['sponsor']['ID'], 'large' );
						print isset( $sponsor['link']['url'] ) ? "</a>" : "";
						// print "<pre>" . print_r( isset( $sponsor['link']['url'] ), true ) . "</pre>";
						// print "<pre>" . print_r( $sponsor, true ) . "</pre>";
					print "</li>";
				}
			print "</ul>";
		print "</div>";
	}