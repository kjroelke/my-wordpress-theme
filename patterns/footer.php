<?php
/**
 * Title: Footer
 * Slug: macrosbysara/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Description: Footer columns with logo, title, tagline and links.
 *
 * @package KJRoelke
 */

?>
<!-- wp:group {"tagName":"footer","metadata":{"name":"Footer"},"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"},":hover":{"color":{"text":"var:preset|color|secondary-light"}}}},"spacing":{"padding":{"top":"var:preset|spacing|xl","bottom":"var:preset|spacing|xl"},"blockGap":"var:preset|spacing|base","margin":{"top":"0","bottom":"0"}}},"backgroundColor":"primary","textColor":"white","layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch","verticalAlignment":"center"}} -->
<footer class="wp-block-group has-white-color has-primary-background-color has-text-color has-background has-link-color"
	style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--xl);padding-bottom:var(--wp--preset--spacing--xl)">
	<!-- wp:social-links {"iconColor":"white","iconColorValue":"rgb(255, 255, 255)","openInNewTab":true,"className":"is-style-logos-only","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|base"}}},"layout":{"type":"flex","justifyContent":"center","orientation":"horizontal"}} -->
	<ul class="wp-block-social-links has-icon-color is-style-logos-only">
		<!-- wp:social-link {"url":"https://www.tiktok.com/@sararoelke23","service":"tiktok","label":"@sararoelke23"} /-->

		<!-- wp:social-link {"url":"https://instagram.com/idreamofmacros","service":"instagram","label":"idreamofmacros"} /-->
	</ul>
	<!-- /wp:social-links -->

	<!-- wp:navigation {"ref":606,"overlayMenu":"never","icon":"menu","style":{"spacing":{"blockGap":"var:preset|spacing|base"},"typography":{"textTransform":"uppercase"},"layout":{"selfStretch":"fit","flexSize":null}},"fontSize":"root","layout":{"type":"flex","justifyContent":"center"}} /-->

	<!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"textTransform":"uppercase"}},"textColor":"white"} -->
	<p class="has-text-align-center has-white-color has-text-color has-link-color" id="copyright" style="text-transform:uppercase">© Macros By Sara, LLC</p>
	<!-- /wp:paragraph -->
</footer>
<!-- /wp:group -->
