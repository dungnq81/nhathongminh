<?php

namespace Webhd\Widgets;

if (!class_exists('MiniCart_Widget')) {
	class MiniCart_Widget extends Widget {
        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->widget_description = __('Display the mini cart', 'hd' );
            $this->widget_name        = __('W - Mini Cart', 'hd' );
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
		 * Outputs the content for the Images Carousel widget instance.
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the Images Carousel widget instance.
		 */
		public function widget($args, $instance)
		{
            ob_start();

            $title = apply_filters( 'widget_title', $this->get_instance_title( $instance ), $instance, $this->id_base );

            echo '<div class="mini-cart-section">';
            if ($title) {
				echo '<span class="cart-title">' . $title . '</span>';
			}
			woo_header_cart();
            echo '</div>';

            $content = ob_get_clean();
            echo $content; // WPCS: XSS ok.
		}
	}
}