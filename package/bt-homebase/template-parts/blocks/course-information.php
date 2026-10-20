<?php
	$terms = get_the_terms( get_the_ID(), 'product_cat' );
	if( $terms !== false ) {
		// Check is a course
		$terms_filtered = array_filter( $terms, fn( $t ) => $t->slug === "courses" );
		if( count( $terms_filtered) > 0 ) {
			?>
				<?php if( $course_structure = get_field('column_one', get_the_ID()) ) { ?>
					<div class="wp-block-course-information-structure wp-block-group alignwide has-white-background-color has-background is-layout-flow wp-block-group-is-layout-flow" style="border-radius:0 0 16px 16px;margin-top:0;margin-bottom:var(--wp--preset--spacing--30);padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
						<h3 class="wp-block-heading">What You’ll Learn</h3>
						<div><?php print $course_structure; ?></div>
					</div>
				<?php } ?>
				<div class="wp-block-course-information-details wp-block-group alignwide has-white-background-color has-background is-layout-flow wp-block-group-is-layout-flow" style="border-radius:16px;margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
					<div class="wp-block-course-information-column">
						<h3 class="wp-block-heading">Details</h3>
						<div class="tabular">
							<?php
								if( $course_duration = get_field('course_duration', get_the_ID()) ) {
									print "<div>
										<h5>Duration</h5>
										<p>$course_duration</p>
									</div>";
								}
	
								if( $course_time = get_field('course_time' , get_the_ID()) ) {
									print "<div>
										<h5>Time</h5>
										<p>$course_time</p>
									</div>";
								}
	
								if( $course_location = get_field('course_location', get_the_ID()) ) {
									print "<div>
										<h5>Location</h5>
										<p>$course_location</p>
									</div>";
								}
	
								if( $course_cost = get_field('course_cost', get_the_ID()) ) {
									print "<div>
										<h5>Cost</h5>
										<p>$course_cost</p>
									</div>";
								}
	
								if( $course_payment_options = get_field('course_payment_options', get_the_ID()) ) {
									print "<div>
										<h5>Payment Options</h5>
										<p>$course_payment_options</p>
									</div>";
								}
							?>
						</div>
					</div>
					<div class="wp-block-course-information-column">
						<h3>Your Presenter</h3>
						<div class="presenter-wrapper">
							<?php if( $presenter_name = get_field('presenter_name', get_the_ID()) ) { ?>
								<?php if( $presenter_image = get_field('presenter_image', get_the_ID()) ) { ?>
									<img src="<?php print $presenter_image['sizes']['thumbnail']; ?>" alt="<?php print $presenter_name; ?>" />
								<?php } ?>
								<h4><?php print $presenter_name; ?></h4>
							<?php } ?>
						</div>
						<?php if( $presenter_bio = get_field('presenter_bio', get_the_ID()) ) { ?>
							<div>
								<?php print $presenter_bio; ?>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php
		}
	}


	