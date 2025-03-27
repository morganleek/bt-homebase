<?php
	global $post;

	// Display Lead Email
	$display_lead_email = get_field('display_lead_email', $post->ID);
	// Display Lead Phone Number
	$display_lead_phone_number = get_field('display_lead_phone_number', $post->ID);
	// Display Lead URL
	$display_lead_url = get_field('display_lead_url', $post->ID);

	print "<div class=\"display-social-links\">";
		print "<button class=\"heart\"></button>";
		if($display_lead_url) {
			print "<button class=\"display-lead-url\"><a href=\"$display_lead_url\" target=\"_blank\"></a></button>";
		}
		print "<button class=\"facebook\"><a href=\"#\"></a></button>";
		print "<button class=\"instagram\"><a href=\"#\"></a></button>";
		if($display_lead_email) {
			print "<button class=\"display-lead-email\"><a href=\"mailto:$display_lead_email\"></a></button>";
		}
		if($display_lead_phone_number) {
			print "<button class=\"display-lead-phone_number\"><a href=\"tel:$display_lead_phone_number\"></a></button>";
		}
	print "</div>";