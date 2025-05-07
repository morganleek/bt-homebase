<?php
	if( is_admin() ) {
		print "<div class=\"wp-block-home-base-site-notice\">";
				print "<p style='text-align: center;'>UPDATES: Text goes here. Can run to two lines if necessary. This gives more space for important centre updates.</p>";
			print "</div>";
	}
	else if( $site_notice = get_field( 'site_notice', 'options' ) ) {
		if( isset( $site_notice['show_notice'] ) && !empty( $site_notice['show_notice'] ) ) {
			print "<div class=\"wp-block-home-base-site-notice\">";
				print $site_notice['notice'];
			print "</div>";
		}
	}
