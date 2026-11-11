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

  // Restrict a WP_Query's 's' search to specific post columns (e.g. title only, or content only)
  function hb_search_columns_filter( array $columns ) {
    return function( $search, $wp_query ) use ( $columns ) {
      global $wpdb;
      if ( empty( $wp_query->query_vars['search_terms'] ) ) {
        return $search;
      }
      $q = $wp_query->query_vars;
      $n = ! empty( $q['exact'] ) ? '' : '%';
      $clauses = '';
      $searchand = '';
      foreach ( (array) $q['search_terms'] as $term ) {
        $like = $n . $wpdb->esc_like( $term ) . $n;
        $ors = [];
        foreach ( $columns as $col ) {
          $ors[] = $wpdb->prepare( "{$wpdb->posts}.`$col` LIKE %s", $like );
        }
        $clauses .= "{$searchand}(" . implode( ' OR ', $ors ) . ')';
        $searchand = ' AND ';
      }
      return $clauses !== '' ? " AND ({$clauses}) " : $search;
    };
  }

  /**
   * Display search helpers
   *
   * Matching rules (agreed with client):
   *  - Multi-word searches must match as a phrase (words adjacent, in order), or all
   *    words must appear within the same single tag. Words scattered across a listing
   *    no longer match.
   *  - Singular/plural variants match each other (vanity/vanities, kitchen/kitchens,
   *    glass/glasses, bench/benches).
   *  - Tags and titles allow partial-word matches (e.g. "bli" finds "public", "blinds").
   *  - Content (description copy) only matches whole words, so "custom" no longer
   *    matches "customers". Markup/block attributes are stripped before matching.
   */
  function hb_search_stopwords() {
    return [ 'a', 'an', 'and', 'or', 'the', 'of', 'for', 'with', 'in', 'on', 'to', 'n' ];
  }

  // Split a search string into lowercase words, dropping stopwords when other words remain
  function hb_search_words( $s ) {
    $words = preg_split( '/[^\p{L}\p{N}]+/u', mb_strtolower( $s ), -1, PREG_SPLIT_NO_EMPTY );
    $kept  = array_values( array_diff( $words, hb_search_stopwords() ) );
    return $kept ?: $words;
  }

  // Regex fragment for one word that also matches its singular/plural variants
  function hb_search_word_pattern( $w ) {
    $len = mb_strlen( $w );
    if ( $len > 4 && preg_match( '/ies$/u', $w ) ) {
      // vanities -> vanit(y|ies)
      return preg_quote( mb_substr( $w, 0, -3 ), '/' ) . '(?:y|ies)';
    }
    if ( $len > 2 && preg_match( '/[^aeiou]y$/u', $w ) ) {
      // vanity -> vanit(y|ies)
      return preg_quote( mb_substr( $w, 0, -1 ), '/' ) . '(?:y|ies)';
    }
    if ( $len > 3 && preg_match( '/(?:s|x|z|ch|sh)es$/u', $w ) ) {
      // benches -> bench(es)
      return preg_quote( mb_substr( $w, 0, -2 ), '/' ) . '(?:es)?';
    }
    if ( $len > 3 && preg_match( '/[^s]s$/u', $w ) ) {
      // kitchens -> kitchen(s|es)
      return preg_quote( mb_substr( $w, 0, -1 ), '/' ) . '(?:s|es)?';
    }
    // kitchen -> kitchen(s|es)
    return preg_quote( $w, '/' ) . '(?:s|es)?';
  }

  // Regex fragment for the whole phrase; words may be separated by spaces/punctuation
  // and optionally one connecting word ("kitchens and bathrooms", "kitchens & bathrooms")
  function hb_search_phrase_pattern( array $words ) {
    $connectors = implode( '|', hb_search_stopwords() );
    $separator  = '[^\p{L}\p{N}]+(?:(?:' . $connectors . ')[^\p{L}\p{N}]+)?';
    return implode( $separator, array_map( 'hb_search_word_pattern', $words ) );
  }

  // Plain, lowercase text for matching (no HTML, block comments, shortcodes or entities)
  function hb_search_clean_text( $text ) {
    $text = strip_shortcodes( (string) $text );
    $text = wp_strip_all_tags( $text );
    $text = html_entity_decode( $text, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
    return mb_strtolower( preg_replace( '/\s+/u', ' ', $text ) );
  }

  /**
   * Search displays.
   *
   * Returns [ 'displays' => primary matches, 'other_displays' => lower-ranked matches ].
   *  - Default:      primary = tag + title matches, other = content-only matches
   *  - $tag_mode:    the user picked a tag suggestion, so primary = displays with that
   *                  exact tag, other = everything else that matches
   */
  function hb_search_displays( $s, $tag_mode = false, $limit = 30 ) {
    $words = hb_search_words( $s );
    if ( empty( $words ) ) {
      return [ 'displays' => [], 'other_displays' => [] ];
    }

    $phrase        = hb_search_phrase_pattern( $words );
    $re_exact      = '/^' . $phrase . '$/iu';
    $re_partial    = '/' . $phrase . '/iu';
    $re_whole      = '/(?<![\p{L}\p{N}])' . $phrase . '(?![\p{L}\p{N}])/iu';
    $re_words_each = array_map( function( $w ) { return '/' . hb_search_word_pattern( $w ) . '/iu'; }, $words );

    // All active displays (≈250, so matching in PHP is cheap and lets us strip markup)
    $display_ids = get_posts( [
      'post_type'      => 'display',
      'post_status'    => 'publish',
      'posts_per_page' => -1,
      'fields'         => 'ids',
      'meta_key'       => 'salesforce_active',
      'meta_value'     => '1',
      'meta_compare'   => '=',
    ] );
    if ( empty( $display_ids ) ) {
      return [ 'displays' => [], 'other_displays' => [] ];
    }

    // Tags per display
    $tags_by_post = [];
    $terms = wp_get_object_terms( $display_ids, 'post_tag', [ 'fields' => 'all_with_object_id' ] );
    if ( ! is_wp_error( $terms ) ) {
      foreach ( $terms as $t ) {
        $tags_by_post[ $t->object_id ][] = mb_strtolower( html_entity_decode( $t->name, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
      }
    }

    // Each word as a complete tag on its own (allowing singular/plural)
    $re_words_tag = array_map( function( $w ) {
      return '/^' . hb_search_word_pattern( $w ) . '$/iu';
    }, $words );

    $tiers = [ 'exact_tag' => [], 'partial_tag' => [], 'title' => [], 'across_tags' => [], 'content' => [] ];

    foreach ( $display_ids as $id ) {
      $post = get_post( $id );
      $tags = $tags_by_post[ $id ] ?? [];

      // 1. Exact tag (allowing singular/plural)
      foreach ( $tags as $tag ) {
        if ( preg_match( $re_exact, $tag ) ) {
          $tiers['exact_tag'][] = $id;
          continue 2;
        }
      }

      // 2. Tag contains the phrase, or contains every search word
      foreach ( $tags as $tag ) {
        if ( preg_match( $re_partial, $tag ) ) {
          $tiers['partial_tag'][] = $id;
          continue 2;
        }
        $all = true;
        foreach ( $re_words_each as $re ) {
          if ( ! preg_match( $re, $tag ) ) { $all = false; break; }
        }
        if ( $all ) {
          $tiers['partial_tag'][] = $id;
          continue 2;
        }
      }

      // 3. Title contains the phrase
      if ( preg_match( $re_partial, hb_search_clean_text( $post->post_title ) ) ) {
        $tiers['title'][] = $id;
        continue;
      }

      // 4. Multi-word only: every word is itself one of the display's tags
      //    (e.g. tags "custom" + "vanities" for "custom vanities"). Words inside longer
      //    tags don't count, so "outdoors" + "kitchen flooring" won't match "outdoor kitchens".
      if ( count( $words ) > 1 && $tags ) {
        $all = true;
        foreach ( $re_words_tag as $re ) {
          if ( ! preg_grep( $re, $tags ) ) { $all = false; break; }
        }
        if ( $all ) {
          $tiers['across_tags'][] = $id;
          continue;
        }
      }

      // 5. Content/excerpt contains the phrase as whole words
      $content = hb_search_clean_text( $post->post_content . ' ' . $post->post_excerpt );
      if ( preg_match( $re_whole, $content ) ) {
        $tiers['content'][] = $id;
      }
    }

    if ( $tag_mode ) {
      $primary = $tiers['exact_tag'];
      $other   = array_merge( $tiers['partial_tag'], $tiers['title'], $tiers['across_tags'], $tiers['content'] );
    } else {
      $primary = array_merge( $tiers['exact_tag'], $tiers['partial_tag'], $tiers['title'] );
      $other   = array_merge( $tiers['across_tags'], $tiers['content'] );
    }

    $format = function( $id ) {
      return [
        'url'          => get_permalink( $id ),
        'title'        => get_the_title( $id ),
        'description'  => get_the_excerpt( $id ),
        'price'        => '',
        'thumbnail'    => get_the_post_thumbnail_url( $id, 'post-thumbnail' ),
        'location_map' => bones_theme_display_location_and_map( $id ),
      ];
    };

    return [
      'displays'       => array_map( $format, array_slice( $primary, 0, $limit ) ),
      'other_displays' => array_map( $format, array_slice( $other, 0, $limit ) ),
    ];
  }

  // Search
  function hb_search() {
    global $wpdb;
    
    // $max_cat_results = 5;
    $max_product_results = 10;
    
    // $repsonse = '';
    $s = strtolower(sanitize_text_field($_REQUEST['s']));
    $post_type = isset( $_REQUEST['post_type'] ) ? strtolower(sanitize_text_field($_REQUEST['post_type'])) : '';
    // Set when the user picked a tag suggestion rather than typing freely
    $tag_mode = ! empty( $_REQUEST['tag'] );

    // Check for cached values (keyed on everything that changes the result)
    $cache_key = 'search_' . md5( $s . '|' . $post_type . '|' . (int) $tag_mode );
    $results = wp_cache_get( $cache_key, 'gcc' );

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
          'post_status'    => 'publish',
          's'              => $s,
          'posts_per_page' => 30,
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
        $results = array_merge( $results, hb_search_displays( $s, $tag_mode ) );
      }

      // Pages
      // if( $post_type === 'page' || $post_type === '' ) {
      //   $pages = new WP_Query( [ 
      //     'post_type'      => 'page',
      //     'post_status'    => 'publish',
      //     's'              => $s,
      //     'posts_per_page' => 5,
      //   ] );

      //   if ( $pages->have_posts() ) {
      //     while ( $pages->have_posts() ) {
      //       $pages->the_post();
      //       $results['pages'][] = [ 
      //         'url'         => get_permalink(),
      //         'title'       => get_the_title(),
      //         'description' => get_the_excerpt(),
      //         'price'       => '',
      //         'thumbnail'   => get_the_post_thumbnail_url( get_the_ID(), 'post-thumbnail' ),
      //       ];
      //     }
      //   } 
      //   wp_reset_postdata();
      // }

      // Products
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
      }
      wp_cache_set( $cache_key, $results, 'gcc', 900 );
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

  function hb_tag_suggestions() {
    global $wpdb;

    // Limit to tags used on published displays when the search is filtered to displays
    $post_type = ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'display' ) ? 'display' : '';
    $cache_key = $post_type ? "homebase_display_tags_display" : "homebase_display_tags";

    // Check for cached tags
    if( $tags = wp_cache_get( $cache_key, 'hb' ) ) {
      wp_send_json_success( $tags );
      wp_die();
    }

    if( $post_type ) {
      // get_terms() ignores post_type, so query tags attached to published posts of this type
      $names = $wpdb->get_col( $wpdb->prepare(
        "SELECT DISTINCT t.name
          FROM {$wpdb->terms} t
          INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_id = t.term_id AND tt.taxonomy = 'post_tag'
          INNER JOIN {$wpdb->term_relationships} tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
          INNER JOIN {$wpdb->posts} p ON p.ID = tr.object_id AND p.post_type = %s AND p.post_status = 'publish'
          ORDER BY t.name ASC",
        $post_type
      ) );
    } else {
      $names = get_terms( [ 
        'taxonomy'   => 'post_tag',
        'hide_empty' => true,
        'fields'     => 'names',
      ] );
    }

    $tags = [];

    if ( !empty( $names ) && !is_wp_error( $names ) ) {
      $tags = array_values( array_unique( array_map( 'strtolower', $names ) ) );
      wp_cache_set( $cache_key, $tags, 'hb', 900 );
    }

    wp_send_json_success( $tags );
    
    wp_die();
  }

  add_action( 'wp_ajax_hb_search_tags', 'hb_tag_suggestions' );
  add_action( 'wp_ajax_nopriv_hb_search_tags', 'hb_tag_suggestions' );

  function hb_migrate_display_galleries() {
    global $wpdb;
    // print_r( 1, true );
    // wp_die();
    
    if( !isset( $_REQUEST['id'] ) ) {
      print 'Missing params';
      wp_die();
    }
    
    $id = intval( $_REQUEST['id'] );

    // Get all existing gallery IDs
    // SELECT * FROM `wp_p2p` WHERE `p2p_from` = 49178
    $p2p_ids = $wpdb->get_col( 
      $wpdb->prepare( 
        "SELECT `p2p_to` FROM `{$wpdb->prefix}p2p` WHERE `p2p_from` = %d", 
        $id 
      )
    );

    // error_log( print_r( $p2p_ids, true ), 0 );

    $gallery = [];
    foreach( $p2p_ids as $pId ) {
      // 49275
      $title = $wpdb->get_var( 
        $wpdb->prepare( "SELECT `meta_value` FROM `{$wpdb->prefix}postmeta` WHERE `post_id` = %d AND `meta_key` = 'gallery_title'", $pId ) 
      );
      $file = $wpdb->get_var( 
        $wpdb->prepare( "SELECT `meta_value` FROM `{$wpdb->prefix}postmeta` WHERE `post_id` = %d AND `meta_key` = 'file'", $pId ) 
      ); // 49265

      // error_log( print_r( $title, true ), 0 );

      // Add title to media
      if( $file && $title ) {
        $attachment = [ 
          'ID'         => $file,
          'post_title' => $title,
        ];
        wp_update_post( $attachment );
        // error_log( print_r( $attachment, true ), 0 );
      }

      // Add image to ACF Gallery
      if( $file ) {
        $gallery[] = $file;
      }
    }
    // Push new gallery on to ACF field
    update_field( "gallery", $gallery , $id );
    // error_log( print_r( $gallery, true ), 0 );

    wp_send_json_success( $id );
    
    wp_die();
  }

  add_action( 'wp_ajax_hb_migrate_display_galleries', 'hb_migrate_display_galleries' );
  add_action( 'wp_ajax_nopriv_hb_migrate_display_galleries', 'hb_migrate_display_galleries' );