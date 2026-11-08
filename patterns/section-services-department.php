<?php
/**
 * Title: Services Banner
 * Slug: farmacia-della-bona/section-services-department
 * Categories: farmacia-della-bona-patterns
 */
?>
 
 
<!-- wp:cover {"url":"section_department_service_image","dimRatio":20,"isDark":false,"namespace":"service-section","className":"is-style-section-2","align":"full"} -->
<div class="wp-block-cover alignfull is-light is-style-section-2"><img class="wp-block-cover__image-background" alt="" src="section_department_service_image" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-20 has-background-dim"></span><div class="wp-block-cover__inner-container">
<!-- wp:group {"className":"is-style-section-header"} -->
<div class="wp-block-group is-style-section-header"><!-- wp:paragraph -->
<p>Servizi Disponibili</p>
<!-- /wp:paragraph -->

<!-- wp:term-name {"textAlign":"center","level":2} /--></div>
<!-- /wp:group -->


<!-- wp:query {"query":{"postType":"service","perPage":4,"inherit":false},"namespace":"service-category-query","className":"alignwide"} -->
<div class="wp-block-query alignwide"><!-- wp:post-template {"layout":{"type":"grid","columnCount":4}} -->
<!-- wp:group {"className":"is-style-card-rounded","layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-card-rounded"><!-- wp:post-featured-image {"width":"80px","height":"80px"} /-->

<!-- wp:post-title /-->

<!-- wp:post-content /--></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query -->





<!-- wp:buttons {"align":"wide","layout":{"type":"flex","justifyContent":"right"}} -->
<div class="wp-block-buttons alignwide"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>"><?php esc_html_e( 'Vedi Tutti i Servizi →', 'farmacia-della-bona' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->


</div></div>
<!-- /wp:cover -->















 
 


 