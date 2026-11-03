<?php

	$term_id = (int) get_field( 'display_category' );

	if( ! $term_id ) {
		if( ! empty( $is_preview ) ) {
			print "<p>Select a display category.</p>";
		}
		return;
	}

	// Same ordering and active filter as the display category archive
	$displays = get_posts( [
		'post_type'      => 'display',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'meta_key'       => 'salesforce_active',
		'meta_value'     => '1',
		'tax_query'      => [
			[
				'taxonomy' => 'display-category',
				'field'    => 'term_id',
				'terms'    => $term_id,
			],
		],
	] );

	if( ! $displays ) {
		if( ! empty( $is_preview ) ) {
			print "<p>No displays found in this category.</p>";
		}
		return;
	}

	get_template_part( 'template-parts/display-slider', null, [
		'displays' => $displays,
		'class'    => 'wp-block-display-category-slider',
	] );
