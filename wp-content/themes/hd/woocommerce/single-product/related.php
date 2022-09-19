<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.9.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var object $related_products
 */
if ( $related_products ) :

?>
<section class="section carousels-section related products related-products section-padding">
    <?php
    $heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'woocommerce' ) );
    if ( $heading ) :
    ?>
        <h2 class="heading-title h3"><span><?php echo esc_html( $heading ); ?></span></h2>
    <?php endif;

    $_data = [];

    $_data['desktop'] = 5;
    $_data['tablet'] = 4;
    $_data['mobile'] = 2;
    $_data['navigation'] = true;
    $_data['pagination'] = "dynamic";
    $_data['autoplay'] = true;
    //$_data['loop'] = true;
    $_data['smallgap'] = 20;

    $_data = json_encode($_data, JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);

    ?>
    <div class="swiper-section grid-products">
        <div class="w-swiper swiper">
            <div class="swiper-wrapper" data-options='<?= $_data;?>'>
                <?php
                foreach ( $related_products as $related_product ) :

                    echo '<div class="swiper-slide">';
                    $post_object = get_post( $related_product->get_id() );
                    setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
                    wc_get_template_part( 'content', 'product' );
                    echo '</div>';

                endforeach;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>
</section>
<?php endif;