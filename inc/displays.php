<?php
/*
 *
 * Inherited from original Home Base theme
 * 
 */

// Display Post Type w/ WP Tags & Custom Cats - hbperth

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
		'taxonomies'      => [ 'post_tag' ],
		'rewrite'         => [ 'slug' => 'displays/%display-category%' ],
		'menu_icon'				=> 'dashicons-cover-image',
		'menu_position'			=> 54
	];
	register_post_type( 'display', $args );
}
add_action( 'init', 'display_posts' );

function create_display_categories() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = [ 
		'name'              => _x( 'Display Categories', 'taxonomy general name' ),
		'singular_name'     => _x( 'Display Category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Display Categories' ),
		'all_items'         => __( 'All Display Categories' ),
		'parent_item'       => __( 'Parent Display Category' ),
		'parent_item_colon' => __( 'Parent Display Category:' ),
		'edit_item'         => __( 'Edit Display Category' ),
		'update_item'       => __( 'Update Display Category' ),
		'add_new_item'      => __( 'Add New Display Category' ),
		'new_item_name'     => __( 'New Display Category Name' ),
		'menu_name'         => __( 'Display Category' ),
	];

	$args = [ 
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'has_archive'       => true,
	];

	register_taxonomy( 'display-category', [ 'display' ], $args );

}
add_action( 'init', 'create_display_categories' );