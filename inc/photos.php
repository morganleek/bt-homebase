<?php
/*
 *
 * Inherited from original Home Base theme
 * 
 */

// Photo Post Type, because WP media just doesn't cut it

function photo_posts() {
	$labels = [ 
		'name'               => __( 'Photos' ),
		'singular_name'      => __( 'Photo' ),
		'add_new'            => __( 'Add Photo' ),
		'add_new_item'       => __( 'Add New Photo' ),
		'edit_item'          => __( 'Edit Photo' ),
		'new_item'           => __( 'Add New Photo' ),
		'view_item'          => __( 'View Photo' ),
		'items_archive'      => 'Photos',
		'search_items'       => __( 'Search Photos' ),
		'not_found'          => __( 'No photos found' ),
		'not_found_in_trash' => __( 'No photos found in trash' ),
	];
	$args = [ 
		'labels'          => $labels,
		'public'          => true,
		'supports'        => [ 
			'title',
			'custom-field',
		],
		'capability_type' => 'post',
		'rewrite'         => [ 'slug' => 'photo' ],
		'taxonomies'      => [ 'post_tag' ],
		'menu_icon'				=> 'dashicons-images-alt',
		'menu_position'			=> 54
	];
	register_post_type( 'photo', $args );
}
add_action( 'init', 'photo_posts' );