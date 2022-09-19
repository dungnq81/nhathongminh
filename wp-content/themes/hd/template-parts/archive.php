<?php

\defined( '\WPINC' ) || die;

use Webhd\Helpers\Str;

$post_page_id = get_option( 'page_for_posts' );
$term = get_queried_object();

if ( $post_page_id && $post_page_id == $term->ID ) { // is posts page
	$desc = post_excerpt( $term, null );
} else {
	$desc = term_excerpt( $term, null );
}

// template-parts/parts/page-title.php
the_page_title_theme();

$archive_title = $term->name ?? '';
if (is_search()) {
    $archive_title = sprintf( __( 'Search Results for: %s', 'hd' ), get_search_query() );
}

/** */
$display_type = 'items';
if (function_exists('get_field') && $term) {
    $display_type = get_field('display-type', $term);
}

?>
<section class="section archives archive-posts">
    <?php if ( Str::stripSpace( $desc ) ) : ?>
    <div class="grid-container">
        <div class="archive-desc heading-desc" data-glyph=""><?= $desc ?></div>
    </div>
    <?php endif; ?>
    <?php
    $feature_slide = true;
    if (function_exists('get_field') && $term) :
        $feature_slide = get_field('feature-slide', $term);
        if ($feature_slide) :
    ?>
    <div class="feature-posts">
        <?php get_template_part( 'template-parts/posts/feature-slides' ); ?>
    </div>
    <?php endif; endif; ?>
    <div class="grid-container<?php if ( 'cong-trinh-tieu-bieu' != $display_type) echo ' width-extra'?>">
        <?php
        if ( 'cong-trinh-tieu-bieu' == $display_type ) :
            get_template_part( 'template-parts/posts/grid-cong-trinh' );
        elseif ( 'giai-phap' == $display_type ) :
            get_template_part( 'template-parts/posts/grid-giai-phap' );
        else :
            get_template_part( 'template-parts/posts/grid' );
        endif;
        ?>
    </div>
</section>