import "./style.scss";
import "./js/account";

import "toastify-js/src/toastify.css"
import "tiny-slider/src/tiny-slider.scss"

// Slider - Library import example
import { tns } from "tiny-slider";
import Toastify from 'toastify-js'

const showMessage = ( message ) => {
	Toastify({
		text: message,
		duration: 6000,
		gravity: "bottom", // `top` or `bottom`
		position: "center", // `left`, `center` or `right`
		escapeMarkup: false,
		style: {
			background: "#fff",
			color: "#000",
			boxShadow: "0px 2px 4px rgba(0, 0, 0, 0.5)",
			link: "#ff00ff"
		},
	}).showToast();
}

// // GSAP
// import { gsap } from 'gsap';
// import { ScrollTrigger } from 'gsap/ScrollTrigger';
// gsap.registerPlugin( ScrollTrigger );

const closeSearch = () => {
	const searches = document.querySelectorAll( ".wp-block-home-base-search-block input[type=\"text\"]" );
	if( searches.length > 1 ) {
		const first = searches.item(0);
		const value = first.value;
		for( let i = 1; i < searches.length; i++ ) {
			searches[i].value = value;
		}
	}
	document.body.classList.remove( "show-home-base-search" );
}

document.addEventListener( 'DOMContentLoaded', () => {
	// document.querySelectorAll( ".wp-block-navigation__responsive-container-open.always-shown" ).forEach( link => {
	// 	link.addEventListener( "click", ( e ) => {
	// 		console.log( "menu click" );
	// 	} );
	// } );

	// Menu scroll trigger
	const header = document.querySelector( "header.wp-block-template-part" );
	if( header ) {
		let lastScrollTop = window.scrollY;
		let scrolling = false;

		window.addEventListener('scroll', ( e ) => {
			if( !scrolling ) {
				scrolling = true;
				setTimeout(() => {
					// Scrolling up and scroll further that top 150px
					const scrollUp = lastScrollTop < window.scrollY && window.scrollY > 150;
					document.body.classList.toggle( "header-hide", scrollUp );
					lastScrollTop = window.scrollY;
					scrolling = false;
				}, 100);
			}
		} );
	}

	// Hide search
	document.body.addEventListener( "keydown", e => {
		if( e.key === "Escape" && document.body.classList.contains( "show-home-base-search" ) ) {
			closeSearch();
		}
	} );

	document.querySelector( ".wp-block-home-base-search-modal" )?.addEventListener( "click", ( e ) => {
		if( e.target.classList.contains( "wp-block-home-base-search-modal" ) ) {
			closeSearch();
		}
	} );

	document.querySelectorAll( '.wp-block-home-base-post-grid-list button' ).forEach( button => {
		button.addEventListener( "click", ( e ) => {
			const button = e.target.closest( "button" );
			if( !button.classList.contains("is-active") ) {
				const postTemplate = document.querySelector( ".wp-block-query .wp-block-post-template, .wp-block-display-categories-grid .wp-block-post-template" );
				
				button.closest( ".wp-block-home-base-post-grid-list" )?.querySelector( ".is-active" )?.classList.remove( "is-active" );
				button.classList.add( "is-active" );
				if( postTemplate ) {
					if( button.classList.contains( "grid" ) ) {
						postTemplate.classList.remove( "is-layout-flow" );
						postTemplate.classList.remove( "wp-block-post-template-is-layout-flow" );
						postTemplate.classList.add( "is-layout-grid" );
						postTemplate.classList.add( "wp-block-post-template-is-layout-grid" );
					}
					else {
						postTemplate.classList.add( "is-layout-flow" );
						postTemplate.classList.add( "wp-block-post-template-is-layout-flow" );
						postTemplate.classList.remove( "is-layout-grid" );
						postTemplate.classList.remove( "wp-block-post-template-is-layout-grid" );
					}
				}
			}
		} );
	} );

	// Display Categories Slider
	document.querySelectorAll( '.wp-block-display-categories-slider .wp-block-post-template, .wp-block-query.is-style-slider .wp-block-post-template' ).forEach( slider => {
		tns( {
			container: slider,
			items: 1,
			slideBy: 'page',
			autoplay: true,
			nav: true,
			gutter: 10,
			controls: false,
			autoplayButtonOutput: false,
			navPosition: "bottom",
			autoPlay: false,
			speed: 600,
			loop: true,
			responsive: {
				782: {
					gutter: 32,
				}
			}
			// startIndex: 2
		} );
	} );

	// Reviews Slider
	document.querySelectorAll( '.review-slider' ).forEach( slider => {
		tns( {
			container: slider,
			items: 1,
			slideBy: 'page',
			autoplay: true,
			nav: false,
			gutter: 16,
			controls: true,
			autoplayButtonOutput: false,
			navPosition: "bottom",
			autoPlay: false,
			speed: 600,
			loop: true,
			responsive: {
				782: {
					items: 3,
					gutter: 54,
				}
			}
			// startIndex: 2
		} );
	} );

	// Lazy load fade in
	document.querySelectorAll( 'img[loading="lazy"]' ).forEach( ( img ) => {
		if( img.complete === true ) {
			img.classList.add( 'has-loaded' );
		}
		img.addEventListener( "load", ( e ) => {
			e.target.classList.add( 'has-loaded' );
		} );
	} );

	// Show Search
	document.querySelector( ".wp-block-button.header-search-trigger" )?.addEventListener( "click", ( e ) => {
		e.preventDefault();
		document.body.classList.toggle( "show-home-base-search" );
	} );

	// Smooth scroll 
	document.querySelectorAll( '.wp-site-blocks a[href*="#"]' ).forEach( a => {
		a.addEventListener( "click", ( e ) => {
			const href = e.target.closest( 'a' ).href?.split( "#" );
			if( href.length === 2 && document.querySelector( `#${href[1]}` ) ) {
				e.preventDefault();
				document.querySelector( `#${href[1]}` ).scrollIntoView( { behavior: "smooth" } );
			}
		} );
	} );

	// My account dropdown
	document.querySelectorAll( ".wp-block-my-profile .config > a" ).forEach( a => {
		a.addEventListener( "click", e => {
			e.preventDefault();
			console.log( e.target.closest( "a" ).href );
			const config = e.target.closest( ".config" );
			if( config ) {
				config.querySelector( ".dropdown" )?.classList.toggle( "visible" );
			}
		} );
	} );

	// const $form = jQuery('form.variations_form.cart');
			
	// $form.on('check_variations', function( event ) {
	// 	// Requires timeout to work
	// 	setTimeout( () => {
	// 		document.querySelectorAll('form.variations_form.cart option:disabled').forEach( ( option ) => {
	// 			option.innerHTML =  option.innerHTML + ' (Out of Stock)'; 
	// 			option.disabled = false;
	// 			option.classList.add( 'enabled', true );
	// 		});
	// 	}, 200 );
	// });

	const blocks_added_to_cart = ( event ) => {
		if( document.body.classList.contains( 'cart-total-0' ) ) {
			document.body.classList.remove( 'cart-total-0' )
		}
	}
	
	document.body.addEventListener( 'wc-blocks_added_to_cart', blocks_added_to_cart );

	// document.body.removeEventListener( "wc-block-empty-cart-frontend" );
} );

// .is-layout-flow
// .wp-block-post-template-is-layout-flow

// .is-layout-grid
// .wp-block-post-template-is-layout-grid

function extractNumber(htmlString) {
  const regex = /\d+\.\d+/;
  const match = htmlString.match(regex);

  if (match) {
    return match[0]; // Returns the first match
  } else {
    return null; // Or you could return an empty string, or throw an error
  }
}

document.addEventListener( 'DOMContentLoaded', () => {
  // Listen for changes on any input inside .mc-variation-radios
  document.addEventListener( 'change', ( e ) => {
    if (e.target.matches('.mc-variation-radios input')) {
      // Get all checked radio buttons within .mc-variation-radios
      const checkedRadios = document.querySelectorAll('.mc-variation-radios input:checked');

      checkedRadios.forEach(function (radio) {
        const radioName = radio.name;
        const radioValue = radio.value;

        // Find the matching select element and set its value
        const select = document.querySelector('select[name="' + radioName + '"]');
        if (select) {
          select.value = radioValue;

          // Manually trigger the change event
          const event = new Event('change', { bubbles: true });
          select.dispatchEvent(event);
        }
      } );
    }
  } );

  // 
  const groupCheckboxes = document.querySelectorAll( ".woocommerce div.product.product-type-grouped input[type=\"checkbox\"]" );
  groupCheckboxes.forEach( ( checkbox, k ) => {
    checkbox.addEventListener( "click", e => {
      if( k < groupCheckboxes.length - 1 ) {
        // const othersDisabled = e.target.checked;
        let othersDisabled = false;
        for(let i = 0; i < groupCheckboxes.length - 1; i++ ) {
          othersDisabled = othersDisabled || groupCheckboxes[i].checked;
        }
        groupCheckboxes[groupCheckboxes.length - 1].disabled = othersDisabled;
        groupCheckboxes[groupCheckboxes.length - 1].checked = false;
      }
      else {
        const othersDisabled = e.target.checked;
        // Disable all others
        groupCheckboxes.forEach( c => {
          if( c !== e.target ) {
            c.disabled = othersDisabled;
            c.checked = false;
          }
        } );
      }

      // Price 
      let price = 0;
      for(let i = 0; i < groupCheckboxes.length; i++ ) {
        if( groupCheckboxes[i].checked ) {
          const amount = groupCheckboxes[i].closest( "tr" ).querySelector( ".amount" );
          if( amount ) {

            price += parseFloat( extractNumber( amount.innerHTML ) );
          }
        }
      }
      const priceHeading = document.querySelector( ".product-description .price" );
      if( priceHeading ) {
        if( priceHeading.dataset['og'] === undefined ) {
          priceHeading.dataset.og = document.querySelector( ".product-description .price" ).innerHTML;
        }

        if( price === 0 ) {
          priceHeading.innerHTML = priceHeading.dataset.og;
        }
        else {
          priceHeading.innerHTML = "$" + price;
        }
      }
    } );
  } );

	document.querySelectorAll( 'form.cart' ).forEach( ( cart ) => {
		let isAvailable = true;
		let variationsData = null;

		// Check if all variations are out of stock and hide form if they are
		if( cart.classList.contains( 'variations_form' ) ) {
			// Get variations JSON data and make an ovbject
			variationsData = JSON.parse( cart.dataset.product_variations );
			if( variationsData ) {
				const variationsInStock = variationsData.filter( ( v ) => v.is_in_stock ).map( v => v.attributes );
				
				cart.querySelectorAll( ".mc-variation-radios li" ).forEach( li => {
					const radioValue = li.querySelector( "input[type='radio']" )?.value;
					const radioName = li.querySelector( "input[type='radio']" )?.name;
					if( radioValue && radioName ) {
						const isEnabled = variationsInStock.filter( available => available[radioName] === radioValue );
						if( isEnabled.length === 0 ) {
							li.remove();
						}	
					}
				} );
			}
			// If all variations are out of stock place message informing of this
			// if( !isAvailable && document.querySelector( '.woocommerce-product-details__short-description' ) ) {
			// 	const outOfStockMessage = document.createElement( 'div' );
			// 	outOfStockMessage.classList.add( 'out-of-stock' );
			// 	outOfStockMessage.innerHTML = 'All variations are out of stock';
			// 	document.querySelector( '.woocommerce-product-details__short-description' ).appendChild( outOfStockMessage );
			// }
		}
	} );

	// const $form = jQuery('form.variations_form.cart');
	// $form.on('check_variations', function( event ) {
	// 	console.log( 'check_variations' );
	// } );

	document.querySelectorAll( ".requires-login" ).forEach( link => {
		// console.log( link );
		// showMessage( "This feature requires <a href=\"#\">logging in</a>" );
		link.addEventListener( "click", ( e ) => {
			e.preventDefault();
			showMessage( "This feature requires you log in to <a href=\"/my-account\">My Account</a>" );
		} );
	} );
} );