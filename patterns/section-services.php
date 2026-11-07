<?php
/**
 * Title: Services Banner
 * Slug: farmacia-della-bona/section-services
 * Categories: farmacia-della-bona-patterns
 */
?>



<!-- wp:group {"className":"is-style-section-1","align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-1">

<!-- wp:paragraph -->
<p>Servizi Disponibili</p>
<!-- /wp:paragraph -->
 <!-- wp:heading -->
<h2 class="wp-block-heading">I Nostri Servizi</h2>
<!-- /wp:heading -->



<!-- wp:query {"query":{"postType":"service","perPage":8,"inherit":false},"namespace":"service-category-query","className":"alignwide"} -->
<div class="wp-block-query alignwide"><!-- wp:post-template {"layout":{"type":"grid","columnCount":4}} -->
    <!-- wp:group {"className":"is-style-card-rounded","layout":{"type":"constrained"}} -->
    <div class="wp-block-group is-style-card-rounded">
        
        <!-- wp:post-featured-image {"width":"80px","height":"80px" } /-->

        <!-- wp:post-title /-->

        <!-- wp:paragraph {
            "metadata":{
                "bindings":{
                    "content":{
                        "source":"farmacia-delle-bona/service-meta"
                    }
                }
            }
        } -->
        <p></p>
        <!-- /wp:paragraph -->

        <!-- wp:post-excerpt /--></div>
    <!-- /wp:group -->
    <!-- /wp:post-template -->
</div>
<!-- /wp:query -->



<!-- wp:buttons {"align":"wide","layout":{"type":"flex","justifyContent":"right"}} -->
<div class="wp-block-buttons alignwide"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>"><?php esc_html_e( 'Vedi Tutti i Servizi →', 'farmacia-della-bona' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->


</div>
<!-- /wp:group -->