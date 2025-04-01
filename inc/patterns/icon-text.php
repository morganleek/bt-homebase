<?php
return [ 
	'title'      => __( 'Icon & Text', 'bones_theme' ),
	'categories' => [ 'query' ],
	'blockTypes' => [ 'core/columns' ],
	'content'    => '<!-- wp:columns {"isStackedOnMobile":false,"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|30"}}}} -->
<div class="wp-block-columns is-not-stacked-on-mobile wp-block-icon-text"><!-- wp:column {"width":"32px"} -->
<div class="wp-block-column" style="flex-basis:32px"><!-- wp:image {"id":50846,"width":"32px","height":"32px","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large is-resized"><img src="https://ho.1fo.au/wp-content/uploads/2025/04/2000-displays.svg" alt="" class="wp-image-50846" style="object-fit:cover;width:32px;height:32px"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"style":{"spacing":{"blockGap":"8px"}}} -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Title</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>...</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->'
];