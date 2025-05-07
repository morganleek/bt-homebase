<?php

	// Init 
  function hb_install() {
    // Grab Categories and Store in Option
		if( get_option( 'hb_product_cat' ) === false ) {
			hb_update_product_cat_cache();
		}
  }

  function hb_modified_term($term_id, $tt_id, $taxonomy) {
    if($taxonomy == "product_cat") {
      // Update terms list
      hb_update_product_cat_cache();
    }
  }
  add_action( 'create_term', 'hb_modified_term', 10, 3 );
  add_action( 'edited_term', 'hb_modified_term', 10, 3 ); 
  add_action( 'delete_term', 'hb_modified_term', 10, 5 ); 

  function hb_update_product_cat_cache() {
    $terms = get_terms( [ 
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
		] );

    $store = [];
    foreach($terms as $t) {
      $store[$t->term_id] = strtolower($t->name);
    }

    update_option('hb_product_cat', $store);
  }

  // Init Hook
	hb_install();

  // Regify Search
  function regifySearch( $s = '' ) {
    // Split by words
    $regex = '/\b[a-z][A-Z]+?\b/i';
    // Returned Matches
    $matches = [];
    preg_match_all( $regex, $s, $matches );
    if( !empty( $matches ) ) {
      // Rejoin with SQL Regex Wild
      return implode( '.*', $matches[0] );
    }
    return '';
  }

  // Search
  function hb_search() {
    global $wpdb;
    
    // $max_cat_results = 5;
    $max_product_results = 10;
    
    // $repsonse = '';
    $s = strtolower(sanitize_text_field($_REQUEST['s']));
    $post_type = isset( $_REQUEST['post_type'] ) ? strtolower(sanitize_text_field($_REQUEST['post_type'])) : '';

    // Check for cached values
    $results = wp_cache_get( "search_$s", 'gcc' );

    if( !$results ) {
      $reg_string = regifySearch( $s );
  
      if(empty($s)) {
        wp_send_json_error('Empty search field');
      }
      
      $results = [];
      $results['s'] = $s;

      if( $post_type === 'post' || $post_type === '' ) {
        // News articles
        $posts = new WP_Query( [ 
          'post_type'      => 'post',
          's'              => $s,
          'posts_per_page' => 5,
        ] );
  
        if ( $posts->have_posts() ) {
          while ( $posts->have_posts() ) {
            $posts->the_post();
            $results['posts'][] = [ 
              'url'         => get_permalink(),
              'title'       => get_the_title(),
              'description' => get_the_excerpt(),
              'price'       => '',
              'thumbnail'   => get_the_post_thumbnail_url( get_the_ID(), 'post-thumbnail' ),
            ];
          }
        } 
  
        wp_reset_postdata();
      }
      
      if( $post_type === 'display' || $post_type === '' ) {
        // Displays
        $displays = new WP_Query( [ 
          'post_type'      => 'display',
          's'              => $s,
          'posts_per_page' => 5,
        ] );
  
        if ( $displays->have_posts() ) {
          while ( $displays->have_posts() ) {
            $displays->the_post();
            $results['displays'][] = [ 
              'url'         => get_permalink(),
              'title'       => get_the_title(),
              'description' => get_the_excerpt(),
              'price'       => '',
              'thumbnail'   => get_the_post_thumbnail_url( get_the_ID(), 'post-thumbnail' ),
            ];
          }
        } 
  
        wp_reset_postdata();
      }

      // Pages
      if( $post_type === 'page' || $post_type === '' ) {
        $pages = new WP_Query( [ 
          'post_type'      => 'page',
          's'              => $s,
          'posts_per_page' => 5,
        ] );

        if ( $pages->have_posts() ) {
          while ( $pages->have_posts() ) {
            $pages->the_post();
            $results['pages'][] = [ 
              'url'         => get_permalink(),
              'title'       => get_the_title(),
              'description' => get_the_excerpt(),
              'price'       => '',
              'thumbnail'   => get_the_post_thumbnail_url( get_the_ID(), 'post-thumbnail' ),
            ];
          }
        } 

        wp_reset_postdata();
      }

      if( $post_type === 'product' || $post_type === '' ) {
        // Products via WP_Query
        $sql = 'SELECT *,
              IF(`post_title`   REGEXP "%1$s",  20, 0 )
            + IF(`post_excerpt` REGEXP "%1$s", 5,  0)
            + IF(`post_name`    REGEXP "%1$s", 1,  0)
          AS `weight`
          FROM (
            SELECT * FROM `wp_posts` 
            WHERE `post_type` = "product" 
            AND `post_status` = "publish"
          ) AS C
          WHERE (
              `post_title`   REGEXP "%1$s" 
            OR `post_excerpt` REGEXP "%1$s"
            OR `post_name`    REGEXP "%1$s"
          )
          ORDER BY `weight` DESC
          LIMIT %2$d';
        $prepare = $wpdb->prepare(
          $sql, 
          $reg_string,
          $max_product_results
        );
        // $results['prod_sql'] = $prepare;
          
        $sql_results = $wpdb->get_results( $prepare );
        
        if($sql_results) {
          foreach($sql_results as $sql_result) {
            $product = wc_get_product( $sql_result->ID );
            // Thumb
            $product_thumb_id = $product->get_image_id( );
            $image_array = wp_get_attachment_image_src( $product_thumb_id, array(150, 150) );
            $image = '';
            if($image_array !== false) {
              $image = $image_array[0]; // URL
            }
            // Add to Results Array
            $results['products'][] = [ 
              'url'         => get_permalink( $sql_result->ID ),
              'title'       => $sql_result->post_title,
              'description' => wp_trim_words( $product->get_short_description(), 8, '&hellip;' ),
              'price'       => wc_price( $product->get_price() ),
              'thumbnail'   => $image,
            ];
          }
        }

        // Cache values for 15min
        wp_cache_set( "search_$s", $results, 'gcc', 900 );
      }
    }

    wp_send_json_success( $results );
    
    wp_die();
  }

  // Ajax
  add_action( 'wp_ajax_hb_search', 'hb_search' );
  add_action( 'wp_ajax_nopriv_hb_search', 'hb_search' );

  // Record Popular Searches After Click

  // Enqueue Scripts
  // function hb_enqueue_scripts() {
  //   wp_enqueue_script( 'woo-search-ggc', hb__PLUGIN_URL . 'dist/js/woo-search-ggc.js', array('jquery'), '1.0.0' );
  //   wp_localize_script( 'woo-search-ggc', 'ggc', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

  //   wp_enqueue_style('woo-search-ggc', hb__PLUGIN_URL . 'dist/css/woo-search-ggc.css', array(), '1.0.0' );
  //   wp_localize_script( 'woo-search-ggc', 'gcc_site', array(
  //     'ajax_url' => admin_url( 'admin-ajax.php' ),
  //     'template_directory' => get_template_directory_uri()
  //   ));
  // }

  // add_action( 'wp_enqueue_scripts', 'hb_enqueue_scripts', 10 );

  // Add Results Div
  // function gcc_footer_results() {
  //   print '<div class="wp-block-woocommerce-ajax-search-results">
  //     <div class="la-ball-fall la-dark">
  //       <div></div>
  //       <div></div>
  //       <div></div>
  //     </div>
  //     <div class="inner"></div>
  //     <div class="more"></div>
  //   </div>';
  // }

  // add_action( 'wp_footer', 'gcc_footer_results' );

	// For the dropdown
	// function hb_catalog_orderby_options( $catalog_orderby_options ) {
	// 	return array_merge(
	// 	[ 'availability' => 'Availability' ], 
	// 		$catalog_orderby_options 
	// 	);
	// }

	// add_filter( 'woocommerce_catalog_orderby', 'hb_catalog_orderby_options', 20, 1 );
	// add_filter( 'woocommerce_default_catalog_orderby_options', 'hb_catalog_orderby_options', 20, 1 );

	/**
	 * Add stock level to search query ORDER by availability
	 * 
	 * Required for both search, shop and category listing
	 */
	function hb_the_posts( $query ) {
		// Is WC Query
		// Check either product_cat or post_type query attributes
		$is_wc_search_query = ( isset( $query->query['post_type'] ) && $query->query['post_type'] == 'product' && 'product_query' == $query->get( 'wc_query' ) ) ?: false;
		$is_wc_category_query = ( isset( $query->query['product_cat'] ) && 'product_query' == $query->get( 'wc_query' ) ) ?: false;
		$is_wc_filter_query = isset( $query->query['gcc_filters_advanced_products_loop'] ) ?: false;

		if( $is_wc_search_query || $is_wc_category_query || $is_wc_filter_query ) {
			$query->query['post_type'] = 'product';
			// $query->query_vars['wc_query'] = 'product_query';
			// $query->query_vars['post_type'] = 'product';
			if( !isset( $query->query['orderby'] ) || $query->query['orderby'] == 'availability' ) {
				$query->query_vars['meta_query'] = array(
					'stock_status' => array(
						'key' => '_stock_status',
						'compare' => 'EXISTS' // 'value' => 'instock' // 'outofstock'
					)
				);

				$query->query_vars['orderby'] = array( 'stock_status' => 'ASC', 'title' => 'ASC' );
				
				if( !isset( $_GET['orderby'] ) ) {
					// Stop it defaulting to relevance 
					$_GET['orderby'] = 'availability';
				}
			}
		}

		return $query;
	}

	add_filter( 'pre_get_posts', 'hb_the_posts', 15, 1 );

	/**
	 * Additional ORDERING by search term in title
	 * 
	 * Only required in search
	 */
	function gcc_posts_request( $request, $query ) {		
		// Is product search
		if( !empty( $query->query['s'] ) && isset( $query->query['post_type'] ) && $query->query['post_type'] == 'product' ) {
			global $wpdb;

			// Search term
			$search = $query->query['s'];
			
			// Add weighted score for titles
			$weight = ' IF(`post_title` REGEXP "%1$s", 1, 0) AS `weight` ';
			
			// Add weighting ranking
			$request_updated = str_replace( 'wp_posts.*', "wp_posts.*, $weight", $request );
			$request_updated = $wpdb->prepare( $request_updated, $search );

			// Ordering updated
			$request_ordering = str_replace( 'wp_posts.post_title ASC', 'weight DESC, wp_posts.post_title ASC', $request_updated );

			return $request_ordering;
		}
		return $request;
	}

	add_filter( 'posts_request', 'gcc_posts_request', 10, 2 );