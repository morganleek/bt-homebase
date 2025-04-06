<?php 
	trait advanced_custom_fields {
		public function bones_theme_acf_register_blocks() {
			// Check function exists.
			if( function_exists('acf_register_block_type') ) {
				// Display description
				acf_register_block_type( [ 
					'name'            => 'display-description',
					'title'           => __( 'Display Description' ),
					'description'     => __( '' ),
					'render_template' => 'template-parts/blocks/display-description.php',
					'category'        => 'formatting',
					'icon'            => 'admin-comments',
					'keywords'        => [],
				] );

				// Display description
				acf_register_block_type( [ 
					'name'            => 'display-location',
					'title'           => __( 'Display Location' ),
					'description'     => __( '' ),
					'render_template' => 'template-parts/blocks/display-location.php',
					'category'        => 'formatting',
					'icon'            => 'admin-comments',
					'keywords'        => [],
				] );

				// Display description
				acf_register_block_type( [ 
					'name'            => 'display-social-links',
					'title'           => __( 'Display Social Links' ),
					'description'     => __( '' ),
					'render_template' => 'template-parts/blocks/display-social-links.php',
					'category'        => 'formatting',
					'icon'            => 'admin-comments',
					'keywords'        => [],
				] );

				// Display description
				acf_register_block_type( [ 
					'name'            => 'display-image-gallery',
					'title'           => __( 'Display Image Gallery' ),
					'description'     => __( '' ),
					'render_template' => 'template-parts/blocks/display-image-gallery.php',
					'category'        => 'formatting',
					'icon'            => 'admin-comments',
					'keywords'        => [],
				] );

				// Post description
				acf_register_block_type( [ 
					'name'            => 'post-social-links',
					'title'           => __( 'Post Social Links' ),
					'description'     => __( '' ),
					'render_template' => 'template-parts/blocks/post-social-links.php',
					'category'        => 'formatting',
					'icon'            => 'admin-comments',
					'keywords'        => [],
				] );

				// Instafeed Placeholder
				acf_register_block_type( [ 
					'name'            => 'instafeed-placeholder',
					'title'           => __( 'Instafeed Placeholder' ),
					'description'     => __( '' ),
					'render_template' => 'template-parts/blocks/instafeed-placeholder.php',
					'category'        => 'formatting',
					'icon'            => 'admin-comments',
					'keywords'        => [],
				] );

				// Display Categories Grid
				acf_register_block_type( [ 
					'name'            => 'display-categories-grid',
					'title'           => __( 'Display Categories Grid' ),
					'description'     => __( '' ),
					'render_template' => 'template-parts/blocks/display-categories-grid.php',
					'category'        => 'formatting',
					'icon'            => 'grid-view',
					'keywords'        => [],
				] );

				// Display Categories Slider
				acf_register_block_type( [ 
					'name'            => 'display-categories-slider',
					'title'           => __( 'Display Categories Slider' ),
					'description'     => __( '' ),
					'render_template' => 'template-parts/blocks/display-categories-slider.php',
					'category'        => 'formatting',
					'icon'            => 'grid-view',
					'keywords'        => [],
				] );

				// Tags Selector
				acf_register_block_type( [ 
					'name'            => 'tags-selector',
					'title'           => __( 'Tags Selector' ),
					'description'     => __( '' ),
					'render_template' => 'template-parts/blocks/tags-selector.php',
					'category'        => 'formatting',
					'icon'            => 'button',
					'keywords'        => [],
				] );

				// My Account
				acf_register_block_type( [ 
					'name'            => 'my-account',
					'title'           => __( 'My Account' ),
					'description'     => __( '' ),
					'render_template' => 'template-parts/blocks/my-account.php',
					'category'        => 'formatting',
					'icon'            => 'admin-users',
					'keywords'        => [],
				] );

				// My Profile
				acf_register_block_type( [ 
					'name'            => 'my-profile',
					'title'           => __( 'My Profile' ),
					'description'     => __( '' ),
					'render_template' => 'template-parts/blocks/my-profile.php',
					'category'        => 'formatting',
					'icon'            => 'admin-users',
					'keywords'        => [],
				] );
			}
		}
	}