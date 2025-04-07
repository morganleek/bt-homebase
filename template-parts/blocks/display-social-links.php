<?php
	global $post;

	// Display Lead Email
	$display_lead_email = get_field('display_lead_email', $post->ID);
	// Display Lead Phone Number
	$display_lead_phone_number = get_field('display_lead_phone_number', $post->ID);
	// Display Lead URL
	$display_lead_url = get_field('display_lead_url', $post->ID);

	print "<div class=\"display-social-links\">";
		homebase_save_button( $post->ID, "display" );
		if( is_user_logged_in() ) {
			print "<button class=\"display-brochure\" data-post-id=\"{$post->ID}\"></button>";
		}
		else {
			print "<a href=\"/my-account\" class=\"display-brochure logged-out\" data-post-id=\"{$post->ID}\"></a>";
		}
		// print "<button class=\"facebook\"><a href=\"#\"></a></button>";
		print "<button class=\"facebook\"><a class=\"reaction_facebook\" target=\"_blank\" href=\"http://www.facebook.com/sharer.php?u=" .get_permalink() . "&amp;t=" . get_the_title() . "\" title=\"Share on Facebook\"></a></button>";
		print "<button class=\"instagram\"><a href=\"#\"></a></button>";

		if($display_lead_email) {
			print "<button class=\"display-lead-email\"><a href=\"mailto:$display_lead_email\"></a></button>";
		}
		if($display_lead_phone_number) {
			print "<button class=\"display-lead-phone_number\"><a href=\"tel:$display_lead_phone_number\"></a></button>";
		}
	print "</div>";