<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

\defined( 'ABSPATH' ) || exit;
/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $product_tabs ) ) :

?>
<div class="woocommerce-tabs wc-tabs-wrapper tabs-wrapper">
<!--    <div class="grid-container no-padding">-->
    <div class="tabs-heading-wrapper">
        <ul class="tabs-heading" role="tablist">
            <?php foreach ( $product_tabs as $key => $product_tab ) : ?>
            <li class="tab-<?php echo esc_attr( $key ); ?>" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
                <a class="ps2id" data-animation-duration="800" data-smooth-scroll href="#wctab-<?php echo esc_attr( $key ); ?>" title="title-<?php echo esc_attr( $key ); ?>">
                    <?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php echo do_shortcode('[inline-search]'); ?>
    </div>
    <div class="tabs-content wc-tabs-content">
        <div class="product-desc-inner desc-inner">
            <?php
            $_flag = false;
            foreach ( $product_tabs as $key => $product_tab ) :
                if ('reviews' == $key) {

                    // Product Descriptions Sidebar
                    if (is_active_sidebar('w-product-desc-sidebar')) :
                        echo '<div class="product-desc-after">';
                        dynamic_sidebar('w-product-desc-sidebar');
                        echo '</div>';
                    endif;

                    $_flag = true;
                }
            ?>
            <div class="woocommerce-tabs-panel woocommerce-tabs-panel--<?php echo esc_attr( $key ); ?>" id="wctab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
                <?php
                if ( isset( $product_tab['callback'] ) ) :
                    call_user_func( $product_tab['callback'], $key, $product_tab );
                endif;
                ?>
            </div>
            <?php endforeach;
            if (!$_flag) {

                // Product Descriptions Sidebar
                if (is_active_sidebar('w-product-desc-sidebar')) :
                    echo '<div class="product-desc-after">';
                    dynamic_sidebar('w-product-desc-sidebar');
                    echo '</div>';
                endif;
            }
            ?>
        </div>
    </div>
    <?php do_action( 'woocommerce_product_after_tabs' ); ?>
<!--    </div>-->
</div>
<?php endif;