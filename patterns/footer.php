<?php
/**
 * Title: Footer
 * Slug: kjr-wp-theme/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Description: Footer with site logo and further navigation.
 *
 * @package KJRoelke
 */

?>
<!-- wp:group {"metadata":{"patternName":"kjr-wp-theme/footer","name":"Footer","description":"Footer with site logo and further navigation.","categories":["footer"]},"style":{"spacing":{"padding":{"top":"var:preset|spacing|base","bottom":"var:preset|spacing|base"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"
	style="padding-top:var(--wp--preset--spacing--base);padding-bottom:var(--wp--preset--spacing--base)">
	<!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide" style="flex-direction:row">
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|xs"}},"layout":{"type":"flex","orientation":"vertical"}} -->
		<div class="wp-block-group" style="flex-direction:column">
			<!-- wp:site-title {"level":2} /-->

			<!-- wp:site-tagline /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:social-links {"iconColor":"accent-3","iconColorValue":"#191919","customIconBackgroundColor":"#e3e3e300","iconBackgroundColorValue":"#e3e3e300","openInNewTab":true,"className":"is-style-default","layout":{"type":"flex","justifyContent":"right"}} -->
		<ul class="wp-block-social-links has-icon-color has-icon-background-color is-style-default">
			<!-- wp:social-link {"url":"https://github.com/kjroelke","service":"github","label":"Github"} /-->

			<!-- wp:social-link {"url":"https://linkedin.com/in/kjroelke","service":"linkedin","label":"LinkedIn"} /-->

			<!-- wp:social-link {"url":"https://instagram.com/kjroelke","service":"instagram"} /-->

			<!-- wp:social-link {"url":"https://open.spotify.com/artist/7o7qRfGGQ9dNYTWJ8xp8RK?si=yfKUZIwvSb2wU6QZJ4Lr9g","service":"spotify"} /-->
		</ul>
		<!-- /wp:social-links -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
