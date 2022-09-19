<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;

if (!class_exists('BriefIntroduction_Widget')) {
    class BriefIntroduction_Widget extends Widget
    {
        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->widget_description = __('Display brief introduction section', 'hd' );
            $this->widget_name        = __('W - Brief Introduction', 'hd' );
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
         * Outputs the content.
         *
         * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
         * @param array $instance Settings for the Images Carousel widget instance.
         */
        public function widget($args, $instance)
        {
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
            <section class="section brief-intro <?= $_class ?>">
                <div class="<?=$ACF->kind_view?> width-extra brief-intro-inner">
                    <div class="col-1 title-container">
                        <?php if ($ACF->sub_title) : ?>
                        <h6 class="sub-title"><?php echo $ACF->sub_title; ?></h6>
                        <?php
                        endif;
                        if ($title) :
                        ?>
                        <h2 class="heading-title"><?php echo $title; ?></h2>
                        <?php endif;
                        if (Str::stripSpace($ACF->html_title)) :
                            echo '<div class="html-title">';
                            echo $ACF->html_title;
                            echo '</div>';
                        endif;
                        if (Str::stripSpace($ACF->html_desc)) : ?>
                        <div class="html-desc"><?php echo $ACF->html_desc; ?></div>
                        <?php endif; ?>
                        <?php if ($ACF->url) : ?>
                        <a href="<?= esc_url($ACF->url); ?>" class="viewmore button" title="<?php echo esc_attr($ACF->button_text); ?>"><?php echo $ACF->button_text; ?></a>
                        <?php endif; ?>
                    </div>
                    <?php
                    if (Str::stripSpace($ACF->background_img)) :
                        $attachment_meta = wp_get_attachment($ACF->background_img);
                        if (Str::stripSpace($attachment_meta->description) && filter_var($attachment_meta->description, FILTER_VALIDATE_URL)) {
                            $_href = $attachment_meta->description;
                        } else {
                            $_href = false;
                        }
                    ?>
                    <div class="col-2 background-col">
                        <?php if ($_href) echo '<a class="after-overlay h-100 _blank" href="' . $_href . '" title>'; ?>
                        <span class="bg"><?php echo wp_get_attachment_image($ACF->background_img, 'large');?></span>
                        <?php if ($_href) echo '</a>'; ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($ACF->gallery) : ?>
                    <div class="gallery-col swiper-section">
                        <?php
                        $swiper_class = ' autoview';
                        $_data["autoview"] = true;
                        $_data["loop"] = true;
                        $_data["autoplay"] = true;
                        $_data["navigation"] = true;
                        $_data["smallgap"] = $ACF->gallery_gap;

                        $_data = json_encode($_data, JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);
                        ?>
                        <div class="w-swiper swiper">
                            <div class="swiper-wrapper<?= $swiper_class ?>" data-options='<?= $_data;?>'>
                                <?php
                                foreach ($ACF->gallery as $gal) :
                                    $attachment_meta = wp_get_attachment($gal);
                                    if (Str::stripSpace($attachment_meta->description) && filter_var($attachment_meta->description, FILTER_VALIDATE_URL)) {
                                        $_href = $attachment_meta->description;
                                    } else {
                                        $_href = false;
                                    }
                                ?>
                                <div class="swiper-slide">
                                    <figure>
                                        <?php
                                        if ($_href) echo '<a class="after-overlay" href="' . $_href . '" title>';
                                        echo wp_get_attachment_image($gal, 'medium');
                                        if ($_href) echo '</a>';
                                        ?>
                                    </figure>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
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