<?php 
	if( $reviews = get_field( "reviews", get_the_ID() ) ) {
		print "<div class='wp-block-course-reviews'>";
			print "<div class='review-slider'>";
				forEach( $reviews as $review ) {
					print "<div class='slide'>";
						print !empty( $review['title'] ) ? "<p><strong>" . $review['title'] . "</strong></p>" : "";
						print $review['review'];
					print "</div>";
				}
			print "</div>";
		print "</div>";
	}
	else {
		print "<div class='no-course-reviews'></div>";
	}