<?php
	
	$terms = get_terms( array(
    'taxonomy'   => 'display-category',
    'hide_empty' => false,
		'parent' => 0
	) );

	print "<div class=\"wp-block-display-categories-grid\">";
		print "<ul class=\"wp-block-post-template wp-block-post-template-is-layout-grid is-layout-grid\">";
			foreach( $terms as $term ) {
				$link = get_term_link( $term );
				$featured_image = get_field( 'featured_image', $term );
				$children = get_terms( array(
					'taxonomy'   => 'display-category',
					'hide_empty' => false,
					'parent' => $term->term_id
				) );
				$additional_classes = $children ? "has-children" : "";
				print "<li class=\"wp-block-post $additional_classes\">";
					print "<div class=\"wp-block-group\">";
						print "<figure style=\"aspect-ratio:16/9;\" class=\"wp-block-post-featured-image\">";
							print "<a href=\"$link\" target=\"_self\">";
								if( $featured_image ) {
									print wp_get_attachment_image( $featured_image['ID'], "large" );
								}
								else {
									print "<img src=\"" . get_bloginfo( 'template_directory' ) . "/assets/images/checker-board.png\" alt=\"Empty\" />";
								}
							print "</a>";
						print "</figure>";
						print "<h3><a href=\"$link\" target=\"_self\">{$term->name}</a></h3>";
						print "<div class=\"wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex\"><div class=\"wp-block-button is-style-fill has-icon__external-arrow\"><a class=\"wp-block-button__link has-brand-charcoal-color has-white-background-color has-text-color has-background has-link-color wp-element-button\" href=\"$link\">Explore<span class=\"wp-block-button__link-icon\" aria-hidden=\"true\"><svg viewBox=\"0 0 10 12\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M8.16868 4.09641L1.30121 11.0121L0 9.71087L6.91566 2.8193H2V0.987976H10V9.01207H8.16868V4.09641Z\"></path></svg></span></a></div></div>";
						if( $children ) {
							print "<ul class=\"children\">";
							foreach( $children as $child ) {
								$child_link = get_term_link( $term );
								$child_featured_image = get_field( 'featured_image', $child );
								print "<li class=\"child\">";
									print "<figure style=\"aspect-ratio:16/9;\" class=\"wp-block-post-featured-image\">";
										print "<a href=\"$child_link\" target=\"_self\">";
											if( $child_featured_image ) {
												print wp_get_attachment_image( $child_featured_image['ID'], "large" );
											}
											else {
												print "<img src=\"" . get_bloginfo( 'template_directory' ) . "/assets/images/checker-board.png\" alt=\"Empty\" />";
											}
										print "</a>";
									print "</figure>";
									print "<h3><a href=\"$child_link\" target=\"_self\">{$child->name}</a></h3>";
									print "<div class=\"wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex\"><div class=\"wp-block-button is-style-fill has-icon__external-arrow\"><a class=\"wp-block-button__link has-brand-charcoal-color has-white-background-color has-text-color has-background has-link-color wp-element-button\" href=\"$child_link\">Explore<span class=\"wp-block-button__link-icon\" aria-hidden=\"true\"><svg viewBox=\"0 0 10 12\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M8.16868 4.09641L1.30121 11.0121L0 9.71087L6.91566 2.8193H2V0.987976H10V9.01207H8.16868V4.09641Z\"></path></svg></span></a></div></div>";
								print "</li>";
							}
							print "</ul>";
						}
					print "</div>";
				
				print "</li>";
			}
		print "</ul>";
	print "</div>";