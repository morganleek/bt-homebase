// import axios from 'axios';

// const api = axios.create({
// 	baseURL: window.location.origin
// });

// // Allowed Search Inputs
// const searchInputs = "header.wp-block-template-part .product-search form .wp-block-search__inside-wrapper input[type=search]";
// const searchForms = "header.wp-block-template-part .product-search form";
// const wooSearchInput = "<input type=\"hidden\" name=\"post_type\" value=\"product\">";
// let currentInput = null;
// // let windowResize;
// const cacheTimeout = 60 * 60; // 1 Hour
// let inputTimeout;

// const handleClickOutside = ( e ) => {
// 	if( e.target.closest( 'header.wp-block-template-part .product-search' ) === null ) {
// 		document.querySelector( 'header.wp-block-template-part .product-search' ).classList.remove( 'is-active' );
// 	}
// }

// document.addEventListener( 'DOMContentLoaded', () => {
// 	// Hide Search 
// 	document.addEventListener( 'click', handleClickOutside );

// 	// Search trigger
// 	document.querySelector( 'header.wp-block-template-part .product-search figure > a' )?.addEventListener( 'click', ( e ) => {
// 		e.preventDefault();
// 		// Makes form appear
// 		document.querySelector( 'header.wp-block-template-part .product-search' )?.classList.toggle( 'is-active' );
// 		// Tells menus to not appear
// 		document.querySelector( 'header.wp-block-template-part' )?.classList.toggle( 'is-search-active' );
// 		// Give form input focus
// 		document.querySelector( '.product-search form input[type=search]' ).focus();
// 	} );

// 	if( document.querySelector( searchForms ) ) {
// 		// Append search results after the form
// 		const searchResults = document.createElement( 'div' );
// 		searchResults.classList.add( "wp-block-woocommerce-ajax-search-results" );
// 		searchResults.innerHTML = '<div class="la-ball-fall la-dark"><div></div><div></div><div></div></div><div class="inner"><div class="products-results"></div><div class="category-results"></div><div class="post-results"></div></div><div class="more"></div>';
// 		document.querySelector( searchForms ).appendChild( searchResults );

// 		const searchInput = document.querySelector( searchInputs );

// 		// Rest
// 		jQuery(searchForms).each(function() {
// 			if(jQuery(this).find('[name="post_type"]').length === 0) {
// 				jQuery(this).prepend(wooSearchInput);
// 			}
// 		});

// 		searchInput.addEventListener( 'keydown', ( e ) => { 
// 			// If already typing stop previous wait
// 			clearTimeout( inputTimeout );
// 			// Wait 500ms after key press to ensure typing has finished
// 			inputTimeout = setTimeout( () => {
// 				currentInput = e.target.value;
// 				if( currentInput.length === 0 ) {
// 					searchResults.classList.remove( 'in-search' );
// 				}
// 				else if( currentInput.length > 2 && validKeyStroke( e ) ) {
// 					searchResults.classList.add( 'in-search', 'loading' );
// 					document.querySelector( '.wp-block-woocommerce-ajax-search-results .more' ).innerHTML = '';
		
// 					// Fetch fresh
// 					api.get( woocommerce_params.ajax_url + '?action=ggc_search&s=' + currentInput ).then( ( values ) => {
// 						searchResults.classList.remove( 'loading' );
	
// 						const { data } = values.data;
	
// 						if( data.products !== undefined ) {
// 							if( document.querySelector( '.wp-block-woocommerce-ajax-search-results .products-results' ) ) {
// 								document.querySelector( '.wp-block-woocommerce-ajax-search-results .products-results' ).innerHTML = renderResults( data.products, 'Products' );
// 							}
// 						}
						
// 						if( data.categories !== undefined ) {
// 							if( document.querySelector( '.wp-block-woocommerce-ajax-search-results .category-results' ) ) {
// 								document.querySelector( '.wp-block-woocommerce-ajax-search-results .category-results' ).innerHTML = renderResults( data.categories, 'Category' );
// 							}
// 						}
	
// 						if( data.posts !== undefined ) {
// 							if( document.querySelector( '.wp-block-woocommerce-ajax-search-results .post-results' ) ) {
// 								document.querySelector( '.wp-block-woocommerce-ajax-search-results .post-results' ).innerHTML = renderResults( data.posts, 'Articles' );
// 							}
// 						}
	
// 						// if( data.pages !== undefined ) {
// 						// 	if( document.querySelector( '.wp-block-woocommerce-ajax-search-results .page-results' ) ) {
// 						// 		document.querySelector( '.wp-block-woocommerce-ajax-search-results .page-results' ).innerHTML = renderResults( data.pages, 'Page' );
// 						// 	}
// 						// }
// 					} );
// 				}
// 			}, 500 );
// 		} );
// 	}
// } );

// function renderResults(items, title) {
// 	var html = '';
// 	html += '<h4>' + title + '</h4>';
// 	html += '<ul>';
// 		for(var i = 0; i < items.length; i++) {
// 			html += '<li>';
// 				html += (items[i].url.length > 0) ? '<a href="' + items[i].url + '">' : '';
// 					html += '<div class="content">';
// 						html += (items[i].title.length > 0) ? '<p class="title"><strong>' + items[i].title + '</strong></p>' : '';
// 						html += (items[i].description.length > 0) ? '<p class="description">' + items[i].description + '</p>' : '';
// 						html += (items[i].price.length > 0) ? '<p class="price"><strong>' + items[i].price + '</strong></p>' : '';
// 					html += '</div>';
// 					html += (items[i].thumbnail.length > 0) ? '<img src="' + items[i].thumbnail + '" alt="">' : ''; //  '<div class="placeholder"></div>';
// 				html += (items[i].url.length > 0) ? '</a>' : '';
// 			html += '</li>';
// 		}
// 	html += '</ul>';
	
// 	return html;
// }

// function validKeyStroke(e) {
// 	return ((e.keyCode > 33 && e.keyCode < 126 && e.keyCode !== 91) || e.keyCode === 8) ? true : false;
// }