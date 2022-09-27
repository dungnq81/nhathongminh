<?php

\defined( '\WPINC' ) || die;

global $post;

if (have_posts()) the_post();
if (post_password_required()) :
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
endif;

$is_sidebar = FALSE;
if (is_active_sidebar('w-news-sidebar') && !is_search()) $is_sidebar = TRUE;

// template-parts/parts/page-title.php
the_page_title_theme();

?>
<section class="section single-post">
    <div class="grid-container width-extra">
        <?php get_template_part('template-parts/parts/sharing'); ?>
        <div class="single-content">
            <div class="col-content cell">
                <h1 class="h4 single-title"><?php echo get_the_title($post); ?></h1>
                <div class="meta">
                    <div class="time" data-glyph=""><?php echo humanize_time(); ?></div>
                    <?php echo post_terms($post->ID); ?>
                </div>
                <?php if ( has_post_thumbnail($post) ) : ?>
                <div class="single-thumbnail">
                    <?php echo get_the_post_thumbnail($post, 'post-thumbnail')?>
                </div>
                <?php endif; ?>
                <?php get_template_part('template-parts/parts/inline-share'); ?>
                <?php echo post_excerpt($post, 'excerpt', true); ?>
                <?php get_template_part('template-parts/parts/upseo'); ?>
                <div class="content clearfix">
                    <?php
                    // post content
                    the_content();

                    the_hashtags();
                    get_template_part('template-parts/parts/inline-share');
                    get_template_part('template-parts/parts/pagination-nav');

                    // If comments are open or we have at least one comment, load up the comment template.
                    the_comment_html();

                    ?>
                </div>
            </div>
            <?php if (TRUE === $is_sidebar) : ?>
            <div class="col-sidebar cell">
                <div class="sidebar--wrap">
                    <?php dynamic_sidebar('w-news-sidebar'); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>