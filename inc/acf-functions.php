<?php
/**
 * Automatically populates ACF select fields based on a naming convention.
 *
 * Examples:
 *   'hero_overlay_color_palette'         -> theme + default + custom palettes
 *   'hero_overlay_color_palette_all'     -> theme + default + custom palettes
 *   'hero_overlay_color_palette_theme'   -> ['color', 'palette', 'theme']
 *   'hero_overlay_color_palette_default' -> ['color', 'palette', 'default']
 */
add_filter( 'acf/load_field', function( $field ) {

    // Only handle select fields that follow the '_color_palette' convention.
    if ( $field['type'] !== 'select' || ! str_contains( $field['name'], '_color_palette' ) ) {
        return $field;
    }

    // Extract the palette origin after the convention marker (e.g. 'all', 'theme', 'default').
    $parts  = explode( '_color_palette', $field['name'], 2 );
    $target = ltrim( $parts[1] ?? '', '_' );

    $valid_origins = [ 'theme', 'default', 'custom' ];

    // With no target, 'all' or an unknown origin, include every palette (including 'custom' for plugins).
    $sources = in_array( $target, $valid_origins, true ) ? [ $target ] : $valid_origins;

    $all_colors = [];
    foreach ( $sources as $source ) {
        $palette = wp_get_global_settings( [ 'color', 'palette', $source ] );

        if ( empty( $palette ) || ! is_array( $palette ) ) {
            continue;
        }

        foreach ( $palette as $color ) {
            // Skip colors without a slug, which is the identifier we store.
            if ( ! isset( $color['slug'] ) ) {
                continue;
            }

            // First match wins, so theme.json colors (first in $sources) are not overridden by core defaults.
            if ( ! isset( $all_colors[ $color['slug'] ] ) ) {
                $all_colors[ $color['slug'] ] = $color;
            }
        }
    }

    if ( empty( $all_colors ) ) {
        return $field;
    }

    $field['choices'] = [ '' => __( 'None (Transparent)', 'farmacia-della-bona' ) ];

    foreach ( $all_colors as $color ) {
        // Use the slug as value and the name (or the slug as fallback) as label.
        $label = isset( $color['name'] ) ? $color['name'] : ucfirst( $color['slug'] );
        $field['choices'][ $color['slug'] ] = $label;
    }

    return $field;
} );
