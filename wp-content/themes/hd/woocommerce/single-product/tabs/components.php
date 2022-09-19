<?php

\defined( '\WPINC' ) || die;// Exit if accessed directly.

if (!function_exists('get_field')) {
    return;
}

global $product;

$heading = apply_filters( 'woocommerce_product_components_heading', __( 'Components', 'hd' ) );

?>
<div class="product-components-inner">
    <?php if ( $heading ) : ?>
    <h3 class="tab-heading-title"><?php echo esc_html( $heading ); ?></h3>
    <?php endif; ?>
    <?php echo $components = get_field('components', $product->get_id());; ?>
</div>