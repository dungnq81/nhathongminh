<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Str;

if ( ! class_exists( 'Cf7_Widget' ) ) {
	class Cf7_Widget extends Widget
    {
        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->widget_description = __('Contact Form 7 + Custom Fields', 'hd' );
            $this->widget_name        = __('W - CF7 Form', 'hd' );
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
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance )
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
            <section class="section cf7-section <?= $_class ?>">
                <?php if ($ACF->wrapper) : ?><div class="grid-container"><?php endif;?>
                    <div class="title-section">
					<?php if ( $title ) : ?>
                    <h2 class="heading-title"><?php echo $title; ?></h2>
					<?php endif;
					if ( Str::stripSpace( $ACF->html_title ) ) :
                        echo '<div class="html-title">';
						echo $ACF->html_title;
                        echo '</div>';
					endif;
					if ( Str::stripSpace( $ACF->html_desc ) ) :
						echo '<div class="html-desc">';
						echo $ACF->html_desc;
						echo '</div>';
					endif;
                    ?>
                    </div>
                    <div class="form-section">
                        <?php if ( $ACF->background_image ) : ?>
                        <span class="bg"><?php echo wp_get_attachment_image($ACF->background_image, 'widescreen');?></span>
                        <?php endif;?>
                        <?php if ( $ACF->form ) :
                            $form = get_post( $ACF->form );
                            echo do_shortcode( '[contact-form-7 id="' . $form->ID . '" title="' . esc_attr( $form->post_title ) . '"]' );
                        endif;
                        ?>
                    </div>
                <?php if ($ACF->wrapper) : ?></div><?php endif; ?>
            </section>
			<?php
                $content = ob_get_clean();
                echo $content; // WPCS: XSS ok.
		}
	}
}