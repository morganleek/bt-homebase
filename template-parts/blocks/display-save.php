<?php
	global $post;

	print "<div class=\"display-save\">";
		if( is_user_logged_in() ) {
			print "<button class=\"my-account logged-in\"><a href=\"/my-account\"></a></button>";
		}
		else {
			print "<button class=\"my-account\"><a href=\"/my-account\">Log into My Account</a></button>";
		}
		homebase_save_button( $post->ID, "display" );
		if( is_user_logged_in() ) {
			print "<button class=\"display-brochure\" title=\"Save Brochure\" data-post-id=\"{$post->ID}\"></button>";
		}
		else {
			print "<a href=\"/my-account\" class=\"display-brochure logged-out requires-login\" data-post-id=\"{$post->ID}\"></a>";
		}
	print "</div>";