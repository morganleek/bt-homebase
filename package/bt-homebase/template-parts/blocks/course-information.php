<?php
	$terms = get_the_terms( get_the_ID(), 'product_cat' );
	if( $terms !== false ) {
		// Check is a course
		$terms_filtered = array_filter( $terms, fn( $t ) => $t->slug === "courses" );
		if( count( $terms_filtered) > 0 ) {
			?>
				<div class="wp-block-course-information wp-block-group alignwide has-white-background-color has-background has-global-padding is-layout-constrained wp-block-group-is-layout-constrained" style="margin-top:0;margin-bottom:0">
					<div class="wp-block-group alignwide has-brand-dust-background-color has-background is-layout-flow wp-block-group-is-layout-flow" style="border-top-left-radius:24px;border-top-right-radius:24px;border-bottom-left-radius:0px;border-bottom-right-radius:0px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
						<h3 class="wp-block-heading">Course information</h3>
					</div>
				</div>
				<?php if( $course_structure = get_field('column_one', get_the_ID()) ) { ?>
					<div class="wp-block-course-information-structure wp-block-group alignwide has-white-background-color has-background is-layout-flow wp-block-group-is-layout-flow" style="border-radius:16px;margin-top:0;margin-bottom:var(--wp--preset--spacing--30);padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
						<h3 class="wp-block-heading">Structure</h3>
						<div><?php print $course_structure; ?></div>
					</div>
				<?php } ?>
				<div class="wp-block-course-information-details wp-block-group alignwide has-white-background-color has-background is-layout-flow wp-block-group-is-layout-flow" style="border-radius:16px;margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
					<h3 class="wp-block-heading">Details</h3>
					<div>
						<?php
							if( $course_duration = get_field('course_duration', get_the_ID()) ) {
								print "<h5>Duration</h5>";
								print "<p>$course_duration</p>";
							}

							if( $course_time = get_field('course_time' , get_the_ID()) ) {
								print "<h5>Time</h5>";
								print "<p>$course_time</p>";
							}

							if( $course_location = get_field('course_location', get_the_ID()) ) {
								print "<h5>Location</h5>";
								print "<p>$course_location</p>";
							}

							if( $course_cost = get_field('course_cost', get_the_ID()) ) {
								print "<h5>Cost</h5>";
								print "<p>$course_cost</p>";
							}

							if( $course_payment_options = get_field('course_payment_options', get_the_ID()) ) {
								print "<h5>Payment Options</h5>";
								print "<p>$course_payment_options</p>";
							}
						?>
					</div>
				</div>
			<?php
		}
	}


	