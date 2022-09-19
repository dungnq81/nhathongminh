<?php

namespace Webhd\Themes;

use Webhd\Helpers\Url;

\defined( '\WPINC' ) || die;

if ( ! class_exists( 'Shortcode' ) ) {
	class Shortcode {

		// ------------------------------------------------------

		public function __construct() {
			add_shortcode( 'safe_mail', [ &$this, 'safe_mailto_shortcode' ], 11 );
			add_shortcode( 'site_logo', [ &$this, 'site_logo_shortcode' ], 11 );

			add_shortcode( 'horizontal_menu', [ &$this, 'horizontal_menu_shortcode' ], 11 );
			add_shortcode( 'vertical_menu', [ &$this, 'vertical_menu_shortcode' ], 11 );

			add_shortcode( 'social_menu', [ &$this, 'social_menu_shortcode' ], 11 );
			add_shortcode( 'mobile_menu', [ &$this, 'mobile_menu_shortcode' ], 11 );
			add_shortcode( 'main_menu', [ &$this, 'main_menu_shortcode' ], 11 );
			add_shortcode( 'term_menu', [ &$this, 'term_menu_shortcode' ], 11 );

			add_shortcode( 'page_title_theme', [ &$this, 'page_title_theme_shortcode' ], 11 );

			add_shortcode( 'inline-search', [ &$this, 'inline_search_shortcode' ], 11 );
		}

		// ------------------------------------------------------

        /**
         * @param $atts
         * @return void
         */
        public function inline_search_shortcode( $atts = [] )
        {
            // override default attributes
            $a = shortcode_atts(
                [
                    'class' => 'inline-search',
                ],
                array_change_key_case( (array)$atts, CASE_LOWER )
            );

            $_unique_id = esc_attr(uniqid('search-form-'));
            $title = __('Search', 'hd' );
            $title_for = __('Search for', 'hd' );
            $placeholder_title = esc_attr(__('Search ...', 'hd' ));

            ?>
            <div class="inside-search <?php echo $a['class']; ?>">
                <form role="search" action="<?php echo Url::home(); ?>" class="frm-search" method="get" accept-charset="UTF-8" data-abide novalidate>
                    <label for="<?php echo $_unique_id; ?>" class="screen-reader-text"><?php echo $title_for; ?></label>
                    <input id="<?php echo $_unique_id; ?>" required pattern="^(.*\S+.*)$" type="search" autocomplete="off" name="s" value="<?php echo get_search_query(); ?>" placeholder="<?php echo $placeholder_title; ?>">
                    <button type="submit" data-glyph="">
                        <span><?php echo $title; ?></span>
                    </button>
                    <?php if (class_exists( '\WooCommerce' )) : ?>
                    <input type="hidden" name="post_type" value="product">
                    <?php endif; ?>
                </form>
            </div>
        <?php
        }

		// ------------------------------------------------------

		/**
		 * @param array $atts
		 *
		 * @return false|string
		 */
		public function page_title_theme_shortcode( $atts = [] ) {
			// override default attributes
			$a = shortcode_atts( [], array_change_key_case( (array) $atts, CASE_LOWER ) );

			ob_start();
			the_page_title_theme();

			return ob_get_clean();
		}

		// ------------------------------------------------------

		/**
		 * @param array $atts
		 *
		 * @return bool|string
         */
		public function social_menu_shortcode( $atts = [] ) {
			// override default attributes
			$a = shortcode_atts(
				[
					'location'   => 'social-nav',
					'menu_class' => 'social-menu menu conn-lnk',
				],
				array_change_key_case( (array)$atts, CASE_LOWER )
			);

			return social_nav( $a['location'], $a['menu_class'] );
		}

		// ------------------------------------------------------

		/**
		 * @param array $atts
		 *
		 * @return bool|string
         */
		public function mobile_menu_shortcode( $atts = [] ) {
			// override default attributes
			$a = shortcode_atts(
				[
					'location'   => 'mobile-nav',
					'menu_class' => 'mobile-menu',
					'menu_id'    => 'mobile-menu',
				],
				array_change_key_case( (array)$atts, CASE_LOWER )
			);

			return mobile_nav( $a['location'], $a['menu_class'], $a['menu_id'] );
		}

		// ------------------------------------------------------

		/**
		 * @param array $atts
		 *
		 * @return bool|string
         */
		public function term_menu_shortcode( $atts = [] ) {
			// override default attributes
			$a = shortcode_atts(
				[
					'location'   => 'policy-nav',
					'menu_class' => 'terms-menu',
				],
				array_change_key_case( (array)$atts, CASE_LOWER )
			);

			return term_nav( $a['location'], $a['menu_class'] );
		}

		// ------------------------------------------------------

		/**
		 * @param array $atts
		 *
		 * @return bool|string
         */
		public function main_menu_shortcode( $atts = [] ) {
			// override default attributes
			$a = shortcode_atts(
				[
					'location'   => 'main-nav',
					'menu_class' => 'desktop-menu',
					'menu_id'    => 'main-menu',
				],
				array_change_key_case( (array)$atts, CASE_LOWER )
			);

			return main_nav( $a['location'], $a['menu_class'], $a['menu_id'] );
		}

		// ------------------------------------------------------

		/**
		 * @param array $atts
		 *
		 * @return bool|string
         */
		public function vertical_menu_shortcode( $atts = [] ) {
			// override default attributes
			$a = shortcode_atts(
				[
					'id'       => '',
					'class'    => 'mobile-menu',
					'location' => 'mobile-nav',
					'depth'    => 4,
				],
				array_change_key_case( (array) $atts, CASE_LOWER )
			);

			return vertical_nav( [
				'menu_id'        => $a['id'],
				'menu_class'     => 'vertical menu ' . $a['class'],
				'theme_location' => $a['location'],
				'depth'          => $a['depth'],
				'echo'           => false,
			] );
		}

		// ------------------------------------------------------

		/**
		 * @param array $atts
		 *
		 * @return bool|string
         */
		public function horizontal_menu_shortcode( $atts = [] ) {

			// override default attributes
			$a = shortcode_atts(
				[
					'id'       => '',
					'class'    => 'desktop-menu',
					'location' => 'main-nav',
					'depth'    => 4,
				],
				array_change_key_case( (array) $atts, CASE_LOWER )
			);

			return horizontal_nav( [
				'menu_id'        => $a['id'],
				'menu_class'     => 'dropdown menu horizontal horizontal-menu ' . $a['class'],
				'theme_location' => $a['location'],
				'depth'          => $a['depth'],
				'echo'           => false,
			] );
		}

		// ------------------------------------------------------

		/**
		 * @param array $atts
		 *
		 * @return string
		 */
		public function site_logo_shortcode( $atts = [] ) {

			// override default attributes
			$a = shortcode_atts(
				[
					'theme' => 'default',
					'class' => 'site-logo',
				],
				array_change_key_case( (array) $atts, CASE_LOWER )
			);

			return site_logo( $a['theme'], $a['class'] );
		}

		// ------------------------------------------------------

		/**
		 * @param array $atts
		 *
		 * @return string
		 */
		public function safe_mailto_shortcode( $atts = [] ) {

			// override default attributes
			$a = shortcode_atts(
				[
					'email'      => 'info@webhd.vn',
					'title'      => '',
					'attributes' => '',
					'class'      => '',
					'id'         => esc_attr( uniqid( 'mail-' ) ),
				],
				array_change_key_case( (array) $atts, CASE_LOWER )
			);

			$_attr = [];
			if ( $a['id'] ) {
				$_attr['id'] = $a['id'];
			}

			if ( $a['class'] ) {
				$_attr['class'] = $a['class'];
			}

			if ( empty( $a['title'] ) ) {
				$a['title'] = esc_attr( $a['email'] );
			}

			$_attr['title'] = $a['title'];

			if ( $a['attributes'] ) {
				$a['attributes'] = array_merge( $_attr, (array) $a['attributes'] );
			} else {
				$a['attributes'] = $_attr;
			}

			return safe_mailto( $a['email'], $a['title'], $a['attributes'] );
		}
	}
}