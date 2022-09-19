<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Url;

if (!class_exists('DropdownSearch_Widget')) {
	class DropdownSearch_Widget extends Widget
	{
        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->widget_description = __('Display the dropdown search box', 'hd' );
            $this->widget_name        = __('W - Dropdown Search', 'hd' );
            $this->settings = [
                'title'        => [
                    'type'  => 'text',
                    'std'   => __( 'Search', 'hd' ),
                    'label' => __( 'Title', 'hd' ),
                ],
                'css_class' => [
                    'type' => 'text',
                    'std' => '',
                    'label' => __('Css Class', 'hd'),
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

            $title = apply_filters( 'widget_title', $this->get_instance_title( $instance ), $instance, $this->id_base );
            $css_class      = isset($instance['css_class']) ? trim(strip_tags($instance['css_class'])) : '';

			// class
			$_class = $this->id;
			if ($css_class) {
				$_class = $_class . ' ' . $css_class;
			}

			$_unique_id = esc_attr(uniqid('search-form-'));
			$attr_title = esc_attr($title);
			$placeholder_title = esc_attr(__('Search ...', 'hd' ));
			$close_title = __('Close', 'hd' );

            ?>
			<div class="search-dropdown--wrap <?php echo $_class; ?>">
				<a class="trigger-s" title="<?php echo $attr_title; ?>" href="javascript:;" data-toggle="dropdown-<?= $_unique_id ?>" data-glyph="">
					<span><?php echo $title; ?></span>
				</a>
				<div role="search" class="dropdown-pane" id="dropdown-<?= $_unique_id ?>" data-dropdown data-auto-focus="true">
					<form role="form" action="<?php echo Url::home(); ?>" method="get" class="frm-search" method="get" accept-charset="UTF-8" data-abide novalidate>
                        <div class="frm-container">
                            <input id="<?php echo $_unique_id; ?>" required pattern="^(.*\S+.*)$" type="search" name="s" value="<?php echo get_search_query(); ?>" placeholder="<?php echo $placeholder_title; ?>" title>
                            <button class="btn-s" type="submit" data-glyph="">
                                <span><?php echo $title; ?></span>
                            </button>
                            <button class="trigger-s-close" type="button" data-glyph="">
                                <span><?php echo $close_title; ?></span>
                            </button>
                            <?php if (class_exists( '\WooCommerce' )) : ?>
                            <input type="hidden" name="post_type" value="product">
                            <?php endif; ?>
                        </div>
					</form>
				</div>
			</div>
		<?php
            $content = ob_get_clean();
            echo $content; // WPCS: XSS ok.

            $this->cache_widget( $args, $content );
		}
	}
}