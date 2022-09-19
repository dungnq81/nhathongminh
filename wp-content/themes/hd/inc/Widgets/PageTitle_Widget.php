<?php

namespace Webhd\Widgets;

if (!class_exists('PageTitle_Widget')) {
    class PageTitle_Widget extends Widget
    {
        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->widget_description = __('Display page title, background and breadcrumbs', 'hd' );
            $this->widget_name        = __('W - Page Title', 'hd' );
            $this->settings = [
                'css_class' => [
                    'type' => 'text',
                    'std' => '',
                    'label' => __('Css Class', 'hd'),
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

            $css_class = isset($instance['css_class']) ? trim(strip_tags($instance['css_class'])) : '';
            the_page_title_theme($css_class);

            $content = ob_get_clean();
            echo $content; // WPCS: XSS ok.
        }
    }
}