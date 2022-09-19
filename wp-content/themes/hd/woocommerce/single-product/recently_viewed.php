<?php

defined( 'ABSPATH' ) || exit;

global $woocommerce;

$viewed_products = !empty($_COOKIE['woocommerce_recently_viewed']) ? (array) explode('|', wp_unslash($_COOKIE['woocommerce_recently_viewed'])) : [];
$viewed_products = array_reverse(array_filter(array_map('absint', $viewed_products)));

$query_args = array(
    'posts_per_page' => 12,
    'post_status'    => 'publish',
    'post_type'      => 'product',
    'post__in'       => $viewed_products,
    'orderby'        => 'rand',
    'order'          => 'desc',
);

$query_args['meta_query'] = array();
$query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();

$r = new WP_Query($query_args);
if ( $r->have_posts() ) :

?>
<section class="section carousels-section related recently-viewed products related-products section-padding">
    <?php
    //$heading = apply_filters( 'woocommerce_product_recently_viewed_products_heading', __( 'Recently viewed products', 'hd' ) );
    $heading = apply_filters( 'woocommerce_product_recently_viewed_products_heading', __( 'Sản phẩm đã xem', 'hd' ) );
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
    $_data['smallgap'] = 20;

    $_data = json_encode($_data, JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);

    ?>
    <div class="swiper-section grid-products">
        <div class="w-swiper swiper">
            <div class="swiper-wrapper" data-options='<?= $_data;?>'>
                <?php
                while ( $r->have_posts() ) : $r->the_post();

                    echo '<div class="swiper-slide">';
                    $post_object = get_post();

                    setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
                    wc_get_template_part( 'content', 'product' );
                    echo '</div>';

                endwhile;
                wp_reset_postdata();

                ?>
            </div>
        </div>
    </div>
</section>
<?php endif;