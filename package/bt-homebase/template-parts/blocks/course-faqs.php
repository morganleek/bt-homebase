<?php 
	if( $faqs = get_field( "faqs", get_the_ID() ) ) {
		print "<div class='wp-block-course-faqs'>";
			forEach( $faqs as $faq ) {
				print "<details>";
					print "<summary><strong>" . $faq['question'] . "</strong></summary>";
					print $faq['answer'];
				print "</details>";
			}
		print "</div>";
	}