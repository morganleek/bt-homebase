<?php

return [ 
	'title'      => __( 'Card Block - Dark', 'bones_theme' ),
	'categories' => [ 'query' ],
	'blockTypes' => [ 'core/query' ],
	'content'    => '<!-- wp:group {"className":"wp-block-card wp-block-card-block-dark","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group wp-block-card wp-block-card-block-dark"><!-- wp:image {"lock":{"move":true,"remove":true}} -->
<figure class="wp-block-image"><img alt=""/></figure>
<!-- /wp:image -->

<!-- wp:group {"lock":{"move":true,"remove":true},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
];