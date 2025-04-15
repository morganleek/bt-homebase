<?php
	global $post;

	print "<div class=\"display-save\">";
		if( is_user_logged_in() ) {
			print "<button class=\"my-account logged-in\"><a href=\"/my-account\"></a></button>";
		}
		else {
			print "<button class=\"my-account\"><a href=\"/my-account\">Log into My Account</a></button>";
		}
		homebase_save_button( $post->ID, "post" );
		print "<button class=\"bookmark\" data-post-title=\"" . $post->post_title . "\" data-post-url=\"" . get_permalink($post->ID) . "\"></button>";
	print "</div>";
	// print "<button class=\"forward\"></button>";