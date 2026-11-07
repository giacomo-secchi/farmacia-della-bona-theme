<?php

add_action( 'init', function() {
    // The source name (including the "delle" typo) is kept as-is because saved content and patterns reference it.
    register_block_bindings_source( 'farmacia-delle-bona/service-meta', [
        'label'              => __( 'Dettagli Servizio', 'farmacia-della-bona' ),
        'get_value_callback' => function( $source_args, $block_instance ) {
            $post_id = $block_instance->context['postId'] ?? null;

            if ( ! $post_id ) {
                return '';
            }

            // Read raw meta so the binding does not fatal when ACF is inactive (true_false stores '1' or '0').
            if ( get_post_meta( $post_id, 'service_type', true ) ) {
                return __( 'Servizio a pagamento', 'farmacia-della-bona' );
            }

            return __( 'Servizio gratuito', 'farmacia-della-bona' );
        },
        'uses_context'       => [ 'postId' ],
    ] );

    register_block_bindings_source( 'farmacia-della-bona/event-date', [
        'label'              => __( 'Logica Data Eventi', 'farmacia-della-bona' ),
        'get_value_callback' => function( $source_args, $block_instance ) {
            $post_id = $block_instance->context['postId'] ?? null;

            if ( ! $post_id ) {
                return '';
            }

            $event_date_raw = get_post_meta( $post_id, 'event_date', true );

            if ( ! $event_date_raw ) {
                return '';
            }

            $timezone = wp_timezone();

            // ACF date pickers store dates as Ymd; fall back to a generic parse for legacy values.
            $event_date = DateTimeImmutable::createFromFormat( '!Ymd', $event_date_raw, $timezone );
            if ( ! $event_date ) {
                try {
                    $event_date = new DateTimeImmutable( $event_date_raw, $timezone );
                } catch ( Exception $e ) {
                    return $event_date_raw;
                }
            }

            $event_date = $event_date->setTime( 0, 0, 0 );
            $today      = new DateTimeImmutable( 'today', $timezone );

            if ( $event_date < $today ) {
                return __( 'Evento già passato', 'farmacia-della-bona' );
            }

            return sprintf(
                /* translators: %s: formatted event date */
                __( 'Data evento: %s', 'farmacia-della-bona' ),
                wp_date( 'd F Y', $event_date->getTimestamp(), $timezone )
            );
        },
        'uses_context'       => [ 'postId' ],
    ] );
} );
