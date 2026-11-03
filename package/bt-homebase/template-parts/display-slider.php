<?php
	/**
	 * Slider of display posts, shared by the display slider blocks.
	 *
	 * @var array $args {
	 *     @type WP_Post[] $displays Displays to show, in slide order.
	 *     @type string    $class    Block wrapper class.
	 * }
	 */

	// wp-block-display-categories-slider picks up the existing slider styles and tiny-slider init
	print "<div class=\"" . esc_attr( $args['class'] ) . " wp-block-display-categories-slider\">";
		print "<ul class=\"wp-block-post-template\">";
			foreach( $args['displays'] as $display ) {
				$link = esc_url( get_permalink( $display ) );
				$title = esc_html( get_the_title( $display ) );
				$thumbnail_id = get_post_thumbnail_id( $display );
				print "<li class=\"wp-block-post\">";
					print "<div class=\"wp-block-group\">";
						print "<figure style=\"aspect-ratio:16/9;\" class=\"wp-block-post-featured-image\">";
							print "<a href=\"$link\" target=\"_self\">";
								if( $thumbnail_id ) {
									print wp_get_attachment_image( $thumbnail_id, "large" );
								}
								else {
									print "<img src=\"" . get_bloginfo( 'template_directory' ) . "/assets/images/checker-board.png\" alt=\"Empty\" />";
								}
							print "</a>";
						print "</figure>";
						print "<h3><a href=\"$link\" target=\"_self\">$title</a></h3>";
					print "</div>";
				print "</li>";
			}
		print "</ul>";
	print "</div>";
