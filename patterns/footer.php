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
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"
	style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--50)">
	<!-- wp:group {"align":"wide","layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:group {"align":"full","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"top"}} -->
		<div class="wp-block-group alignfull">
			<!-- wp:columns -->
			<div class="wp-block-columns">
				<!-- wp:column {"width":"100%"} -->
				<div class="wp-block-column" style="flex-basis:100%">
					<!-- wp:site-title {"level":2} /-->

					<!-- wp:site-tagline /-->
				</div>
				<!-- /wp:column -->

				<!-- wp:column {"width":""} -->
				<div class="wp-block-column">
					<!-- wp:spacer {"height":"var:preset|spacing|40","width":"0px"} -->
					<div style="height:var(--wp--preset--spacing--40);width:0px" aria-hidden="true"
						class="wp-block-spacer"></div>
					<!-- /wp:spacer -->
				</div>
				<!-- /wp:column -->
			</div>
			<!-- /wp:columns -->

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|80"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"top","justifyContent":"space-between"}} -->
			<div class="wp-block-group">
				<!-- wp:social-links {"iconColor":"accent-3","iconColorValue":"#191919","customIconBackgroundColor":"#e3e3e300","iconBackgroundColorValue":"#e3e3e300","openInNewTab":true,"className":"is-style-default"} -->
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
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
