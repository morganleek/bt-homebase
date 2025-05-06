<?php
	$photos = new WP_Query( [ 
		'connected_type'  => 'displays_to_photos',
		'connected_items' => get_queried_object(),
		'nopaging'        => true,
	] );
?>

<div class="display-image-gallery">
	<?php if( $photos->have_posts() ) : ?>
	<ul class="gallery-items">
		<?php 
			while ( $photos->have_posts() ) {
				$photos->the_post();
				$photo = get_field( "file", get_the_ID() );
				$thumbnail = wp_get_attachment_image($photo);
				$thumbnail_url = wp_get_attachment_image_src( $photo );
				if( $thumbnail ) {
					$gallery_title = get_field( "gallery_title", get_the_ID() ) ? "<h6>" . get_field( "gallery_title", get_the_ID() ) . "</h6>" : "";
					$gallery_description = get_field( "gallery_description", get_the_ID() ) ? "<p>" . get_field( "gallery_description", get_the_ID() ) . "</p>" : "";
					print "<li id=\"photo_" . get_the_ID() . "\">";
						print "<a class=\"display_photo\" href=\"#view_photo_" . get_the_ID() . "\">";
							print "<div class=\"overlay\">{$gallery_title}{$gallery_description}</div>";
							print $thumbnail;
							homebase_save_to_collection_button( get_the_ID() );
						print "</a>";
					print "</li>";
				}
			}
		?>
	</ul>
	<?php endif; ?>
</div>

<div class="full_gallery">
	<a class="close_full_gallery" href="#">Close</a>
	<div class="full_gallery_slides">
		<?php while ( $photos->have_posts() ) :
			$photos->the_post(); 
			$photo = get_field( "file", get_the_ID() );
			$photo_full = wp_get_attachment_image_src( $photo, "full" );

			$photo_title = get_field( "gallery_title", get_the_ID() );
			$photo_desc = get_field( "gallery_description", get_the_ID() );
			?>
			<div class="slide" id="view_photo_<?php the_ID(); ?>">
				<div class="slide_text">
					<?php print $photo_title ? "<h3>$photo_title</h3>" : ""; ?>
					<?php print $photo_desc ? "<p>$photo_desc</p>" : ""; ?>
					<div class="slide_buttons">
						<?php homebase_save_to_collection_button( get_the_ID() ); ?>
					</div>
				</div>
				<img src="<?php echo $photo_full[0]; ?>" />
			</div>
		<?php endwhile; ?>
	</div>
</div>