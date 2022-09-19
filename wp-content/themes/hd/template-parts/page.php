<?php

\defined( '\WPINC' ) || die;

global $post;

if (have_posts()) the_post();
if (post_password_required()) :
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
endif;

// template-parts/parts/page-title.php
the_page_title_theme();

?>
<section class="section single-post single-page">
    <div class="grid-container width-extra">
        <div class="col-content">
            <?php echo post_excerpt($post, 'excerpt', true); ?>
            <?php get_template_part('template-parts/parts/upseo'); ?>
            <div class="content clearfix">
                <?php
                // post content
                the_content();

                // If comments are open or we have at least one comment, load up the comment template.
                the_comment_html();
                ?>
            </div>
        </div>
    </div>
</section>