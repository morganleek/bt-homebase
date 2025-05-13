<?php
	global $post;

	// Display Lead Email
	$display_lead_email = get_field('display_lead_email', $post->ID);
	// Display Lead Phone Number
	$display_lead_phone_number = get_field('display_lead_phone_number', $post->ID);

	$display_lead_url = get_field('display_lead_url', $post->ID);
	// Display Lead URL
	// $display_lead_url = get_field('display_lead_url', $post->ID);

	print "<div class=\"display-social-links\">";
		if($display_lead_phone_number) {
			print "<a class=\"display-lead-phone_number\" href=\"tel:$display_lead_phone_number\"></a>";
		}
		if($display_lead_email) {
			print "<a class=\"display-lead-email\" href=\"mailto:$display_lead_email\"></a>";
		}
		if($display_lead_url) {
			print "<a class=\"display-lead-url\" target=\"_blank\" href=\"$display_lead_url\" title=\"" . get_the_title() . "\"></a>";
		}
		// print "<a class=\"facebook reaction_facebook\" target=\"_blank\" href=\"http://www.facebook.com/sharer.php?u=" .get_permalink() . "&amp;t=" . get_the_title() . "\" title=\"Share on Facebook\"></a>";
		// print "<a class=\"instagram\" href=\"#\"></a>";

	print "</div>";


