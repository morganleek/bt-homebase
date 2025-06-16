<?php 
	add_filter( 'login_form_middle', 'home_base_login_form_middle', 20, 1 );
	if( !function_exists( 'home_base_login_form_middle' ) ) {
		function home_base_login_form_middle( $atts ) {
			return '<button>Sign In<svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.16868 4.09641L1.30121 11.0121L0 9.71087L6.91566 2.8193H2V0.987976H10V9.01207H8.16868V4.09641Z" fill="#F9B000"></path></svg></button>';
		}
	}

	// $current_user = wp_get_current_user();
	// 	$current_user_ID = $current_user->ID;
	// }
	// else {
	
	if ( !is_user_logged_in() ) {
		?>
			<div class="wp-block-group alignfull has-global-padding is-layout-constrained wp-container-core-group-is-layout-11 wp-block-group-is-layout-constrained" style="margin-top:0;margin-bottom:0;padding-bottom:var(--wp--preset--spacing--40)">
				<div class="wp-block-group has-white-background-color has-background has-global-padding is-layout-constrained wp-container-core-group wp-block-group-is-layout-constrained" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
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
						</div>
					</div>
				</div>
			</div>
		<?php
	}