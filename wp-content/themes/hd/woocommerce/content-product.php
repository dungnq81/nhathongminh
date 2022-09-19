<?php

/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

use Webhd\Helpers\Str;

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || false === wc_get_loop_product_visibility($product->get_id()) || !$product->is_visible())
    return;

?>
<article <?php wc_product_class('item', $product); ?>>
    <?php
    $link = apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product);
    $thumbnail = woocommerce_get_product_thumbnail();

    $ratio = get_theme_mod_ssl('product_menu_setting');
    $ratio_class = $ratio;
    if ($ratio == 'default' || empty($ratio))
        $ratio_class = '1-1';

    ?>
    <a rel="follow" class="d-block" href="<?= get_permalink() ?>" aria-label="<?php the_title_attribute() ?>" tabindex="0">
        <div class="cover">
            <span class="after-overlay res auto scale ar-<?= $ratio_class ?>"><?php echo get_the_post_thumbnail(null, 'medium'); ?></span>
            <?php

            // label
            if (function_exists('get_fields')) :
                $ACF = get_fields($product->get_id());
                $label_txt = $ACF['label_txt'] ?? '';
                $label_color = $ACF['label_color'] ?? '';
                $label_bgcolor = $ACF['label_bgcolor'] ?? '';
                if ($label_txt) :
                    $_css = '';
                    if ($label_bgcolor) $_css .= 'background-color:' . $label_bgcolor . ';';
                    if ($label_color) $_css .= 'color:' . $label_color . ';';
                    if ($_css) $_css = ' style="' . $_css . '"';
                    echo '<div class="hot-label hotlabel"><span' . $_css . '>' . $label_txt . '</span></div>';
                endif;
            endif;
            ?>
        </div>
    </a>
    <div class="cover-content">
        <h6><a rel="follow" title="<?php the_title_attribute() ?>" href="<?= $link ?>"><?php echo get_the_title(); ?></a></h6>
        <?php

        // loop/rating.php
        woocommerce_template_loop_rating();

        if ($product->get_price()) :

            // loop/price.php
            woocommerce_template_loop_price();

            // loop/sale-flash.php
            woocommerce_show_product_loop_sale_flash();
        else :
            $_hotline = get_theme_mod_ssl( 'hotline_setting' );
            $_tel     = Str::stripSpace( $_hotline );
            $_href = ($_tel) ? 'tel:' . $_tel : $link;
        ?>
        <div class="noprice"><a class="contact-for-price" title="<?php echo __('Contact', 'hd'); ?>" href="<?= $_href ?>" data-id="<?php echo $post->ID; ?>" data-title="<?php the_title_attribute() ?>" data-url="<?php echo $link; ?>"><?php echo __('Contact', 'hd'); ?></a></div>
        <?php endif; ?>
        <div class="product-button loop-product-button">
            <?php

            // loop/quick-view.php
            //woocommerce_template_loop_quick_view();

            // loop/add-to-cart.php
            woocommerce_template_loop_add_to_cart();
            ?>
        </div>
    </div>
</article>