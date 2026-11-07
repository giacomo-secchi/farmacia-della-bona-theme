<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

$queried_object = get_queried_object();

// Read fields from the current taxonomy term, or from the options page on every other view (e.g. the homepage).
$selector = $queried_object instanceof WP_Term ? 'term_' . $queried_object->term_id : 'option';

$fields = get_fields( $selector );
$fields = is_array( $fields ) ? $fields : [];

$hero_title         = (string) get_field( 'hero_title', $selector, false );
$hero_subtitle      = $fields['hero_subtitle'] ?? '';
$hero_button_text   = $fields['hero_button_text'] ?? '';
$hero_button_link   = $fields['hero_button_link'] ?? '';
$hero_content       = $fields['hero_content'] ?? '';
$hero_image         = $fields['hero_image'] ?? null;
$hero_list          = $fields['hero_list'] ?? [];
$hero_product_id    = absint( $fields['hero_product_id'] ?? 0 );
$hero_overlay_color = sanitize_html_class( (string) ( $fields['hero_overlay_color_palette'] ?? '' ) );

$hero_image_url = is_array( $hero_image ) ? (string) ( $hero_image['url'] ?? '' ) : '';

// Escaped values for the HTML markup.
$title_html       = nl2br( esc_html( $hero_title ) );
$subtitle_html    = esc_html( $hero_subtitle );
$content_html     = nl2br( esc_html( $hero_content ) );
$button_text_html = esc_html( $hero_button_text );
$button_link_attr = esc_url( $hero_button_link );
$image_url_attr   = esc_url( $hero_image_url );

$list_items_html = '';
if ( is_array( $hero_list ) ) {
	foreach ( $hero_list as $item ) {
		$text = esc_html( $item['hero_list_item'] ?? '' );
		if ( '' === $text ) {
			continue;
		}

		$list_items_html .= "<!-- wp:list-item -->\n<li>{$text}</li>\n<!-- /wp:list-item -->\n";
	}
}

// Cover block attributes, serialized safely for the block comment delimiter.
$cover_attributes = [
	'dimRatio'           => 70,
	'minHeight'          => 65,
	'minHeightUnit'      => 'vh',
	'isUserOverlayColor' => true,
	'isDark'             => true,
	'namespace'          => 'hero',
	'align'              => 'full',
	'className'          => 'is-style-background-2',
	'style'              => [
		'spacing' => [
			'margin'  => [ 'top' => '0', 'bottom' => '0' ],
			'padding' => [ 'top' => 'var:preset|spacing|80', 'bottom' => 'var:preset|spacing|80' ],
		],
	],
	'layout'             => [ 'type' => 'constrained' ],
];

if ( '' !== $image_url_attr ) {
	$cover_attributes = [ 'url' => $hero_image_url ] + $cover_attributes;
}

$overlay_class = 'wp-block-cover__background has-background-dim-70 has-background-dim';
if ( '' !== $hero_overlay_color ) {
	$cover_attributes['overlayColor'] = $hero_overlay_color;
	$overlay_class                    = "wp-block-cover__background has-{$hero_overlay_color}-background-color has-background-dim-70 has-background-dim";
}

$cover_attributes_json = serialize_block_attributes( $cover_attributes );

$image_html = '' !== $image_url_attr
	? "<img class=\"wp-block-cover__image-background\" alt=\"\" src=\"{$image_url_attr}\" data-object-fit=\"cover\"/>"
	: '';

$subtitle_block = '' !== $subtitle_html
	? "<!-- wp:paragraph -->\n<p>{$subtitle_html}</p>\n<!-- /wp:paragraph -->"
	: '';

$content_block = '' !== $content_html
	? "<!-- wp:paragraph -->\n<p>{$content_html}</p>\n<!-- /wp:paragraph -->"
	: '';

$list_block = '' !== $list_items_html
	? "<!-- wp:list {\"className\":\"is-style-no-style\",\"fontSize\":\"large\"} -->\n<ul class=\"wp-block-list is-style-no-style has-large-font-size\">{$list_items_html}</ul>\n<!-- /wp:list -->"
	: '';

$buttons_block = ( '' !== $button_text_html && '' !== $button_link_attr )
	? <<<HTML
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"left"}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="{$button_link_attr}">{$button_text_html}</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
HTML
	: '';

// Render the product column only when a valid product is selected.
$product_column = '';
if ( $hero_product_id && 'product' === get_post_type( $hero_product_id ) ) {
	$product_attributes_json = serialize_block_attributes(
		[
			'productId' => $hero_product_id,
			'className' => 'is-style-card',
		]
	);

	$product_column = <<<HTML
<!-- wp:column {"verticalAlignment":"center","layout":{"type":"constrained","contentSize":"300px"}} -->
<div class="wp-block-column is-vertically-aligned-center">
	<!-- wp:woocommerce/single-product {$product_attributes_json} -->
	<div class="wp-block-woocommerce-single-product woocommerce is-style-card">
		<!-- wp:woocommerce/product-image {"showSaleBadge":false,"imageSizing":"thumbnail","isDescendentOfSingleProductBlock":true,"width":"100%","height":""} -->
			<!-- wp:woocommerce/product-sale-badge {"align":"right"} /-->
		<!-- /wp:woocommerce/product-image -->
		<!-- wp:post-title {"textAlign":"center","level":3,"isLink":true,"align":"center","__woocommerceNamespace":"woocommerce/product-query/product-title"} /-->
		<!-- wp:woocommerce/product-summary {"isDescendentOfSingleProductBlock":true} /-->
	</div>
	<!-- /wp:woocommerce/single-product -->
</div>
<!-- /wp:column -->
HTML;
}

$content = <<<HTML
<!-- wp:cover {$cover_attributes_json} -->
<div class="wp-block-cover alignfull is-dark is-style-background-2" style="min-height:65vh;margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)">{$image_html}<span aria-hidden="true" class="{$overlay_class}"></span><div class="wp-block-cover__inner-container"><!-- wp:columns {"verticalAlignment":"center","align":"wide","powderMotion":true,"powderMotionEffect":"fadeInUp","powderDuration":1,"powderMotionDistance":"60"} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center" data-motion="fadeInUp" data-duration="1" data-distance="60"><!-- wp:column {"verticalAlignment":"center","layout":{"type":"constrained","justifyContent":"right"}} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:heading {"fontSize":"xxx-large"} -->
<h2 class="wp-block-heading has-xxx-large-font-size">{$title_html}</h2>
<!-- /wp:heading -->

{$subtitle_block}

{$content_block}

{$list_block}

{$buttons_block}</div>
<!-- /wp:column -->

{$product_column}</div>
<!-- /wp:columns --></div></div>
<!-- /wp:cover -->
HTML;
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php echo do_blocks( $content ); ?>
</div>
