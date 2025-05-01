import "./style.scss";
import "./js/account";

import "toastify-js/src/toastify.css"
import "tiny-slider/src/tiny-slider.scss"

// Slider - Library import example
import { tns } from "tiny-slider";

console.log( 'here' );

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
} );

// .is-layout-flow
// .wp-block-post-template-is-layout-flow

// .is-layout-grid
// .wp-block-post-template-is-layout-grid
