<?php
/**
 * Service category ordering.
 *
 * Mirrors the core `menu_order` logic of posts: an integer that defaults to 0,
 * sorted ascending, with ties broken alphabetically by name.
 */

// Register the order as term meta, so it has a type, a default and is exposed in the REST API like core fields.
add_action( 'init', function() {
    register_term_meta( 'service_cat', 'menu_order', [
        'type'              => 'integer',
        'description'       => __( 'Ordine della categoria', 'farmacia-della-bona' ),
        'single'            => true,
        'default'           => 0,
        // A closure, not 'intval': the sanitize filter passes 4 args and PHP 8 internal functions reject extras.
        'sanitize_callback' => function( $value ) {
            return (int) $value;
        },
        'show_in_rest'      => true,
    ] );
} );

/**
 * Returns the non-empty service categories sorted like core sorts pages: by order, then by name.
 *
 * @return WP_Term[]
 */
function farmaciadellabona_get_ordered_service_categories() {
    $terms = get_terms( [
        'taxonomy'   => 'service_cat',
        'hide_empty' => true,
    ] );

    if ( is_wp_error( $terms ) ) {
        return [];
    }

    usort( $terms, function( $a, $b ) {
        // get_term_meta() returns the registered default (0) when the order was never saved.
        $order = (int) get_term_meta( $a->term_id, 'menu_order', true ) <=> (int) get_term_meta( $b->term_id, 'menu_order', true );
        return $order ?: strcasecmp( $a->name, $b->name );
    } );

    return $terms;
}
