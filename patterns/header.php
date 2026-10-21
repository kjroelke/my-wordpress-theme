<?php
/**
 * Title: Header
 * Slug: kjr-wp-theme/header
 * Categories: header
 * Block Types: core/template-part/header
 * Description: Header with site title and navigation.
 *
 * @package KJRoelke
 */

?>
<!-- wp:group {"metadata":{"name":"Header Container"},"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
	<!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide" style="flex-direction:row">
		<!-- wp:site-logo {"width":120,"shouldSyncIcon":true} /-->

		<!-- wp:group {"layout":{"type":"default"}} -->
		<div class="wp-block-group" style="min-width:120px">
			<!-- wp:navigation {"ref":1316,"style":{"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"400"}},"fontFamily":"subheadings","layout":{"type":"flex","justifyContent":"right"}} /-->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
