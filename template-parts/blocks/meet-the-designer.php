<?php

	echo "<div class=\"wp-block-meet-the-designer\">";

		$args = [ 
			'posts_per_page' => '-1',
			'product_cat' => 'meet-the-designer',
			'post_type' => 'product',
			'orderby' => 'menu_order',
			'order' => 'ASC'
		];
		$inspireseries = new WP_Query( $args );
		if ( $inspireseries->have_posts() ) {
			while ( $inspireseries->have_posts() ) {
				$inspireseries->the_post();
				echo "<div class=\"meet-the-designer-post-wrapper\" id=\"" . get_the_ID() . "\">";
					echo "<div class=\"meet-the-designer-post-title\"><h4>" . get_the_title() . "</h4></div>";
					// the_post_thumbnail( 'large', array( 'class' => 'meet-the-designer-post-thumbnail' ) );
					// echo "<div class=\"meet-the-designer-content\">";
					// 	the_content();
					// echo "</div>";
					// echo "<div class=\"meet-the-designer-excerpt\">";
					// 	the_excerpt();
					// echo "</div>";
					// echo "<div class=\"meet-the-designer-details-wrapper\">";
					// 	echo "<table class=\"variations csnasno\" cellspacing=\"0\">";
					// 		echo "<tbody>";
					// 			echo "<tr>";
					// 				echo "<td class=\"label\">Location</td>";
					// 				echo "<td class=\"value valuez\">" . get_field( 'inspire_series_location' ) . "</td>";
					// 			echo "</tr>";
					// 			echo "<tr>";
					// 				echo "<td class=\"label\">Cost</td>";
					// 				echo "<td class=\"value\">" . get_field( 'inspire_series_cost' ) . "</td>";
					// 			echo "</tr>";
					// 		echo "</tbody>";
					// 	echo "</table>";
					// echo "</div>";
					echo "<div class=\"meet-the-designer-variation-wrapper\">";
						woocommerce_template_single_add_to_cart();
					echo "</div>";
				echo "</div>";
				// echo get_post_field( 'post_content', $post->ID );
			}
		}
		
		wp_reset_postdata();

		$args = [ 
			'posts_per_page' => '-1',
			'product_cat' => 'BDAWA',
			'post_type' => 'product',
			'orderby' => 'menu_order',
			'order' => 'ASC'
		];
		$bdawa = new WP_Query( $args );
		if ( $bdawa->have_posts() ) {
			while ( $bdawa->have_posts() ) {
				$bdawa->the_post();
				echo "<div class=\"meet-the-designer-post-wrapper\" id=\"" . get_the_ID() . "\">";
					echo "<div class=\"meet-the-designer-post-title\"><h4>" . get_the_title() . "</h4></div>";
					// the_post_thumbnail( 'large', array( 'class' => 'meet-the-designer-post-thumbnail' ) );
					// echo "<div class=\"meet-the-designer-content\">";
					// 	the_content();
					// echo "</div>";
					// echo "<div class=\"meet-the-designer-excerpt\">";
					// 	the_excerpt();
					// echo "</div>";
					// echo "<div class=\"meet-the-designer-details-wrapper\">";
					// 	echo "<table class=\"variations\" cellspacing=\"0\">";
					// 		echo "<tbody>";
					// 			echo "<tr>";
					// 				echo "<td class=\"label\">Location</td>";
					// 				echo "<td class=\"value\">" . get_field( 'inspire_series_location' ) . "</td>";
					// 			echo "</tr>";
					// 			echo "<tr>";
					// 				echo "<td class=\"label\">Cost</td>";
					// 				echo "<td class=\"value\">" . get_field( 'inspire_series_cost' ) . "</td>";
					// 			echo "</tr>";
					// 		echo "</tbody>";
					// 	echo "</table>";
					// echo "</div>";
					echo "<div class=\"meet-the-designer-variation-wrapper\">";
						woocommerce_template_single_add_to_cart();
					echo "</div>";
				echo "</div>";
				// echo get_post_field( 'post_content', $post->ID );
			}
		}

		wp_reset_postdata();

	echo "</div>"; // .wp-block-meet-the-designer