<?php
/*
 *
 * Inherited from original Home Base theme
 * 
 */

// Display Post Type w/ WP Tags & Custom Cats - hbperth
function hb_display_post_link( $post_link, $id = 0 ) {
	$post = get_post( $id );

	if ( is_object( $post ) && 'display' == $post->post_type ) {
		$terms = wp_get_object_terms( $post->ID, 'display-category' );

		if ( $terms ) {
			return str_replace( '%display-category%', $terms[0]->slug, $post_link );
		}
	}

	return $post_link;
}

add_filter( 'post_type_link', 'hb_display_post_link', 1, 3 );

function display_posts() {
	$labels = [ 
		'name'               => __( 'Displays' ),
		'singular_name'      => __( 'Display' ),
		'add_new'            => __( 'Add New Display' ),
		'add_new_item'       => __( 'Add New Display' ),
		'edit_item'          => __( 'Edit Display' ),
		'new_item'           => __( 'Add New Display' ),
		'view_item'          => __( 'View Display' ),
		'items_archive'      => 'Displays',
		'search_items'       => __( 'Search displays' ),
		'not_found'          => __( 'No displays found' ),
		'not_found_in_trash' => __( 'No displays found in trash' ),
	];

	$args = [ 
		'labels'          => $labels,
		'public'          => true,
		'supports'        => [ 
			'title',
			'editor',
			'thumbnail',
			'excerpt',
			'custom-field',
		],
		'capability_type' => 'post',
		'has_archive'     => false,
		'query_var'       => true,
		'taxonomies'      => [ 'post_tag' ],
		'rewrite'         => [ 'slug' => 'displays/%display-category%' ],
		'menu_icon'				=> 'dashicons-cover-image',
		'menu_position'			=> 54,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'show_in_rest'        => true,
		'hierarchical'        => false,
		// 'has_archive'         => true,
		'can_export'          => true,
		'rewrite_no_front'    => false,
		'show_in_menu'        => true
	];
	register_post_type( 'display', $args );
}
add_action( 'init', 'display_posts' );

function create_display_categories() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = [
		"name" => esc_html__( "Display Categories", "twentytwentyfive" ),
		"singular_name" => esc_html__( "Display Category", "twentytwentyfive" ),
	];
	
	$args = [
		"label" => esc_html__( "Display Categories", "twentytwentyfive" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'display-category', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "display-category",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];

	register_taxonomy( 'display-category', [ 'display' ], $args );

}
add_action( 'init', 'create_display_categories' );

// Display ordering
function bones_theme_display_order( $query ){
	if( ! is_admin() && is_archive() ) {
		if( isset( $query->query['display-category'] ) ) {
			$query->set( 'orderby', 'title' );
			$query->set( 'order', 'ASC' );
		}
	}
};
add_action( 'pre_get_posts', 'bones_theme_display_order', 0); 