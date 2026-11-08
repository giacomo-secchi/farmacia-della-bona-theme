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

// "Ordine" field on the add service category form, styled like the core term fields.
add_action( 'service_cat_add_form_fields', function() {
    ?>
    <div class="form-field term-menu-order-wrap">
        <label for="term-menu-order"><?php esc_html_e( 'Ordine', 'farmacia-della-bona' ); ?></label>
        <input type="number" name="menu_order" id="term-menu-order" value="0" step="1" />
        <p><?php esc_html_e( 'Posizione del banner nella pagina Servizi: i numeri più bassi vengono prima. A parità di numero, ordine alfabetico.', 'farmacia-della-bona' ); ?></p>
    </div>
    <?php
} );

// "Ordine" field on the edit service category form.
add_action( 'service_cat_edit_form_fields', function( $term ) {
    ?>
    <tr class="form-field term-menu-order-wrap">
        <th scope="row"><label for="term-menu-order"><?php esc_html_e( 'Ordine', 'farmacia-della-bona' ); ?></label></th>
        <td>
            <input type="number" name="menu_order" id="term-menu-order" value="<?php echo esc_attr( (int) get_term_meta( $term->term_id, 'menu_order', true ) ); ?>" step="1" />
            <p class="description"><?php esc_html_e( 'Posizione del banner nella pagina Servizi: i numeri più bassi vengono prima. A parità di numero, ordine alfabetico.', 'farmacia-della-bona' ); ?></p>
        </td>
    </tr>
    <?php
} );

// Save the order. Core has already checked the nonce and the capability before firing these hooks.
$farmaciadellabona_save_service_cat_order = function( $term_id ) {
    if ( isset( $_POST['menu_order'] ) ) {
        update_term_meta( $term_id, 'menu_order', (int) wp_unslash( $_POST['menu_order'] ) );
    }
};
add_action( 'created_service_cat', $farmaciadellabona_save_service_cat_order );
add_action( 'edited_service_cat', $farmaciadellabona_save_service_cat_order );

// Add the order column to the service categories list.
add_filter( 'manage_edit-service_cat_columns', function( $columns ) {
    $columns['menu_order'] = __( 'Ordine', 'farmacia-della-bona' );
    return $columns;
} );

// Fill the column with the order value (0 when never set, like core menu_order).
add_filter( 'manage_service_cat_custom_column', function( $content, $column, $term_id ) {
    if ( 'menu_order' === $column ) {
        $content = esc_html( (int) get_term_meta( $term_id, 'menu_order', true ) );
    }
    return $content;
}, 10, 3 );
