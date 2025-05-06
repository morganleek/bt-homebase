<?php
	
	$terms = get_terms( [ 
		'taxonomy'   => 'display-category',
		'hide_empty' => false,
		'parent'     => 0,
	] );

	print "<div class=\"wp-block-display-categories-slider\">";
		print "<ul class=\"wp-block-post-template\">";
			foreach( $terms as $term ) {
				$link = get_term_link( $term );
				$featured_image = get_field( 'featured_image', $term );
				// $children = get_terms( [ 
				// 	'taxonomy'   => 'display-category',
				// 	'hide_empty' => false,
				// 	'parent'     => $term->term_id,
				// ] );
				// $additional_classes = $children ? "has-children" : "";
				print "<li class=\"wp-block-post\">"; // $additional_classes
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
					print "</div>";
				
				print "</li>";
			}
		print "</ul>";
	print "</div>";