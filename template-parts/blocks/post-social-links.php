
<?php
	global $post;

	print "<div class=\"display-social-links\">";
		if( is_user_logged_in() ) {
			homebase_save_button( $post->ID, "post" );
		}
	print "</div>";