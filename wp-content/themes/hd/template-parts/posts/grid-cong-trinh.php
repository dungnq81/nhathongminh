<?php

use Webhd\Helpers\Cast;

\defined( '\WPINC' ) || die;

if ( have_posts() ) :

?>
<div class="section grid-posts grid-cong-trinh grid-x">
    <?php

    // Start the Loop.
    while ( have_posts() ) : the_post();

        echo "<div class=\"cell\">";
        get_template_part('template-parts/posts/loop-cong-trinh', null, [ 'hide_meta' => true, 'hide_view_detail' => true ]);
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