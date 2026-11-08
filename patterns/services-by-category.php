<?php
/**
 * Title: Services by Category
 * Slug: farmacia-della-bona/services-by-category
 * Categories: farmacia-della-bona-patterns
 * Description: One banner per service category, alternating the image style (section-2) with the plain style (section-1).
 */

// Sorted like core sorts pages: by the category "Ordine" field, then alphabetically.
$service_categories = farmaciadellabona_get_ordered_service_categories();

// Services without a category get a final catch-all banner, so none disappear from the page.
$has_uncategorized = (bool) get_posts(
	array(
		'post_type'      => 'service',
		'posts_per_page' => 1,
		'fields'         => 'ids',
		'tax_query'      => array(
			array(
				'taxonomy' => 'service_cat',
				'operator' => 'NOT EXISTS',
			),
		),
	)
);

$banners = array();
foreach ( $service_categories as $service_category ) {
	$banners[] = array(
		'title'     => $service_category->name,
		'tax_query' => array( 'include' => array( 'service_cat' => array( $service_category->term_id ) ) ),
		'image_id'  => (int) get_term_meta( $service_category->term_id, 'service_cat_image', true ),
	);
}
if ( $has_uncategorized ) {
	$banners[] = array(
		'title'     => __( 'Altri Servizi', 'farmacia-della-bona' ),
		'tax_query' => array( 'exclude' => array( 'service_cat' => wp_list_pluck( $service_categories, 'term_id' ) ) ),
		'image_id'  => 0,
	);
}

/**
 * Prints the kicker + title header shared by every banner.
 */
$print_header = function ( $title ) {
	?>
<!-- wp:group {"className":"is-style-section-header"} -->
<div class="wp-block-group is-style-section-header"><!-- wp:paragraph -->
<p><?php esc_html_e( 'Servizi Disponibili', 'farmacia-della-bona' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php echo esc_html( $title ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->
	<?php
};

/**
 * Prints the services grid of one banner, filtered by the given taxQuery.
 */
$print_services = function ( $tax_query ) {
	$query_attrs = array(
		'query'     => array(
			'postType' => 'service',
			'perPage'  => 100,
			'inherit'  => false,
			'taxQuery' => $tax_query,
		),
		'namespace' => 'service-category-query',
		'className' => 'alignwide',
	);
	?>
<!-- wp:query <?php echo wp_json_encode( $query_attrs ); ?> -->
<div class="wp-block-query alignwide"><!-- wp:post-template {"layout":{"type":"grid","columnCount":4}} -->
<!-- wp:group {"className":"is-style-card-rounded","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-card-rounded"><!-- wp:post-featured-image {"width":"80px","height":"80px"} /-->

<!-- wp:post-title /-->

<!-- wp:paragraph {"textColor":"primary","style":{"spacing":{"margin":{"top":"4px"}},"typography":{"fontSize":"12px","fontWeight":"400","letterSpacing":"0.05em","lineHeight":"1.4","textTransform":"uppercase"}},"metadata":{"bindings":{"content":{"source":"farmacia-delle-bona/service-meta"}}}} -->
<p class="has-primary-color has-text-color" style="margin-top:4px;font-size:12px;font-weight:400;letter-spacing:0.05em;line-height:1.4;text-transform:uppercase"></p>
<!-- /wp:paragraph -->

<!-- wp:post-excerpt {"style":{"spacing":{"margin":{"top":"12px"}}}} /--></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query -->
	<?php
};
?>
<!-- wp:heading {"level":1,"className":"screen-reader-text"} -->
<h1 class="wp-block-heading screen-reader-text"><?php esc_html_e( 'I Nostri Servizi', 'farmacia-della-bona' ); ?></h1>
<!-- /wp:heading -->

<?php
foreach ( $banners as $index => $banner ) :
	// Banners start with the image style and then alternate with the plain one.
	if ( 0 === $index % 2 ) :
		$image_url = $banner['image_id'] ? wp_get_attachment_image_url( $banner['image_id'], 'full' ) : '';

		// The category image is dimmed so the white header stays readable; without an image there is no background.
		$cover_attrs = array(
			'url'       => $image_url,
			'dimRatio'  => $image_url ? 50 : 0,
			'isDark'    => true,
			'className' => 'is-style-section-2',
			'align'     => 'full',
		);
		?>
<!-- wp:cover <?php echo wp_json_encode( array_filter( $cover_attrs ) ); ?> -->
<div class="wp-block-cover alignfull is-style-section-2"><?php if ( $image_url ) : ?><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( $image_url ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-50 has-background-dim"></span><?php endif; ?><div class="wp-block-cover__inner-container">
		<?php $print_header( $banner['title'] ); ?>
		<?php $print_services( $banner['tax_query'] ); ?>
</div></div>
<!-- /wp:cover -->
	<?php else : ?>
<!-- wp:group {"className":"is-style-section-1","align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-1">
		<?php $print_header( $banner['title'] ); ?>
		<?php $print_services( $banner['tax_query'] ); ?>
</div>
<!-- /wp:group -->
	<?php
	endif;
endforeach;
