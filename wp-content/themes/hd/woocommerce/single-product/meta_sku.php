<?php

defined( 'ABSPATH' ) || exit;

global $product;

if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) :
?>
<div class="product_meta product_sku">
    <span class="sku_wrapper"><span class="sku-txt"><?php esc_html_e( 'SKU:', 'hd' ); ?></span><span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woocommerce' ); ?></span></span>
</div>
<?php endif;