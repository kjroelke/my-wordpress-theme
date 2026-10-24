<?php
/**
 * Title: Article Header
 * Slug: kjr/article-header
 * Categories: header
 * Description: Article header with post title, meta, and edition information.
 *
 * @package KJRoelke
 */

?>
<!-- wp:group {"tagName":"header","templateLock":false,"lock":{"move":false,"remove":false},"metadata":{"name":"Header"},"align":"wide","style":{"border":{"bottom":{"color":"var:preset|color|black","width":"3px"}},"spacing":{"padding":{"bottom":"var:preset|spacing|base"}}},"layout":{"type":"default"}} -->
<header class="wp-block-group alignwide"
	style="border-bottom-color:var(--wp--preset--color--black);border-bottom-width:3px;padding-bottom:var(--wp--preset--spacing--base)">
	<!-- wp:post-terms {"term":"category","className":"heading-line-right","style":{"typography":{"fontStyle":"normal","fontWeight":"700","textTransform":"uppercase"}}} /-->

	<!-- wp:post-title {"level":1} /-->

	<!-- wp:group {"metadata":{"name":"Post Meta","categories":["text"],"patternName":"kjr/post-meta"},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
	<div class="wp-block-group" style="flex-direction:row">
		<!-- wp:post-date {"metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}}} /-->

		<!-- wp:post-author-name /-->

		<!-- wp:post-time-to-read /-->
	</div>
	<!-- /wp:group -->
</header>
<!-- /wp:group -->
