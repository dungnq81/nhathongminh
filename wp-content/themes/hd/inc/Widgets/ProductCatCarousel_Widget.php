<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;
use Webhd\Helpers\Url;

if (!class_exists('ProductCatCarousel_Widget')) {
    class ProductCatCarousel_Widget extends Widget {
        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->widget_description = __('Display products categories carousels + Custom Fields', 'hd' );
            $this->widget_name        = __('W - Products Cat Carousels', 'hd' );
            $this->settings = [
                'title'        => [
                    'type'  => 'text',
                    'std'   => '',
                    'label' => __( 'Title', 'hd' ),
                ],
            ];

            parent::__construct();
        }

        /**
         * Creating widget front-end
         *
         * @param array $args
         * @param array $instance
         */
        public function widget($args, $instance) {

            ob_start();

            /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
            $title = apply_filters( 'widget_title', $this->get_instance_title( $instance ), $instance, $this->id_base );

            // ACF attributes
            $ACF = $this->acfFields( 'widget_' . $args['widget_id'] );
            if ( is_null( $ACF ) ) {
                wp_die( __( 'Required: "Advanced Custom Fields" plugin', 'hd' ) );
            }

            // class
            $_class = $this->id;
            if ( $ACF->css_class ) {
                $_class = $_class . ' ' . $ACF->css_class;
            }

            // glyph_icon
            $glyph_icon = '';
            if (Str::stripSpace($ACF->glyph_icon)) {
                $glyph_icon = ' data-glyph-after="' . $ACF->glyph_icon . '"';
            }

            //...
            $fullwidth = '';
            if ( ! $ACF->full_width ) {
                $fullwidth = ' grid-container width-extra';
            }

            ?>
            <section class="section carousels-section filter-productcat <?= $_class ?>">
                <div class="grid-container width-extra title-container">
                    <?php if ($title) : ?>
                    <?php if (Str::stripSpace($ACF->link_title)) : ?>
                    <a class="d-block" href="<?=$ACF->link_title?>" title="<?=esc_attr($title)?>">
                    <?php endif; ?>
                        <h2 class="heading-title"<?=$glyph_icon?>><?php echo $title; ?></h2>
                    <?php if (Str::stripSpace($ACF->link_title))
                        echo '</a>'; ?>
                    <?php endif; ?>
                    <?php
                    // menu list
                    if ($ACF->menu_list) :
                        ?>
                        <ul class="menu_list">
                            <?php foreach ( $ACF->menu_list as $key => $term_id ) :
                                $term = get_term( $term_id );
                                if ($term) :
                                ?>
                                <li><a href="<?php echo get_term_link($term_id, 'product_cat'); ?>" title="<?php echo esc_attr($term->name); ?>"><?php echo $term->name; ?></a></li>
                            <?php endif; endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                <div id="<?php echo $this->id; ?>" aria-labelledby="<?php echo esc_attr($title); ?>">
                    <?php  if ($ACF->cat_list) : ?>
                    <div class="items-container<?=$fullwidth?>">
                        <div class="swiper-section carousel-productcat grid-productcat">
                            <?php
                            $swiper_class = '';
                            $_data = [];
                            if ($ACF->gap) {
                                $_data["gap"] = true;
                                $swiper_class .= ' gap';
                            } elseif ($ACF->smallgap) {
                                $_data["smallgap"] = $ACF->smallgap;
                                $swiper_class .= ' smallgap';
                            }

                            if ($ACF->navigation) $_data["navigation"] = true;
                            if ($ACF->pagination) $_data["pagination"] = "dynamic";
                            if ($ACF->delay) $_data["delay"] = $ACF->delay;
                            if ($ACF->speed) $_data["speed"] = $ACF->speed;
                            if ($ACF->autoplay) $_data["autoplay"] = true;
                            if ($ACF->loop) $_data["loop"] = true;
                            if ($ACF->centered) $_data["centered"] = true;

                            if (!$ACF->number_desktop || !$ACF->number_tablet || !$ACF->number_mobile) {
                                $_data["autoview"] = true;
                                $swiper_class .= ' autoview';
                            } else {
                                $_data["desktop"] = $ACF->number_desktop;
                                $_data["tablet"] = $ACF->number_tablet;
                                $_data["mobile"] = $ACF->number_mobile;
                            }

                            if ($ACF->row > 1) {
                                $_data["row"] =  $ACF->row;
                                $_data["loop"] = false;
                            }

                            $_data = json_encode($_data, JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);

                            ?>
                            <div class="w-swiper swiper">
                                <div class="swiper-wrapper<?= $swiper_class ?>" data-options='<?= $_data;?>'>
                                    <?php
                                    foreach ( $ACF->cat_list as $key => $term_id ) :
                                        $term = get_term( $term_id );
                                        if ($term) :
                                            $thumbnail_id = get_term_meta( $term_id, 'thumbnail_id', true );
                                            ?>
                                            <div class="swiper-slide">
                                                <a <?php wc_product_cat_class('d-block item'); ?> href="<?php echo get_term_link($term_id, 'product_cat'); ?>" title="<?php echo esc_attr($term->name); ?>">
                                                    <div class="cover">
                                                        <span class="after-overlay res auto scale ar-1-1"><?php echo wp_get_attachment_image($thumbnail_id, 'medium'); ?></span>
                                                    </div>
                                                    <div class="cover-content">
                                                        <h6><?php echo $term->name; ?></h6>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php
                                        endif;
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php if ($ACF->url) : ?>
                        <a href="<?= esc_url($ACF->url) ?>" class="viewmore button" title="<?php echo esc_attr($ACF->button_text) ?>"><?php echo $ACF->button_text; ?></a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </section>
            <?php
            $content = ob_get_clean();
            echo $content; // WPCS: XSS ok.
        }
    }
}