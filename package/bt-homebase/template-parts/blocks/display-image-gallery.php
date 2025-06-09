<?php
	// $photos = new WP_Query( [ 
	// 	'connected_type'  => 'displays_to_photos',
	// 	'connected_items' => get_queried_object(),
	// 	'nopaging'        => true,
	// ] );
	$photos = get_field( 'gallery', get_the_ID() );

	$photos_display = array_map( fn( $photo ) => [ 
		'ID'						=> $photo['ID'],
		'title_sm'      => ! empty( $photo['title'] ) ? "<h6>" . $photo['title'] . "</h6>" : "",
		'title_lg'      => ! empty( $photo['title'] ) ? "<h3>" . $photo['title'] . "</h3>" : "",
		'description'   => ! empty( $photo['description'] ) ? "<p>" . $photo['description'] . "</p>" : "",
		'thumbnail'     => wp_get_attachment_image( $photo['ID'], 'thumbnail' ),
		'full'          => wp_get_attachment_image( $photo['ID'], 'full' ),
		'button'        => homebase_save_to_collection_button( $photo['ID'], true )
	], $photos );
?>

<div class="display-image-gallery">
	<?php if( !empty( $photos_display ) ) : ?>
		<ul class="gallery-items">
			<?php 
				foreach ( $photos_display as $photo ) {
					print "<li id=\"photo_" . $photo['ID'] . "\">";
						print "<a class=\"display_photo\" href=\"#view_photo_" . $photo['ID'] . "\">";
							print "<div class=\"overlay\">{$photo['title_sm']}{$photo['description']}</div>";
							print $photo['thumbnail'];
							print $photo['button'];
						print "</a>";
					print "</li>";
				}
			?>
		</ul>
	<?php endif; ?>
</div>

<div class="full_gallery">
	<a class="close_full_gallery" href="#">Close</a>
	<div class="full_gallery_slides">
		<?php foreach ( $photos_display as $photo ) : ?>
			<div class="slide" id="view_photo_<?php print $photo['ID']; ?>">
				<div class="slide_text">
					<?php print $photo['title_lg']; ?>
					<?php print $photo['description']; ?>
					<!-- <div class="slide_buttons">
						<?php print $photo['button']; ?>
					</div> -->
				</div>
				<?php echo $photo['full']; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>