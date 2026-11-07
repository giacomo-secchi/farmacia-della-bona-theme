<?php


// Add the order column to the services list.
add_filter( 'manage_service_posts_columns', function( $columns ) {
    $columns['menu_order'] = __( 'Order', 'farmacia-della-bona' );
    return $columns;
});

// Fill the column with the menu_order value.
add_action( 'manage_service_posts_custom_column', function( $column, $post_id ) {
    if ( $column === 'menu_order' ) {
        $order = get_post( $post_id )->menu_order;
        echo esc_html( $order );
    }
}, 10, 2 );

// Make the column sortable by clicking its header.
add_filter( 'manage_edit-service_sortable_columns', function( $columns ) {
    $columns['menu_order'] = 'menu_order';
    return $columns;
} );
