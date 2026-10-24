<?php
/**
 * Title: Sidebar
 * Slug: kjr-wp-theme/sidebar
 * Categories: kjr-layouts
 * Block Types: core/template-part/sidebar
 * Description: Sidebar with search, recent posts, and categories.
 *
 * @package KJRoelke
 */

?>
<!-- wp:group {"tagName":"aside","metadata":{"patternName":"kjr-wp-theme/sidebar","name":"Sidebar","description":"Sidebar with search, recent posts, and categories.","categories":["kjr-layouts"]},"style":{"color":{"background":"#0095c73d"},"spacing":{"padding":{"top":"var:preset|spacing|base","bottom":"var:preset|spacing|base","left":"var:preset|spacing|base","right":"var:preset|spacing|base"},"blockGap":"var:preset|spacing|xl"}},"layout":{"type":"constrained"}} -->
<aside class="wp-block-group has-background"
	style="background-color:#0095c73d;padding-top:var(--wp--preset--spacing--base);padding-right:var(--wp--preset--spacing--base);padding-bottom:var(--wp--preset--spacing--base);padding-left:var(--wp--preset--spacing--base)">
	<!-- wp:search {"label":"Search","buttonText":"Search","buttonUseIcon":true} /-->

	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|sm"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"fontSize":"md"} -->
		<h2 class="wp-block-heading has-md-font-size"> Recent Posts</h2>
		<!-- /wp:heading -->

		<!-- wp:latest-posts {"displayPostDate":true} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|sm"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"fontSize":"md"} -->
		<h2 class="wp-block-heading has-md-font-size">Categories</h2>
		<!-- /wp:heading -->

		<!-- wp:categories {"showHierarchy":true} /-->
	</div>
	<!-- /wp:group -->
</aside>
<!-- /wp:group -->
