<?php
/**
 * Title: Header - custom site logo
 * Slug: farmacia-della-bona/header-site-logo
 * Categories: header, farmacia-della-bona-patterns
 * Block Types: core/template-part/header
 */
?>
<!-- wp:group {"metadata":{"name":"Header"},"align":"full","style":{"spacing":{"margin":{"top":"0px"},"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"gradient":"gradient-1","layout":{"inherit":true,"type":"constrained"}} -->
<div class="wp-block-group alignfull has-gradient-1-gradient-background has-background" style="margin-top:0px;padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30)"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"0"}}},"layout":{"type":"flex","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide"><!-- wp:group {"style":{"spacing":{"blockGap":"10px"},"layout":{"selfStretch":"fit","flexSize":null}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group">
		<!-- wp:image {"align":"center","width":140,"height":71,"sizeSlug":"full","linkDestination":"custom"} -->
		<figure class="wp-block-image aligncenter size-full is-resized"><a href="/"><img src="<?php echo esc_url( get_stylesheet_directory_uri() ) . '/assets/images/site-logo.svg'; ?>" alt="Farmacia Della Bona logo" width="140" height="71"/></a></figure>
		<!-- /wp:image -->
    </div>
<!-- /wp:group -->

		<?php

			$about_page = get_page_by_path( 'chi-siamo' );
			$about_id   = $about_page?->ID ?? 0;
			$about_url  = $about_id ? get_permalink( $about_id ) : home_url( '/chi-siamo/' );

			$contact_page = get_page_by_path( 'contattaci' );
			$contact_id   = $contact_page?->ID ?? 0;
			$contact_url  = $contact_id ? get_permalink( $contact_id ) : home_url( '/contattaci/' );

			// Departments live in the main menu so every item shares the same spacing.
			$departments = get_terms(
				array(
					'taxonomy'   => 'department',
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC',
				)
			);
			$departments = is_wp_error( $departments ) ? array() : $departments;
		?>
<!-- wp:navigation {"textColor":"primary","className":"site-menu","metadata":{"ignoredHookedBlocks":["woocommerce/customer-account","woocommerce/mini-cart"]},"style":{"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"600","letterSpacing":"0.05em"},"spacing":{"blockGap":"var:preset|spacing|30"},"layout":{"selfStretch":"fill","flexSize":null}},"fontSize":"x-small","layout":{"type":"flex","justifyContent":"space-between","setCascadingProperties":true},"ariaLabel":"<?php esc_attr_e( 'Main menu', 'farmacia-della-bona' ); ?>"} -->
	<?php foreach ( $departments as $department ) : ?>
	<!-- wp:navigation-link <?php echo wp_json_encode( array( 'label' => $department->name, 'type' => 'department', 'id' => $department->term_id, 'url' => get_term_link( $department ), 'kind' => 'taxonomy' ) ); ?> /-->
	<?php endforeach; ?>
	<!-- wp:navigation-link {"label":"<?php esc_html_e( 'Servizi', 'farmacia-della-bona' ); ?>","type":"service","url":"<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>","kind":"post-type"} /-->
	<!-- wp:navigation-link {"label":"<?php esc_html_e( 'Eventi', 'farmacia-della-bona' ); ?>","type":"event","url":"<?php echo esc_url( get_post_type_archive_link( 'event' ) ); ?>","kind":"post-type"} /-->
	<!-- wp:navigation-link {"label":"<?php esc_html_e( 'Chi Siamo', 'farmacia-della-bona' ); ?>","type":"page","id":<?php echo $about_id; ?>,"url":"<?php echo esc_url( $about_url ); ?>","kind":"post-type"} /-->
	<!-- wp:navigation-link {"label":"<?php esc_html_e( 'Contattaci', 'farmacia-della-bona' ); ?>","type":"page","id":<?php echo $contact_id; ?>,"url":"<?php echo esc_url( $contact_url ); ?>","kind":"post-type"} /-->
	<!-- /wp:navigation --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->



