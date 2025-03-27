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
				if( $thumbnail ) {
					$gallery_title = get_field( "gallery_title", get_the_ID() ) ? "<h6>" . get_field( "gallery_title", get_the_ID() ) . "</h6>" : "";
					$gallery_description = get_field( "gallery_description", get_the_ID() ) ? "<p>" . get_field( "gallery_description", get_the_ID() ) . "</p>" : "";
					print "<li>";
						print "<div class=\"overlay\">{$gallery_title}{$gallery_description}</div>";
						print $thumbnail;
						print "<button class=\"header\"></button>";
					print "</li>";
				}
			}
		?>
	</ul>
	<?php endif; ?>
</div>