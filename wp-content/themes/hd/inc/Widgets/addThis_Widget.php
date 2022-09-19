<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;

if (!class_exists('addThis_Widget')) {
    class addThis_Widget extends Widget
    {
        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->widget_description = __('Display list contact + Custom Fields', 'hd' );
            $this->widget_name        = __('W - addThis', 'hd' );
            $this->settings = [];

            parent::__construct();
        }

        /**
         * Creating widget front-end
         *
         * @param array $args
         * @param array $instance
         */
        public function widget($args, $instance)
        {
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
            ?>
            <section class="section add_this-section <?= $_class ?>">
                <?php
                if ($ACF->listbox_contact) :

                    echo "<ul class=\"add_this {$this->id}\">";
                        foreach ($ACF->listbox_contact as $item) :
                            $item = Cast::toObject($item);

                            echo '<li>';

                            $wrapper_open = '<div class="add_this-inner">';
                            $wrapper_close = '</div>';
                            if (Str::stripSpace($item->link)) {
                                $_a_class = 'add_this-inner';
                                if ($item->_blank) $_a_class .= ' _blank';
                                $wrapper_open = '<a class="' . $_a_class . '" href="' . $item->link . '" title="' . $title . '">';
                                $wrapper_close = '</a>';
                            }

                            echo $wrapper_open;

                            // has image thumb
                            if ($item->image) {
                                echo wp_get_attachment_image($item->image, 'thumbnail');
                            }

                            // svg
                            if ($item->svg) {
                                echo $item->svg;
                            }

                            //echo '<div>';
                            echo '<span class="title">' . $item->title . '</span>';
                            //echo '</div>';

                            echo $wrapper_close;

                            echo '</li>';

                        endforeach;
                    echo "</ul>";

                endif;
                ?>
            </section>
            <?php
            $content = ob_get_clean();
            echo $content; // WPCS: XSS ok.

            $this->cache_widget( $args, $content );
        }
    }
}