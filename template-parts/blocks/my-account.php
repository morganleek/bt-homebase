<?php

	add_filter( 'login_form_middle', 'home_base_login_form_middle', 20, 1 );

	function home_base_login_form_middle( $atts ) {
		return '<button>Sign In<svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.16868 4.09641L1.30121 11.0121L0 9.71087L6.91566 2.8193H2V0.987976H10V9.01207H8.16868V4.09641Z" fill="#F9B000"></path></svg></button>';
	}

	function saved_displays($current_user_ID, $display ) {
		// Fetch associated save
		$args = [ 
			'post_type'      => 'save',
			'posts_per_page' => 1,
			'author'         => $current_user_ID,
			'meta_query'     => [ 
				[ 
					'key'     => 'saved',
					'value'   => $display->ID,
					'compare' => 'IN',
				],
			],
		];

		$saved_object = get_posts( $args );
		$save = array_shift( $saved_object );
		$permalink = get_permalink( $display->ID );
		$phone = get_field( 'display_lead_phone_number', $display->ID );
		$email = get_field( 'display_lead_email', $display->ID );
		$url = get_field( 'display_lead_url', $display->ID );
		?>

		<?php if ( get_field( 'salesforce_active', $display->ID ) ) : ?>
			<li class="wp-block-post post-<?php print $display->ID; ?> display type-display status-publish has-post-thumbnail hentry">
				<div
					class="wp-block-group has-global-padding is-layout-constrained wp-block-group-is-layout-constrained"
					style="padding-bottom:var(--wp--preset--spacing--20)">
					<figure style="aspect-ratio:16/9;" class="wp-block-post-featured-image">
						<a href="<?php print $permalink; ?>" target="_self">
							<?php print wp_get_attachment_image( get_post_thumbnail_id( $display->ID ) , "large" ); ?>
						</a>
					</figure>
					<div class="title-wrapper">
						<h4 class="wp-block-post-title">
							<a href="<?php print $permalink; ?>" target="_self"><?php print $display->post_title; ?></a>
						</h4>
						<div class="saved_display_content">
							<ul class="actions">
								<?php if ( $phone ) : ?>
									<li><a href="#call_exhibitor_<?php echo $display->ID; ?>" class="call_now"
											data-display-name="<?php echo $display->post_title; ?>">Call Now</a></li>
								<?php endif; ?>
								<?php if ( $email ) : ?>
									<li><a href="#email_exhibitor_<?php echo $display->ID; ?>" class="email_now"
											data-display-name="<?php echo $display->post_title; ?>">Message Now</a></li>
								<?php endif; ?>
								<?php if ( $url ) : ?>
									<li><a href="<?php echo $url; ?>" target="new" class="visit_website_now"
											data-display-name="<?php echo $display->post_title; ?>">Visit Website</a></li>
								<?php endif; ?>
								<li><a href="#" class="edit_notes" data-post-id="<?php echo $save->ID; ?>">My Notes</a></li>
							</ul>
						</div>
					</div>
					<?php if ( get_field( 'brochure', $display->ID ) ) : ?>
						<div class="wp-block-buttons is-content-justification-center is-layout-flex wp-block-buttons-is-layout-flex">
							<div class="wp-block-button is-style-outline has-icon__external-arrow download">
								<a class="wp-block-button__link wp-element-button brochure brochure_download" href="<?php the_field( 'brochure', $display->ID ); ?>"
								data-display="<?php echo $display->post_title; ?>" data-display-name="<?php echo $display->post_title; ?>"
								target="new">
									Download Brochure
									<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M11.9998 19.5L6.0767 13.5073L7.38733 12.2067L11.0672 15.8534V5.5H12.9323V15.8534L16.6122 12.2067L17.9229 13.5073L11.9998 19.5Z" fill="#F9B000"/>
									</svg>
								</a>
							</div>
						</div>
					<?php endif; ?>
				</div>

				
				
				<?php if ( false ) : // $phone ?>
					<div id="call_exhibitor_<?php echo $display->ID; ?>" class="modal">
						<div class="modal_box">
							<h2>Call <?php echo $display->post_title; ?></h2>
							<p><a href="tel:<?php echo $phone; ?>" data-display-name="<?php echo $display->post_title; ?>"
									class="my_account_call"><?php echo $phone; ?></a></p>
						</div>
					</div>
				<?php endif; ?>
				<?php if ( false ) : // $email ?>
					<div id="email_exhibitor_<?php echo $display->ID; ?>" class="modal">
						<div class="modal_box">
							<h2>Message <?php echo $display->post_title; ?></h2>
							<?php echo do_shortcode( '[contact-form-7 id="11379" destination-email="' . $email . '"]' ); ?>
						</div>
					</div>
				<?php endif; ?>
			</li>
		<?php else : ?>
			<li class="wp-block-post display type-display status-publish has-post-thumbnail hentry">
				<div
					class="wp-block-group has-global-padding is-layout-constrained wp-block-group-is-layout-constrained"
					style="padding-bottom:var(--wp--preset--spacing--20)">
					<figure style="aspect-ratio:16/9;" class="wp-block-post-featured-image">
						<a href="<?php print $permalink; ?>" target="_self">
							<img src="<?php bloginfo( 'template_url' ); ?>/assets/images/ex-display.png" alt="" />
						</a>
					</figure>
					<h4 class="wp-block-post-title">
						<?php print $display->post_title; ?> - No longer avaiable
					</h4>
				</div>
			</li>
		<?php endif;
	}

	function edit_account_form() {
		if ( ! empty( $_GET['updated'] ) ) : ?>
			<div class="account_update_success"><?php _e( 'Profile successfully updated', 'textdomain' ); ?></div>
		<?php endif; ?>
		
		<?php if ( ! empty( $_GET['validation'] ) ) : ?>
		
			<?php if ( $_GET['validation'] == 'emailnotvalid' ) : ?>
				<div class="account_update_error"><?php _e( 'The supplied email address is not valid', 'textdomain' ); ?></div>
			<?php elseif ( $_GET['validation'] == 'emailexists' ) : ?>
				<div class="account_update_error"><?php _e( 'The supplied email address already exists', 'textdomain' ); ?></div>
			<?php elseif ( $_GET['validation'] == 'passwordmismatch' ) : ?>
				<div class="account_update_error"><?php _e( 'The given passwords did not match', 'textdomain' ); ?></div>
			<?php elseif ( $_GET['validation'] == 'unknown' ) : ?>
				<div class="account_update_error">
					<?php _e( 'An unknown error occurred, please try again or contact the website administrator', 'textdomain' ); ?></div>
			<?php endif; ?>
		
		
		<?php endif; ?>
		
		<?php $current_user = wp_get_current_user();
		$myaccount_opt_in = get_the_author_meta( 'myaccount_opt_in', $current_user->ID ); ?>
		
		<?php $current_user_project_type = get_user_meta( $current_user->ID, 'project_type', true );
		$current_user_project_commencing = get_user_meta( $current_user->ID, 'project_commencing', true );
		$current_user_project_value = get_user_meta( $current_user->ID, 'project_value', true ); ?>
		
		
		<form method="post" id="adduser" action="<?php the_permalink(); ?>">
			<h3>Your information</h3>
			<p>
				<label for="first-name"><?php _e( 'First name', 'textdomain' ); ?></label>
				<input class="text-input" name="first-name" type="text" id="first-name"
					value="<?php the_author_meta( 'first_name', $current_user->ID ); ?>" />
			</p>
			<p>
				<label for="last-name"><?php _e( 'Last name', 'textdomain' ); ?></label>
				<input class="text-input" name="last-name" type="text" id="last-name"
					value="<?php the_author_meta( 'last_name', $current_user->ID ); ?>" />
			</p>
			<p>
				<label for="email"><?php _e( 'E-mail', 'textdomain' ); ?></label>
				<input class="text-input" name="email" type="text" id="email"
					value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
			</p>
			<h3>Your project information</h3>
			<p>
				<?php $project_types = [ 'Building', 'Renovating', 'Owner-Building', 'Purchasing', 'No project planned' ]; ?>
				<label for="project_type">Project Type</label>
				<select name="project_type" id="project_type">
					<option value="">-- Please select --</option>
					<?php if ( ! in_array( $current_user_project_type, $project_types ) ) {
						$current_user_project_type == 'No project planned';
					} ?>
					<?php foreach ( $project_types as $project_type ) : ?>
						<?php $selected = ( $current_user_project_type == $project_type ) ? 'selected' : ''; ?>
						<?php echo '<option value="' . $project_type . '"' . $selected . ' data-attr="' . $current_user_project_type . '">' . $project_type . '</option>'; ?>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<?php $project_commencings = [ 'Immediately', 'Within 6 months', '6 to 12 months', '12 months plus', 'No project planned' ]; ?>
				<label for="project_commencing">Project Commencing</label>
		
				<select name="project_commencing" id="project_commencing">
					<option value="">-- Please select --</option>
		
					<?php foreach ( $project_commencings as $project_commencing ) : ?>
						<?php $selected = ( $current_user_project_commencing == $project_commencing ) ? 'selected' : ''; ?>
						<?php echo '<option value="' . $project_commencing . '"' . $selected . '>' . $project_commencing . '</option>'; ?>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label for="project_value">Estimated Project Value: $<span
						id="projectvalue_value"><?php echo $current_user_project_value; ?></span></label>
				<?php $v = $current_user_project_value > 0 ? $current_user_project_value : '2'; ?>
				<input name="project_value" type="range" min="0" max="2000000" value="<?php echo $v; ?>"
					class="projectvalue_slider" id="project_value" step="25000">
				<style>
					/* The slider itself */
					.projectvalue_slider {
						margin-top: 10px;
						-webkit-appearance: none;
						width: 100%;
						height: 15px;
						border-radius: 5px;
						background: #d3d3d3;
						outline: none;
						opacity: 0.7;
						-webkit-transition: .2s;
						transition: opacity .2s;
					}
		
					/* Mouse-over effects */
					.projectvalue_slider:hover {
						opacity: 1;
						/* Fully shown on mouse-over */
					}
		
					/* The slider handle (use -webkit- (Chrome, Opera, Safari, Edge) and -moz- (Firefox) to override default look) */
					.projectvalue_slider::-webkit-slider-thumb {
						-webkit-appearance: none;
						appearance: none;
						width: 25px;
						height: 25px;
						border-radius: 50%;
						background: #fdc800;
						cursor: pointer;
					}
		
					.projectvalue_slider::-moz-range-thumb {
						width: 25px;
						height: 25px;
						border-radius: 50%;
						background: #fdc800;
						cursor: pointer;
					}
				</style>
				<script>
					var slider = document.getElementById("project_value");
					var output = document.getElementById("projectvalue_value");
					output.innerHTML = slider.value;
		
		
					slider.oninput = function () {
						output.innerHTML = this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
					}
				</script>
			</p>
		
		
			<p style="display: none">
				<label for="myaccount_opt_in">Would you like to hear from Home Base and our Exhibitors?</label>
				<select name="myaccount_opt_in" id="myaccount_opt_in">
					<option value="Yes" <?php if ( $myaccount_opt_in == "Yes" )
						echo 'selected="selected"'; ?>>Yes please</option>
					<option value="No" <?php if ( $myaccount_opt_in == "No" )
						echo 'selected="selected"'; ?>>No thanks</option>
				</select>
			</p>
		
			<p class="form-submit">
				<input name="updateuser" type="submit" id="updateuser" class="submit button"
					value="<?php _e( 'Update profile', 'textdomain' ); ?>" />
				<?php wp_nonce_field( 'update-user' ) ?>
				<input name="honey-name" value="" type="text" style="display:none;"></input>
				<input name="action" type="hidden" id="action" value="update-user" />
			</p>
		</form>
		<?php
	}

	if ( $_SERVER['REQUEST_METHOD'] == 'POST' && ! empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {
		$current_user = wp_get_current_user();
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user' ) ) {
			wp_redirect( get_permalink() . '?validation=unknown#profile' );
			exit;
		}
	
		if ( ! empty( $_POST['honey-name'] ) ) {
			wp_redirect( get_permalink() . '?validation=unknown#profile' );
			exit;
		}
	
		if ( ! empty( $_POST['email'] ) ) {
			$posted_email = esc_attr( $_POST['email'] );
			if ( ! is_email( $posted_email ) ) {
				wp_redirect( get_permalink() . '?validation=emailnotvalid#profile' );
				exit;
			} elseif ( email_exists( $posted_email ) && ( email_exists( $posted_email ) != $current_user->ID ) ) {
				wp_redirect( get_permalink() . '?validation=emailexists#profile' );
				exit;
			} else {
				wp_update_user( [ 
				'ID'         => $current_user->ID,
				'user_email' => $posted_email,
			] );
			}
		}
	
		if ( ! empty( $_POST['pass1'] ) || ! empty( $_POST['pass2'] ) ) {
			switch ( $_POST['pass1'] ) {
				case $_POST['pass2']:
					wp_update_user( array(
						'ID'        => $current_user->ID,
						'user_pass' => esc_attr( $_POST['pass1'] ),
					) );
					break;
				default:
					wp_redirect( get_permalink() . '?validation=passwordmismatch#profile' );
					exit;
			}
		}
	
		if ( ! empty( $_POST['first-name'] ) ) {
			update_user_meta( $current_user->ID, 'billing_first_name', esc_attr( $_POST['first-name'] ) );
			update_user_meta( $current_user->ID, 'first_name', esc_attr( $_POST['first-name'] ) );
		}
	
		if ( ! empty( $_POST['last-name'] ) ) {
			update_user_meta( $current_user->ID, 'billing_last_name', esc_attr( $_POST['last-name'] ) );
			update_user_meta( $current_user->ID, 'last_name', esc_attr( $_POST['last-name'] ) );
		}
	
		// Project Data
	
		if ( ! empty( $_POST['project_type'] ) ) {
			update_user_meta( $current_user->ID, 'project_type', sanitize_text_field( $_POST['project_type'] ) );
		}
	
		if ( ! empty( $_POST['project_commencing'] ) ) {
			update_user_meta( $current_user->ID, 'project_commencing', sanitize_text_field( $_POST['project_commencing'] ) );
		}
	
		update_user_meta( $current_user->ID, 'project_value', sanitize_text_field( $_POST['project_value'] ) );
		update_user_meta( $current_user->ID, 'myaccount_opt_in', esc_attr( $_POST['myaccount_opt_in'] ) );
	
		wp_redirect( get_permalink() . '?updated=true#profile' );
		exit;	
	}

	// Set up logged in user

	if ( is_user_logged_in() ) {
		$current_user = wp_get_current_user();
		$current_user_ID = $current_user->ID;
	
		// Determine if current user profile is complete
	
		$current_user_project_type = get_user_meta( $current_user_ID, 'project_type', true );
		$current_user_project_commencing = get_user_meta( $current_user_ID, 'project_commencing', true );
	
		//$projectinfoincomplete = ( empty($current_user_project_type) || empty($current_user_project_commencing) ) ? 1 : 0;
		$projectinfoincomplete = 0;
	
		// add default collections if none exist
		homebase_seed_collections();
	
		$displays = [];
		$articles = [];
		$saved_ids = [];
	
		$args = [ 
			'post_type'      => 'save',
			'author'         => $current_user_ID,
			'posts_per_page' => -1
		];
	
		$saves = get_posts( $args );
	
		foreach ( $saves as $save ) {
			$saved = get_post_meta( $save->ID, 'saved', true );
			array_push( $saved_ids, $saved );
		}
	
		if ( $saved_ids ) {
	
			$displays = get_posts( [ 
				'post_type'      => array( 'display' ),
				'post__in'       => $saved_ids,
				'posts_per_page' => -1
			] );
	
			$articles = get_posts( [ 
				'post_type'      => [ 'post' ],
				'post__in'       => $saved_ids,
				'posts_per_page' => -1
			] );
	
		}
	
		// Course Kits - fetch all kits available to current user
	
		$today = date( 'Ymd' );
	
		$course_kit_args = [ 
			'post_type'      => 'course-kit',
			'meta_query'     => [ 
				[ 
					'key'     => 'availability_date',
					'compare' => '<=',
					'value'   => $today,
				],
				[ 
					'key'     => 'attendees',
					'compare' => 'LIKE',
					'value'   => $current_user->user_email // current user email
				],
			],
			'orderby'        => 'meta_value_num',
			'order'          => 'DESC',
			'posts_per_page' => -1
		];
	
		$course_kits = get_posts( $course_kit_args );
	}
?>

<?php if ( is_user_logged_in() ) : ?>
		<div
			class="wp-block-group alignfull has-brand-dust-background-color has-background has-global-padding is-layout-constrained wp-block-group-is-layout-constrained"
			style="margin-top:0;margin-bottom:0;padding-bottom:var(--wp--preset--spacing--80)">
			<div
				class="wp-block-group alignwide is-style-overlap has-white-background-color has-background has-global-padding is-layout-constrained wp-block-group-is-layout-constrained"
				style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
				<div class="wp-block-group profile-menu has-black-color has-text-color has-link-color is-content-justification-center is-nowrap is-layout-flex wp-block-group-is-layout-flex">
					<p><a href="#collections">Collections</a></p>
					<p><a href="#displays">Displays</a></p>
					<p><a href="#courses">Courses</a></p>
					<p><a href="#posts">Blog</a></p>
					<!-- <p><a href="#profile">Profile</a></p> -->
				</div>
			</div>

			<div
				class="wp-block-group alignwide is-style-default has-global-padding is-layout-constrained wp-block-group-is-layout-constrained"
				id="our-centre">
				<div class="my_account_tab wp-block-group alignwide" id="collections">
					<h3>Collections</h3>
					<div class="wp-block-group alignwide has-white-background-color has-background has-global-padding is-layout-constrained wp-block-group-is-layout-constrained" style="border-radius:24px;padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--30)"></div>
				</div>
				<div class="my_account_tab wp-block-group alignwide" id="displays">
					<h3>Displays</h3>
					<?php if ( $displays ) : ?>
						<div class="wp-block-query is-layout-flow wp-block-query-is-layout-flow">
							<ul class="columns-2 wp-block-post-template is-layout-flow wp-block-post-template-is-layout-flow">
								<?php foreach ( $displays as $display ) : ?>
									<?php saved_displays($current_user_ID, $display) ?>
								<?php endforeach; ?>
								<?php wp_reset_postdata(); ?>
							</ul>
						</div>
					<?php else : ?>
						<div class="my_account_empty">
							<p>You have no displays saved yet. Browse <a href="/displays">Displays</a></p>
						</div>
					<?php endif; ?>
				</div>
				<div class="my_account_tab wp-block-group alignwide" id="courses">
					<h3>Courses</h3>
					<?php if ( $course_kits ) : ?>
						<?php foreach ( $course_kits as $course_kit ) : ?>
							<?php 
								$downloads = get_field( 'downloads', $course_kit->ID ); ?>
								<?php if ( have_rows( 'downloads', $course_kit->ID ) ) : ?>
									
									<?php while ( have_rows( 'downloads', $course_kit->ID ) ) :
												the_row(); ?>
										<div class="course_kit">
											<?php if ( get_sub_field( 'pdf' ) ) : ?>
												<?php $thumbnail = get_sub_field( 'thumbnail' );
												$thumb = $thumbnail['sizes']['thumbnail']; ?>
													<img src="<?php print $thumbnail['sizes']['medium']; ?>" alt="<?php the_sub_field( 'title' ); ?>" />
													<div>
														<h4><?php the_sub_field( 'title' ); ?></h4>
														<p><?php the_sub_field( 'description' ); ?></p>
													</div>
													<div>

														<div class="wp-block-buttons is-content-justification-center is-layout-flex wp-block-buttons-is-layout-flex" style="column-gap: 10px;">
															<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19 23"><path d="M13.200195,22.5h-6.400391c-1.837891,0-2.760254,0-3.615723-.436523-.756836-.384766-1.361816-.989258-1.748047-1.74707-.436035-.856445-.436035-1.779297-.436035-3.616211v-.700195c0-.552734.447754-1,1-1s1,.447266,1,1v.700195c0,1.469727,0,2.279297.218262,2.708008.192871.378906.495117.680664.873047.873047.428711.21875,1.238281.21875,2.708496.21875h6.400391c1.469727,0,2.279297,0,2.708008-.21875.378906-.192383.680664-.494141.873047-.87207.21875-.429688.21875-1.239258.21875-2.708984V6.299805c0-1.470215,0-2.279785-.217773-2.70752-.193359-.378906-.495117-.681152-.874023-.874023-.428711-.218262-1.238281-.218262-2.708008-.218262h-2.700195c-.552246,0-1-.447754-1-1s.447754-1,1-1h2.700195c1.836914,0,2.759766,0,3.616211.436035.757812.38623,1.362305.991211,1.748047,1.748535.435547.85498.435547,1.777344.435547,3.615234v10.400391c0,1.836914,0,2.759766-.435547,3.616211-.385742.757812-.990234,1.362305-1.749023,1.748047-.855469.435547-1.77832.435547-3.615234.435547ZM14,17.5H6c-.552246,0-1-.447266-1-1s.447754-1,1-1h8c.552734,0,1,.447266,1,1s-.447266,1-1,1ZM14,13.5h-4.5c-.552246,0-1-.447266-1-1,0-.552246.447754-1,1-1h4.5c.552734,0,1,.447754,1,1,0,.552734-.447266,1-1,1ZM4,13.5c-2.205566,0-4-1.794434-4-4v-4c0-.552246.447754-1,1-1s1,.447754,1,1v4c0,1.103027.896973,2,2,2s2-.896973,2-2v-5.5c0-.275879-.224121-.5-.5-.5s-.5.224121-.5.5v5.5c0,.552246-.447754,1-1,1s-1-.447754-1-1v-5.5c0-1.378418,1.121582-2.5,2.5-2.5s2.5,1.121582,2.5,2.5v5.5c0,2.205566-1.794434,4-4,4ZM14,9.5h-3.5c-.552246,0-1-.447754-1-1s.447754-1,1-1h3.5c.552734,0,1,.447754,1,1s-.447266,1-1,1Z" style="fill:#38332f;"/></svg>
															<div class="wp-block-button is-style-outline has-icon__external-arrow download">
																<a class="wp-block-button__link wp-element-button brochure brochure_download" href="<?php the_sub_field( 'pdf' ); ?>" target="new">
																	Download PDF
																	<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M11.9998 19.5L6.0767 13.5073L7.38733 12.2067L11.0672 15.8534V5.5H12.9323V15.8534L16.6122 12.2067L17.9229 13.5073L11.9998 19.5Z" fill="#F9B000"></path>
																	</svg>
																</a>
															</div>
														</div>
													</div>
												
											<?php endif; ?>
										</div>
									<?php endwhile; ?>
										
								<?php endif;	
							?>
						<?php endforeach; ?>

						<?php wp_reset_postdata(); ?>
					<?php else : ?>
						<div class="my_account_empty">
							<p>You have no course downloads available yet. If you have recently enrolled in a Home Base <a
									href="/courses">course</a>, your downloads will be available here soon.</p>
						</div>
					<?php endif; ?>
				</div>
				<div class="my_account_tab wp-block-group alignwide" id="posts">
					<h3>Blog</h3>
					<?php if ( $articles ) : ?>
						<div class="wp-block-query is-layout-flow wp-block-query-is-layout-flow">
							<ul class="columns-2 wp-block-post-template is-layout-flow wp-block-post-template-is-layout-flow">
								<?php foreach ( $articles as $article ) : ?>
									<li class="wp-block-post post-<?php print $article->ID; ?> post type-post status-publish format-standard has-post-thumbnail hentry category-blog tag-architect tag-design-ideas tag-designer tag-home-design tag-luxury-homes tag-new-build tag-perth-designers">
										<div class="wp-block-group has-global-padding is-layout-constrained wp-block-group-is-layout-constrained" style="padding-bottom:var(--wp--preset--spacing--20)">
											<figure style="aspect-ratio:16/9;" class="wp-block-post-featured-image">
												<a href="<?php the_permalink( $article->ID ); ?>" target="_self">
													<?php print get_the_post_thumbnail( $article, "large" ); ?>
												</a>
											</figure>
		
											<div class="wp-block-group has-global-padding is-layout-constrained wp-block-group-is-layout-constrained" style="padding-right:8px;padding-left:8px">
												<div class="wp-block-group has-global-padding is-layout-constrained wp-block-group-is-layout-constrained">
													<div style="font-size:13px;" class="has-link-color wp-block-post-date has-text-color has-brand-charcoal-color">
														<time datetime="<?php print $article->post_date; ?>"><?php print get_the_date( 'j M, Y', $article->ID ); ?></time>
													</div>
		
													<div class="has-link-color wp-elements-5e8c8a684c2d35a3a46c96815f25414c wp-block-post-author has-text-color has-brand-yellow-color">
														<div class="wp-block-post-author__content">
															<p class="wp-block-post-author__name"><?php print get_the_author_meta( 'display_name', $article->post_author ); ?></p>
														</div>
													</div>
												</div>
		
												<h3 class="wp-block-post-title">
													<a href="<?php the_permalink( $article->ID ); ?>" target="_self"><?php print $article->post_title; ?></a>
												</h3>
		
												<div class="wp-block-post-excerpt">
													<p class="wp-block-post-excerpt__excerpt"><?php print get_the_excerpt( $article ); ?></p>
												</div>

											</div>
											<div class="wp-block-buttons is-content-justification-center is-layout-flex wp-block-buttons-is-layout-flex">
												<div class="wp-block-button is-style-outline has-icon__external-arrow">
													<a class="wp-block-button__link wp-element-button" href="<?php print get_permalink( $article->ID ); ?>">
														Read blog
														<svg viewBox="0 0 10 12" xmlns="http://www.w3.org/2000/svg"><path d="M8.16868 4.09641L1.30121 11.0121L0 9.71087L6.91566 2.8193H2V0.987976H10V9.01207H8.16868V4.09641Z"></path></svg>
													</a>
												</div>
											</div>
										</div>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php else : ?>
						<div class="my_account_empty">
							<p>You have no blog posts saved. Visit <a href="/the-loft">The Loft</a> to get started.</p>
						</div>
					<?php endif; ?>
				</div>
				<div class="my_account_tab wp-block-group alignwide <?php echo $projectinfoincomplete ? 'active' : ''; ?>" id="profile" style="display: none;">
					<?php if ( $projectinfoincomplete ) : ?>
						<div class="account_update_error">Please complete your project information</div>
					<?php endif; ?>
					<?php edit_account_form(); ?>
				</div>
				
			</div>





		</div>
	<?php else : ?>
		<div class="wp-block-group alignfull has-brand-dust-background-color has-background has-global-padding is-layout-constrained wp-container-core-group-is-layout-11 wp-block-group-is-layout-constrained" style="margin-top:0;margin-bottom:0;padding-bottom:var(--wp--preset--spacing--40)">
			<div class="wp-block-group is-style-overlap has-white-background-color has-background has-global-padding is-layout-constrained wp-container-core-group wp-block-group-is-layout-constrained" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
				<div class="homebase_my_account_auth_prompt">
					<?php 
						if ( isset( $_GET['login'] ) && $_GET['login'] == 'failed' ) {
							echo "<p class='login_unsuccessful'>Login unsuccessful. Please try again or <a href=\"" .  get_bloginfo( 'siteurl' ) . "/wp-login.php?action=lostpassword\">reset your password</a>.</p>";
						}
					?>
					<?php wp_login_form( [ 
						'redirect' => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
						'form_id'  => 'my-account-loginform',
						'remember' => false,
					] ); ?>
		
					<div class="account-create-reset">
						<p><a class="reset-password" href="<?php bloginfo( 'url' ); ?>/wp-login.php?action=lostpassword">Reset password</a></p>
						<p>No Account? <a href="<?php bloginfo( 'url' ); ?>/wp-login.php?action=register">Create one</a></p>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>