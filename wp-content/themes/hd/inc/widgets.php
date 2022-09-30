<?php
/**
 * register_widget functions
 *
 * @author   WEBHD
 */

use Webhd\Widgets\addThis_Widget;
use Webhd\Widgets\BriefIntroduction_Widget;
use Webhd\Widgets\Cf7_Widget;
use Webhd\Widgets\DropdownSearch_Widget;
use Webhd\Widgets\LinkBox_Widget;
use Webhd\Widgets\ListBox_Widget;
use Webhd\Widgets\MediaCarousel_Widget;
use Webhd\Widgets\MiniCart_Widget;
use Webhd\Widgets\offCanvas_Widget;
use Webhd\Widgets\PageTitle_Widget;
use Webhd\Widgets\Posts_Widget;
use Webhd\Widgets\PostsCarousel_Widget;
use Webhd\Widgets\ProductCat_Widget;
use Webhd\Widgets\ProductCatCarousel_Widget;
use Webhd\Widgets\Products_Widget;
use Webhd\Widgets\ProductsCarousel_Widget;
use Webhd\Widgets\Search_Widget;
use Webhd\Widgets\WC_Widget_Layered_Nav_Ex;

\defined('\WPINC') || die;

if (!function_exists('__register_widgets')) {

    /**
     * Registers a WP_Widget widget
     *
     * @return void
     */
    function __register_widgets(): void
    {
        class_exists(offCanvas_Widget::class) && register_widget(new offCanvas_Widget);
        class_exists(Search_Widget::class) && register_widget(new Search_Widget);
        class_exists(DropdownSearch_Widget::class) && register_widget(new DropdownSearch_Widget);
        class_exists(PageTitle_Widget::class) && register_widget(new PageTitle_Widget);

        class_exists('\ACF') && class_exists(LinkBox_Widget::class) && register_widget(new LinkBox_Widget);
        class_exists('\ACF') && class_exists(ListBox_Widget::class) && register_widget(new ListBox_Widget);
        class_exists('\ACF') && class_exists(MediaCarousel_Widget::class) && register_widget(new MediaCarousel_Widget);
        class_exists('\ACF') && class_exists(BriefIntroduction_Widget::class) && register_widget(new BriefIntroduction_Widget);
        class_exists('\ACF') && class_exists(Products_Widget::class) && register_widget(new Products_Widget);
        class_exists('\ACF') && class_exists(ProductsCarousel_Widget::class) && register_widget(new ProductsCarousel_Widget);
        class_exists('\ACF') && class_exists(ProductCat_Widget::class) && register_widget(new ProductCat_Widget);
        class_exists('\ACF') && class_exists(ProductCatCarousel_Widget::class) && register_widget(new ProductCatCarousel_Widget);
        class_exists('\ACF') && class_exists(Posts_Widget::class) && register_widget(new Posts_Widget);
        class_exists('\ACF') && class_exists(PostsCarousel_Widget::class) && register_widget(new PostsCarousel_Widget);
        class_exists('\ACF') && class_exists(addThis_Widget::class) && register_widget(new addThis_Widget);

        class_exists('\WooCommerce') && class_exists(MiniCart_Widget::class) && register_widget(new MiniCart_Widget);
        class_exists('\WPCF7') && class_exists('\ACF') && class_exists(Cf7_Widget::class) && register_widget(new Cf7_Widget);

        /** */
        if (class_exists( 'Woo_Variation_Swatches_Pro' )) {
            if ( wc_string_to_bool( woo_variation_swatches()->get_option( 'show_swatches_on_filter_widget', 'yes' ) ) ) {

                //unregister_widget( 'WC_Widget_Layered_Nav' );
                unregister_widget( 'Woo_Variation_Swatches_Pro_Widget_Layered_Nav' );
                register_widget(new WC_Widget_Layered_Nav_Ex);
            }
        }
    }

    /** */
    add_action('widgets_init', '__register_widgets', 10);
}