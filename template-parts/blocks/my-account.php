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
					<h4 class="wp-block-post-title">
						<a href="<?php print $permalink; ?>" target="_self"><?php print $display->post_title; ?></a>
					</h4>
				</div>

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
				<?php if ( get_field( 'brochure', $display->ID ) ) : ?>
					<a style="display: none;" class="brochure brochure_download" href="<?php the_field( 'brochure', $display->ID ); ?>"
						data-display="<?php echo $display->post_title; ?>" data-display-name="<?php echo $display->post_title; ?>"
						target="new"><span class="is_brochure">Download Brochure</span></a>
				<?php endif; ?>
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
			style="margin-top:0;margin-bottom:0;padding-bottom:var(--wp--preset--spacing--40)">
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
				id="our-centre"
				style="border-radius:24px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">



				<div class="my_account_tab" id="collections">
					<h3>Collections</h3>
					<div class="wp-block-group"></div>
					<ul class="collection_names" id="collection_names"></ul>
					<div class="collection_images" id="collection_images"></div>
				</div>
				<div class="my_account_tab" id="displays">
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
				<div class="my_account_tab" id="courses">
					<h3>Courses</h3>
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
				<div class="my_account_tab" id="posts">
					<h3>Blog</h3>
					<?php if ( $articles ) : ?>
						<div class="wp-block-query is-layout-flow wp-block-query-is-layout-flow">
							<ul class="columns-2 wp-block-post-template is-layout-flow wp-block-post-template-is-layout-flow">
								<?php foreach ( $articles as $article ) : ?>
									<li class="wp-block-post post-49925 post type-post status-publish format-standard has-post-thumbnail hentry category-blog tag-architect tag-design-ideas tag-designer tag-home-design tag-luxury-homes tag-new-build tag-perth-designers">
										<div class="wp-block-group has-global-padding is-layout-constrained wp-block-group-is-layout-constrained" style="padding-bottom:var(--wp--preset--spacing--20)">
											<figure style="aspect-ratio:16/9;" class="wp-block-post-featured-image">
												<a href="<?php the_permalink( $article->ID ); ?>" target="_self">
													<?php print get_the_post_thumbnail( $article, "large" ); ?>
												</a>
											</figure>
		
											<div class="wp-block-group has-global-padding is-layout-constrained wp-block-group-is-layout-constrained" style="padding-right:8px;padding-left:8px">
												<div class="wp-block-group has-global-padding is-layout-constrained wp-block-group-is-layout-constrained">
													<div style="font-size:13px;" class="has-link-color wp-block-post-date has-text-color has-brand-charcoal-color">
														<time datetime="">{{ TIME }}</time>
													</div>
		
													<div class="has-link-color wp-elements-5e8c8a684c2d35a3a46c96815f25414c wp-block-post-author has-text-color has-brand-yellow-color">
														<div class="wp-block-post-author__content">
															<p class="wp-block-post-author__name"><?php print $article->post_author; ?></p>
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
				<div class="my_account_tab <?php echo $projectinfoincomplete ? 'active' : ''; ?>" id="profile" style="display: none;">
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