<?php

use Webhd\Helpers\Cast;

\defined( '\WPINC' ) || die;

if ( have_posts() ) :

?>
<div class="posts-list">
    <?php
    $i = 0;

    // Start the Loop.
    while ( have_posts() ) : the_post();

        if (0 === $i) echo '<div class="first">';
        elseif (1 === $i) echo '<div class="second">';

        get_template_part('template-parts/posts/loop');
        if (0 === $i) echo '</div>';

        ++$i;

        // End the loop.
    endwhile;
    if (1 < $i) echo '</div>';
    wp_reset_postdata();
    ?>
</div>
<?php
	// Previous/next page navigation.
	pagination_links();
else :
	get_template_part( 'template-parts/parts/content-none' );
endif;