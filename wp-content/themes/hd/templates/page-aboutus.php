<?php
/**
 * The template for displaying about us
 * Template Name: Trang giới thiệu
 * Template Post Type: page
 */

\defined( '\WPINC' ) || die;

use Webhd\Helpers\Str;

get_header();

global $post;

if (have_posts()) the_post();
if (post_password_required()) :
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
endif;

$content = $post->post_content;
if (Str::stripSpace($content)) {
    echo '<section class="section homepage-section"><div class="grid-container">';
    echo '<div class="content clearfix">';

    // post content
    the_content();
    echo '</div></div>';
    echo '</section>';
}

// about-us widget
if (is_active_sidebar('w-about-us-sidebar')) :
    dynamic_sidebar('w-about-us-sidebar');
endif;

get_footer();