<?php
/**
 * Title: Post Meta (Author, Date, Edition)
 * Slug: kjr/post-meta
 * Categories: text
 * Description: Post meta information including author, date, and edition.
 *
 * @package KJRoelke
 */

?>
<!-- wp:group {"metadata":{"name":"Post Meta","categories":["text"],"patternName":"kjr/post-meta"},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="flex-direction:row">
	<!-- wp:post-date {"metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}}} /-->

	<!-- wp:post-author-name /-->

	<!-- wp:post-time-to-read /-->
</div>
<!-- /wp:group -->
