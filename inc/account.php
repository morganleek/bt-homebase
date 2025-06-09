<?php
/*
 *
 * Inherited from original Home Base theme
 * 
 */


///////// HOME BASE MY ACCOUNT AREA ///////// 

// Post type 'save' for saving displays and posts - stores post or display ID as integer in post_meta
// Post type 'collection' - for saving images - stores image ID within array in post_meta
// Mostly run via ajax - see account.js

// 1.0 homebase_saves - define save post type
// 1.1 homebase_save_button - output save buttons
// 1.2 homebase_save_dialog - output modal wrappers for save interactions and notes
// 1.3 homebase_save_record - check whether post is already saved by user - return boolean
// 1.4 homebase_save - create a save record
// 1.5 homebase_unsave - destroy a save record
// 1.6 homebase_fetch_notes - fetch notes stored with a save record
// 1.7 homebase_save_notes - update notes stored with a save record

// 2.0 homebase_collections - define collection post type
// 2.1 homebase_save_to_collection_button - output save to collection buttons
// 2.2 homebase_collection_dialog - output modal wrappers for collection interactions
// 2.3 homebase_collection_record - check whether image is already saved in a collection by user - return boolean
// 2.4 homebase_save_image - save an image to a collection
// 2.5 homebase_add_collection
// 2.6 homebase_load_images

// 3.0 homebase_brochure_downloads - define brochure download post type 

// 3.2 homebase_course_kits - define course kit post type


function homebase_login_stylesheet() {
	wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/style-login.css' );
}

add_action( 'login_enqueue_scripts', 'homebase_login_stylesheet' );

// Helpers 
function in_array_r( $needle, $haystack, $strict = false ) {
	foreach ( $haystack as $item ) {
		if ( ( $strict ? $item === $needle : $item == $needle ) || ( is_array( $item ) && in_array_r( $needle, $item, $strict ) ) ) {
			return true;
		}
	}

	return false;
}

// 1.0
function homebase_saves() {
	$labels = [ 
		'name'          => __( 'Saves' ),
		'singular_name' => __( 'Save' ),
	];

	$args = [ 
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => false,
		'supports'           => [ 
			'title',
			'author',
			'custom-field',
		],
		'capability_type'    => 'post',
		'has_archive'        => false,
		'menu_icon'				 => 'dashicons-heart',
		'menu_position'			=> 54
	];

	register_post_type( 'save', $args );
}
add_action( 'init', 'homebase_saves' );

// Admin columns

add_filter( 'manage_save_posts_columns', 'homebase_saves_columns_head', 10 );
add_action( 'manage_save_posts_custom_column', 'homebase_saves_columns_content', 10, 2 );

function homebase_saves_columns_head( $columns ) {
	unset( $columns['date'], $columns['title'] );

	$columns['type'] = 'Type';
	$columns['saved_post'] = 'Saved Post';

	return $columns;
}

function homebase_saves_columns_content( $column_name, $post_ID ) {
	if ( $column_name == 'type' ) {
		$saved_post_ID = get_post_meta( $post_ID, 'saved', true );
		$saved_post = get_post( $saved_post_ID );
		echo $saved_post->post_type;
	}

	if ( $column_name == 'saved_post' ) {
		$saved_post_ID = get_post_meta( $post_ID, 'saved', true );
		$saved_post = get_post( $saved_post_ID );
		echo $saved_post->post_title;
	}

}

// 1.1

function homebase_save_button( $post_ID, $post_type ) {
	if ( is_user_logged_in() ) {

		$current_user = wp_get_current_user();
		$current_user_ID = $current_user->ID;

		$post_title = get_the_title( $post_ID );

		if ( homebase_save_record( $post_ID, $current_user_ID ) ) {
			echo "<button class=\"heart myaccount unsave\" data-post-type=\"$post_type\" data-post-id=\"$post_ID\" data-post-title=\"$post_title\" data-user-id=\"$current_user_ID\" title=\"Remove from My Account\"></button>";
		} else {
			echo "<button class=\"heart myaccount save\" data-post-type=\"$post_type\" data-post-id=\"$post_ID\" data-post-title=\"$post_title\" data-user-id=\"$current_user_ID\" title=\"Save to My Account\"></button>";
		}

	} else {
		echo '<a href="/my-account" class="heart myaccount save logged-out requires-login"></a>';
	}

}

///////// SAVE EXISTS - check if user has saved a post 

function homebase_save_record( $post_ID, $current_user_ID ) {
	$args = [ 
		'post_type'  => 'save',
		'author'     => $current_user_ID,
		'meta_query' => [ 
			[ 
				'key'     => 'saved',
				'value'   => $post_ID,
				'compare' => 'IN',
			],
		],
	];

	$saves = new WP_Query( $args );
	return $saves->have_posts();

}

// Save Display
function homebase_save() {
	$post_ID = $_REQUEST['post_ID'];
	$current_user = wp_get_current_user();

	if ( ! homebase_save_record( $post_ID, $current_user->ID ) ) {
		wp_insert_post( [ 
			'post_type'   => 'save',
			'post_title'  => $post_ID,
			'post_status' => 'publish',
			'meta_input'  => [ 'saved' => $post_ID ],
		] );
	}

	wp_send_json_success();
}

// Remove display
function homebase_unsave() {
	$post_ID = $_REQUEST['post_ID'];
	$current_user = wp_get_current_user();
	$current_user_ID = $current_user->ID;

	$args = [ 
		'post_type'  => 'save',
		'author'     => $current_user_ID,
		'meta_query' => [ 
			[ 
				'key'     => 'saved',
				'value'   => $post_ID,
				'compare' => 'IN',
			],
		],
	];

	$saves = new WP_Query( $args );
	
	while ( $saves->have_posts() ) :
		$saves->the_post();
		wp_delete_post( get_the_ID() );
	endwhile;

	wp_send_json_success();
}

function homebase_fetch_notes() {
	$post_ID = $_REQUEST['post_ID'];
	$notes = get_post_meta( $post_ID, 'notes', true );

	$return = [ 
		'notes'   => $notes,
		'post_id' => $post_ID,
	];

	wp_send_json_success( $return );
}

function homebase_save_notes() {
	$post_ID = $_REQUEST['post_ID'];
	$notes = $_REQUEST['notes'];

	$args = [ 
		'ID'         => $post_ID,
		'meta_input' => [ 'notes' => $notes ],
	];

	$post_id = wp_update_post( $args );

	wp_send_json_success();
}

add_action( 'wp_ajax_homebase_unsave', 'homebase_unsave' );
add_action( 'wp_ajax_homebase_save', 'homebase_save' );
add_action( 'wp_ajax_homebase_save_brochure', 'homebase_save_brochure' );
add_action( 'wp_ajax_homebase_fetch_notes', 'homebase_fetch_notes' );
add_action( 'wp_ajax_homebase_save_notes', 'homebase_save_notes' );

add_filter( 'shortcode_atts_wpcf7', 'custom_shortcode_atts_wpcf7_filter', 10, 3 );

function custom_shortcode_atts_wpcf7_filter( $out, $pairs, $atts ) {
	$my_attr = 'destination-email';

	if ( isset( $atts[ $my_attr ] ) ) {
		$out[ $my_attr ] = $atts[ $my_attr ];
	}

	return $out;
}

// 2.0

function homebase_collections() {
	$labels = [ 
		'name'          => __( 'Collections' ),
		'singular_name' => __( 'Collection' ),
	];

	$args = [ 
		'labels'          => $labels,
		'public'          => true,
		'supports'        => [ 
			'title',
			'author',
			'custom-fields',
		],
		'capability_type' => 'post',
		'has_archive'     => false,
		'menu_icon'				=> 'dashicons-welcome-widgets-menus'
	];

	register_post_type( 'collection', $args );
}
add_action( 'init', 'homebase_collections' );

// 2.1

function homebase_save_to_collection_button( $post_ID, $return = false ) {
	$html = '';
	if ( is_user_logged_in() ) {
		// $current_user = wp_get_current_user();
		// $current_user_ID = $current_user->ID;
		if ( homebase_image_saved( $post_ID ) ) {
			$html .= "<button class=\"heart myaccount unsaveimage\" data-post-id=\"$post_ID\" title=\"\"></button>";
		} else {
			// $photo = get_field( "file", $post_ID );
			$photo_thumb_URL = wp_get_attachment_image_src( $post_ID, "thumbnail" );
			$html .= "<button class=\"heart myaccount saveimage\" data-post-id=\"$post_ID\" data-image-thumb-url=\"$photo_thumb_URL[0]\" title=\"\"></button>"; // 
		}
	} else {
		$html .= '<a href="/my-account" class="myaccount saveimage requires-login"></a>';
	}

	if( $return ) {
		return $html;
	}
	print $html;
}

// 2.2 

function homebase_collection_dialog() {
	if ( is_user_logged_in() ) {

		$svg = '<svg width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.16868 4.09641L1.30121 11.0121L0 9.71087L6.91566 2.8193H2V0.987976H10V9.01207H8.16868V4.09641Z" fill="#F9B000"></path></svg>';

		$current_user = wp_get_current_user();
		$current_user_ID = $current_user->ID;

		$args = [ 
			'post_type' => 'collection',
			'author'    => $current_user_ID,
		];

		$collections = get_posts( $args );

		echo '<div class="account_modal_collection_mask wp-block-account-modal-collection-mask">';
		echo '<div class="account_modal_collection"><div class="account_modal_content">';

		// Save Image
		echo '<div class="close-wrapper">Close <span class="account_modal_close"></span></div>';
		echo '<form id="new_image_form">';
		echo '<div class="image_form_header"><div class="collection_image_preview"></div>';
		echo '<h3>Save Image</h3>';
		echo '<p>Add this image to an existing Collection or create a new Collection.</p>';
		echo '</div>';
		echo '<ul class="save_collections">';

		foreach ( $collections as $collection ) {
			$images = json_decode( get_post_meta( $collection->ID, 'images', true ), TRUE );

			if ( count( $images ) > 0 ) {
				$image1 = array_values( $images )[0];
				$photo = get_field( "file", $image1 );
				$photo_thumb = wp_get_attachment_image_src( $photo, "thumbnail" );
				$bg_img = $photo_thumb[0] ?? "";
			} else {
				$bg_img = '';
			}

			echo "<li><input type=\"radio\" id=\"collection_{$collection->ID}\" name=\"existing_collection_id\" value=\"{$collection->ID}\"/><label for=\"collection_{$collection->ID}\"><span>{$collection->post_title}</span></label></li>";
		}

		echo '<li><input type="radio" id="add_new_collection" name="existing_collection_id" value="0"/><label for="add_new_collection"><span>Create Collection</span></label></li></ul>';
		echo '<input type="text" id="new_image_collection_name" name="new_collection_name" placeholder="New collection name" autocomplete="off" maxlength="30"/>';
		echo '<input type="hidden" id="save_image_id" value="" />';
		echo "<button id=\"photo_save_submit\" class=\"saved_view_account\">Save Image $svg</button>";
		echo '</form>';

		// Edit Image
		echo '<form id="edit_image_form">';
		echo '<div class="image_form_header">
			<div class="collection_image_preview"></div>
			<h3>Edit Image</h3>
			<div>
				<p>Move or copy this image to another collection</p>
				<select class="edit_image_actions">
					<option id="image_move" name="edit_image_action" value="move" selected>Move</option>
					<option id="image_copy" name="edit_image_action" value="copy">Copy</option>
				</select>
			</div>
		</div>';
		// echo '<p>Choose a Collection to copy/move this image to:</p>';
		echo '<div class="edit_image_collections" id="edit_image_collections"></div>';
		echo '<input type="hidden" id="edit_image_id" value="" />';
		echo '<input type="hidden" id="original_collection_id" value="" />';
		echo '<div class="modal-button-wrapper columns-2">';
			echo "<button id=\"edit_image_submit\" class=\"saved_view_account\">Save $svg</button>";
			echo "<a id=\"delete_image_submit\" href=\"#\">Remove image from collection $svg</a>";
		echo '</div>';
		echo '</form>';

		// New Collection
		echo '<form id="new_collection_form">';
		echo '<h3>New Collection</h3>';
		echo '<input type="text" id="new_collection_name" name="new_collection_name" placeholder="New Collection name" autocomplete="off" maxlength="30"/>';
		echo "<button id=\"new_collection_submit\" class=\"saved_view_account\">Save Collection $svg</button>";
		echo '</form>';

		// Edit Collection
		echo '<form id="edit_collection_form">';
		echo '<h3>Edit Collection Name</h3>';
		echo '<input type="text" id="edit_collection_name" name="edit_collection_name" placeholder="New Collection name" autocomplete="off" maxlength="30"/>';
		echo '<input type="hidden" id="edit_collection_id" value="" />';
		echo "<button id=\"edit_collection_submit\" class=\"saved_view_account\">Save Collection $svg</button>";
		echo '</form>';

		// Delete Collection
		echo '<form id="delete_collection_form">';
		echo '<h3>Delete Collection</h3><p>Are you sure you want to delete this Collection? This cannot be undone and will also delete any notes you have saved for this Collection.</p>';
		echo '<input type="hidden" id="delete_collection_id" value="" />';
		echo "<button id=\"delete_collection_submit\">Delete Collection $svg</button>";
		echo '</form>';

		// Notes
		echo '<form id="edit_notes_form">';
		echo '<h3>My Notes</h3>';
		echo '<textarea id="saved_notes" placeholder="" autocomplete="off" /></textarea>';
		echo '<input type="hidden" id="saved_notes_id" value="" />';
		echo '<div class="edit-notes-wrapper"><a id="edit_notes_email_button" href="" title="Share your notes via email"></a></div>';
		echo "<button id=\"notes_submit\" class=\"saved_view_account\">Save Notes $svg</button>";
		echo '</form>';

		// Manage Image
		echo '<form id="manage_image_form">';
		echo '<h3>Image Saved</h3><p>This image is saved in your account. Visit your account page to move, copy or delete this image between/from your collections.</p>';
		echo "<a id=\"update_image_submit\" class=\"saved_view_account\" href=\"/my-account/\">Visit My Account $svg</a>";
		echo '</form>';

		// HELP
		echo '<div id="my_account_help">';
		echo '<h3>Welcome to My Account!</h3>';
		echo '<p>Please see below for a guide to the My Account features, which can be used as you navigate the Home Base website and are logged in.</p>
		<p>To help you get started, we have added some pre-made Collections to your My Account – however, you can add more, edit Collection names or delete Collections by selecting the Collection.</p>
		<div class="heart"><p>Whilst browsing the Home Base website, save images to your My Account Collections with the heart icon.</p></div>
		<div class="pencil"><p>Move, copy or delete images in your Collections by using the edit icon.</p></div>
		<div class="savetomyaccount"><p>Save displays to My Account with the ‘Save to My Account’ button. From here you can contact the exhibitor, visit their website, download a brochure (if available) or make notes. Your saved displays will be available in the Displays area of My Account. Remove a display by selecting the ‘Remove from My Account’ button on the display’s Home Base website page.</p>
		<p>You can also save blog posts from The Loft by using the ‘Save to My Account’ button on each post. 
		</p></div>
		<div class="savetomyaccount"><p>Save and download brochures (if available) with the ‘Download Brochure’ button. Saved brochures are stored in the ‘Displays’ section of My Account. To delete a brochure, remove the associated display by selecting the ‘Remove from My Account’ button on the display’s Home Base website page.
		</p></div>';

		echo '</div>';

		// Message
		echo '<div class="account_modal_message"></div>';

		echo '</div></div></div>';
	}
}

add_action( 'wp_footer', 'homebase_collection_dialog' );

// 2.3

function homebase_image_saved( $post_ID ) {
	$current_user = wp_get_current_user();
	$current_user_ID = $current_user->ID;

	$args = [ 
		'post_type' => 'collection',
		'author'    => $current_user_ID,
	];

	$collections = get_posts( $args );

	$saved_images = [];

	foreach ( $collections as $collection ) {

		$images = json_decode( get_post_meta( $collection->ID, 'images', true ), TRUE );
		array_push( $saved_images, $images );

	}

	return in_array_r( $post_ID, $saved_images ) ? 1 : 0;

}

// 2.4 

function homebase_save_image() {
	$image = $_REQUEST['post_ID'];
	$existing_collection_id = $_REQUEST['existing_collection_id'];
	$new_collection_name = $_REQUEST['new_collection_name'];
	// $current_user = wp_get_current_user();
	// $current_user_ID = $current_user->ID;

	if ( $existing_collection_id ) {

		$images = json_decode( 
			get_post_meta( $existing_collection_id, 'images', true ), 
			true
		);
		$images[] = $image;

		$args = [ 
			'ID'         => $existing_collection_id,
			'meta_input' => [ 'images' => json_encode( array_unique( $images ) ) ],
		];

		$post_id = wp_update_post( $args );
	} else {
		if( strlen($new_collection_name ) > 0 ) {
			$existing_images = [];
			$existing_images[] = $image;
	
			$image_array = json_encode( $existing_images );
	
			$args = [ 
				'post_type'   => 'collection',
				'post_title'  => $new_collection_name,
				'post_status' => 'publish',
				'meta_input'  => [ 'images' => $image_array ],
			];
	
			$post_id = wp_insert_post( $args );
		}
	}

	$response_array = [];

	if ( ! is_wp_error( $post_id ) ) {
		wp_send_json_success();

	} else {
		wp_send_json_error();
	}
}

// 2.5

function homebase_add_collection() {
	$new_collection_name = $_REQUEST['new_collection_name'];
	$current_user = wp_get_current_user();
	$current_user_ID = $current_user->ID;

	$existing_images = [];

	$image_array = json_encode( $existing_images );

	$args = [ 
		'post_type'   => 'collection',
		'post_title'  => $new_collection_name,
		'post_status' => 'publish',
		'meta_input'  => [ 'images' => $image_array ],
	];

	$post_id = wp_insert_post( $args );

	wp_send_json_success();

}

// 2.6

function homebase_load_collections() {
	if ( is_user_logged_in() ) {

		// $hide_collection_names_class = $_REQUEST['collection'] ? 'hidden' : '';

		$show_collection_id = isset( $_REQUEST['collection'] ) ? str_replace( '#collection_', '', $_REQUEST['collection'] ) : '';

		$current_user = wp_get_current_user();
		$current_user_ID = $current_user->ID;
		$collections = [];

		$args = [ 
			'post_type'      => 'collection',
			'author'         => $current_user_ID,
			'posts_per_page' => -1
		];

		$collections = get_posts( $args );

		$html = '';
		$index = '';

		if ( $collections ) {

			$index .= "<ul class=\"collection_names\" id=\"collection_names\">";

			$html .= '<div class="collection_images">';

			foreach ( $collections as $collection ) {

				$activeclass = ( $collection->ID == $show_collection_id ) ? 'active' : '';
				$gallery = '';
				$images = json_decode( get_post_meta( $collection->ID, 'images', true ), TRUE );

				$html .= "<div class=\"collection $activeclass\" id=\"collection_{$collection->ID}\">";
					// $html .= "<h4>{$collection->post_title}</h4>";
					$html .= "<ul class=\"collection_links\">
						<li>
							<a class=\"edit_notes\" data-post-id=\"{$collection->ID}\" data-post-name=\"{$collection->post_title}\" href=\"#\" title=\"Notes on this Collection\"></a>
						</li>
						<li>
							<a class=\"edit_collection\" data-collection=\"{$collection->ID}\" data-collection-name=\"{$collection->post_title}\" href=\"#\" title=\"Edit Collection Name\"></a>
						</li>
						<li>
							<a href=\"#\" class=\"delete_collection\" data-collection=\"{$collection->ID}\" title=\"Delete Collection\"></a>
						</li>
					</ul>";
					$html .= '<ul class="collection_grid">';

					if ( $images ) {

						// Store first thumb in variable here, pass to index below

						$gallery .= "<div class=\"full_gallery\" id=\"gallery_{$collection->ID}\" data-collection=\"{$collection->ID}\">";
						$gallery .= '<div class="full_gallery_slides">';

						$i = 0;

						foreach ( $images as $image ) {

							$i++;

							$photo = get_field( "file", $image );
							$title = get_field( "gallery_title", $image );
							$description = get_field( "gallery_description", $image );
							$photo_thumb = wp_get_attachment_image_src( $photo, "thumbnail" );
							$photo_large = wp_get_attachment_image( $photo, "large" );
							$photo_full = wp_get_attachment_image_src( $photo, "full" );

							if ( $i == 1 ) {
								$bg_img = $photo_thumb[0];
							}

							$html .= "<li class=\"collection_image\" id=\"collection_{$collection->ID}_photo_$image\">
								<a href=\"#collection_{$collection->ID}_photo_$image\" data-gallery=\"{$collection->ID}\" class=\"collection-image-link\">
									$photo_large
								</a>
								<span class=\"edit_image\" data-image=\"$image\" data-image-thumb-url=\"$photo_thumb[0]\" data-collection=\"{$collection->ID}\"></span>
							</li>";

							$gallery .= "<div class=\"slide\" id=\"collection_{$collection->ID}_photo_$image\">";
							$gallery .= "<div class=\"slide_text\"><h3>$title</h3><p>$description</p></div>";
							$gallery .= "<img src=\"$photo_full[0]\" />";
							$gallery .= '</div>';

						}

						$gallery .= '</div><a class="close_full_gallery"></a></div>';

					}

					$index .= "<li>
						<figure class=\"wp-block-post-featured-image\">
							<a class=\"collection_link\" href=\"#collection_{$collection->ID}\">
								$photo_large
							</a>
						</figure>
						<h3>
							<a class=\"collection_link\" href=\"#collection_{$collection->ID}\">{$collection->post_title}</a>
						</h3>
					</li>";

					$bg_img = '';

					// $html .= '<li><a href="#" class="close_collection"><span>Back to Collections</span></a></li>';

					$html .= '</ul>';

					$html .= "<div class=\"wp-block-buttons is-content-justification-center is-layout-flex wp-block-buttons-is-layout-flex\">
						<div class=\"wp-block-button has-icon__external-arrow\">
							<a class=\"wp-block-button__link wp-element-button close_collection\" href=\"#\">Back to Collections <span class=\"wp-block-button__link-icon\" aria-hidden=\"true\"><svg viewBox=\"0 0 10 12\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M8.16868 4.09641L1.30121 11.0121L0 9.71087L6.91566 2.8193H2V0.987976H10V9.01207H8.16868V4.09641Z\"></path></svg></span></a>
						</div>
					</div>";
				$html .= '</div>';

				// Append gallery

				$html .= $gallery;
			}

			$index .= '</ul>';
			$index .= "<div class=\"wp-block-buttons is-content-justification-center is-layout-flex wp-block-buttons-is-layout-flex\">
				<div class=\"wp-block-button has-icon__external-arrow\">
					<a class=\"wp-block-button__link wp-element-button\" href=\"#\" id=\"add_new_collection\">Add Collection<span class=\"wp-block-button__link-icon\" aria-hidden=\"true\"><svg viewBox=\"0 0 10 12\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M8.16868 4.09641L1.30121 11.0121L0 9.71087L6.91566 2.8193H2V0.987976H10V9.01207H8.16868V4.09641Z\"></path></svg></span></a>
				</div>
			</div>";
			$html .= '</div>';

		} else {
			$html .= '<div class="my_account_empty"><p><a href="#" id="add_first_collection">Add your first collection</a></p></div>';
		}

		wp_send_json( "$index$html" );

	}

}

// 2.7

function homebase_delete_collection() {
	$collection = $_REQUEST['collection'];

	wp_delete_post( $collection );
	wp_send_json_success();

}

// 2.8 

function homebase_edit_collection() {
	$collection = $_REQUEST['collection'];
	$edit_collection_name = $_REQUEST['edit_collection_name'];

	$args = [ 
		'ID'         => $collection,
		'post_title' => $edit_collection_name,
	];

	$post_id = wp_update_post( $args );

	wp_send_json_success();

}

// 2.9

function homebase_load_destination_collections() {
	if ( is_user_logged_in() ) {

		$current_user = wp_get_current_user();
		$current_user_ID = $current_user->ID;
		$exclude = $_REQUEST['exclude'];
		$collections = [];

		$args = [ 
			'post_type'      => 'collection',
			'author'         => $current_user_ID,
			'exclude'        => [ $exclude ],
			'posts_per_page' => -1
		];

		$collections = get_posts( $args );

		$html = '<ul class="save_collections">';

		foreach ( $collections as $collection ) {

			$images = json_decode( get_post_meta( $collection->ID, 'images', true ), TRUE );

			if ( count( $images ) > 0 ) {
				$image1 = array_values( $images )[0];
				$photo = get_field( "file", $image1 );
				$photo_thumb = wp_get_attachment_image_src( $photo, "thumbnail" );
				$bg_img = $photo_thumb[0];

			} else {
				$bg_img = '';
			}

			$html .= "<li><input type=\"radio\" id=\"destination_collection_{$collection->ID}\" name=\"destination_collection_id\" value=\"{$collection->ID}\"/><label for=\"destination_collection_{$collection->ID}\"><span>{$collection->post_title}</span></label></li>";
		}

		$html .= '<li><input type="radio" id="add_new_destination_collection" name="destination_collection_id" value="0"/><label for="add_new_destination_collection"><span>Add New Collection</span></label></li>';

		$html .= '</ul>';
		$html .= '<input type="text" id="new_destination_collection_name" name="new_destination_collection_name" placeholder="New Collection name" autocomplete="off" maxlength="30"/>';

		wp_send_json( $html );

	}

}

// 3.0 

function homebase_edit_image() {
	$image = $_REQUEST['image'];
	$edit_action = $_REQUEST['edit_action'];
	$original_collection_id = $_REQUEST['original_collection_id'];
	$destination_collection_id = $_REQUEST['destination_collection_id'];
	$new_destination_collection_name = $_REQUEST['new_destination_collection_name'];

	// Save image to destination

	if ( $destination_collection_id != 0 ) {

		$images = json_decode( get_post_meta( $destination_collection_id, 'images', true ), TRUE );
		$images[] = $image;

		$args = [ 
			'ID'         => $destination_collection_id,
			'meta_input' => [ 'images' => json_encode( array_unique( $images ) ) ],
		];

		$destination_id = wp_update_post( $args );

	} else {

		$existing_images = [];
		$existing_images[] = $image;

		$image_array = json_encode( $existing_images );

		$args = [ 
			'post_type'   => 'collection',
			'post_title'  => $new_destination_collection_name,
			'post_status' => 'publish',
			'meta_input'  => [ 'images' => $image_array ],
		];

		$destination_id = wp_insert_post( $args );

	}

	// If moving, also delete image from original collection

	if ( $edit_action == 'move' ) {

		$original_collection_images = json_decode( get_post_meta( $original_collection_id, 'images', true ), TRUE );
		$original_collection_images = array_diff( $original_collection_images, [ $image ] );

		$args = [ 
			'ID'         => $original_collection_id,
			'meta_input' => [ 'images' => json_encode( array_unique( $original_collection_images ) ) ],
		];

		$origin_id = wp_update_post( $args );
	}

	wp_send_json_success();

}

function homebase_delete_image() {
	$image = $_REQUEST['image'];
	$collection_id = $_REQUEST['original_collection_id'];

	$collection_images = json_decode( get_post_meta( $collection_id, 'images', true ), true );
	$updated_images = array_filter( $collection_images, fn( $id ) => $id !== $image );
	
	$args = [ 
		'ID'         => $collection_id,
		'meta_input' => [ 'images' => json_encode( array_unique( $updated_images ) ) ],
	];

	wp_update_post( $args );
	wp_send_json_success();
}

function homebase_seed_collections() {
	$current_user = wp_get_current_user();
	$current_user_ID = $current_user->ID;

	$args = [ 
		'post_type' => 'collection',
		'author'    => $current_user_ID,
	];

	$collections = new WP_Query( $args );

	if ( $collections->post_count == 0 ) {

		$seed_collections = [ 
			'Dream Kitchens',
			'Inspirational Bathrooms',
			'Creative Outdoors',
			'Amazing Benchtops',
			'Concrete and Render',
		];

		foreach ( $seed_collections as $collection ) {

			$existing_images = [];
			$image_array = json_encode( $existing_images );

			$args = [ 
				'post_type'   => 'collection',
				'post_title'  => $collection,
				'author'      => $current_user_ID,
				'post_status' => 'publish',
				'meta_input'  => [ 'images' => $image_array ],
			];

			$post_id = wp_insert_post( $args );

		}

	}

}

// 3.0

function homebase_brochure_downloads() {
	$labels = [ 
		'name'          => __( 'Brochure Downloads' ),
		'singular_name' => __( 'Brochure Download' ),
	];

	$args = [ 
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => false,
		'supports'           => [ 
			'title',
			'author',
			'custom-fields',
		],
		'capability_type'    => 'post',
		'has_archive'        => false,
		'menu_icon'					 => 'dashicons-download'
	];

	register_post_type( 'brochure-download', $args );
}
add_action( 'init', 'homebase_brochure_downloads' );

// 3.1

add_filter( 'manage_brochure-download_posts_columns', 'homebase_brochure_download_columns_head', 10 );
add_filter( 'manage_brochure-download_posts_custom_column', 'homebase_brochure_download_columns_content', 10, 2 );

function homebase_brochure_download_columns_head( $columns ) {
	unset( $columns['author'], $columns['title'] );
	$columns['first_name'] = 'First Name';
	$columns['last_name'] = 'Last Name';
	$columns['email'] = 'Email';
	$columns['display'] = 'Display';
	$columns['opt_in'] = 'My Account Opt-in';
	return $columns;
}

function homebase_brochure_download_columns_content( $column, $post_ID ) {
	$author_id = get_post_field( 'post_author', $post_ID );

	$user = get_user_by( 'id', $author_id );

	if ( $column == 'first_name' ) {
		echo get_user_meta( $author_id, 'first_name', true );
	}

	if ( $column == 'last_name' ) {
		echo get_user_meta( $author_id, 'last_name', true );
	}

	if ( $column == 'email' ) {
		echo $user->user_email;
	}

	if ( $column == 'display' ) {
		echo get_post_meta( $post_ID, 'display', true );
	}

	if ( $column == 'opt_in' ) {
		echo get_user_meta( $author_id, 'myaccount_opt_in', true );
	}

}

///////// SAVE BUTTON ACTIONS

function homebase_track_brochure_download() {
	$display = $_REQUEST['display'];

	$current_user = wp_get_current_user();
	$current_user_ID = $current_user->ID;

	if ( strlen( $display ) > 0 ) {

		$args = [ 
			'post_type'   => 'brochure-download',
			'author'      => $current_user_ID,
			'post_status' => 'publish',
			'meta_input'  => [ 'display' => $display ],
		];

		$post_id = wp_insert_post( $args );

		wp_send_json_success();
	}
}

// 3.2

function homebase_course_kits() {
	$labels = [ 
		'name'          => __( 'Course Kits' ),
		'singular_name' => __( 'Course Kit' ),
	];

	$args = [ 
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => false,
		'supports'           => [ 'title' ],
		'capability_type'    => 'post',
		'has_archive'        => false,
		'menu_icon'					 => 'dashicons-admin-tools',
		'menu_position'			=> 54
	];

	register_post_type( 'course-kit', $args );
}
add_action( 'init', 'homebase_course_kits' );
add_action( 'wp_ajax_homebase_track_brochure_download', 'homebase_track_brochure_download' );
add_action( 'wp_ajax_homebase_save_image', 'homebase_save_image' );
add_action( 'wp_ajax_homebase_edit_image', 'homebase_edit_image' );
add_action( 'wp_ajax_homebase_delete_image', 'homebase_delete_image' );
add_action( 'wp_ajax_homebase_add_collection', 'homebase_add_collection' );
add_action( 'wp_ajax_homebase_edit_collection', 'homebase_edit_collection' );
add_action( 'wp_ajax_homebase_delete_collection', 'homebase_delete_collection' );
add_action( 'wp_ajax_homebase_load_collections', 'homebase_load_collections' );
add_action( 'wp_ajax_homebase_load_destination_collections', 'homebase_load_destination_collections' );
add_action( 'wp_ajax_homebase_load_destination_collections', 'homebase_load_destination_collections' );