<?php

namespace Webhd\Themes;

/**
 * Deferred Class
 * @author   WEBHD
 */

\defined( '\WPINC' ) || die;

if ( ! class_exists( 'Defer' ) ) {
	class Defer {
		public function __construct() {

			$this->_cleanup();

			add_filter( 'body_class', [ &$this, 'body_classes' ], 10, 1 );
			add_filter( 'post_class', [ &$this, 'post_classes' ], 10, 1 );
			add_filter( 'nav_menu_css_class', [ &$this, 'nav_menu_css_class' ], 10, 2 );

			add_action( 'wp_footer', [ &$this, 'deferred_scripts' ], 1000 );

			add_filter( 'script_loader_tag', [ &$this, 'script_loader_tag' ], 11, 3 );
			add_filter( 'style_loader_tag', [ &$this, 'style_loader_tag' ], 11, 2 );

			add_action( 'wp_default_scripts', [ &$this, 'remove_jquery_migrate' ] );
			add_action( 'wp_enqueue_scripts', [ &$this, 'enqueue_scripts' ], 1001 );
		}

		/** ---------------------------------------- */

		/**
		 * Launching operation cleanup.
		 */
		protected function _cleanup() {

			// Xóa widget mặc định "Welcome to WordPress".
			remove_action( 'welcome_panel', 'wp_welcome_panel' );

			// wp_head
			remove_action( 'wp_head', 'rsd_link' ); // Remove the EditURI/RSD link
			remove_action( 'wp_head', 'wlwmanifest_link' ); // Remove Windows Live Writer Manifest link
			remove_action( 'wp_head', 'wp_shortlink_wp_head' ); // Remove the shortlink
			remove_action( 'wp_head', 'wp_generator' ); // remove WordPress Generator
			remove_action( 'wp_head', 'feed_links_extra', 3 ); //remove comments feed.
			remove_action( 'wp_head', 'adjacent_posts_rel_link' ); // Remove relational links for the posts adjacent to the current post.
			remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' ); // Remove prev and next links
			remove_action( 'wp_head', 'parent_post_rel_link' );
			remove_action( 'wp_head', 'start_post_rel_link' );
			remove_action( 'wp_head', 'index_rel_link' );
			remove_action( 'wp_head', 'feed_links', 2 );

			/**
			 * Remove wp-json header from WordPress
			 * Note that the REST API functionality will still be working as it used to;
			 * this only removes the header code that is being inserted.
			 */
			remove_action( 'wp_head', 'rest_output_link_wp_head' );
			remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
			remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );

			// all actions related to emojis
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );

			// Emoji detection script.
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );

			// staticize_emoji
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		}

		/** ---------------------------------------- */

		/**
		 * Adds custom classes to the array of body classes.
		 *
		 * @param array $classes Classes for the body element.
		 *
		 * @return array
		 */
		public function body_classes( $classes ) {

			// Check whether we're in the customizer preview.
			if ( is_customize_preview() ) {
				$classes[] = 'customizer-preview';
			}

			foreach ( $classes as $class ) {
				if (
					str_contains( $class, 'page-template-templates' )
					|| str_contains( $class, 'page-template-templatespage-homepage-php' )
					|| str_contains( $class, 'wp-custom-logo' )
				) {
					$classes = array_diff( $classes, [ $class ] );
				}
			}

            if ((is_home() || is_front_page() && class_exists( '\WooCommerce' ))) {
                $classes[] = 'woocommerce';
            }

			// dark mode func
			//$classes[] = 'light-mode';

			return $classes;
		}

		/** ---------------------------------------- */

		/**
		 * Adds custom classes to the array of post classes.
		 *
		 * @param array $classes Classes for the post element.
		 *
		 * @return array
		 */
		public function post_classes( $classes ) {

			// remove_sticky_class
			if ( in_array( 'sticky', $classes ) ) {
				$classes   = array_diff( $classes, [ "sticky" ] );
				$classes[] = 'wp-sticky';
			}

			// remove tag-, category- classes
			foreach ( $classes as $class ) {
				if (
					str_contains( $class, 'tag-' )
					|| str_contains( $class, 'category-' )
					//|| str_contains( $class, 'video_cat-' )
					//|| str_contains( $class, 'project_cat-' )
					|| str_contains( $class, 'product_cat-' )
					//|| str_contains( $class, 'gallery_cat-' )
					//|| str_contains( $class, 'service_cat-' )
					//|| str_contains( $class, 'video_tag-' )
					//|| str_contains( $class, 'project_tag-' )
					|| str_contains( $class, 'product_tag-' )
					//|| str_contains( $class, 'gallery_tag-' )
					//|| str_contains( $class, 'service_tag-' )
				) {
					$classes = array_diff( $classes, [ $class ] );
				}
			}

			return $classes;
		}

		/** ---------------------------------------- */

		/**
		 * Add 'is-active' class for the current menu item.
		 *
		 * @param $classes
		 * @param $item
		 *
		 * @return array
		 */
		public function nav_menu_css_class( $classes, $item ) {
			if ( ! is_array( $classes ) ) {
				$classes = [];
			}

			// remove menu-item-type-, menu-item-object- classes
			foreach ( $classes as $class ) {
				if ( str_contains($class, 'menu-item-type-')
				     || str_contains($class, 'menu-item-object-')
				) {
					$classes = array_diff( $classes, [ $class ] );
				}
			}

			if ( 1 == $item->current
			     || $item->current_item_ancestor
			     || $item->current_item_parent
			) {
				//$classes[] = 'is-active';
				$classes[] = 'active';
			}

			return $classes;
		}

		/** ---------------------------------------- */

		public function enqueue_scripts() {

			/*extra scripts*/
			wp_enqueue_script( "draggable", get_template_directory_uri() . "/assets/js/plugins/draggable.js", [], false, true );
			wp_enqueue_script( "backtop", get_template_directory_uri() . "/assets/js/plugins/backtop.js", [], false, true );
			wp_enqueue_script( "shares", get_template_directory_uri() . "/assets/js/plugins/shares.min.js", [ "jquery" ], false, true );

			//$widgets_block_editor_off = get_theme_mod_ssl( 'use_widgets_block_editor_setting' );
			$gutenberg_widgets_off = get_theme_mod_ssl( 'gutenberg_use_widgets_block_editor_setting' );
			$gutenberg_off = get_theme_mod_ssl( 'use_block_editor_for_post_type_setting' );
			if ( $gutenberg_widgets_off && $gutenberg_off ) {
				wp_dequeue_style( 'wp-block-library' );
				wp_dequeue_style( 'wp-block-library-theme' );
			}
		}

		/** ---------------------------------------- */

		/**
		 * @param $scripts
		 */
		public function remove_jquery_migrate( $scripts ) {
			if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
				$script = $scripts->registered['jquery'];
				if ( $script->deps ) {
					// Check whether the script has any dependencies
					$script->deps = array_diff( $script->deps, [ 'jquery-migrate' ] );
				}
			}
		}

		/** ---------------------------------------- */

		/**
		 * @param string $tag
		 * @param string $handle
		 * @param string $src
		 *
		 * @return string
		 */
		public function script_loader_tag( string $tag, string $handle, string $src ): string {

			$str_parsed = [];
			$str_parsed = apply_filters( 'defer_script_loader_tag', $str_parsed );

			return lazy_script_tag( $str_parsed, $tag, $handle, $src );
		}

		/** ---------------------------------------- */

		/**
		 * @param string $html
		 * @param string $handle
		 *
		 * @return string
		 */
		public function style_loader_tag( string $html, string $handle ): string {

			// add style handles to the array below
			$styles = [];
			$styles = apply_filters( 'defer_style_loader_tag', $styles );

			return lazy_style_tag( $styles, $html, $handle );
		}

		/** ---------------------------------------- */

		/**
		 * @return void
		 */
		public function deferred_scripts() {

			// Facebook
			$fb_appid  = get_theme_mod_ssl( 'fb_menu_setting' );
			$fb_locale = get_f_locale();
			if ( $fb_appid ) {
				echo "<script>";
				echo "window.fbAsyncInit = function() {FB.init({appId:'" . $fb_appid . "',status:true,xfbml:true,autoLogAppEvents:true,version:'v12.0'});};";
				echo "</script>";
				echo "<script async defer crossorigin=\"anonymous\" data-type='lazy' data-src=\"https://connect.facebook.net/" . $fb_locale . "/sdk.js\"></script>";
			}

			$fb_pageid   = get_theme_mod_ssl( 'fbpage_menu_setting' );
			$fb_livechat = get_theme_mod_ssl( 'fb_chat_setting' );
			if ( $fb_appid && $fb_pageid && $fb_livechat && ! is_customize_preview() ) {
				if ( $fb_pageid ) {
					echo '<script async defer data-type="lazy" data-src="https://connect.facebook.net/' . $fb_locale . '/sdk/xfbml.customerchat.js"></script>';
					$_fb_message = __( 'If you need assistance, please leave a message here. Thanks', 'hd' );
					echo '<div class="fb-customerchat" attribution="setup_tool" page_id="' . $fb_pageid . '" theme_color="#CC3366" logged_in_greeting="' . esc_attr( $_fb_message ) . '" logged_out_greeting="' . esc_attr( $_fb_message ) . '"></div>';
				}
			}

			// Zalo
			$zalo_oaid     = get_theme_mod_ssl( 'zalo_oa_menu_setting' );
			$zalo_livechat = get_theme_mod_ssl( 'zalo_chat_setting' );
			if ( $zalo_oaid ) {
				if ( $zalo_livechat ) {
					echo '<div class="zalo-chat-widget" data-oaid="' . $zalo_oaid . '" data-welcome-message="' . __( 'Rất vui khi được hỗ trợ bạn.', 'hd' ) . '" data-autopopup="0" data-width="350" data-height="420"></div>';
				}

				echo "<script defer data-type='lazy' data-src=\"https://sp.zalo.me/plugins/sdk.js\"></script>";
			}

			/***Set delay timeout milisecond***/
			$timeout   = 5000;
			$inline_js = 'const loadScriptsTimer=setTimeout(loadScripts,' . $timeout . ');const userInteractionEvents=["mouseover","keydown","touchstart","touchmove","wheel"];userInteractionEvents.forEach(function(event){window.addEventListener(event,triggerScriptLoader,{passive:!0})});function triggerScriptLoader(){loadScripts();clearTimeout(loadScriptsTimer);userInteractionEvents.forEach(function(event){window.removeEventListener(event,triggerScriptLoader,{passive:!0})})}';
			$inline_js .= "function loadScripts(){document.querySelectorAll(\"script[data-type='lazy']\").forEach(function(elem){elem.setAttribute(\"src\",elem.getAttribute(\"data-src\"));elem.removeAttribute(\"data-src\");})}";
			echo "\n";
			echo '<script src="data:text/javascript;base64,' . base64_encode( $inline_js ) . '"></script>';
			echo "\n";
		}
	}
}