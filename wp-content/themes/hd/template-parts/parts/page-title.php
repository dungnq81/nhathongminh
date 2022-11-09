<?php

\defined( '\WPINC' ) || die;

$_class = '';
$breadcrumb_bg = get_theme_mod_ssl('breadcrumb_bg_setting');
if ($breadcrumb_bg) {
	$_class .= ' has-background';
}

//...
if (isset($args['css_class']) ) {
    $_class .= ' ' . trim($args['css_class']);
}

$title = '';
if (function_exists('is_shop') && @is_shop()) {
    $title = get_the_title( get_option( 'woocommerce_shop_page_id' ) );
} else {
    $object = get_queried_object();
    $title = $object->name ?? '';
    if (!$title) {
        $title = $object->post_title ?? '';
    }
}

if (is_search()) {
    $title = sprintf( __( 'Search Results for: %s', 'hd' ), get_search_query() );
}

?>
<section class="section section-title<?= $_class ?>" tabindex="-1">
	<div class="title-bg parallax-bg" data-parallax="{&quot;y&quot;: 50}"></div>
    <div class="grid-container title-inner">
        <?php
        if (@is_single() ) {
            //$h_tag = 'h2';
            echo '<h2 class="title h3">' . $title . '</h2>';
        } else {
            //$h_tag = 'h1';
            echo '<h1 class="title h3">' . $title . '</h1>';
        }
        ?>
    </div>
    <div class="breadcrumbs-container">
        <div class="grid-container">
        <?php
        if (function_exists('the_breadcrumbs')) :
            the_breadcrumbs();
        elseif (function_exists('woocommerce_breadcrumb')) :
            woocommerce_breadcrumb();
        elseif (function_exists('rank_math_the_breadcrumbs')) :
            rank_math_the_breadcrumbs();
        endif;
        ?>
        </div>
    </div>
</section>
