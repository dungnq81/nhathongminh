<?php

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

// header
get_header('shop');

//$term = get_queried_object();

$is_sidebar = FALSE;
if (is_active_sidebar('w-product-sidebar') && !is_search()) $is_sidebar = TRUE;

the_page_title_theme();

/**
 * Hook: woocommerce_before_main_content.
 *
 * @see woocommerce_breadcrumb - 20 - removed
 * @see WC_Structured_Data::generate_website_data() - 30
 */
do_action('woocommerce_before_main_content');

if ( !is_search() ) :
    if ( (@is_shop()) && is_active_sidebar('w-shop-sidebar')) :
        wc_get_template_part('shop', 'product');
    endif;

    // Product Attributes
    if (is_active_sidebar('w-product-attributes-sidebar')) :
        echo '<section class="section product-attributes"><div class="grid-container width-extra"><div class="product-attributes-inner">';
            dynamic_sidebar('w-product-attributes-sidebar');
        echo '</div></div></section>';
    endif;
endif;

?>
<section class="section archive-products">
    <div class="grid-container width-extra">
        <?php
        /**
         * Hook: woocommerce_before_archive.
         */
        do_action('woocommerce_before_archive');
        ?>
        <div class="grid-x grid-padding-x">
            <div class="archive-products-inner cell<?php if (TRUE === $is_sidebar) echo ' medium-12 large-9 has-sidebar'; ?>">
                <?php
                if (woocommerce_product_loop()) :

                    /**
                     * Hook: woocommerce_before_shop_loop.
                     *
                     * @see woocommerce_output_all_notices - 8
                     * @see woocommerce_result_count - 20
                     * @see woocommerce_catalog_ordering - 30
                     */
                    do_action('woocommerce_before_shop_loop');
                ?>
                <div class="grid-products grid-x<?php if (is_search()) echo ' main-content-search'; ?>">
                    <?php
                    $i = 0;

                    //...
                    $term_banner = null;
                    $term_banner_link = null;

                    if ( !is_search() && is_post_type_archive( 'product' ) && 0 === absint( get_query_var( 'paged' ) )) {
                        $shop_page = get_post( wc_get_page_id( 'shop' ) );
                        if ( $shop_page && function_exists('get_fields') ) {
                            $ACF = get_fields($shop_page->ID);

                            $term_banner = $ACF['term_banner'] ?? null;
                            $term_banner_link = $ACF['term_banner_link'] ?? null;

                            if ($term_banner && !$term_banner_link) {
                                $term_banner_link = get_permalink( wc_get_page_id( 'shop' ) );
                            }
                        }
                    }
                    else if ( !is_search() && is_product_taxonomy() && 0 === absint( get_query_var( 'paged' ) )) {
                        $term = get_queried_object();
                        if ($term && function_exists('get_fields')) {
                            $ACF = get_fields($term);

                            $term_banner = $ACF['term_banner'] ?? null;
                            $term_banner_link = $ACF['term_banner_link'] ?? null;

                            if ($term_banner && !$term_banner_link) {
                                $term_banner_link = get_term_link($term, 'product_cat');
                            }
                        }
                    }

                    //...
                    if (wc_get_loop_prop('total')) :

                        // Start the Loop.
                        while (have_posts()) : the_post();

                            //...
                            if (0 === $i && $term_banner) {
                                echo '<div class="cell banner-cell">';
                                echo '<figure>';
                                if ($term_banner_link) echo '<a class="after-overlay _blank" href="' . $term_banner_link . '" title>';
                                echo wp_get_attachment_image($term_banner, 'medium');
                                if ($term_banner_link) echo '</a>';
                                echo '</figure>';
                                echo '</div>';
                            }

                            //echo '<div class="cell">';
                            echo '<div class="cell cell-' . $i . '">';
                            wc_get_template_part('content', 'product');
                            echo '</div>';

                            // End the loop.
                            ++$i;
                        endwhile;
                    endif;
                    wp_reset_postdata();

                    ?>
                </div>
                <?php
                    /**
                     * Hook: woocommerce_after_shop_loop.
                     *
                     * @see woocommerce_pagination - 10
                     */
                    do_action('woocommerce_after_shop_loop');
                else :
                    // @see wc_no_products_found - 10
                    do_action('woocommerce_no_products_found');
                endif;
                ?>
            </div>
            <?php if (TRUE === $is_sidebar) : ?>
                <div class="cell sidebar-col medium-12 large-3">
                    <div class="sidebar--wrap">
                        <?php dynamic_sidebar('w-product-sidebar'); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php

    echo '<div class="grid-container width-extra">';
        /**
         * Hook: woocommerce_after_shop.
         *
         * @see woocommerce_output_recently_viewed_products - 19
         */
        do_action('woocommerce_after_shop');
    echo '</div>';

    /**
     * Hook: woocommerce_archive_description.
     *
     * @see woocommerce_taxonomy_archive_description - 10
     * @see woocommerce_product_archive_description - 10
     */
    do_action('woocommerce_archive_description');
    ?>
</section>
<?php

/**
 * Hook: woocommerce_after_main_content.
 *
 */
do_action('woocommerce_after_main_content');

// footer
get_footer('shop');
