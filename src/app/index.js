import "./style.scss";
import "./js/account";

import "toastify-js/src/toastify.css"
import "tiny-slider/src/tiny-slider.scss"

// Slider - Library import example
import { tns } from "tiny-slider";

document.addEventListener( 'DOMContentLoaded', () => {
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
			gutter: 32,
			controls: false,
			autoplayButtonOutput: false,
			navPosition: "bottom",
			autoPlay: false,
			speed: 600,
			loop: true
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
} );

// .is-layout-flow
// .wp-block-post-template-is-layout-flow

// .is-layout-grid
// .wp-block-post-template-is-layout-grid
