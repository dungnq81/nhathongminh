<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

\defined( 'ABSPATH' ) || exit;

global $product;

echo '<div class="woo-notices"><div class="grid-container width-extra">';
    /**
     * Hook: woocommerce_before_single_product.
     *
     * @see woocommerce_output_all_notices - 10
     */
    do_action( 'woocommerce_before_single_product' );
echo '</div></div>';

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
    <div class="product-details">
        <div class="grid-container width-extra">
            <div class="product-detail-inner">
                <?php
                /**
                 * Hook: woocommerce_before_single_product_summary.
                 *
                 * @see woocommerce_show_product_sale_flash - 10
                 * @see woocommerce_show_product_images - 20
                 */
                do_action( 'woocommerce_before_single_product_summary' );
                ?>
                <div class="summary entry-summary">
                    <?php
                    /**
                     * Hook: woocommerce_single_product_summary.
                     *
                     * @see woocommerce_template_single_title - 5
                     * @see woocommerce_template_single_meta_sku - 9
                     * @see woocommerce_template_single_rating - 10
                     * @see woocommerce_template_single_price - 10
                     * @see woocommerce_template_single_excerpt - 20
                     * @see woocommerce_template_single_add_to_cart - 30
                     * @see woocommerce_template_single_meta - 40 // remove sku
                     * @see woocommerce_template_single_sharing - 50
                     * @see WC_Structured_Data::generate_product_data() - 60
                     */
                    do_action( 'woocommerce_single_product_summary' );
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="product_summary">
        <div class="grid-container width-extra">
            <?php
            /**
             * Hook: woocommerce_after_single_product_summary.
             *
             * @see woocommerce_output_product_data_tabs - 10
             * @see woocommerce_upsell_display - 15
             * @see woocommerce_output_related_products - 20
             * @see woocommerce_output_recently_viewed_products - 25
             */
            do_action( 'woocommerce_after_single_product_summary' );
            ?>
        </div>
    </div>
</div>
<?php
do_action( 'woocommerce_after_single_product' );