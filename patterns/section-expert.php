<?php
/**
 * Title: Expert Section
 * Slug: farmacia-della-bona/section-expert
 * Categories: farmacia-della-bona-patterns
 */
?>

<!-- wp:group {"className":"is-style-section-1","align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-1">

<!-- wp:group {"className":"is-style-section-header"} -->
<div class="wp-block-group is-style-section-header"><!-- wp:term-description /-->

<!-- wp:term-name {"level":2} /--></div>
<!-- /wp:group -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%"><!-- wp:query {"query":{"postType":"team_member","perPage":1,"inherit":false},"namespace":"staff-highlight-query"} -->
<div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"default","columnCount":3}} -->
<!-- wp:group {"className":"is-style-card-rounded","layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-card-rounded">
        <!-- wp:post-featured-image {"width":"140px","height":"140px" } /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"0px"}},"layout":{"type":"flex"}} -->
<div class="wp-block-group" >
<!-- wp:post-title {"level":3} /-->
    
<!-- wp:paragraph -->
<p>Esperto di&nbsp;</p>
<!-- /wp:paragraph -->
<!-- wp:term-name /--></div>
<!-- /wp:group -->


<!-- wp:post-content /--></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:column -->

<!-- wp:column {"width":""} -->
<div class="wp-block-column"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:query {"queryId":8,"query":{"postType":"event","perPage":2,"inherit":false},"namespace":"event-highlight-query"} -->
<div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"default","columnCount":3}} -->
<!-- wp:group {"className":"is-style-card","layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-card"><!-- wp:post-featured-image {"width":"80px","height":"80px"} /-->

<!-- wp:post-title /-->

        <!-- wp:paragraph {
            "metadata":{
                "bindings":{
                    "content":{
                        "source":"farmacia-della-bona/event-date"
                    }
                }
            }
        } -->
        <p></p>
        <!-- /wp:paragraph -->

<!-- wp:post-content /--></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query -->

<!-- wp:query {"query":{"postType":"post","perPage":2,"sticky":"only","inherit":false},"namespace":"post-highlight-query"} -->
<div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"grid","columnCount":2}} -->
<!-- wp:group {"className":"is-style-card-full-bleed","layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-card-full-bleed"><!-- wp:post-featured-image {"aspectRatio":"auto","width":"100%","height":"180px","scale":"cover"} /-->

<!-- wp:post-title /-->

<!-- wp:post-author-name {"isLink":true} /-->

<!-- wp:post-excerpt {"moreText":"Leggi l'articolo","excerptLength":33} /--></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->



 

</div>
<!-- /wp:group -->
