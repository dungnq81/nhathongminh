<?php

\defined( '\WPINC' ) || die;

if ( have_posts() ) :

?>
<div class="section grid-posts grid-giai-phap grid-x">
    <?php

    // Start the Loop.
    while ( have_posts() ) : the_post();

        echo "<div class=\"cell\">";
        get_template_part( 'template-parts/posts/loop-giai-phap', null, [ 'hide_meta' => true ] );
        echo "</div>";

        // End the loop.
    endwhile;
    wp_reset_postdata();
    ?>
</div>
<?php
	// Previous/next page navigation.
	pagination_links();
else :
	get_template_part( 'template-parts/parts/content-none' );
endif;