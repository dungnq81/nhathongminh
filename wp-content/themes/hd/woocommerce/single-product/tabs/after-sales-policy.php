<?php

\defined( '\WPINC' ) || die;// Exit if accessed directly.

if (!function_exists('get_field')) {
    return;
}

global $product;

$heading = apply_filters( 'woocommerce_product_after_sales_heading', __('Thiết kế', 'hd') );

?>
<div class="product-after_sales-inner">
    <?php if ( $heading ) : ?>
        <h3 class="tab-heading-title"><?php echo esc_html( $heading ); ?></h3>
    <?php endif; ?>
    <?php echo $after_sales = get_field('after_sales', $product->get_id());; ?>
</div>