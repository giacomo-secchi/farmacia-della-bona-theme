<?php

add_filter( 'farmaciadellabona_block_variations_config', function ( $config ) {

    $config = is_array( $config ) ? $config : [];

    $theme_variations = [
        // Query Loop variations.
        'core/query' => [
            [
                'name'      => 'staff-highlight-query',
                'title'     => __( 'Membro Staff in Evidenza', 'farmacia-della-bona' ),
                'description' => __( 'Mostra il membro selezionato per questa categoria', 'farmacia-della-bona' ),
                'isActive'   => [ 'namespace' ],
                'attributes' => [
                    'namespace' => 'staff-highlight-query',
                    'query'     => [
                        'postType' => 'team_member',
                        'perPage'  => 1,
                        'inherit'  => false,
                    ],
                    'callback'  => 'farmaciadellabona_filter_staff_query'
                ],
                'scope'      => [ 'inserter' ],
            ],
            [
                'name'       => 'event-highlight-query',
                'title'      => __( 'Evento in Evidenza', 'farmacia-della-bona' ),
                'description' => __( 'Mostra l\'evento selezionato per questa categoria', 'farmacia-della-bona' ),
                'isActive'   => [ 'namespace' ],
                'attributes' => [
                    'namespace' => 'event-highlight-query',
                    'query'     => [
                        'postType' => 'event',
                        'perPage'  => 2,
                        'inherit'  => false,
                    ],
                    'callback'  => 'farmaciadellabona_filter_events_query'
                ],
                'scope'      => [ 'inserter' ], // Makes it visible in the block inserter.
            ],
            [
                'name'       => 'post-highlight-query',
                'title'      => __( 'Articoli in Evidenza', 'farmacia-della-bona' ),
                'description' => __( 'Mostra gli articoli selezionati per questa categoria', 'farmacia-della-bona' ),
                'isActive'   => [ 'namespace' ],
                'attributes' => [
                    'namespace' => 'post-highlight-query',
                    'query'     => [
                        'perPage'  => 2,
                        'inherit'  => false,
                    ],
                    'callback'  => 'farmaciadellabona_filter_posts_query'
                ],
                'scope'      => [ 'inserter' ]
            ],
            [
                'name'       => 'service-category-query',
                'title'      => __( 'Servizi della Categoria Corrente', 'farmacia-della-bona' ),
                'description' => __( 'Filtra i servizi in base alla categoria visualizzata', 'farmacia-della-bona' ),
                'isActive'   => [ 'namespace' ],
                'attributes' => [
                    'namespace' => 'service-category-query',
                    'query'     => [
                        'postType' => 'service',
                        'perPage'  => 6,
                        'inherit'  => false,
                    ],
                    'callback'  => 'farmaciadellabona_filter_services_by_current_cat'
                ],
                'scope'      => [ 'inserter' ],
            ],
        ],
        'core/cover' => [
            [
                'name'      => 'service-section',
                'title'     => __( 'Sezione Servizi del Reparto', 'farmacia-della-bona' ),
                'description' => __( 'Visualizza servizi disponibili per il Reparto', 'farmacia-della-bona' ),
                'isActive'   => [ 'namespace' ],
                'attributes' => [
                    'namespace'     => 'service-section',
                    'acf_fields'    => [
                        'section_department_service_image'
                    ],
                ],
            ]
        ]
    ];

    // Append to variations registered by other callbacks instead of replacing them.
    foreach ( $theme_variations as $block_name => $variations ) {
        $config[ $block_name ] = array_merge( $config[ $block_name ] ?? [], $variations );
    }

    return $config;
} );

/**
 * Returns the current department term ID, or 0 when the current view is not a department archive.
 */
function farmaciadellabona_get_current_department_id() {
    return is_tax( 'department' ) ? (int) get_queried_object_id() : 0;
}

/**
 * Restricts a query to the given department term.
 */
function farmaciadellabona_add_department_tax_query( $query, $department_id ) {
    $query['tax_query'] = [[
        'taxonomy' => 'department',
        'field'    => 'term_id',
        'terms'    => $department_id,
    ]];

    return $query;
}

// Staff query: shows the manager selected on the current department.
function farmaciadellabona_filter_staff_query( $query, $block ) {

    $department_id = farmaciadellabona_get_current_department_id();

    if ( ! $department_id ) {
        return $query;
    }

    $query['post_type']      = 'team_member';
    $query['posts_per_page'] = 1;

    // ACF stores taxonomy fields as term meta; reading it directly avoids a fatal error when ACF is inactive.
    $staff_id = (int) get_term_meta( $department_id, 'department_manager', true );

    // Without a selected manager, show nothing instead of an unrelated team member.
    $query['post__in'] = $staff_id ? [ $staff_id ] : [ 0 ];

    return $query;
}

// Events query: upcoming events, nearest first, limited to the current department when on its archive.
function farmaciadellabona_filter_events_query( $query, $block ) {

    $query['post_type'] = 'event';
    $query['meta_query'] = [[
        'key'     => 'event_date',
        'value'   => current_time( 'Ymd' ),
        'compare' => '>=',
        'type'    => 'DATE',
    ]];

    // Order by the event date so the nearest event appears first.
    $query['meta_key'] = 'event_date';
    $query['orderby']  = 'meta_value_num';
    $query['order']    = 'ASC';

    $department_id = farmaciadellabona_get_current_department_id();
    if ( $department_id ) {
        $query = farmaciadellabona_add_department_tax_query( $query, $department_id );
    }

    return $query;
}

// Posts query: limited to the current department when on its archive.
function farmaciadellabona_filter_posts_query( $query, $block ) {

    $department_id = farmaciadellabona_get_current_department_id();
    if ( $department_id ) {
        $query = farmaciadellabona_add_department_tax_query( $query, $department_id );
    }

    return $query;
}

// Services query: ordered manually, limited to the current department when on its archive.
function farmaciadellabona_filter_services_by_current_cat( $query, $block ) {

    $query['post_type'] = 'service';
    $query['orderby']   = 'menu_order';
    $query['order']     = 'ASC';

    $department_id = farmaciadellabona_get_current_department_id();
    if ( $department_id ) {
        $query = farmaciadellabona_add_department_tax_query( $query, $department_id );
    }

    return $query;
}
