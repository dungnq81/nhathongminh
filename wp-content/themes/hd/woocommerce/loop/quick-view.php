<?php
/**
 * Loop Quick View
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/quick-view.php.
 */

defined('ABSPATH') || exit;

global $product;

echo apply_filters(
    'woocommerce_loop_quick_view_link', // WPCS: XSS ok.
    sprintf(
        '<a href="%s" class="%s quick-view" title="%s" data-glyph="" %s><span>%s</span></a>',
        home_url( '?quick-view=' . $product->get_id(), 'relative' ),
        esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
        __( 'Quick View', 'hd' ),
        isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
        __( 'Quick View', 'hd' )
    ),
    $product,
    $args
);