<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;

if (!class_exists('ListBox_Widget')) {
    class ListBox_Widget extends Widget
    {
        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->widget_description = __('Display linkbox list + Custom Fields', 'hd' );
            $this->widget_name        = __('W - LinkBox List', 'hd' );
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
            <section class="section listbox-section <?= $_class ?>">
                <?php if ($ACF->wrapper) echo '<div class="grid-container width-extra">'; ?>
                <div class="title-section">
                    <?php if ( $title ) : ?>
                    <h2 class="heading-title"><?php echo $title; ?></h2>
                    <?php endif; ?>
                    <?php if (Str::stripSpace($ACF->html_desc)) : ?>
                    <div class="html-desc"><?php echo $ACF->html_desc; ?></div>
                    <?php endif; ?>
                </div>
                <?php
                if ($ACF->list_box) :
                    echo "<ul class=\"listbox {$this->id}\">";
                    foreach ($ACF->list_box as $item) {
                        $item = Cast::toObject($item);

                        echo '<li>';

                        //...
                        $glyph = '';
                        if (Str::stripSpace($item->glyph)) {
                            $glyph = ' data-glyph="' . $item->glyph . '"';
                        }

                        $wrapper_open = '<div class="linkbox-inner"' . $glyph . '>';
                        $wrapper_close = '</div>';
                        if (Str::stripSpace($item->url)) {
                            $_a_class = 'linkbox-inner';
                            if ($item->_blank) $_a_class .= ' _blank';
                            $wrapper_open = '<a class="' . $_a_class . '" href="' . $item->url . '" title="' . $title . '"' . $glyph . '>';
                            $wrapper_close = '</a>';
                        }

                        //...
                        echo $wrapper_open;

                        // has image thumb
                        if ($item->image) {
                            echo wp_get_attachment_image($item->image, 'thumbnail');
                        }

                        echo '<div>';
                        echo '<span class="title">' . $item->title . '</span>';
                        if (Str::stripSpace($item->desc)) {
                            echo '<span class="desc">' . $item->desc . '</span>';
                        }
                        echo '</div>';

                        echo $wrapper_close;

                        echo '</li>';
                    }
                    echo "</ul>";
                endif;
                if ($ACF->wrapper) echo '</div>'; ?>

            </section>
            <?php
            $content = ob_get_clean();
            echo $content; // WPCS: XSS ok.

            $this->cache_widget( $args, $content );
        }
    }
}