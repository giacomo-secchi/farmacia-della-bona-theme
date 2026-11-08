<?php
/**
 * Title: Footer - default
 * Slug: powder/footer
 * Categories: powder-footer
 * Block Types: core/template-part/footer
 */
?>
<!-- wp:group {"metadata":{"name":"Footer"},"align":"full","style":{"spacing":{"margin":{"top":"0"},"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"fontSize":"x-small","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-x-small-font-size" style="margin-top:0;padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30)">
	<!-- wp:group {"align":"wide","layout":{"type":"flex","allowOrientation":false,"justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:group {"style":{"spacing":{"blockGap":"5px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group">
			<!-- wp:group {"style":{"spacing":{"blockGap":"5px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph -->
				<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:site-title {"level":0,"isLink":false,"style":{"typography":{"fontStyle":"normal","fontWeight":"300","lineHeight":"1.75"}},"fontSize":"x-small"} /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
		<!-- wp:social-links {"openInNewTab":true,"size":"has-small-icon-size","className":"is-style-outline","style":{"spacing":{"blockGap":{"left":"5px"}}}} -->
		<ul class="wp-block-social-links has-small-icon-size is-style-outline">
			<!-- wp:social-link {"url":"https://x.com/","service":"x"} /-->
			<!-- wp:social-link {"url":"https://www.linkedin.com/","service":"linkedin"} /-->
			<!-- wp:social-link {"url":"https://instagram.com/","service":"instagram"} /-->
			<!-- wp:social-link {"url":"https://www.facebook.com/","service":"facebook"} /-->
			<!-- wp:social-link {"url":"https://www.threads.net/","service":"threads"} /-->
		</ul>
		<!-- /wp:social-links -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->


<!-- wp:jetpack/send-a-message {"className":"is-style-floating-bottom-right"} -->
<div class="wp-block-jetpack-send-a-message is-style-floating-bottom-right"><!-- wp:jetpack/whatsapp-button {"countryCode":"39IT","phoneNumber":"3761476498"} -->
<div class="wp-block-jetpack-whatsapp-button is-color-dark has-no-text"><a class="whatsapp-block__button" href="https://api.whatsapp.com/send?phone=393761476498&amp;text=Salve%2C%20ho%20preso%20le%20informazioni%20di%20WhatsApp%20dal%20sito%20web." style="background-color:#25D366;color:#fff" target="_self" rel="noopener noreferrer"></a></div>
<!-- /wp:jetpack/whatsapp-button --></div>
<!-- /wp:jetpack/send-a-message -->