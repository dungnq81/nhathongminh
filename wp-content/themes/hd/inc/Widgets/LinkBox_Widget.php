<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Str;

if (!class_exists('LinkBox_Widget')) {
    class LinkBox_Widget extends Widget
    {
        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->widget_description = __('Display link box + Custom Fields', 'hd' );
            $this->widget_name        = __('W - LinkBox', 'hd' );
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

            if ( $this->get_cached_widget( $args ) ) {
                return;
            }

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
            if (Str::stripSpace($ACF->css_class)) {
                $_class = $_class . ' ' . $ACF->css_class;
            }

            //...
            $glyph = '';
            if (Str::stripSpace($ACF->glyph)) {
                $glyph = ' data-glyph="' . $ACF->glyph . '"';
            }

            $wrapper_open = '<div class="linkbox-inner"' . $glyph . '>';
            $wrapper_close = '</div>';
            if (Str::stripSpace($ACF->url)) {
                $_a_class = 'linkbox-inner';
                if ($ACF->_blank) $_a_class .= ' _blank';
                $wrapper_open = '<a class="' . $_a_class . '" href="' . $ACF->url . '" title="' . $title . '"' . $glyph . '>';
                $wrapper_close = '</a>';
            }

            ?>
            <div class="linkbox <?php echo $_class; ?>">
                <?php
                echo $wrapper_open;

                // has image thumb
                if ($ACF->thumb_image) {
                    echo wp_get_attachment_image($ACF->thumb_image, 'thumbnail');
                }

                echo '<div>';
                echo '<span>' . $title . '</span>';
                if (Str::stripSpace($ACF->html_desc)) {
                    echo $ACF->html_desc;
                }
                echo '</div>';
                echo $wrapper_close;

                ?>
            </div>
            <?php
            $content = ob_get_clean();
            echo $content; // WPCS: XSS ok.

            $this->cache_widget( $args, $content );
        }
    }
}