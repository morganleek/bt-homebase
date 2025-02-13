<?php
/**
 * Title: header
 * Slug: /header
 * Inserter: no
 */
?>
<!-- wp:group {"style":{"position":{"type":""}},"layout":{"inherit":"true","type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:group {"align":"full","style":{"spacing":{"padding":{"bottom":"30px","top":"30px","left":"30px","right":"30px"}}},"layout":{"type":"flex","justifyContent":"space-between"}} -->
<div class="wp-block-group alignfull" style="padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:group {"layout":{"type":"flex"}} -->
<div class="wp-block-group"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/home-base-logo-horiz.svg" alt="" class=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:group -->

<!-- wp:navigation {"ref":50466,"style":{"spacing":{"margin":{"top":"0"}}},"layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"right","orientation":"horizontal"}} /-->

<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Search</a></div>
<!-- /wp:button -->

<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">User</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:woocommerce/mini-cart {"productCountVisibility":"always"} /-->

<!-- wp:navigation {"ref":50466,"overlayMenu":"always","icon":"menu"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"}} -->
<div id="search-overlay" class="wp-block-group" style="margin-top:0;margin-bottom:0"><!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">{{ Search }}</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->