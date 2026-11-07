<?php

/**
 * Returns the block variations configuration, built once per request.
 *
 * The render filters below run for every block on the page, so the translated
 * configuration is cached instead of being rebuilt on each call.
 *
 * @return array
 */
function farmaciadellabona_get_block_variations_config() {
    static $config = null;

    if ( null === $config ) {
        $config = apply_filters( 'farmaciadellabona_block_variations_config', [] );
    }

    return $config;
}

/**
 * This function intercepts the block rendering process to apply custom logic based on the block's 'namespace' attribute.
 * It handles 'core/query' blocks by registering a one-time filter to modify WP_Query arguments for dynamic 
 * filtering, while for all other blocks, it executes a callback immediately to allow on-the-fly 
 * modifications of the block's HTML output or behavior.
 * It uses a centralized configuration filtered via 'farmaciadellabona_block_variations_config'.
 *
 * @param string|null $pre_render   The block HTML output. Default null.
 * @param array       $parsed_block The block being rendered, including attributes and content.
 * @return string|null              The modified block HTML or null to continue standard rendering.
 */
add_filter( 'pre_render_block', function ( $pre_render, $parsed_block ) {
    // Get the block name. If it's empty or null, return the original pre-render value.
    $block_name = $parsed_block['blockName'] ?? null;
    if ( ! $block_name ) {
        return $pre_render;
    }

    // Retrieve the centralized configuration for block variations.
    $config = farmaciadellabona_get_block_variations_config();
    
    // Early exit if the current block type is not defined in our configuration.
    if ( ! isset( $config[ $block_name ] ) ) {
        return $pre_render;
    }
 
    // Iterate through the variations defined for this specific block type.
    foreach ( $config[ $block_name ] as $variation ) {

        // Check if the block instance's namespace matches the variation namespace defined in config.
        $current_namespace = $parsed_block['attrs']['namespace'] ?? '';
        $target_namespace  = $variation['attributes']['namespace'] ?? '';

        if ( $current_namespace !== '' && $current_namespace === $target_namespace ) {

            
            // Verify if the callback function is provided and is a valid callable.
            $callback = $variation['attributes']['callback'] ?? null;
            if ( ! is_callable( $callback ) ) {
                continue;
            };

            /**
             * Special handling for 'core/query' blocks.
             * These blocks fetch data later, so we hook into 'query_loop_block_query_vars'.
             */
            if ( 'core/query' === $block_name ) {

                $query_callback = function( $query, $block ) use ( &$query_callback, $callback ) {
                    /**
                     * IMPORTANT: Remove the filter immediately after execution.
                     * This prevents the logic from affecting subsequent Query Loop blocks on the same page.
                     */
                    remove_filter( 'query_loop_block_query_vars', $query_callback, 10 );
                   
                    // Execute the custom logic defined in the variation config.
                    return call_user_func( $callback, $query, $block );
                };

                add_filter( 'query_loop_block_query_vars', $query_callback, 10, 2 );
            } else {
                /**
                 * For non-query blocks, execute the callback immediately.
                 * This can be used to modify the block's HTML output ($pre_render).
                 */

                return call_user_func( $callback, $pre_render, $parsed_block );
            }

            // Stop the loop once a matching namespace is found and processed.
            break;
        }
    }

    return $pre_render;
}, 10, 2 );



/**
 * Intercept block rendering to dynamically inject ACF data.
 * This function checks if a block has a registered 'namespace' and, if so,
 * cross-references it with the centralized configuration to swap 
 * placeholders with real data from taxonomies or options.
 */
add_filter( 'render_block', function( $block_content, $block ) {
    $namespace = $block['attrs']['namespace'] ?? '';

    // Skip if no namespace is defined.
    if ( empty( $namespace ) ) {
        return $block_content;
    }

    $config = farmaciadellabona_get_block_variations_config();
    $block_name = $block['blockName'];

    // Skip if the block type is not in our configuration.
    if ( ! isset( $config[ $block_name ] ) ) {
        return $block_content;
    }

    // Find the specific variation config that matches the current namespace.
    $target_variation = null;
    foreach ( $config[ $block_name ] as $variation ) {
        if ( ( $variation['attributes']['namespace'] ?? '' ) === $namespace ) {
            $target_variation = $variation;
            break;
        }
    }

    // Skip if no matching variation or no ACF fields are defined.
    $fields_to_process = $target_variation['attributes']['acf_fields'] ?? [];
    if ( empty( $fields_to_process ) ) {
        return $block_content;
    }

    // --- Processing Phase ---
    $term = get_queried_object();
    $replacements = [];
    $empty_placeholders = [];

    foreach ( $fields_to_process as $field_slug ) {
        // retrieve field object.
        $field_object = get_field_object( $field_slug, $term ) ?: get_field_object( $field_slug, 'option' );

        if ( ! $field_object || empty( $field_object['value'] ) ) {
            $empty_placeholders[] = $field_slug;
            continue;
        }

        $field_type  = $field_object['type'];
        $field_value = $field_object['value'];


        switch ( $field_type ) {
            case 'repeater':
                $items = [];
    
                foreach ( (array) $field_value as $row ) {
    
                    if ( ! is_array( $row ) ) {
                        continue;
                    }
            
                    $first_val = reset( $row );

                    // Skip empty value.
                    if ( empty( $first_val ) ) {
                        continue;
                    }

                    $items[] = esc_html( (string) $first_val );
                }

                if ( ! empty( $items ) ) {
                    // Tag-swap trick to multiply <li> while keeping the original block's attributes.
                    $replacements[ $field_slug ] = implode( '</li><li>', $items );
                } else {

                    $replacements[ $field_slug ] = ''; 
                    $empty_placeholders[] = $field_slug;
                    
                }

                break;
                
            case 'image':
                $replacements[ $field_slug ] = is_array( $field_value ) ? esc_url( $field_value['url'] ) : esc_url( $field_value );
                break;

            case 'link':
                $replacements[ $field_slug ] = esc_url( $field_value['url'] ?? '' );
                break;
            
            case 'post_object':
            case 'page_link':
                // ACF returns either the post ID or the post object; keep the numeric ID.
                $replacements[ $field_slug ] = is_object( $field_value ) ? $field_value->ID : (string) $field_value;
                break;

            default:
                // Manage simple texts, select, radio, ecc.
                $replacements[ $field_slug ] = esc_html( (string) $field_value );
                
                // Convert *_color fields into native WordPress CSS classes.
                if ( str_contains( $field_slug, '_color_palette_' ) && ! empty( $field_value ) ) {
                    $replacements[ $field_slug ] = sprintf( 
                        'has-%s-background-color has-background', 
                        esc_attr( $field_value ) 
                    );
                }

                break;
        }
    }

    // Perform string replacement on the entire block HTML.
    if ( ! empty( $replacements ) ) {
        $block_content = str_replace( array_keys( $replacements ), array_values( $replacements ), $block_content );
    }

    // Remove any remaining placeholders from the HTML if the corresponding ACF fields are empty.
    if ( ! empty( $empty_placeholders ) ) {
        $block_content = str_replace( $empty_placeholders, '', $block_content );
    }

    return $block_content;
}, 10, 2 );
