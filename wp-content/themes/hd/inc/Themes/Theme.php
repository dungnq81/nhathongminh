<?php

namespace Webhd\Themes;

\defined( '\WPINC' ) || die;

use Webhd\Helpers\Css;
use Webhd\Helpers\Str;

/**
 * Global Theme Class
 *
 * @author WEBHD
 */

if ( ! class_exists( 'Theme' ) ) {
	class Theme {
		public function __construct() {
			add_action( 'after_setup_theme', [ &$this, 'after_setup_theme' ] );

			add_action( 'wp_enqueue_scripts', [ &$this, 'enqueue_scripts' ], 10 );
			add_action( 'wp_enqueue_scripts', [ &$this, 'non_latin_languages' ], 28 );
			add_action( 'wp_enqueue_scripts', [ &$this, 'enqueue_inline_css' ], 30 ); // After WooCommerce.

			add_action( 'login_enqueue_scripts', [ &$this, 'login_enqueue_script' ], 30 );
			add_action( 'enqueue_block_editor_assets', [ &$this, 'block_editor_assets' ] );
		}

		/** ---------------------------------------- */

		/**
		 * Init function
		 *
		 * @return void
		 */
		public function init() {
			( new Hook );
			( new Customizer ); // Customizer additions.
			( new Shortcode );

			if ( is_admin() ) {
				( new Admin );
			} else {
                ( new Fonts );
				( new Defer );
			}
		}

		/** ---------------------------------------- */

		/**
		 * Sets up theme defaults and registers support for various WordPress features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 */
		public function after_setup_theme() {
			/**
			 * Make theme available for translation.
			 * Translations can be filed at WordPress.org.
			 * See: https://translate.wordpress.org/projects/wp-themes/hello-elementor
			 */
			load_theme_textdomain( 'hd', trailingslashit( WP_LANG_DIR ) . 'themes/' );
			load_theme_textdomain( 'hd', get_stylesheet_directory() . '/languages' );
			load_theme_textdomain( 'hd', get_template_directory() . '/languages' );

			// Add theme support for various features.
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'post-thumbnails' );
			//add_theme_support( 'post-formats', [ 'aside', 'image', 'gallery', 'video', 'quote', 'link', 'status' ] );
			add_theme_support( 'title-tag' );
			add_theme_support( 'html5', [
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'script',
				'style',
				'navigation-widgets'
			] );
			add_theme_support( 'customize-selective-refresh-widgets' );
			add_theme_support( 'align-wide' );
			add_theme_support( 'responsive-embeds' );

			// Add support for block styles.
			add_theme_support( 'wp-block-styles' );

			// Enqueue editor styles.
			// This theme styles the visual editor to resemble the theme style.
			add_theme_support( 'editor-styles' );
			add_editor_style( get_template_directory_uri() . "/assets/css/editor-style.css" );

			// Remove Template Editor support until WP 5.9 since more Theme Blocks are going to be introduced.
			remove_theme_support( 'block-templates' );

			// Enable excerpt to page
			add_post_type_support( 'page', 'excerpt' );

			if ( apply_filters( 'fullwidth_oembed', true ) ) {

				// Filters the oEmbed process to run the responsive_oembed_wrapper() function.
				add_filter( 'embed_oembed_html', [ &$this, 'responsive_oembed_wrapper' ], 10, 3 );
			}

			// Set default values for the upload media box
			update_option( 'image_default_align', 'center' );
			update_option( 'image_default_size', 'large' );

			/**
			 * Add support for core custom logo.
			 *
			 * @link https://codex.wordpress.org/Theme_Logo
			 */
			$logo_height = 120;
			$logo_width  = 240;

			add_theme_support(
				'custom-logo',
				apply_filters(
					'custom_logo_args',
					[
						'height'      => $logo_height,
						'width'       => $logo_width,
						'flex-height' => true,
						'flex-width'  => true,
						'unlink-homepage-logo' => false,
					]
				)
			);

			// Adds `async`, `defer` and attribute support for scripts registered or enqueued by the theme.
			$loader = new ScriptLoader;
			add_filter( 'script_loader_tag', [ &$loader, 'filterScriptTag' ], 10, 3 );
		}

		/** ---------------------------------------- */

		/**
		 * Add CSS for third-party plugins.
         *
		 * @return void
		 */
		public function enqueue_inline_css() {
			$css = new Css;

            // header bg
            $header_bg = get_theme_mod_ssl( 'header_bg_setting' );
            $header_bgcolor = get_theme_mod_ssl( 'header_bgcolor_setting' );

            if ( $header_bg ) {
                $css->set_selector( '.inside-header::before' );
                $css->add_property( 'background-image', 'url(' . $header_bg . ')' );
            }

            if ($header_bgcolor) {
                $css->set_selector( '.inside-header' );
                $css->add_property( 'background-color', $header_bgcolor );
            }

			// footer bg
			$footer_bg = get_theme_mod_ssl( 'footer_bg_setting' );
			$footer_bgcolor = get_theme_mod_ssl( 'footer_bgcolor_setting' );

			if ( $footer_bg ) {
				$css->set_selector( '.site-footer::before' );
				$css->add_property( 'background-image', 'url(' . $footer_bg . ')' );
			}

            if ($footer_bgcolor) {
                $css->set_selector( '.site-footer' );
                $css->add_property( 'background-color', $footer_bgcolor );
            }

			// breadcrumbs bg
			$breadcrumb_bg = get_theme_mod_ssl( 'breadcrumb_bg_setting' );
			if ( $breadcrumb_bg ) {
				$css->set_selector( 'section.section-title>.title-bg' );
				$css->add_property( 'background-image', 'url(' . $breadcrumb_bg . ')' );
			}

			if ( $css->css_output() ) {
				wp_add_inline_style( 'app-style', $css->css_output() );
			}
		}

		/** ---------------------------------------- */

		/**
		 * Enqueue non-latin language styles
		 *
		 * @return void
		 */
		public function non_latin_languages() {
			$custom_css = $this->_get_non_latin_css();
			if ( $custom_css ) {
				wp_add_inline_style( 'app-style', $custom_css );
			}
		}

		/** ---------------------------------------- */

		/**
		 * Enqueue scripts and styles
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			// stylesheet.
			wp_register_style( "plugin-style", get_template_directory_uri() . '/assets/css/plugins.css', [], W_THEME_VERSION );
            wp_register_style( "layout-style", get_template_directory_uri() . '/assets/css/layout.css', [ "plugin-style" ], W_THEME_VERSION );

            wp_enqueue_style( "app-style", get_template_directory_uri() . '/assets/css/app.css', [ "layout-style" ], W_THEME_VERSION );

			// scripts.
			wp_enqueue_script( "app", get_template_directory_uri() . "/assets/js/app.js", [ "jquery" ], W_THEME_VERSION, true );
			wp_script_add_data( "app", "defer", true );

			// inline js
			$l10n = [
				'ajaxUrl'  => esc_url( admin_url( 'admin-ajax.php' ) ),
				'baseUrl'  => trailingslashit( site_url() ),
				'themeUrl' => trailingslashit( get_template_directory_uri() ),
				'locale'   => get_f_locale(),
				'lang'     => get_lang(),
				//'domain'    => DOMAIN_CURRENT_SITE,
                'lg' => [
                    'view_more' => __( 'View more', 'hd' ),
                    'view_detail' => __( 'Detail', 'hd' )
                ]
			];

			wp_localize_script( 'jquery-core', 'HD', $l10n );

			/*comments*/
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}
		}

		/** ---------------------------------------- */

		/**
		 * Gutenberg editor
		 *
		 * @return void
		 */
		public function block_editor_assets() {
			wp_enqueue_style( 'editor-style', get_template_directory_uri() . "/assets/css/editor-style.css" );
		}

		/** ---------------------------------------- */

		/**
		 * Adds a responsive embed wrapper around oEmbed content
		 *
		 * @param string $html The oEmbed markup.
		 * @param string $url The URL being embedded.
		 * @param array $attr An array of attributes.
		 *
		 * @return string Updated embed markup.
		 */
		public function responsive_oembed_wrapper( $html, $url, $attr ) {
			$add_oembed_wrapper = apply_filters( 'responsive_oembed_wrapper_enable', true );
			$allowed_providers  = apply_filters(
				'allowed_fullwidth_oembed_providers',
				[
					'vimeo.com',
					'youtube.com',
					'youtu.be',
					'wistia.com',
					'wistia.net',
				]
			);

			if ( Str::strposOffset( $url, $allowed_providers ) ) {
				if ( $add_oembed_wrapper ) {
					$html = ( '' !== $html ) ? '<div class="oembed-container">' . $html . '</div>' : '';
				}
			}

			return $html;
		}

		/** ---------------------------------------- */

		/**
		 * @retun void
		 */
		public function login_enqueue_script() {
			wp_enqueue_style( "login-style", get_template_directory_uri() . "/assets/css/admin.css", [], W_THEME_VERSION );
			wp_enqueue_script( "login", get_template_directory_uri() . "/assets/js/login.js", [ "jquery" ], W_THEME_VERSION, true );

			// custom script/style
			$logo    = get_theme_file_uri( "/assets/img/logo.png" );
			$logo_bg = get_theme_file_uri( "/assets/img/login-bg.jpg" );

			$css = new Css;
			if ( $logo_bg ) {
				$css->set_selector( 'body.login' );
				$css->add_property( 'background-image', 'url(' . $logo_bg . ')' );
			}
			if ( $logo ) {
				$css->set_selector( 'body.login #login h1 a' );
				$css->add_property( 'background-image', 'url(' . $logo . ')' );
			} else {
				$css->set_selector( 'body.login #login h1 a' );
				$css->add_property( 'background-image', 'unset' );
			}

			if ( $css->css_output() ) {
				wp_add_inline_style( 'login-style', $css->css_output() );
			}
		}

		/** ---------------------------------------- */

		/**
		 * @param string $type
		 *
		 * @return string
		 */
		private function _get_non_latin_css( string $type = 'front-end' ) {

			// Fetch site locale.
			$locale = get_bloginfo( 'language' );

			// Define fallback fonts for non-latin languages.
			$font_family = apply_filters(
				'w_get_localized_font_family_types',
				[
					// Chinese Simplified (China) - Noto Sans SC.
					'zh-CN' => [
						'\'PingFang SC\'',
						'\'Helvetica Neue\'',
						'\'Microsoft YaHei New\'',
						'\'STHeiti Light\'',
						'sans-serif'
					],

					// Chinese Traditional (Taiwan) - Noto Sans TC.
					'zh-TW' => [
						'\'PingFang TC\'',
						'\'Helvetica Neue\'',
						'\'Microsoft YaHei New\'',
						'\'STHeiti Light\'',
						'sans-serif'
					],

					// Chinese (Hong Kong) - Noto Sans HK.
					'zh-HK' => [
						'\'PingFang HK\'',
						'\'Helvetica Neue\'',
						'\'Microsoft YaHei New\'',
						'\'STHeiti Light\'',
						'sans-serif'
					],

					// Korean.
					'ko-KR' => [
						'\'Apple SD Gothic Neo\'',
						'\'Malgun Gothic\'',
						'\'Nanum Gothic\'',
						'Dotum',
						'sans-serif'
					],

					// Thai.
					'th'    => [ '\'Sukhumvit Set\'', '\'Helvetica Neue\'', 'Helvetica', 'Arial', 'sans-serif' ],
				]
			);

			// Return if the selected language has no fallback fonts.
			if ( empty( $font_family[ $locale ] ) ) {
				return '';
			}

			// Define elements to apply fallback fonts to.
			$elements = apply_filters(
				'w_get_localized_font_family_elements',
				[
					'front-end' => [
						'body',
						'input',
						'textarea',
						'button',
						'.button',
						'.faux-button',
						'.wp-block-button__link',
						'.wp-block-file__button',
						'.has-drop-cap:not(:focus)::first-letter',
						'.has-drop-cap:not(:focus)::first-letter',
						'.entry-content .wp-block-archives',
						'.entry-content .wp-block-categories',
						'.entry-content .wp-block-cover-image',
						'.entry-content .wp-block-latest-comments',
						'.entry-content .wp-block-latest-posts',
						'.entry-content .wp-block-pullquote',
						'.entry-content .wp-block-quote.is-large',
						'.entry-content .wp-block-quote.is-style-large',
						'.entry-content .wp-block-archives *',
						'.entry-content .wp-block-categories *',
						'.entry-content .wp-block-latest-posts *',
						'.entry-content .wp-block-latest-comments *',
						'.entry-content p',
						'.entry-content ol',
						'.entry-content ul',
						'.entry-content dl',
						'.entry-content dt',
						'.entry-content cite',
						'.entry-content figcaption',
						'.entry-content .wp-caption-text',
						'.comment-content p',
						'.comment-content ol',
						'.comment-content ul',
						'.comment-content dl',
						'.comment-content dt',
						'.comment-content cite',
						'.comment-content figcaption',
						'.comment-content .wp-caption-text',
						'.widget_text p',
						'.widget_text ol',
						'.widget_text ul',
						'.widget_text dl',
						'.widget_text dt',
						'.widget-content .rssSummary',
						'.widget-content cite',
						'.widget-content figcaption',
						'.widget-content .wp-caption-text'
					],
				]
			);

			// Return if the specified type doesn't exist.
			if ( empty( $elements[ $type ] ) ) {
				return '';
			}

			// Return the specified styles.
			$css = new Css;
			$css->set_selector( implode( ',', $elements[ $type ] ) );
			$css->add_property( 'font-family', implode( ',', $font_family[ $locale ] ) );

			return $css->css_output();
		}
	}
}