<?php
	add_action( 'woocommerce_after_cart_item_name', 'home_base_woocommerce_after_cart_remove', 100, 2 );

	function home_base_woocommerce_after_cart_remove( $cart_item, $cart_item_key ) {
		$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
		$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

		printf(
			'<div class="remove-wrapper"><a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">Remove</a></div>',
			esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
			/* translators: %s is the product name */
			esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
			esc_attr( $product_id ),
			esc_attr( $_product->get_sku() )
		);
	}

	// Change variations to radio buttons 
	function home_base_radio_variations( $html, $args ) {
		
		// in wc_dropdown_variation_attribute_options() they also extract all the array elements into variables
		$options   = $args[ 'options' ];
		$product   = $args[ 'product' ];
		$attribute = $args[ 'attribute' ];
		$name      = $args[ 'name' ] ? $args[ 'name' ] : 'attribute_' . sanitize_title( $attribute );
		$id        = $args[ 'id' ] ? $args[ 'id' ] : sanitize_title( $attribute );
		$class     = $args[ 'class' ];

		
		if( empty( $options ) || ! $product ) {
			return $html;
		}
		
		// Check it is in `Meet` categories
		$terms = get_the_terms( get_the_ID(), 'product_cat' );
		if( $terms !== false ) {
			// Check is a course
			$terms_filtered = array_filter( $terms, fn( $t ) => $t->slug === "meet-the-designer" || $t->slug === "BDAWA" );
			if( count( $terms_filtered) === 0 ) {
				return $html;
			}
		}
		
		// HTML for our radio buttons
		$radios = '<ul class="mc-variation-radios">';

		// taxonomy-based attributes
		if( taxonomy_exists( $attribute ) ) {
			$terms = wc_get_product_terms(
				$product->get_id(),
				$attribute,
				[ 
					'fields' => 'all',
				]
			);

			foreach( $terms as $term ) {
				if( in_array( $term->slug, $options, true ) ) {
					$checked = checked( $args[ 'selected' ], $term->slug, false );
					$radios .= "<li><input type=\"radio\" id=\"{$name}-{$term->slug}\" name=\"{$name}\" value=\"{$term->slug}\" {$checked}><label for=\"{$name}-{$term->slug}\">{$term->name}</label><br /></li>";
				}
			}
		// individual product attributes
		} else {
			foreach( $options as $option ) {
				$checked = sanitize_title( $args[ 'selected' ] ) === $args[ 'selected' ] ? checked( $args[ 'selected' ], sanitize_title( $option ), false ) : checked( $args[ 'selected' ], $option, false );
				$radios .= "<li><input type=\"radio\" id=\"{$name}-{$option}\" name=\"{$name}\" value=\"{$option}\" {$checked}><label for=\"{$name}-{$option}\">{$option}</label></li>";
			}
		}
		
		$radios .= '</ul>';
		// $radios .= "<pre>" . print_r( $args, true ) . "</pre>";

		return "$html$radios";
		
	}

	add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'home_base_radio_variations', 100, 2 );

	function home_base_variation_gray_out( $active, $variation ) {
		// check if the variation is in stock
		if( ! $variation->is_in_stock() ) {
			// set the active variable to false
			$active = false;
		}
		
		// return the active variable
		return $active;
	}
	
	add_filter( 'woocommerce_variation_is_active', 'home_base_variation_gray_out', 10, 2 );

	function home_base_woocommerce_product_single_add_to_cart_text( $text, $product ) {
		$terms = get_the_terms( get_the_ID(), 'product_cat' );
		if( $terms === false ) {
			return $text;
		}
		
		// Check is a course
		$terms_filtered = array_filter( $terms, fn( $t ) => $t->slug === "meet-the-designer" || $t->slug === "BDAWA" );
		if( count( $terms_filtered) === 0 ) {
			return $text;
		}
		
		return "Book consultation";
	}

	add_filter( 'woocommerce_product_single_add_to_cart_text', "home_base_woocommerce_product_single_add_to_cart_text", 20, 2 );

	// Increase inline AJAX requests
	add_filter( 'woocommerce_ajax_variation_threshold', fn ( $n ) => 100, 20, 1);	

	// Remove single product gallery zoom
	function home_base_remove_product_zoom() {
		remove_theme_support( 'wc-product-gallery-zoom' );
	}
	add_action( 'template_redirect', 'home_base_remove_product_zoom', 100 );

	// Breadcrumbs
	function home_base_change_breadcrumb_home_text( $defaults ) {
		// Change the breadcrumb home text from 'Home' to 'Apartment'
		$defaults['delimiter'] = '<span class="sep">&nbsp;&#47;&nbsp;</span>';
		$defaults['before'] = "<span class=\"link\">";
		$defaults['after'] = "</span>";
		// error_log( print_r( $defaults, true ), 0 );
		return $defaults;
	}
	add_filter( 'woocommerce_breadcrumb_defaults', 'home_base_change_breadcrumb_home_text', 100 );

	function homebase_woocommerce_checkout_before_customer_details() {
		$cart = WC()->cart;
		$show_attendees = 'hide';
		$no_attendees = 1;
		foreach($cart->get_cart() as $cartkey => $cartitem){
			if(has_term('Courses', 'product_cat', $cartitem['product_id'])){
				if($cartitem['quantity'] > 1){
					$show_attendees = 'show';
					$no_attendees = $cartitem['quantity'];
				}
			}
		}
		print "<div id=\"customer-order-details\" data-attendees=\"$show_attendees\" data-attendee-count=\"$no_attendees\"></div>";
	}
	add_action( 'woocommerce_checkout_before_customer_details', 'homebase_woocommerce_checkout_before_customer_details', 20 );