<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;
use Webhd\Helpers\Url;

if (!class_exists('ProductCat_Widget')) {
    class ProductCat_Widget extends Widget {
        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->widget_description = __('Display products categories + Custom Fields', 'hd' );
            $this->widget_name        = __('W - Products Cat', 'hd' );
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

            ?>
            <section class="section filter-productcat <?= $_class ?>">
                <div class="grid-container width-extra title-container">
                    <?php if ($title) : ?>
                    <h2 class="heading-title"><?php echo $title; ?></h2>
                    <?php endif; ?>
                    <?php if (Str::stripSpace($ACF->html_desc)) : ?>
                    <div class="html-desc"><?php echo $ACF->html_desc; ?></div>
                    <?php endif; ?>
                </div>
                <div id="<?php echo $this->id; ?>" aria-labelledby="<?php echo esc_attr($title); ?>">
                    <?php
                    // loop
                    if ( $ACF->product_cat ) :
                        if ( ! $ACF->full_width ) echo '<div class="grid-container width-extra">';
                    ?>
                    <div class="grid-x grid-productcat list-productcat">
                        <?php
                        foreach ( $ACF->product_cat as $key => $term_id ) :
                            $term = get_term( $term_id );
                            if ($term) :
                                $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                        ?>
                        <div class="cell cell-<?=$key?>">
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
                    <?php if ($ACF->url) : ?>
                    <a href="<?= esc_url($ACF->url) ?>" class="viewmore" title="<?php echo esc_attr($ACF->button_text) ?>"><?php echo $ACF->button_text; ?></a>
                    <?php endif; ?>
                    <?php if (!$ACF->full_width) echo '</div>'; ?>
                    <?php endif; ?>
                </div>
            </section>
            <?php
            $content = ob_get_clean();
            echo $content; // WPCS: XSS ok.
        }
    }
}