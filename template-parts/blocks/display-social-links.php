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
			print "<a class=\"display-lead-phone_number\" data-phone=\"$display_lead_phone_number\" data-id=\"{$post->ID}\"></a>";
			print "<dialog class=\"display-lead-phone-modal\" data-id=\"{$post->ID}\">
				<button class=\"close-dialog\">Close</button>
				<h4 style=\"text-align: center;\">Call $display_lead_phone_number</h4>
			</dialog>";
		}
		if($display_lead_email) {
			print "<a href=\"#email_exhibitor_{$post->ID}\" class=\"display-lead-email\" data-display-name=\"{$post->post_title}\" data-display-email=\"$display_lead_email\" data-id=\"{$post->ID}\"></a>";
			print "<dialog class=\"display-email-modal\">";
				print "<button class=\"close-dialog\">Close</button>";
				print "<h4>Message <span class=\"post-title\">{$post->post_title}</span></h4><br />";

				// $regex = "/(id=\"field_supplier__field\")(.*?)(value=\"\")/i";
				$form = FrmFormsController::get_form_shortcode( [ 'id' => 2 ] );
				// $form = preg_replace( $regex, '\1\2value="' . $display_lead_email . '"', $form );
				print $form;
			print "</dialog>";
		}
		if($display_lead_url) {
			print "<a class=\"display-lead-url\" target=\"_blank\" href=\"$display_lead_url\" title=\"" . get_the_title() . "\"></a>";
		}
		// print "<a class=\"facebook reaction_facebook\" target=\"_blank\" href=\"http://www.facebook.com/sharer.php?u=" .get_permalink() . "&amp;t=" . get_the_title() . "\" title=\"Share on Facebook\"></a>";
		// print "<a class=\"instagram\" href=\"#\"></a>";

	print "</div>";


