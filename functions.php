<?php

// Require the composer autoload for getting conflict-free access to enqueue
require_once __DIR__ . '/vendor/autoload.php';

// Other functions
require get_template_directory() . '/inc/block-patterns.php';
// Extra tools
require get_template_directory() . '/inc/tools.php';
// Inherited Libraries
require get_template_directory() . '/inc/account.php';
require get_template_directory() . '/inc/displays.php';
require get_template_directory() . '/inc/inspiration.php';
require get_template_directory() . '/inc/photos.php';
// ACF
require get_template_directory() . '/inc/acf.php';
// P2P Connections 
require get_template_directory() . '/inc/p2p.php';
// Search
require get_template_directory() . '/inc/search.php';
// SVG Clip-paths
require get_template_directory() . '/inc/clip-paths.php';


// Do stuff through this plugin
class BoneThemeInit
{
	use advanced_custom_fields;
	public $enqueue;

	public function __construct()
	{
		
		$theme_version = wp_get_theme()->get('Version');
		$version_string = is_string($theme_version) ? $theme_version : '1.0.0';

		// It is important that we init the Enqueue class right at the plugin/theme load time
		$this->enqueue = new \WPackio\Enqueue(
			// Name of the project, same as `appName` in wpackio.project.js
			'bonesTheme',
			// Output directory, same as `outputPath` in wpackio.project.js
			'dist',
			// Version of your plugin
			$version_string,
			// Type of your project, same as `type` in wpackio.project.js
			'theme',
			// Plugin location, pass false in case of theme.
			false,
			// Theme type
			'regular'
		);
		// Enqueue a few of our entry points
		add_action('wp_enqueue_scripts', [$this, 'plugin_enqueue']);
		add_action('after_setup_theme', [$this, 'bones_theme_support']);
		add_action( 'admin_init', [$this, 'bones_theme_editor_styles' ] );
		// add_action( 'wp_head', [ $this, 'bones_theme_preload_webfonts' ] );
		add_action('wp_head', [$this, 'bones_theme_load_favicons']);
		add_action('init', [$this, 'bones_name_register_block_styles'], 100);
		add_action('enqueue_block_editor_assets', [$this, 'bones_theme_enqueue_block_variations']);

		// ACF 
		add_action( 'acf/init', [$this, 'bones_theme_acf_register_blocks'] );

		// Gallery Sliders
		// add_action( 'wp_print_styles', [ $this, 'update_styles' ] );

		// Copyright Year 
		if ( ! is_admin() ) {
			add_action( 'render_block', [$this, 'bones_theme_render_block'], 5, 2 );
		}

		// Woocommerce Image Sizes
		add_filter( 'woocommerce_get_image_size_gallery_thumbnail', fn( $size ) => [ 
			'width'  => 300,
			'height' => 300,
			'crop'   => 1,
		] );

		add_filter( 'woocommerce_get_image_size_thumbnail', fn( $size ) => [ 
			'width'  => 300,
			'height' => 300,
			'crop'   => 1,
		] );

		add_filter( 'woocommerce_get_image_size_single', fn( $size ) => [ 
			'width'  => 768,
			'height' => 768,
			'crop'   => 0,
		] );	

		remove_image_size( '1536x1536' );
		remove_image_size( 'medium_large' );
		add_image_size( 'medium_large', 1024, 1024, 0 );

		add_action( 'plugins_loaded', [$this, 'bones_theme_remove_image_sizes'], 10 );
	}

	public function bones_theme_remove_image_sizes() {
		remove_image_size( '1536x1536' );
		remove_image_size( 'medium_large' );
		
	}

	public function plugin_enqueue()
	{
		$enqueue_location = $this->enqueue->enqueue('app', 'main', []);

		wp_enqueue_style( 'fonts', "https://cloud.typography.com/7503134/6320352/css/fonts.css", [], '1.0', 'all' );

		// Inline styles for fonts
		// wp_add_inline_style( 'bones-theme-style', $this->bones_theme_get_font_face_styles() );
		// 

		$ajax_object = [
			'ajax_url' => admin_url( 'admin-ajax.php' )
		];
		
		wp_localize_script( 
			$enqueue_location['js'][sizeof($enqueue_location) - 1]['handle'], 
			'home_base', 
			$ajax_object 
		);
		
	}

	public function bones_theme_render_block( $block_content, $block ) {
		// Copyright Year
		if( $block['blockName'] === "core/paragraph" ) {
			$year_regex = "/({YEAR})/i";
			$block_content = preg_replace( $year_regex, date('Y'), $block_content );
		}

		// Change Hamburger
		if( $block['blockName'] === "core/navigation" ) {
			$svg_regex = "/<svg.*?\/svg>/i";
			$svg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 12H21M3 6H21M9 18H21" stroke="#38332F" stroke-width="2" stroke-linecap="square" stroke-linejoin="round"/></svg>';
			$block_content = preg_replace( $svg_regex, $svg, $block_content );
		}

		// Mini-cart Icon
		if( 
			$block['blockName'] === "woocommerce/mini-cart"
		) {
			$svg_regex = "/<svg.*?class=\"wc-block-mini-cart__icon\".*?>.*?\/svg>/i";
			$svg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="wc-block-mini-cart__icon"><path d="M2 2H3.30616C3.55218 2 3.67519 2 3.77418 2.04524C3.86142 2.08511 3.93535 2.14922 3.98715 2.22995C4.04593 2.32154 4.06333 2.44332 4.09812 2.68686L4.57143 6M4.57143 6L5.62332 13.7314C5.75681 14.7125 5.82355 15.2031 6.0581 15.5723C6.26478 15.8977 6.56108 16.1564 6.91135 16.3174C7.30886 16.5 7.80394 16.5 8.79411 16.5H17.352C18.2945 16.5 18.7658 16.5 19.151 16.3304C19.4905 16.1809 19.7818 15.9398 19.9923 15.6342C20.2309 15.2876 20.3191 14.8247 20.4955 13.8988L21.8191 6.94969C21.8812 6.62381 21.9122 6.46087 21.8672 6.3335C21.8278 6.22177 21.7499 6.12768 21.6475 6.06802C21.5308 6 21.365 6 21.0332 6H4.57143ZM10 21C10 21.5523 9.55228 22 9 22C8.44772 22 8 21.5523 8 21C8 20.4477 8.44772 20 9 20C9.55228 20 10 20.4477 10 21ZM18 21C18 21.5523 17.5523 22 17 22C16.4477 22 16 21.5523 16 21C16 20.4477 16.4477 20 17 20C17.5523 20 18 20.4477 18 21Z" stroke="#38332F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
			$block_content = preg_replace( $svg_regex, $svg, $block_content );
		}

		if( $block['blockName'] === "acf/my-account" ) {
			$login_regex = "/(id=\"user_login\" )/i";
			$block_content = preg_replace( $login_regex, "id=\"user_login\" placeholder=\"Email\" ", $block_content );

			$pass_regex = "/(id=\"user_pass\" )/i";
			$block_content = preg_replace( $pass_regex, "id=\"user_pass\" placeholder=\"Password\" ", $block_content );
		}

		// error_log( $block['blockName'], 0 );

		return $block_content;
	}

	public function bones_theme_support()
	{
		// Add support for block styles.
		// add_theme_support( 'wp-block-styles' );

		// Add post thumbnails
		// add_theme_support( 'post-thumbnails', array( 'post' ) );

		// Add responsive embedded content
		// add_theme_support( 'responsive-embeds' );

		// Add editor styles
		// add_theme_support( 'editor-styles' );

		// add_theme_support( 'custom-spacing' );

		// add_editor_style( 'style.css' );

		// Enqueue editor styles.
		$assets = $this->enqueue->getAssets('app', 'main', [
			'js' => true,
			'css' => true,
			'js_dep' => [],
			'css_dep' => [],
			'in_footer' => true,
			'media' => 'all',
		]);

		if (!empty($assets['css'])) {
			foreach ($assets['css'] as $css) {
				$url = str_replace(trailingslashit(get_template_directory_uri()), '', $css['url']);
				add_editor_style($url);
			}
		}
	}

	public function bones_theme_load_favicons()
	{
		// global $_wp_additional_image_sizes;
		// error_log( print_r( $_wp_additional_image_sizes, true ), 0 );
		print '<link rel="icon" href="' . get_theme_file_uri('assets/favicon/favicon.svg?v=1.0.0') . '" type="image/svg+xml">';
	}

	public function bones_theme_editor_styles() {
		wp_add_inline_style( 'wp-admin', $this->bones_theme_get_font_face_styles() );
	}

	public function bones_theme_get_font_face_styles() {
		return "
			#adminmenu .wp-menu-image img {
				padding-top: 0;
			}
		";
	}

	// public function bones_theme_preload_webfonts() {
	// 	print '<link rel="preload" href="' . esc_url( get_theme_file_uri( 'assets/fonts/SourceSerif4Variable-Roman.ttf.woff2' ) ) . '" as="font" type="font/woff2" crossorigin>';
	// }

	public function bones_name_register_block_styles()
	{
		// Groups
		register_block_style( 'core/group', [
			'name' => 'overlap',
			'label' => __( 'Overlap', 'bones_name' )
		] );

		// Slider
		register_block_style( 'getwid/content-slider-slide', [
		  'name' => 'overflow',
		  'label' => __( 'Overflow', 'bones_name' ),
		] );

		// Query
		register_block_style( 'core/query', [
		  'name' => 'slider',
		  'label' => __( 'Slider', 'bones_name' ),
		] );

		// Columns
		register_block_style( 'core/columns', [
		  'name' => 'stack-reversed',
		  'label' => __( 'Stack Reversed', 'bones_name' ),
		] );

		// Buttons
		// register_block_style( 'core/button', [
		//   'name' => 'play',
		//   'label' => __( 'Play', 'bones_name' ),
		// ] );
	}

	public function bones_theme_enqueue_block_variations()
	{
		wp_enqueue_script(
			'bones-theme-enqueue-block-variations',
			get_template_directory_uri() . '/assets/js/variations.js',
			['wp-blocks', 'wp-dom-ready'],
			wp_get_theme()->get('Version'),
			false
		);
	}

	// public function update_gallery_styles( $styles ) {
	// 	$regex = '/(.wp-block-gallery[\S\w]*)/i';
	// 	return preg_replace( $regex, '${1}:not(.is-style-gallery-slider) ', $styles );
	// }

	// public function update_styles() {
	// 	// Extends upon wp-includes/script-loader.php/wp_maybe_inline_styles()
	// 	global $wp_styles;
	// 	if( isset( $wp_styles->registered['wp-block-gallery'] ) ) {
	// 		// `src` is set to false when it's already inlined
	// 		if( $wp_styles->registered['wp-block-gallery']->src !== false ) {
	// 			// based on code from inclues `wp_maybe_inline_styles` line:2818
	// 			$css = file_get_contents( $wp_styles->registered['wp-block-gallery']->extra['path'] );

	// 			// update relative urls
	// 			$css = _wp_normalize_relative_css_links( $css, $wp_styles->registered['wp-block-gallery']->src );

	// 			// set `src` to `false` and add styles inline
	// 			$wp_styles->registered[ 'wp-block-gallery' ]->src = false;
	// 			if ( empty( $wp_styles->registered[ 'wp-block-gallery' ]->extra['after'] ) ) {
	// 				$wp_styles->registered[ 'wp-block-gallery' ]->extra['after'] = array();
	// 			}
	// 			array_unshift( $wp_styles->registered[ 'wp-block-gallery' ]->extra['after'], $css );
	// 		}

	// 		// update `src` using regex to exclude our `.is-style-gallery-slider` class 
	// 		if( isset( $wp_styles->registered['wp-block-gallery']->extra['after'] ) ) {
	// 			$wp_styles->registered['wp-block-gallery']->extra['after'] = array_map( [ $this, 'update_gallery_styles' ], $wp_styles->registered['wp-block-gallery']->extra['after'] );
	// 		}	
	// 	}
	// }
}

// Init
new BoneThemeInit();

add_action( 'breadcrumb_block_single_prepend', function ( $post, $breadcrumbs_instance ) {
	if ( 'display' === $post->post_type ) {
		$terms = get_the_terms( $post, 'display-category' );
		if( !empty( $terms ) ) {
			$breadcrumbs_instance->add_item( "Displays", "/displays" );
			$breadcrumbs_instance->add_item( $terms[0]->name, get_term_link( $terms[0] ) );
		}
	}
}, 10, 2 );

// add_filter( 'breadcrumb_block_get_args', function ( $args ) {
// 	// $args['separator'] = '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="1em" height="1em" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M3.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L9.293 8 3.646 2.354a.5.5 0 0 1 0-.708z"/><path fill-rule="evenodd" d="M7.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L13.293 8 7.646 2.354a.5.5 0 0 1 0-.708z"/></svg>';
// 	$args['separator'] = '<span>&gt;</span>';
// 	return $args;
// } );


