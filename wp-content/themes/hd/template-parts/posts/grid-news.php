<?php

\defined( '\WPINC' ) || die;

$is_sidebar = FALSE;
if (is_active_sidebar('w-news-sidebar') && !is_search()) $is_sidebar = TRUE;

?>
<div class="grid-news-outer grid-padding-x grid-x">
    <?php if (TRUE === $is_sidebar) : ?>
    <div class="col-sidebar cell">
        <div class="sidebar--wrap">
            <?php dynamic_sidebar('w-news-sidebar'); ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="col-content cell">
        <?php if ( have_posts() ) : ?>
        <div class="section grid-posts grid-x">
            <?php

            // Start the Loop.
            while ( have_posts() ) : the_post();

                echo "<div class=\"cell\">";
                get_template_part( 'template-parts/posts/loop-news' );
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
        ?>
    </div>
</div>