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
					'name'            => 'display-save',
					'title'           => __( 'Display Save Buttons' ),
					'description'     => __( '' ),
					'render_template' => 'template-parts/blocks/display-save.php',
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

				// Display Category Slider
				acf_register_block_type( [
					'name'            => 'display-category-slider',
					'title'           => __( 'Display Category Slider' ),
					'description'     => __( 'Slider of all displays in a chosen display category.' ),
					'render_template' => 'template-parts/blocks/display-category-slider.php',
					'category'        => 'formatting',
					'icon'            => 'grid-view',
					'keywords'        => [],
				] );

				acf_add_local_field_group( [
					'key'      => 'group_display_category_slider',
					'title'    => 'Block: Display Category Slider',
					'fields'   => [
						[
							'key'           => 'field_display_category_slider_term',
							'label'         => 'Display Category',
							'name'          => 'display_category',
							'type'          => 'taxonomy',
							'taxonomy'      => 'display-category',
							'field_type'    => 'select',
							'required'      => 1,
							'allow_null'    => 0,
							'add_term'      => 0,
							'save_terms'    => 0,
							'load_terms'    => 0,
							'return_format' => 'id',
							'multiple'      => 0,
						],
					],
					'location' => [
						[
							[
								'param'    => 'block',
								'operator' => '==',
								'value'    => 'acf/display-category-slider',
							],
						],
					],
				] );

				// Specific Display Slider
				acf_register_block_type( [
					'name'            => 'specific-display-slider',
					'title'           => __( 'Specific Display Slider' ),
					'description'     => __( 'Slider of hand-picked displays.' ),
					'render_template' => 'template-parts/blocks/specific-display-slider.php',
					'category'        => 'formatting',
					'icon'            => 'grid-view',
					'keywords'        => [],
				] );

				acf_add_local_field_group( [
					'key'      => 'group_specific_display_slider',
					'title'    => 'Block: Specific Display Slider',
					'fields'   => [
						[
							'key'           => 'field_specific_display_slider_displays',
							'label'         => 'Displays',
							'name'          => 'displays',
							'type'          => 'relationship',
							'post_type'     => [ 'display' ],
							'post_status'   => [ 'publish' ],
							'filters'       => [ 'search', 'taxonomy' ],
							'required'      => 1,
							'return_format' => 'id',
						],
					],
					'location' => [
						[
							[
								'param'    => 'block',
								'operator' => '==',
								'value'    => 'acf/specific-display-slider',
							],
						],
					],
				] );

				// Only offer Display Categories in the taxonomy filter (no Tags).
				// Set at prepare time so the dropdown changes but the list of selectable displays doesn't.
				add_filter( 'acf/prepare_field/key=field_specific_display_slider_displays', function( $field ) {
					$slugs = get_terms( [
						'taxonomy'   => 'display-category',
						'hide_empty' => false,
						'fields'     => 'slugs',
					] );
					if( ! is_wp_error( $slugs ) ) {
						$field['taxonomy'] = array_map( fn( $slug ) => "display-category:$slug", $slugs );
					}
					return $field;
				} );

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

				// Meet the Designer
				acf_register_block_type( [ 
					'name'            => 'meet-the-designer',
					'title'           => __( 'Meet the Designer' ),
					'description'     => __( '' ),
					'render_template' => 'template-parts/blocks/meet-the-designer.php',
					'category'        => 'formatting',
					'icon'            => 'admin-users',
					'keywords'        => [],
				] );

				// Post save
				acf_register_block_type( [ 
					'name'            => 'post-save',
					'title'           => __( 'Post Save Buttons' ),
					'description'     => __( '' ),
					'render_template' => 'template-parts/blocks/post-save.php',
					'category'        => 'formatting',
					'icon'            => 'admin-comments',
					'keywords'        => [],
				] );

				// Site notice
				acf_register_block_type( [ 
					'name'            => 'site-notice',
					'title'           => __( 'Site Notice' ),
					'description'     => __( '' ),
					'render_template' => 'template-parts/blocks/site-notice.php',
					'category'        => 'formatting',
					'icon'            => 'admin-site-alt3',
					'keywords'        => [],
				] );

				// Course Information
				acf_register_block_type( [ 
					'name'            => 'course-information',
					'title'           => __( 'Course Information' ),
					'description'     => __( '' ),
					'render_template' => 'template-parts/blocks/course-information.php',
					'category'        => 'formatting',
					'icon'            => 'admin-site-alt3',
					'keywords'        => [],
				] );

				// Course Sponsors
				acf_register_block_type( [ 
					'name'            => 'course-sponsors',
					'title'           => __( 'Course Sponsors' ),
					'description'     => __( '' ),
					'render_template' => 'template-parts/blocks/course-sponsors.php',
					'category'        => 'formatting',
					'icon'            => 'admin-site-alt3',
					'keywords'        => [],
				] );

				// Course FAQs
				acf_register_block_type( [ 
					'name'            => 'course-faqs',
					'title'           => __( 'Course FAQs' ),
					'description'     => __( '' ),
					'render_template' => 'template-parts/blocks/course-faqs.php',
					'category'        => 'formatting',
					'icon'            => 'admin-site-alt3',
					'keywords'        => [],
				] );

				// Course Reviews
				acf_register_block_type( [ 
					'name'            => 'course-reviews',
					'title'           => __( 'Course Reviews' ),
					'description'     => __( '' ),
					'render_template' => 'template-parts/blocks/course-reviews.php',
					'category'        => 'formatting',
					'icon'            => 'admin-site-alt3',
					'keywords'        => [],
				] );

				// OBSN Login
				acf_register_block_type( [ 
					'name'            => 'obsn-login',
					'title'           => __( 'OBSN Login' ),
					'description'     => __( '' ),
					'render_template' => 'template-parts/blocks/obsn-login.php',
					'category'        => 'formatting',
					'icon'            => 'admin-site-alt3',
					'keywords'        => [],
				] );

				// OBSN Nav
				// acf_register_block_type( [ 
				// 	'name'            => 'obsn-nav',
				// 	'title'           => __( 'OBSN Nav' ),
				// 	'description'     => __( '' ),
				// 	'render_template' => 'template-parts/blocks/obsn-nav.php',
				// 	'category'        => 'formatting',
				// 	'icon'            => 'admin-site-alt3',
				// 	'keywords'        => [],
				// ] );
			}
		}
	}