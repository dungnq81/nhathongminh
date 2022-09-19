<?php

namespace Webhd\Themes;

\defined( '\WPINC' ) || die;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;

if ( ! class_exists( 'Hook' ) ) {
	class Hook {
		public function __construct() {
			$this->doActions();
			$this->doFilters();
		}

		// ------------------------------------------------------
		// Actions hook
		// ------------------------------------------------------

		public function doActions() {

			// wp_footer actions
			add_action( 'wp_footer', [ &$this, 'bubble_hotline' ], 97 );
			add_action( 'wp_footer', [ &$this, 'back_to_top' ], 98 );
			add_action( 'wp_footer', [ &$this, 'footer_extra_script' ], 99 );

			// wp_print_footer_scripts
			add_action( 'wp_print_footer_scripts', [ &$this, 'js_fix' ] );

			// hide admin bar
			add_action( "user_register", [ &$this, 'user_register' ], 10, 1 );
		}

		// ------------------------------------------------------

		/**
		 * @param $user_id
		 *
		 * @return void
		 */
		public function __user_register( $user_id ) {
			update_user_meta( $user_id, 'show_admin_bar_front', false );
			update_user_meta( $user_id, 'show_admin_bar_admin', false );
		}

		// ------------------------------------------------------

		/**
		 * Fix skip link focus
		 *
		 * This does not enqueue the script because it is tiny and because it is only for IE11,
		 * thus it does not warrant having an entire dedicated blocking script being loaded.
		 *
		 * @link https://git.io/vWdr2
		 */
		public function js_fix() {
			if ( file_exists( $skip_link = get_template_directory() . '/assets/js/plugins/skip-link-focus-fix.js' ) ) {
				echo '<script>';
				include $skip_link;
				echo '</script>';
			}

            if ( file_exists( $flex_gap = get_template_directory() . '/assets/js/plugins/flex-gap.js' ) ) {
                echo '<script>';
                include $flex_gap;
                echo '</script>';
            }

			// The following is minified via `npx terser --compress --mangle -- assets/js/skip-link-focus-fix.js`.
		}

		// ------------------------------------------------------

		public function footer_extra_script() { ?>
            <script>document.documentElement.classList.remove("no-js");if (-1 !== navigator.userAgent.indexOf('MSIE') || -1 !== navigator.appVersion.indexOf('Trident/')) {document.documentElement.classList.add('is-IE');}</script>
			<?php

            //...
		}

		// ------------------------------------------------------

		/**
		 * Build the back to top button
		 *
		 * - GeneratePress
		 * - @since 1.3.24
		 */
		public function back_to_top() {
			$back_to_top = apply_filters( 'w_back_to_top', true );
			if ( ! $back_to_top ) {
				return;
			}

			echo apply_filters( // phpcs:ignore
				'back_to_top_output',
				sprintf(
					'<a title="%1$s" aria-label="%1$s" rel="nofollow" href="#" class="w-back-to-top toTop o_draggable" style="opacity:0;visibility:hidden;" data-scroll-speed="%2$s" data-start-scroll="%3$s" data-glyph=""></a>',
					esc_attr__( 'Scroll back to top', 'hd' ),
					absint( apply_filters( 'back_to_top_scroll_speed', 400 ) ),
					absint( apply_filters( 'back_to_top_start_scroll', 300 ) ),
					//SVG_Icons::get_svg( 'ui', 'arrow_right', 24 )
				)
			);
		}

		// ------------------------------------------------------

		public function bubble_hotline() {

			// hotline
			$_hotline = get_theme_mod_ssl( 'hotline_setting' );
			$_tel     = Str::stripSpace( $_hotline );
			if ( $_tel ) {
				echo '<div class="hotline-mobile o_draggable">';
				echo '<a title="' . esc_attr( $_hotline ) . '" href="tel:' . $_tel . '">';
				echo '<div class="hl-circle"></div><div class="hl-circle-fill"></div><div class="hl-circle-icon" data-glyph=""></div>';
				echo '</a>';
				echo '</div>';
			}
		}

		// ------------------------------------------------------
		// Filters hook
		// ------------------------------------------------------

		public function doFilters() {

			// Adding Shortcode in WordPress Using Custom HTML Widget
			add_filter( 'widget_text', 'do_shortcode' );
			add_filter( 'widget_text', 'shortcode_unautop' );

			// Disable XML-RPC authentication
            // Filter whether XML-RPC methods requiring authentication, such as for publishing purposes, are enabled.
			add_filter( 'xmlrpc_enabled', '__return_false' );
			add_filter( 'pre_update_option_enable_xmlrpc', '__return_false' );
			add_filter( 'pre_option_enable_xmlrpc', '__return_zero' );
			add_filter( 'pings_open', '__return_false', 9999 );

			add_filter( 'wp_headers', function ( $headers ) {
				unset( $headers['X-Pingback'], $headers['x-pingback'] );
				return $headers;
			} );

			//...
			if ( ! WP_DEBUG ) {

				// Remove WP version from RSS.
				add_filter( 'the_generator', '__return_empty_string' );

				add_filter( 'style_loader_src', [ &$this, 'remove_version_scripts_styles' ], 11, 1 );
				add_filter( 'script_loader_src', [ &$this, 'remove_version_scripts_styles' ], 11, 1 );
			}

			// Changing the alt text on the logo to show your site name
			add_filter( 'login_headertext', function () {
				return get_option( 'blogname' );
			} );

			// Changing the logo link from wordpress.org to your site
			add_filter( 'login_headerurl', function () {
				return esc_url( site_url( '/' ) );
			} );

			// comment off default
			add_filter( 'wp_insert_post_data', function ( $data ) {
				if ( $data['post_status'] == 'auto-draft' ) {
					$data['comment_status'] = 0;
					$data['ping_status']    = 0;
				}
				return $data;
			}, 11, 1 );

			// Add support for buttons in the top-bar menu
			add_filter( 'wp_nav_menu', function ( $ulclass ) {
				$find    = [ '/<a rel="button"/', '/<a title=".*?" rel="button"/' ];
				$replace = [ '<a rel="button" class="button"', '<a rel="button" class="button"' ];
				return preg_replace( $find, $replace, $ulclass, 1 );
			} );

			// normalize upload filename
			add_filter( 'sanitize_file_name', function ( string $filename ) {
				return remove_accents( $filename );
			}, 10, 1 );

			// filter post search only by title
			add_filter( "posts_search", [ &$this, 'post_search_by_title' ], 500, 2 );

			//...
			add_filter( 'excerpt_more', function () {
				return '...';
			} );

			// remove id li navigation
			add_filter( 'nav_menu_item_id', '__return_null', 10, 3 );

			// add multiple for category dropdown
			add_filter( 'wp_dropdown_cats', [ &$this, 'dropdown_cats_multiple' ], 10, 2 );

			/**
			 * Use the is-active class of ZURB Foundation on wp_list_pages output.
			 * From required+ Foundation http://themes.required.ch.
			 */
			add_filter( 'wp_list_pages', function ( $input ) {
				$pattern = '/current_page_item/';
				$replace = 'current_page_item is-active';
				return preg_replace( $pattern, $replace, $input );
			}, 10, 2 );
		}

		// ------------------------------------------------------

		/**
		 * @param $output
		 * @param $r
		 *
		 * @return mixed|string|string[]
		 */
		public function dropdown_cats_multiple( $output, $r ) {
			if ( isset( $r['multiple'] ) && $r['multiple'] ) {
				$output = preg_replace( '/^<select/i', '<select multiple', $output );
				$output = str_replace( "name='{$r['name']}'", "name='{$r['name']}[]'", $output );
				foreach ( array_map( 'trim', explode( ",", $r['selected'] ) ) as $value ) {
					$output = str_replace( "value=\"{$value}\"", "value=\"{$value}\" selected", $output );
				}
			}

			return $output;
		}

		// ------------------------------------------------------

		/**
		 * @param $search
		 * @param $wp_query
		 *
		 * @return string
		 */
		public function post_search_by_title( $search, $wp_query ) {
			global $wpdb;

			if ( empty( $search ) ) {
				return $search; // skip processing – no search term in query
			}

            $q = $wp_query->query_vars;
            $n = !empty($q['exact']) ? '' : '%';

			$search = $searchand = '';
            foreach (Cast::toArray($q['search_terms']) as $term) {
                $term = esc_sql($wpdb->esc_like($term));
                $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
                $searchand = " AND ";
            }

			if ( ! empty( $search ) ) {
				$search = " AND ({$search}) ";
				if ( ! is_user_logged_in() ) {
					$search .= " AND ($wpdb->posts.post_password = '') ";
				}
			}

			return $search;
		}

		// ------------------------------------------------------

		/**
		 * Remove version from scripts and styles
		 *
		 * @param $src
		 * @return bool|string
		 */
		public function remove_version_scripts_styles( $src ) {
			if ( $src && str_contains( $src, 'ver=' ) ) {
				$src = remove_query_arg( 'ver', $src );
			}

			return $src;
		}
	}
}