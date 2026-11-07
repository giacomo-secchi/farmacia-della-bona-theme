<?php
require get_stylesheet_directory() . '/inc/config.php';
require get_stylesheet_directory() . '/inc/pattern-categories.php';
require get_stylesheet_directory() . '/inc/register-blocks.php';
require get_stylesheet_directory() . '/inc/blocks-variations/registration.php';
require get_stylesheet_directory() . '/inc/blocks-variations/render.php';
require get_stylesheet_directory() . '/inc/blocks-styles.php';
require get_stylesheet_directory() . '/inc/blocks-binding.php';

if ( is_admin() ) {
    require get_stylesheet_directory() . '/inc/admin.php';
}

if ( class_exists( 'ACF' ) ) {
    require get_stylesheet_directory() . '/inc/acf-functions.php';
}