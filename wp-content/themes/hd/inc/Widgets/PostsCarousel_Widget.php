<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;

if (!class_exists('PostsCarousel_Widget')) {
	class PostsCarousel_Widget extends Widget
	{
        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->widget_description = __('Your site&#8217;s filter posts carousel by category.', 'hd' );
            $this->widget_name        = __('W - Posts Carousel', 'hd' );
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

            //...
            $r = query_by_terms($ACF->cat, 'category', 'post', $ACF->include_children, $ACF->number);
            if (!$r) return;

            ?>
			<section class="section carousels-section filter-posts <?= $_class ?>">
                <div class="grid-container width-extra title-container">
                    <?php if ($ACF->sub_title) : ?>
                    <h6 class="sub-title"><?php echo $ACF->sub_title; ?></h6>
                    <?php
                    endif; if ($title) : ?>
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
                </div>
                <div id="<?php echo $this->id; ?>" aria-labelledby="<?php echo esc_attr($title); ?>">
				    <?php
                        if (!$ACF->full_width) echo '<div class="grid-container width-extra swiper-container">';
                        else echo '<div class="swiper-container">';
                    ?>
                        <div class="swiper-section carousel-posts grid-posts">
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
                            if ($ACF->freemode) $_data["freemode"] = true;
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
                                    $i = 0;

                                    // Load slides loop.
                                    while ($r->have_posts() && $i < $ACF->number) : $r->the_post();
                                        echo '<div class="swiper-slide">';
                                        //get_template_part('template-parts/posts/loop');
                                        get_template_part('template-parts/posts/loop', null, Cast::toArray($ACF));
                                        echo '</div>';
                                        ++$i;
                                    endwhile;
                                    wp_reset_postdata();
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php if ($ACF->url) : ?>
                        <a href="<?= esc_url($ACF->url) ?>" class="viewmore button" title="<?php echo esc_attr($ACF->button_text) ?>"><?php echo $ACF->button_text; ?></a>
                        <?php endif; ?>
                    </div>
                </div>
			</section>
		    <?php
                $content = ob_get_clean();
                echo $content; // WPCS: XSS ok.
		}
	}
}