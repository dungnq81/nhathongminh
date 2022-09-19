<?php
/**
 * Menus functions
 * @author   WEBHD
 */
\defined( '\WPINC' ) || die;

if ( ! function_exists( '__register_menus' ) ) {
	function __register_menus() {

		/**
		 * Register Menus
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_nav_menus#Examples
		 */
		register_nav_menus(
			[
				'main-nav'   => __( 'Primary Menu', 'hd' ),
				//'second-nav' => __( 'Secondary Menu', 'hd' ),
				'mobile-nav' => __( 'Handheld Menu', 'hd' ),
				'social-nav' => __( 'Social menu', 'hd' ),
				'policy-nav' => __( 'Terms menu', 'hd' ),
			]
		);
	}

	add_action( 'after_setup_theme', '__register_menus', 10 );
}