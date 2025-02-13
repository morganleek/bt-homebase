<?php

function bone_theme_register_block_patterns() {
	$block_pattern_categories = [ 
		'special-blocks' => [ 'label' => __( 'Special Blocks', 'bone_theme' ) ],
	];

	$block_pattern_categories = apply_filters( 'bone_theme_block_pattern_categories', $block_pattern_categories );

	foreach ( $block_pattern_categories as $name => $properties ) {
		if ( ! WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( $name ) ) {
			register_block_pattern_category( $name, $properties );
		}
	}

	$block_patterns = [ 
		'card',
		'card-block-light',
		'card-block-dark'
	];

	$block_patterns = apply_filters( 'bone_theme_block_patterns', $block_patterns );

	foreach ( $block_patterns as $block_pattern ) {
		$pattern_file = get_theme_file_path( "/inc/patterns/$block_pattern.php" );

		register_block_pattern(
			"bone_theme/$block_pattern",
			require $pattern_file
		);
	}
}
add_action( 'init', 'bone_theme_register_block_patterns', 9 );
