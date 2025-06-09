<?php
/*
 *
 * Inherited from original Home Base theme
 * 
 */

// Inspiration Board Post Type Using WP Tags

// function inspiration_posts() {
// 	$labels = [ 
// 		'name'               => __( 'Inspire Boards' ),
// 		'singular_name'      => __( 'Inspire Boards' ),
// 		'add_new'            => __( 'Add New' ),
// 		'add_new_item'       => __( 'Add New Inspire Board' ),
// 		'edit_item'          => __( 'Edit Inspire Board' ),
// 		'new_item'           => __( 'Add New Inspire Board' ),
// 		'view_item'          => __( 'View Inspire Board' ),
// 		'items_archive'      => 'Inspire Boards',
// 		'search_items'       => __( 'Search Inspire Boards' ),
// 		'not_found'          => __( 'No Inspire Boards found' ),
// 		'not_found_in_trash' => __( 'No Inspire Boards found in trash' ),
// 	];
// 	$args = [ 
// 		'labels'            => $labels,
// 		'public'            => true,
// 		'supports'          => [ 
// 			'title',
// 			'thumbnail',
// 			'excerpt',
// 			'custom-field',
// 		],
// 		'capability_type'   => 'post',
// 		'rewrite'           => [ 'slug' => 'inspire-me' ],
// 		'show_in_nav_menus' => true,
// 		'has_archive'       => true,
// 		'taxonomies'        => [ 'post_tag' ],
// 	];
// 	register_post_type( 'inspire-board', $args );
// }
// add_action( 'init', 'inspiration_posts' );