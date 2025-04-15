import axios, {isCancel, AxiosError} from 'axios';
import Toastify from 'toastify-js'
import Flickity from 'flickity';

let collection = null;

const showMessage = ( message ) => {
	Toastify({
		text: message,
		duration: 3000,
		gravity: "bottom", // `top` or `bottom`
		position: "center", // `left`, `center` or `right`
		escapeMarkup: false,
		style: {
			background: "#fff",
			color: "#000",
			boxShadow: "0px 2px 4px rgba(0, 0, 0, 0.5)",
		},
	}).showToast();
}

// close modals
const closeModals = () => {
	document.body.classList.remove( 
		"collection_action", "collection_action_new", "collection_action_image", "collection_action_edit", 
		"collection_action_delete", "collection_action_loading", "collection_action_edit_image", 
		"collection_action_complete", "account_saved", "collection_action_notes", 
		"collection_action_manage_image", "collection_action_remove_image", "collection_help");
} 

document.addEventListener( 'DOMContentLoaded', () => {
	if( document.querySelector( '.display-save' ) ) {
		document.querySelectorAll( '.display-save .heart, .display-save .display-brochure' ).forEach( button => {
			button.addEventListener( 'click', ( e ) => {
				const isUnsave = e.target.classList.contains( 'unsave' );
				const data = {
					action: isUnsave ? 'homebase_unsave' : 'homebase_save',
					post_ID: e.target.dataset.postId,
					post_type: e.target.dataset.postType
				};
				const postTypeTitles = {
					'post': 'Blog Post',
					'display': 'Display'
				};
		
				let successMessage = "";
				if( e.target.classList.contains( 'heart' ) ) {
					if( isUnsave ) {
						successMessage = "<h4>" + postTypeTitles[data.post_type] + " Removed</h4><p>This " + postTypeTitles[data.post_type] + " has been removed from My Account</p>"
					}
					else {
						successMessage = "<h4>" + postTypeTitles[data.post_type] + " Saved</h4><p>This " + postTypeTitles[data.post_type] + " has been saved to My Account</p>";
					}
				}
				else {
					if( isUnsave ) {
						successMessage = "<p>This brochure has been removed from My Account<p>";
					}
					else {
						successMessage = "<h4>Brochure Saved</h4><p>This brochure is now available to view in My Account</p>"
					}
				}
		
				axios.get( home_base.ajax_url, { params: data } )
				.then( res => {
					e.target.classList.toggle( 'unsave' );
					// Show message
					showMessage( successMessage );
				})
				.catch( error => {
					console.log( error );
					showMessage( "Something has gone wrong" );
				} );
			} );
		} );	
	}

	document.querySelectorAll( '.display-image-gallery .heart' ).forEach( button => {
		button.addEventListener( 'click', ( e ) => {
			const post_ID = e.target.dataset.postId;
			const image_thumb_url = e.target.dataset.imageThumbUrl;
			
			document.getElementById( "save_image_id" ).value = post_ID;
			document.querySelector( "#new_image_form .collection_image_preview" ).style.backgroundImage = 'url(' + image_thumb_url + ')';
			document.body.classList.add( "collection_action", "collection_action_image" );
		} );
	} );

	// collections
	document.querySelector( "#collections, #displays" )?.addEventListener( "click", ( e ) => {
		e.preventDefault();
		
		let target = e.target;
		// Images may hijack click event
		if( target.nodeName === 'IMG' ) {
			target = target.closest( 'a' );
		}

		// add first collection		
		// add collection
		if( target.id === "add_new_collection" || target.id === "add_first_collection" ) {
			document.body.classList.add( "collection_action", "collection_action_new" );
		}

		// show collection
		if( target.classList.contains( 'collection_link' ) ) {
			const href = target.href;
			const id = target.attributes.href.value;
			history.pushState( {}, '', href );

			document.querySelector( '#collection_names' ).classList.add( 'hidden' );
			document.querySelectorAll( '.collection' ).forEach( col => col.classList.remove( 'active' ) );
			document.querySelector( id )?.classList.add( 'active' );
		}
		
		// close collection 
		if( target.classList.contains( 'close_collection' ) ) {
			document.querySelector( "#collection_names" ).classList.remove( 'hidden' );
			document.querySelectorAll( '.collection' ).forEach( col => col.classList.remove( 'active' ) );

			history.pushState( {}, '', '#collections' );
		}

		// edit collection
		if( target.classList.contains( 'edit_collection' ) ) {
			document.querySelector('#edit_collection_name').value = target.dataset.collectionName;
			document.querySelector('#edit_collection_id').value = target.dataset.collection;
			document.body.classList.add( "collection_action", "collection_action_edit" );
		}
		
		// delete collection
		if( target.classList.contains( 'delete_collection' ) ) {
			document.querySelector( "#delete_collection_id" ).value = target.dataset.collection;;
			document.body.classList.add( "collection_action", "collection_action_delete" );
		}

		// show notes
		if( target.classList.contains( 'edit_notes' ) ) {
			const data = {
				action: 'homebase_fetch_notes',
				post_ID: target.dataset.postId
			};

			document.body.classList.add( 'collection_action_loading' );

			axios.get( home_base.ajax_url, { params: data } )
			.then( res => {
				console.log( res.data.data );
				document.querySelector( '#saved_notes' ).value = res.data.data.notes;
				document.querySelector( '#saved_notes_id' ).value = res.data.data.post_id;
				
				document.body.classList.remove( 'collection_action_loading' );
				document.body.classList.add( 'collection_action', 'collection_action_notes' );
			})
			.catch( error => {
				console.log( error );
				showMessage( "Something has gone wrong" );
			} );
		}

		// open gallery 
		if( target.classList.contains( 'collection-image-link' ) ) {
			console.log( 'collection-image-link' );
			var galleryId = target.dataset.gallery;
			document.body.classList.add( 'showing_gallery' );
			document.querySelector( "#gallery_" + galleryId ).classList.add( 'active' );
			collection.resize();
		}

		// close gallery
		if( target.classList.contains( 'close_full_gallery' ) ) {
			document.body.classList.remove( 'showing_gallery' );
			target.closest( '.full_gallery' ).classList.remove( 'active' );
			const collectionHash = target.closest( '.full_gallery' ).dataset.collection;
			history.pushState( {}, "", '#collection_' + collectionHash);
		}

		// edit image
		if( target.classList.contains( 'edit_image' ) ) {
			const image = target.dataset.image;
			const image_thumb_url = target.dataset.imageThumbUrl;			
			const original_collection = target.dataset.collection;
			document.querySelector('#edit_image_id').value = image;
			document.querySelector('#original_collection_id').value = original_collection;
			document.querySelector('#edit_image_form .collection_image_preview').style.backgroundImage = 'url(' + image_thumb_url + ')';
			document.body.classList.add( "collection_action", "collection_action_edit_image" );
			
			loadDestinationCollections( original_collection );
		}
	} );

	

	document.querySelector( ".account_modal_close" )?.addEventListener( "click", ( e ) => {
		closeModals();
	});

	// save image modal - toggle new collection name
	document.querySelectorAll( "input[name='existing_collection_id']" ).forEach( radio => {
		radio.addEventListener( "change", ( e ) => { 
			document.querySelector( "#new_image_collection_name" ).value = "";
			document.querySelector( "#new_image_collection_name" ).classList.toggle( "active", parseInt( e.target.value ) === 0 );
		} );
	} );

	// save image modal - submit "previously #photo_save_submit click"
	document.querySelector( "#new_image_form" )?.addEventListener( "submit", ( e ) => {
		e.preventDefault();
		
		if( document.querySelector( "#save_image_id" ) ) {
			const photo = document.querySelector( "#save_image_id" ).value;
			const data = {
				action: 'homebase_save_image',
				post_ID: photo,
				existing_collection_id: document.querySelector( "input[name=existing_collection_id]:checked" ).value,
				new_collection_name: document.querySelector( "#new_image_collection_name" ).value
			};

			if( !data.existing_collection_id || ( data.existing_collection_id === 0 && data.new_collection_name.length === 0 ) ) {
				return;
			}
			
			document.body.classList.add( "collection_action_loading" );
			axios.get( home_base.ajax_url, { params: data } )
			.then( res => {
				document.body.classList.remove( "collection_action_loading" );
				// console.log( document.querySelector( "#photo_" + photo + " .myaccount" ) );
				// console.log( "#photo_" + photo + " .myaccount" );
				document.querySelector( "#photo_" + photo + " .myaccount" )?.classList.remove( "saveimage" );
				document.querySelector( "#photo_" + photo + " .myaccount" )?.classList.add( "unsaveimage" );
				document.querySelector( "#view_photo_" + photo + " .myaccount" )?.classList.remove( "saveimage" );
				document.querySelector( "#view_photo_" + photo + " .myaccount" )?.classList.add( "unsaveimage" );
				
				closeModals(); 
				// Show message
				showMessage( "<h5>Image Saved</h5><p>This image has been saved to My Account</p><p><a class='saved_view_account' href='/my-account'>View My Account</a></p>" );
			})
			.catch( error => {
				console.log( error );
				showMessage( "Something has gone wrong" );
			} );
		}		
	} );

	// email notes
	document.querySelector( '#edit_notes_email_button' )?.addEventListener( 'click', ( e ) => {
		e.preventDefault();
		const emailbody = document.querySelector( '#saved_notes' ).value;
		window.location = 'mailto:?subject=Note from Home Base My Account&body=' + emailbody;
	} );

	// add new collection
	// delete existing collection
	// edit collection name
	// edit notes
	// edit image
	// delete image
	document.querySelectorAll( "#new_collection_form, #delete_collection_form, #edit_collection_form, #edit_notes_form, #edit_image_form, #delete_image_submit" ).forEach( trigger => {
		const eventType = trigger.tagName === "FORM" ? "submit" : "click";
		trigger.addEventListener( eventType, ( e ) => {
			e.preventDefault();
			processRequest( e.target.id );
		} );
	} );

	// edit collections
	document.querySelector( "#edit_image_collections" )?.addEventListener( "change", ( e ) => {
		// save image item - toggle new collection name
		if( e.target.name === "destination_collection_id" ) {
			document.querySelector( "#new_destination_collection_name" ).value = "";
			document.querySelector( "#new_destination_collection_name" ).classList.toggle( "active", parseInt( e.target.value ) === 0 );
		}
	} );

	// load collections
	if( document.querySelector( "#collections" ) ) {
		loadCollections();
	}

	// Bookmark Post
	document.querySelectorAll( ".display-save button.bookmark" ).forEach( button => {
		button.addEventListener( "click", ( e ) => {
			// const title = e.target.dataset.postTitle;
			// const url = e.target.dataset.postUrl;
			// let createBookmark = browser.bookmarks.create({
			// 	title: title,
			// 	url: url,
			// });
			
			// createBookmark.then(onCreated);
		} );

		// const onCreated = (node) => {
		// 	showMessage( "<h5>Page bookmarked</h5>" );
		// }
	} );
	
	
} );

const processRequest = ( targetId ) => {
	let data = {};
	let confirmation = "";

	switch( targetId ) {
		case "new_collection_form":
			data = {
				action: 'homebase_add_collection',
				new_collection_name: document.querySelector( "#new_collection_name" ).value
			};
			confirmation = "<h4>New Collection Saved</h4><p>This Collection has been saved to My Account</p>";
			break;
		case "delete_collection_form":
			data = {
				action: 'homebase_delete_collection',
				collection: document.querySelector( '#delete_collection_id' ).value
			};
			confirmation = "<h4>Collection Deleted</h4><p>This Collection has been removed from your account</p>"
			break;
		case "edit_collection_form":
			data = {
				action: 'homebase_edit_collection',
				collection: document.querySelector( '#edit_collection_id' ).value,
				edit_collection_name: document.querySelector( '#edit_collection_name' ).value
			}
			confirmation = "<h4>Collection Updated</h4><p>This Collection name has been updated</p>"
			break;
		case "edit_notes_form":
			data = {
				action: 'homebase_save_notes',
				post_ID: document.querySelector( '#saved_notes_id' ).value,
				notes: document.querySelector( '#saved_notes' ).value
			};
			confirmation = "<h4>Notes Saved</h4>";
			break;
		case "edit_image_form":
			data = {
				action: 'homebase_edit_image',
				image: document.querySelector( '#edit_image_id' ).value,
				edit_action: document.querySelector( 'input[name=edit_image_action]:checked').value,
				original_collection_id: document.querySelector( '#original_collection_id').value,
				destination_collection_id: document.querySelector( 'input[name=destination_collection_id]:checked').value,
				new_destination_collection_name: document.querySelector( '#new_destination_collection_name').value
			};
			confirmation = "<h4>Image Updated</h4><p>Image has been moved/copied</p>";
			break;
		case "delete_image_submit":
			data = {
				action: 'homebase_delete_image',
				image: document.querySelector( "#edit_image_id" ).value,
				original_collection_id: document.querySelector( "#original_collection_id" ).value
			};
			confirmation = "<h4>Image Removed</h4><p>Image successfully removed from this collection</p>";
			break;
		default:
			return;
	}

	if( new_collection_name ) {
		document.body.classList.add( "collection_action_loading" );
	
		axios.get( home_base.ajax_url, { params: data } )
		.then( res => {
			closeModals(); 
			// reload collections
			loadCollections();
			// Show message
			showMessage( confirmation );
		})
		.catch( error => {
			console.log( error );
			showMessage( "Something has gone wrong" );
		} );
	}
}
		
// EMAIL NOTES

// TRACK BROCHURE DOWNLOAD
// 		$('main').on("click", ".brochure_download", function() {
// 			$this = $(this);
// 			event.preventDefault();
// 			var brochureURL = $this.attr('href');
// 			var data = {
// 				action: 'homebase_track_brochure_download',
// 				display: $this.data('display')
//     		};
//     		$.ajax({	
// 				url: '/wp-admin/admin-ajax.php',
// 				data: data,
// 				method: 'POST',
// 				dataType: 'json'
// 			});
// 			window.open(brochureURL, '_blank');
// 		});
		
// 		$(".full_gallery").on("click", ".saveimage", function() {
// 			event.preventDefault();
// 			var post_ID = $(this).data('post-id');
// 			var image_thumb_url = $(this).data('image-thumb-url');
// 			$('#save_image_id').val(post_ID);
// 			$('#new_image_form .collection_image_preview').css('background-image', 'url(' + image_thumb_url + ')');
// 			$('body').addClass('collection_action collection_action_image');
// 		});
// 		$(".display_photos").on("click", ".unsaveimage", function() {
// 			event.preventDefault();
// 			$('body').addClass('collection_action collection_action_manage_image');
// 		});
// 		$(".full_gallery").on("click", ".unsaveimage", function() {
// 			event.preventDefault();
// 			$('body').addClass('collection_action collection_action_manage_image');
// 		});
		
// COLLECTION MODAL ACTIONS

// HELP
// 		$("#my_account_help_btn").click(function() {
// 			event.preventDefault();
// 			$('body').addClass('collection_action collection_help');
// 			localStorage.setItem('showhelptips', 1);
// 			$('body').removeClass('show_account_help_tips');
// 		});
		
		
// 		// Email exhibitor
// 		$(".email_now, .call_now").fancybox({
// 			openEffect	: 'fade',
// 			closeEffect	: 'none',
// 			padding: 0,
// 			autoSize: false,
// 			width: 600,
// 			autoHeight: true
// 		});

// 	},

const loadCollections = ( collection ) => {
	document.querySelector( '#collections' ).classList.add( 'loading' );

	axios.get( home_base.ajax_url, { 
		params: {
			action: 'homebase_load_collections',
			collection: collection
		} 
	} )
	.then( res => {
		document.querySelector( '#collections' ).classList.remove( 'loading' );
		document.querySelector( '#collections > .wp-block-group' ).innerHTML = res.data;	
		
		collection = new Flickity( '.full_gallery_slides', {
			cellAlign: 'left',
			contain: true,
			pageDots: false,
			hash: true,
			selectedAttraction: 0.01, 
			friction: 0.15, 
			wrapAround: true, 
			arrowShape: { "x0": 10, "x1": 60, "y1": 50, "x2": 63, "y2": 47, "x3": 17 }
		} );
	} )
	.catch( error => {
		showMessage( "Something has gone wrong" );
	} );		
};

const loadDestinationCollections = ( original_collection ) => {
	document.querySelector( '#collections' ).classList.add( 'loading' );

	axios.get( home_base.ajax_url, { 
		params: {
			action: 'homebase_load_destination_collections',
			exclude: original_collection
		}
	} )
	.then( res => {
		document.querySelector( '#collections' ).classList.remove( 'loading' );
		document.querySelector( '#edit_image_collections' ).innerHTML = res.data;	
	} )
	.catch( error => {
		showMessage( "Something has gone wrong" );
	} );
}

// Function to handle page load, tab, and popstate events
// function shift(hash) {
// if (hash) {
// // need to handle gallery images here too
// if (hash.indexOf("collection_") !== -1 && hash.indexOf("photo_") !== -1) {
// hash = '#collections';
// history.pushState(null, null, hash);
// document.querySelector('.my_account_tabs_nav li:first-child').classList.add('active');
// document.querySelector('.my_account_tab:first-child').classList.add('active');
// myHomeBase.loadCollections(0);
// } else if (hash.indexOf("collection_") !== -1 && hash.indexOf("photo_") === -1) {
// var tabsNavItems = document.querySelectorAll('.my_account_tabs_nav li');
// var accountTabs = document.querySelectorAll('.my_account_tab');

// tabsNavItems.forEach(function(item) {
// item.classList.remove('active');
// });
// accountTabs.forEach(function(tab) {
// tab.classList.remove('active');
// });

// document.querySelector('.my_account_tabs_nav li:first-child').classList.add('active');
// document.querySelector('.my_account_tab:first-child').classList.add('active');
// myHomeBase.loadCollections(hash);
// } else {
// document.querySelector("#collection_names").classList.remove('hidden');
// var collections = document.querySelectorAll('.collection');

// collections.forEach(function(collection) {
// collection.classList.remove('active');
// });

// tabsNavItems = document.querySelectorAll('.my_account_tabs_nav li');
// accountTabs = document.querySelectorAll('.my_account_tab');

// tabsNavItems.forEach(function(item) {
// item.classList.remove('active');
// });
// accountTabs.forEach(function(tab) {
// tab.classList.remove('active');
// });

// var activeTab = document.querySelector('.my_account_tabs_nav a[href="' + hash + '"]');
// if (activeTab) {
// activeTab.parentElement.classList.add('active');
// }
// var activeSection = document.querySelector(hash);
// if (activeSection) {
// activeSection.classList.add('active');
// }
// myHomeBase.loadCollections(0);
// }
// } else {
// hash = '#collections';
// history.pushState(null, null, hash);
// document.querySelector('.my_account_tabs_nav li:first-child').classList.add('active');
// document.querySelector('.my_account_tab:first-child').classList.add('active');
// myHomeBase.loadCollections(0);
// }
	// }

// document.addEventListener('DOMContentLoaded', function() {
// 	var showhelptips = localStorage.getItem('showhelptips');
// 	if (showhelptips === null) {
// 			document.body.classList.add('show_account_help_tips');
// 	}
// 	myHomeBase.init();
// });