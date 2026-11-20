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
		// $terms = get_the_terms( get_the_ID(), 'product_cat' );
		// if( $terms !== false ) {
		// 	// Check is a course
		// 	$terms_filtered = array_filter( $terms, fn( $t ) => $t->slug === "meet-the-designer" || $t->slug === "BDAWA" );
		// 	if( count( $terms_filtered) === 0 ) {
		// 		return $html;
		// 	}
		// }
		
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
				$title = preg_replace(
						'/\b(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday)\s+/i',
						'$1<br />',
						$option
				);
				$radios .= "<li><input type=\"radio\" id=\"{$name}-{$option}\" name=\"{$name}\" value=\"{$option}\" {$checked}><label for=\"{$name}-{$option}\">{$title}</label></li>";
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

	/**
	 * Extra Product Options: render the "Before Add To Cart Button" sections
	 * (table.thwepo-extra-options) directly after the quantity field.
	 *
	 * The Add to Cart with Options block fires the classic add-to-cart hooks before
	 * and after its inner blocks, so nothing fires between the quantity selector and
	 * the button. Instead the plugin's output is captured where it normally renders
	 * and re-inserted after the quantity selector block (or the classic quantity hook).
	 */
	final class Home_Base_THWEPOF_After_Quantity {
		const HOOK = 'home_base_thwepof_before_add_to_cart_button';

		private static $in_block = 0;
		private static $pending  = '';

		public static function init() {
			// Point the plugin at our own action so we control where its output lands.
			add_filter( 'thwepof_hook_name_before_add_to_cart_button', fn() => self::HOOK );

			add_action( 'woocommerce_before_add_to_cart_button', [ __CLASS__, 'capture' ], 10 );
			add_action( 'woocommerce_after_add_to_cart_quantity', [ __CLASS__, 'flush_classic' ], 10 );

			add_filter( 'render_block_data', [ __CLASS__, 'block_start' ], 10, 1 );
			add_filter( 'render_block_woocommerce/add-to-cart-with-options-quantity-selector', [ __CLASS__, 'after_quantity_block' ], 10, 1 );
			add_filter( 'render_block_woocommerce/add-to-cart-with-options', [ __CLASS__, 'block_end' ], 10, 1 );
		}

		public static function capture() {
			global $product;

			ob_start();
			do_action( self::HOOK );
			$html = ob_get_clean();

			// Keep the field-name list where it was: it's required for validation, and the
			// block uses form inputs in this hook's output to decide to render a real <form>.
			if ( preg_match( '/<input[^>]*id="thwepof_product_fields"[^>]*>/', $html, $match ) ) {
				echo $match[0]; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped by the plugin.
				$html = str_replace( $match[0], '', $html );
			}

			// Grouped products have a quantity per child product, so leave their sections where they were.
			$is_grouped = $product instanceof WC_Product && $product->is_type( 'grouped' );
			if ( $is_grouped || '' === trim( $html ) ) {
				echo $html; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped by the plugin.
				return;
			}

			self::$pending = $html;
		}

		// Classic templates: woocommerce_after_add_to_cart_quantity fires right after the quantity input.
		public static function flush_classic() {
			if ( self::$in_block || '' === self::$pending ) {
				return;
			}
			echo self::$pending; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped by the plugin.
			self::$pending = '';
		}

		public static function block_start( $parsed_block ) {
			if ( isset( $parsed_block['blockName'] ) && 'woocommerce/add-to-cart-with-options' === $parsed_block['blockName'] ) {
				self::$in_block++;
			}
			return $parsed_block;
		}

		public static function after_quantity_block( $content ) {
			if ( ! self::$in_block || '' === self::$pending ) {
				return $content;
			}
			$content      .= self::$pending;
			self::$pending = '';
			return $content;
		}

		public static function block_end( $content ) {
			self::$in_block = max( 0, self::$in_block - 1 );

			// Safety net (e.g. no quantity selector in the template part): keep the fields inside the form.
			if ( '' !== self::$pending ) {
				$pos = strrpos( $content, '</form>' );
				if ( false !== $pos ) {
					$content = substr_replace( $content, self::$pending, $pos, 0 );
				}
				self::$pending = '';
			}
			return $content;
		}
	}
	Home_Base_THWEPOF_After_Quantity::init();


	/**
	 * Quantity choices: replace the quantity field with radio buttons for 1–4 items,
	 * each showing the price for that many (including WooCommerce Bulk Discount).
	 *
	 * This prints the prices for each product's add-to-cart form; src/app/js/quantity-choices.js
	 * builds the radios, hides the quantity field and keeps it in sync, so the
	 * normal `quantity` input is still what gets submitted and validated.
	 *
	 * Filters:
	 * - home_base_qty_choices_enabled ( bool $enabled, WC_Product $product )
	 * - home_base_qty_choices_max     ( int $max = 4, WC_Product $product )
	 * - home_base_qty_choice_label    ( string $label, int $qty, WC_Product $product )
	 */
	final class Home_Base_Quantity_Choices {
		private static $data = [];

		public static function init() {
			add_action( 'woocommerce_before_add_to_cart_form', [ __CLASS__, 'collect' ] );
			add_action( 'wp_footer', [ __CLASS__, 'print_data' ], 5 );
		}

		public static function collect() {
			global $product;

			if ( ! $product instanceof WC_Product || isset( self::$data[ $product->get_id() ] ) ) {
				return;
			}
			if ( ! $product->is_type( [ 'simple', 'variable' ] ) || $product->is_sold_individually() || ! $product->is_purchasable() ) {
				return;
			}
			if ( ! apply_filters( 'home_base_qty_choices_enabled', true, $product ) ) {
				return;
			}

			$max    = max( 1, (int) apply_filters( 'home_base_qty_choices_max', 4, $product ) );
			$labels = [];
			for ( $qty = 1; $qty <= $max; $qty++ ) {
				/* translators: %d: number of attendees */
				$labels[ $qty ] = apply_filters( 'home_base_qty_choice_label', sprintf( _n( '%d person', '%d people', $qty, 'bones_name' ), $qty ), $qty, $product );
			}

			$prices = [];
			if ( $product->is_type( 'variable' ) ) {
				foreach ( $product->get_available_variations( 'objects' ) as $variation ) {
					$prices[ $variation->get_id() ] = self::prices( $variation, $product, $max );
				}
			} else {
				$prices[ $product->get_id() ] = self::prices( $product, $product, $max );
			}

			self::$data[ $product->get_id() ] = [
				'max'    => $max,
				'labels' => $labels,
				'prices' => $prices,
			];
		}

		/**
		 * Line totals for 1..$max of a product, as price HTML (with the undiscounted price struck through).
		 */
		private static function prices( WC_Product $priced, WC_Product $parent, $max ) {
			$prices = [];
			for ( $qty = 1; $qty <= $max; $qty++ ) {
				$regular = (float) wc_get_price_to_display( $priced, [ 'qty' => $qty ] );
				$total   = self::apply_bulk_discount( $regular, $qty, $parent );

				$prices[ $qty ] = $total < $regular
					? wc_format_sale_price( $regular, $total )
					: wc_price( $total );
			}
			return $prices;
		}

		/**
		 * Mirrors Woo_Bulk_Discount_Plugin_t4m::get_discounted_coeff() and how it adjusts the
		 * cart line (that method is protected and the plugin instance isn't stored).
		 */
		private static function apply_bulk_discount( $line_total, $qty, WC_Product $parent ) {
			if ( ! class_exists( 'Woo_Bulk_Discount_Plugin_t4m' ) ) {
				return $line_total;
			}

			$product_id = $parent->get_id();
			$enabled    = get_post_meta( $product_id, '_bulkdiscount_enabled', true );
			if ( '' !== $enabled && 'yes' !== $enabled ) {
				return $line_total;
			}
			if ( 'yes' === get_option( 'woocommerce_t4m_remove_discount_on_coupon', 'yes' ) && WC()->cart && ! empty( WC()->cart->applied_coupons ) ) {
				return $line_total;
			}

			// The plugin reads its tiers from this private product instead, when it exists.
			$configurer = get_posts( [
				'post_type'      => 'product',
				'post_status'    => 'private',
				'title'          => 'wc_bulk_discount_configurer',
				'posts_per_page' => 1,
				'fields'         => 'ids',
			] );
			if ( $configurer ) {
				$product_id = $configurer[0];
			}

			$type     = get_option( 'woocommerce_t4m_discount_type', '' );
			$meta_key = 'flat' === $type ? '_bulkdiscount_discount_flat_' : ( 'fixed' === $type ? '_bulkdiscount_discount_fixed_' : '_bulkdiscount_discount_' );

			$best_qty = 0;
			$discount = 0.0;
			for ( $i = 1; $i <= 5; $i++ ) {
				$tier_qty = (float) get_post_meta( $product_id, "_bulkdiscount_quantity_$i", true );
				if ( $tier_qty > $best_qty && $qty >= $tier_qty ) {
					$best_qty = $tier_qty;
					$discount = (float) get_post_meta( $product_id, $meta_key . $i, true );
				}
			}

			if ( 'flat' === $type ) {
				// Flat amount off the whole line.
				return max( 0, $line_total - max( 0, $discount ) );
			}
			if ( 'fixed' === $type ) {
				// Fixed amount off each item.
				return max( 0, $line_total - max( 0, $discount * $qty ) );
			}
			// Percentage.
			return $line_total * min( 1.0, max( 0, ( 100.0 - round( $discount, 2 ) ) / 100.0 ) );
		}

		public static function print_data() {
			if ( empty( self::$data ) ) {
				return;
			}
			wp_print_inline_script_tag( 'window.homeBaseQtyChoices = ' . wp_json_encode( self::$data ) . ';' );
		}
	}
	Home_Base_Quantity_Choices::init();
