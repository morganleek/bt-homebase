<?php
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
		$thumb = get_the_post_thumbnail_url( $display->ID, 'search' );
		$phone = get_field( 'display_lead_phone_number', $display->ID );
		$email = get_field( 'display_lead_email', $display->ID );
		$url = get_field( 'display_lead_url', $display->ID );
		?>

		<?php if ( get_field( 'salesforce_active', $display->ID ) ) : ?>
			<li>
				<a class="thumb" href="<?php echo $permalink; ?>" style="background-image:url(<?php echo $thumb; ?>); "></a>
				<div class="saved_display_content">
					<h2><a href="<?php echo $permalink; ?>"><?php echo $display->post_title; ?></a></h2>
					<p><?php echo $display->post_excerpt; ?></p>
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
				<?php if ( get_field( 'brochure', $display->ID ) ) : ?>
					<a class="brochure brochure_download" href="<?php the_field( 'brochure', $display->ID ); ?>"
						data-display="<?php echo $display->post_title; ?>" data-display-name="<?php echo $display->post_title; ?>"
						target="new"><span class="is_brochure">Download Brochure</span></a>
				<?php else : ?>
					<span class="brochure"><span>No brochure available</span></span>
				<?php endif; ?>
				<?php if ( $phone ) : ?>
					<div id="call_exhibitor_<?php echo $display->ID; ?>" class="modal">
						<div class="modal_box">
							<h2>Call <?php echo $display->post_title; ?></h2>
							<p><a href="tel:<?php echo $phone; ?>" data-display-name="<?php echo $display->post_title; ?>"
									class="my_account_call"><?php echo $phone; ?></a></p>
						</div>
					</div>
				<?php endif; ?>
				<?php if ( $email ) : ?>
					<div id="email_exhibitor_<?php echo $display->ID; ?>" class="modal">
						<div class="modal_box">
							<h2>Message <?php echo $display->post_title; ?></h2>
							<?php echo do_shortcode( '[contact-form-7 id="11379" destination-email="' . $email . '"]' ); ?>
						</div>
					</div>
				<?php endif; ?>
			</li>
		<?php else : ?>
			<li>
				<span class="thumb"
					style="background-image:url('https://www.homebaseperth.com.au/wp-content/themes/homebase/img/ex_display.png'); "></span>
				<div class="saved_display_content">
					<h2><?php echo $display->post_title; ?></h2>
					<p>This display is no longer available at Home Base. Please visit <a href="/displays">displays</a> or call our
						Welcome Desk on 9388 1088 to find a suitable alternative.</p>
					<ul class="actions">
						<li><a href="#" class="edit_notes" data-post-id="<?php echo $save->ID; ?>">My Notes</a></li>
					</ul>
				</div>

				<span class="brochure"><span>No brochure available</span></span>

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
		<section class="node_title">
			<ul class="my_account_util_nav">
				<li><a href="<?php echo wp_logout_url( get_permalink() ); ?>">Logout</a></li>
				<li><a id="my_account_help_btn" href="#">HELP</a></li>
			</ul>
			<div class="my_account_help_tip">Find My Account HELP here</div>
		</section>
		<section class="node_body">
			<div class="homebase_my_account <?php echo $projectinfoincomplete ? 'my_account_disabled' : ''; ?>">
				<ul class="my_account_tabs_nav">
					<li><a href="#collections">Collections</a></li>
					<li><a href="#displays">Displays</a></li>
					<li><a href="#posts">The Loft</a></li>
					<li><a href="#profile">Profile</a></li>
					<li><a href="#courses">Course Downloads</a></li>
				</ul>

				<div class="my_account_tab_help_tip">Start here</div>
				<div class="my_account_tab" id="collections">
					<ul class="collection_names" id="collection_names"></ul>
					<div class="collection_images" id="collection_images"></div>
				</div>
				<div class="my_account_tab" id="displays">
					<?php if ( $displays ) : ?>
						<ul class="saved_displays">
							<?php foreach ( $displays as $display ) : ?>
								<?php saved_displays($current_user_ID, $display) ?>
							<?php endforeach; ?>
							<?php wp_reset_postdata(); ?>
						</ul>
					<?php else : ?>
						<div class="my_account_empty">
							<p>You have no displays saved yet. Browse <a href="/displays">Displays</a></p>
						</div>
					<?php endif; ?>
				</div>
				<div class="my_account_tab" id="posts">
					<?php if ( $articles ) : ?>
						<div class="loft_content">
							<ul class="posts">
								<?php foreach ( $articles as $post ) :
									setup_postdata( $post ); ?>
									<?php $title = get_the_title(); ?>
									<li class="post_excerpt"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'loft' ); ?>
											<div>
												<p class="post_date"><?php the_time( 'd.m.Y' ); ?></p>
												<h2><?php echo substr( $title, 0, 40 ) . '...' ?></h2>
											</div>
										</a>
									</li>
								<?php endforeach; ?>
								<?php wp_reset_postdata(); ?>
							</ul>
						</div>
					<?php else : ?>
						<div class="my_account_empty">
							<p>You have no blog posts saved. Visit <a href="/the-loft">The Loft</a> to get started.</p>
						</div>
					<?php endif; ?>
				</div>
				<div class="my_account_tab <?php echo $projectinfoincomplete ? 'active' : ''; ?>" id="profile">
					<?php if ( $projectinfoincomplete ) : ?>
						<div class="account_update_error">Please complete your project information</div>
					<?php endif; ?>
					<?php edit_account_form(); ?>
				</div>
				<div class="my_account_tab" id="courses">
					<?php if ( $course_kits ) : ?>
						<?php foreach ( $course_kits as $course_kit ) : ?>
							<?php 
								$downloads = get_field( 'downloads', $course_kit->ID ); ?>
								<?php if ( have_rows( 'downloads', $course_kit->ID ) ) : ?>
									<div class="course_kit">
										<h2><?php echo $course_kit->post_title; ?></h2>
										<ul class="saved_displays">
											<?php while ( have_rows( 'downloads', $course_kit->ID ) ) :
												the_row(); ?>
												<?php if ( get_sub_field( 'pdf' ) ) : ?>
													<?php $thumbnail = get_sub_field( 'thumbnail' );
													$thumb = $thumbnail['sizes']['search']; ?>
													<li>
														<span class="thumb" style="background-image:url(<?php echo $thumb; ?>);"></span>
														<div class="saved_display_content">
															<h2><?php the_sub_field( 'title' ); ?></h2>
															<p><?php the_sub_field( 'description' ); ?></p>
														</div>
														<a class="brochure brochure_download" href="<?php the_sub_field( 'pdf' ); ?>" target="new"><span
																class="is_brochure">Download PDF</span></a>
													</li>
												<?php endif; ?>
											<?php endwhile; ?>
										</ul>
									</div>
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
			</div>
		</section>
	<?php else : ?>
		<div class="homebase_my_account_auth_prompt">
			<h1>My Account</h1>
			<p>Welcome to My Account. Create a personal account to save images, Home Base displays and exhibitor contact information. Plus, download product brochures and create Collections to inspire you for your current or upcoming home building, renovating or design project.</p>

			<?php 
				if ( $_GET['login'] == 'failed' ) {
					echo "<p class='login_unsuccessful'>Login unsuccessful. Please try again or <a href='<?php bloginfo( 'siteurl' ); ?>/wp-login.php?action=lostpassword'>reset your password</a>.</p>";
				}
			?>
			<?php wp_login_form( [ 
				'redirect' => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
				'form_id'  => 'my-account-loginform',
				'remember' => false,
			] ); ?>

			<a href="<?php bloginfo( 'url' ); ?>/wp-login.php?action=register">
				<div class="create_account_link">Create Account</div>
			</a>

			<div class="reset_password_link">
				<a href="<?php bloginfo( 'url' ); ?>/wp-login.php?action=lostpassword">Reset password</a>
			</div>
		</div>
	<?php endif; ?>