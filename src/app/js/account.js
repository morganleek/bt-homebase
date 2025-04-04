import axios, {isCancel, AxiosError} from 'axios';
import Toastify from 'toastify-js'

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
// 

document.addEventListener( 'DOMContentLoaded', () => {
	if( document.querySelector( '.display-social-links' ) ) {
		document.querySelectorAll( '.display-social-links .heart, .display-social-links .display-brochure' ).forEach( button => {
			button.addEventListener( 'click', ( e ) => {
				const data = {
					action: 'homebase_save',
					post_ID: e.target.dataset.postId,
					post_type: e.target.dataset.postType
				};
		
				let successMessage = "";
				if( e.target.classList.contains( 'heart' ) ) {
					successMessage = data.post_type + " Saved\nThis " + data.post_type + " has been saved to `My Account`";
				}
				else {
					successMessage = "Brochure Saved\nThis brochure is now available to view in `My Account`"
				}
		
				axios.get( home_base.ajax_url, { params: data } )
				.then( res => {
					e.target.classList.add( 'unsave' );
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

	// document.querySelector( "#add_new_collection" )?.addEventListener( "click", () => {
	// 	document.body.classList.add( "collection_action", "collection_action_new" );
	// } );

	// close modals
	const closeModals = () => {
		document.body.classList.remove( "collection_action", "collection_action_new", "collection_action_image", "collection_action_edit", "collection_action_delete", "collection_action_loading", "collection_action_edit_image", "collection_action_complete", "account_saved", "collection_action_notes", "collection_action_manage_image", "collection_action_remove_image", "collection_help") ;
	}

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

			const successMessage = "<h5>Image Saved</h5><p>This image has been saved to My Account</p><p><a class='saved_view_account' href='/my-account'>View My Account</a></p>";

			if( !data.existing_collection_id || ( data.existing_collection_id === 0 && data.new_collection_name.length === 0 ) ) {
				return;
			}
			
			document.body.classList.add( "collection_action_loading" );
			axios.get( home_base.ajax_url, { params: data } )
			.then( res => {
				document.body.classList.remove( "collection_action_loading" );
				
				document.querySelector( "#photo_" + photo + " .myaccount" )?.classList.remove( "saveimage" );
				document.querySelector( "#photo_" + photo + " .myaccount" )?.classList.add( "unsaveimage" );
				document.querySelector( "#view_photo_" + photo + " .myaccount" )?.classList.remove( "saveimage" );
				document.querySelector( "#view_photo_" + photo + " .myaccount" )?.classList.add( "unsaveimage" );
				
				closeModals(); 
				// Show message
				showMessage( successMessage );
			})
			.catch( error => {
				console.log( error );
				showMessage( "Something has gone wrong" );
			} );
		}		
	} );

	// Add Collection 
	// document.querySelector( "#new_collection_submit" )?.addEventListener( "click", ( e ) => {
	// 	e.preventDefault();

	// 	const data = {
	// 		action: 'homebase_add_collection',
	// 		new_collection_name: document.querySelector( "#new_collection_name" ).value
	// 	};

	// 	const successMessage = "<h2>New Collection Saved</h2><p>This Collection has been saved to My Account</p>";

	// 	console.log(data);

	// // if (new_collection_name) {

	// // $.ajax({

	// // url: '/wp-admin/admin-ajax.php',
	// // data: data,
	// // method: 'POST',
	// // dataType: 'json',
	// // beforeSend: function(xhr){

	// // $('body').addClass('collection_action_loading');

	// // },
	// // success:function(data){

	// // $('.account_modal_collection_mask .account_modal_message').html(successMessage);
	// // $('body').addClass('collection_action_complete');
	// // $('#new_collection_name').val('');

	// // myHomeBase.loadCollections();

	// // },
	// // error:function(data){

	// // }

	// // });

	// // }

	// // });
} );

// var myHomeBase = {
	
// 	bindUIActions: function() {
		
// 		// EMAIL NOTES
		
// 		$('#edit_notes_email_button').on('click', function (event) {
// 			event.preventDefault();
			
// 			var emailbody = $('#saved_notes').val();
// 			window.location = 'mailto:?subject=Note from Home Base My Account&body=' + emailbody;

// 		});
			
// 		// TRACK BROCHURE DOWNLOAD
		
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
		
// 		// SAVE DISPLAYS AND POSTS
		

		

		
// 		// UNSAVE DISPLAYS AND POSTS
		
// 		$('main').on("click", ".unsave", function() {
			
// 			$this = $(this);
			
// 			event.preventDefault();
			
// 			var data = {
// 				action: 'homebase_unsave',
// 				post_ID: $this.data('post-id'),
// 				post_type: $this.data('post-type')
				
//     		};
    		
//     		var successMessage = "<h2>" + data.post_type + " Removed</h2><p>This " + data.post_type + " has been removed from My Account</p>";
    		
//     		console.log(data);

//     		$.ajax({
				
// 				url: '/wp-admin/admin-ajax.php',
// 				data: data,
// 				method: 'POST',
// 				dataType: 'json',
// 				beforeSend: function(xhr){
					
// 					$('body').addClass('collection_action_loading');
					
// 				},
// 				success:function(data){
					
// 					$('.account_modal_collection_mask .account_modal_message').html(successMessage);
// 					$('body').addClass('collection_action_complete');
// 					$this.removeClass('unsave').addClass('save').prop('title', 'Save to My Account');
					
// 				},
// 				error:function(data){
	  			
// 				}
					
// 			});
    		
// 		});

// 		// SHOW NOTES PANEL
		
// 		$(".homebase_my_account").on("click", ".edit_notes", function() {
			
// 			event.preventDefault();

// 			var data = {
// 				action: 'homebase_fetch_notes',
// 				post_ID: $(this).data('post-id')
//     		};
    		
//     		$.ajax({
				
// 				url: '/wp-admin/admin-ajax.php',
// 				data: data,
// 				method: 'POST',
// 				dataType: 'json',
// 				beforeSend: function(xhr){
					
// 					$('body').addClass('collection_action_loading');
					
// 				},
// 				success:function(data){
					
// 					$('#saved_notes').val(data.data.notes);
// 					$('#saved_notes_id').val(data.data.post_id);
					
// 					$('body').removeClass('collection_action_loading').addClass('collection_action collection_action_notes');

// 				},
// 				error:function(data){
	  			
// 				}
					
// 			});
			

// 		});
		
// 		// SAVE NOTES
		
// 		$("#notes_submit").on("click", function() {
			
// 			event.preventDefault();
			
// 			$save = $('#saved_notes_id').val();
// 			$notes = $('#saved_notes').val();
			
//     		var data = {
// 				action: 'homebase_save_notes',
// 				post_ID: $save,
// 				notes: $notes
//     		};
    		
//     		var successMessage = "<h2>Notes Saved</h2>";
    		
//     		$.ajax({
				
// 				url: '/wp-admin/admin-ajax.php',
// 				data: data,
// 				method: 'POST',
// 				dataType: 'json',
// 				beforeSend: function(xhr){
					
// 					$('body').addClass('collection_action_loading');

// 				},
// 				success:function(data){
					
// 					$('.account_modal_collection_mask .account_modal_message').html(successMessage);
// 					$('body').addClass('collection_action_complete');

// 				},
// 				error:function(data){
	  			
// 				}
					
// 			});

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
		
// 		$("input[name='existing_collection_id']").on("change", function() { 
// 			if (this.value == 0) {
// 				$('#new_image_collection_name').addClass('active').val('');
// 			} else {
// 				$('#new_image_collection_name').removeClass('active').val('');
// 			}
// 		});
		
// 		$("#edit_image_form").on("change", "input[name='destination_collection_id']", function() { 
// 			if (this.value == 0) {
// 				$('#new_destination_collection_name').addClass('active').val('');
// 			} else {
// 				$('#new_destination_collection_name').removeClass('active').val('');
// 			}
// 		});
			
		

		
// 		// COLLECTION MODAL ACTIONS
		

		
// 		$("#collections").on("click", "#add_first_collection", function() {
			
// 			event.preventDefault();
// 			$('body').addClass('collection_action collection_action_new');

// 		});
		
// 		$("#collections").on("click", ".edit_collection", function() {
			
// 			event.preventDefault();
			
// 			var collection = $(this).data('collection');
// 			var existing_name = $(this).data('collection-name');

// 			$('#edit_collection_name').val(existing_name);
// 			$('#edit_collection_id').val(collection);
// 			$('body').addClass('collection_action collection_action_edit');

// 		});
		
// 		$("#collections").on("click", ".delete_collection", function() {
			
// 			event.preventDefault();
			
// 			var collection = $(this).data('collection');
// 			$('#delete_collection_id').val(collection);
// 			$('body').addClass('collection_action collection_action_delete');

// 		});
		
// 		$("#collections").on("click", ".edit_image", function() {

// 			event.preventDefault();
// 			var image = $(this).data('image');
// 			var image_thumb_url = $(this).data('image-thumb-url');
			
// 			var original_collection = $(this).data('collection');
// 			$('#edit_image_id').val(image);
// 			$('#original_collection_id').val(original_collection);
// 			$('#edit_image_form .collection_image_preview').css('background-image', 'url(' + image_thumb_url + ')');
// 			$('body').addClass('collection_action collection_action_edit_image');
			
// 			myHomeBase.loadDestinationCollections(original_collection);

// 		});
		

		
		
		
// 		// ADD COLLECTION
		

		
// 		// EDIT COLLECTION
		
		
// 		$("#edit_collection_submit").on("click", function() {
			
// 			event.preventDefault();
			
//     		var data = {
// 				action: 'homebase_edit_collection',
// 				collection: $('#edit_collection_id').val(), 
// 				edit_collection_name: $('#edit_collection_name').val()
//     		};
    		
//     		var successMessage = "<h2>Collection Updated</h2><p>This Collection name has been updated</p>";
    		
//     		if (edit_collection_name) {
	    		
// 	    		$.ajax({
				
// 					url: '/wp-admin/admin-ajax.php',
// 					data: data,
// 					method: 'POST',
// 					dataType: 'json',
// 					beforeSend: function(xhr){
						
// 						$('body').addClass('collection_action_loading');
	
// 					},
// 					success:function(data){
						
// 						$('.account_modal_collection_mask .account_modal_message').html(successMessage);
// 						$('body').addClass('collection_action_complete');

// 					},
// 					error:function(data){
		  			
// 					}
					
// 				});
	    		
//     		}

// 		});
		
		
		
// 		// DELETE COLLECTION
		
// 		$("#delete_collection_submit").on("click", function() {
			
// 			event.preventDefault();
			
//     		var data = {
// 				action: 'homebase_delete_collection',
// 				collection: $('#delete_collection_id').val()
//     		};
    		
//     		var successMessage = "<h2>Collection Deleted</h2><p>This Collection has been removed from My Account</p>";
    		
//     		if (new_collection_name) {
	    		
// 	    		$.ajax({
				
// 					url: '/wp-admin/admin-ajax.php',
// 					data: data,
// 					method: 'POST',
// 					dataType: 'json',
// 					beforeSend: function(xhr){
						
// 						$('body').addClass('collection_action_loading');
	
// 					},
// 					success:function(data){
						
// 						$('.account_modal_collection_mask .account_modal_message').html(successMessage);
// 						$('body').addClass('collection_action_complete');
						
// 						myHomeBase.loadCollections();

// 					},
// 					error:function(data){
		  			
// 					}
					
// 				});
	    		
//     		}

// 		});
		
// 		// EDIT IMAGE
			
// 		$("#edit_image_form").on("click", "#edit_image_submit", function() {
			
// 			event.preventDefault();
			
//     		var data = {
// 				action: 'homebase_edit_image',
// 				image: $('#edit_image_id').val(),
// 				edit_action: $('input[name=edit_image_action]:checked').val(),
// 				original_collection_id: $('#original_collection_id').val(),
// 				destination_collection_id: $('input[name=destination_collection_id]:checked').val(),
// 				new_destination_collection_name: $('#new_destination_collection_name').val()
//     		};
    		
//     		var successMessage = "<h2>Image Updated</h2><p>Image has been moved/copied.</p>";
    		
//     		// If desintaiotn ID is either > 0 or 0 and we have new name string
    		
//     		if (!data.destination_collection_id || data.destination_collection_id == 0 && data.new_destination_collection_name.length == 0 ) {
	    		
// 	    		return;
	    		
// 	    	} else {

// 	    		$.ajax({
				
// 					url: '/wp-admin/admin-ajax.php',
// 					data: data,
// 					method: 'POST',
// 					dataType: 'json',
// 					beforeSend: function(xhr){
						
// 						$('body').addClass('collection_action_loading');
	
// 					},
// 					success:function(data){
						
// 						$('.account_modal_collection_mask .account_modal_message').html(successMessage);
// 						$('body').addClass('collection_action_complete');
						
// 						myHomeBase.loadCollections();

// 					},
// 					error:function(data){
		  			
// 					}
					
// 				});
	    		
//     		}

// 		});
		
// 		// REMOVE IMAGE
			
// 		$("#edit_image_form").on("click", "#delete_image_submit", function() {
			
// 			event.preventDefault();
			
//     		var data = {
// 				action: 'homebase_delete_image',
// 				image: $('#edit_image_id').val(),
// 				original_collection_id: $('#original_collection_id').val()
//     		};
    		
//     		var successMessage = "<h2>Image Removed</h2><p>Image successfully removed from this collection</p>";
    		
 
//     		$.ajax({
			
// 				url: '/wp-admin/admin-ajax.php',
// 				data: data,
// 				method: 'POST',
// 				dataType: 'json',
// 				beforeSend: function(xhr){
					
// 					$('body').addClass('collection_action_loading');

// 				},
// 				success:function(){
					
// 					$('.account_modal_collection_mask .account_modal_message').html(successMessage);
// 					$('body').addClass('collection_action_complete');
					
// 					// Hide image from collection
					
// 					console.log('#collection_' + data.original_collection_id + ' #photo_' + data.image);
// 					$('#collection_' + data.original_collection_id + '_photo_' + data.image).fadeOut();
					
// 				},
// 				error:function(data){
	  			
// 				}
				
// 			});
	    		
// 		});
		

// 		// SHOW COLLECTION
		
// 		$("#collections").on("click", ".collection_link", function() {
			
// 			c = $(this).attr('href'); 
			
// 			history.pushState(null, null, c);
			
// 			$("#collection_names").addClass('hidden');
// 			$('.collection').removeClass('active');
// 			$(c).addClass('active');
			
// 			return(false);
	
// 		});
		
// 		// CLOSE COLLECTION
		
// 		$("#collections").on("click", ".close_collection", function() {
			
// 			$("#collection_names").removeClass('hidden');
// 			$('.collection').removeClass('active');
			
// 			history.pushState(null, null, '#collections');

// 			return(false);
	
// 		});
		
//         // HELP
		
// 		$("#my_account_help_btn").click(function() {
			
// 			event.preventDefault();
// 			$('body').addClass('collection_action collection_help');
			
// 			localStorage.setItem('showhelptips', 1);
// 			$('body').removeClass('show_account_help_tips');

// 		});
		
// 		// ACCOUNT TABS
		
// 		$('.my_account_tabs_nav a').click(function() {
			
// 			t = $(this).attr('href'); 
			
// 			history.pushState(null, null, t);
// 			myHomeBase.shift(t);
			
// 			localStorage.setItem('showhelptips', 1);
// 			$('body').removeClass('show_account_help_tips');
			
// 			return(false);
			
// 		});
		
// 		// POPSTATE
		
// 		window.addEventListener("popstate", function(e) {
			
// 			$("body").removeClass('collection_action collection_action_new collection_action_image collection_action_edit collection_action_delete collection_action_loading collection_action_edit_image collection_action_complete account_saved collection_action_notes collection_action_manage_image collection_action_remove_image collection_help');

// 			myHomeBase.shift(location.hash);
		
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
	
// 	loadCollections: function(collection) {
		
// 		var data = {
//             action: 'homebase_load_collections',
//             collection: collection
//         };
		
// 		$.ajax({
				
// 			url: '/wp-admin/admin-ajax.php',
// 			data: data,
// 			dataType: 'html',
// 			method: 'POST',
// 			beforeSend: function(xhr){
				
// 				$('#collections').html('<div class="loading_collections"></div>');
					
// 			},
// 			success:function(response){	
				
// 				html = jQuery.parseJSON(response);
				
// 				$('#collections').html(html);	
				
// 				$('.full_gallery_slides').flickity({
// 					cellAlign: 'left',
// 					contain: true,
// 					pageDots: false,
// 					hash: true,
// 					selectedAttraction: 0.01, 
// 					friction: 0.15, 
// 					wrapAround: true, 
// 					arrowShape: { "x0": 10, "x1": 60, "y1": 50, "x2": 63, "y2": 47, "x3": 17 }
// 				});
				
// 			}
					
// 		});
		
// 	},
	
// 	loadDestinationCollections: function(original_collection) {
		
// 		var data = {
//             action: 'homebase_load_destination_collections',
//             exclude: original_collection
//         };
        
//         console.log(data.exclude);
		
// 		$.ajax({
				
// 			url: '/wp-admin/admin-ajax.php',
// 			data: data,
// 			dataType: 'html',
// 			method: 'POST',
// 			beforeSend: function(xhr){
				
// 				$('#edit_image_collections').html('<div class="loading_collections"></div>');
					
// 			},
// 			success:function(response){	

// 				html = jQuery.parseJSON(response);
// 				$('#edit_image_collections').html(html);		
// 			}
					
// 		});
		
// 	},
	
	
// 	// Function to handle page load, tab, and popstate events
	
// 	shift: function(hash) {
		
// 		if (hash) {
			
// 			// need to handle gallery images here too
			
// 			if ( hash.indexOf("collection_") != -1 && hash.indexOf("photo_") != -1 ) {

// 				hash = '#collections';
// 				history.pushState(null, null, hash);
			
// 				$('.my_account_tabs_nav li:first').addClass('active');
// 				$('.my_account_tab:first').addClass('active');
			
// 				myHomeBase.loadCollections(0);
				
// 			} else if ( hash.indexOf("collection_") != -1 && hash.indexOf("photo_") === -1 ) {

// 				$('.my_account_tabs_nav li').removeClass('active');
// 				$('.my_account_tab').removeClass('active');
// 				$('.my_account_tabs_nav li:first').addClass('active');
// 				$('.my_account_tab:first').addClass('active');
				
// 				myHomeBase.loadCollections(hash);
				
// 			} else {
				
// 				$("#collection_names").removeClass('hidden');
// 				$('.collection').removeClass('active');
				
// 				$('.my_account_tabs_nav li').removeClass('active');
// 				$('.my_account_tab').removeClass('active');
// 				$('.my_account_tabs_nav a[href="' + hash + '"]').parent().addClass('active');
// 				$(hash).addClass('active');
				
// 				myHomeBase.loadCollections(0);

// 			}

// 		} else {

// 			hash = '#collections';
// 			history.pushState(null, null, hash);
			
// 			$('.my_account_tabs_nav li:first').addClass('active');
// 			$('.my_account_tab:first').addClass('active');
			
// 			myHomeBase.loadCollections(0);
			
// 		}
		
// 	},

// 	init: function() {
		
// 		this.bindUIActions();

// 	},

// 	initMyAccountPage: function() {
		
// 		this.shift(window.location.hash);

// 	}
    
// };

// $(document).ready(function() { 
	
// 	var showhelptips = localStorage.getItem('showhelptips');
	
// 	if ( showhelptips == null ) {

// 		$('body').addClass('show_account_help_tips');
// 	}
	
// 	$("#collections").on("click", ".close_full_gallery", function() {
		
// 		$('body').removeClass('showing_gallery');
// 		$(this).parents('.full_gallery').removeClass('active');
// 		c = $(this).parents('.full_gallery').data('collection');
		
// 		hash = '#collection_' + c;
// 		history.pushState(null, null, hash);

// 	});
	
// 	$("#collections").on("click", ".collection_image a", function() {
		
// 		$('body').addClass('showing_gallery');
// 		var g = $(this).data('gallery');
// 		$('#gallery_' + g).addClass('active');

// 		$('.full_gallery_slides').flickity('resize');
		
// 	});
   
// 	myHomeBase.init();
	
// });