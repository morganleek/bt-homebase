<?php

	$display_ids = get_field( 'displays' );

	if( ! $display_ids ) {
		if( ! empty( $is_preview ) ) {
			print "<p>Select one or more displays.</p>";
		}
		return;
	}

	// Keeps the editor's chosen order and drops any display unpublished since it was picked
	$displays = get_posts( [
		'post_type'      => 'display',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'post__in'       => $display_ids,
		'orderby'        => 'post__in',
	] );

	if( ! $displays ) {
		if( ! empty( $is_preview ) ) {
			print "<p>None of the selected displays are published.</p>";
		}
		return;
	}

	get_template_part( 'template-parts/display-slider', null, [
		'displays' => $displays,
		'class'    => 'wp-block-specific-display-slider',
	] );
