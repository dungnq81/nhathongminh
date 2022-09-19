<?php

/**
 * Configure responsive images sizes
 * @package WordPress
 */

\defined( '\WPINC' ) || die;

/**
 * thumbnail (480x0)
 * medium (768x0)
 * large (1024x0)
 *
 * widescreen (1920x9999)
 * post-thumbnail (1200x9999)
 */

// custom thumb
add_image_size( 'widescreen', 1920, 9999, false );
add_image_size( 'post-thumbnail', 1200, 9999, false );

// -----------------------------------------------------------------------

if ( ! function_exists( '__filter_image_sizes' ) ) {
	add_filter( 'intermediate_image_sizes_advanced', '__filter_image_sizes' );

	/**
	 * Disable unwanted image sizes
	 *
	 * @param $sizes
	 * @return mixed
	 */
	function __filter_image_sizes( $sizes ) {
		unset( $sizes['medium_large'] );

		unset( $sizes['1536x1536'] ); // disable 2x medium-large size
		unset( $sizes['2048x2048'] ); // disable 2x large size

		return $sizes;
	}
}

// -----------------------------------------------------------------------

// Disable Scaled
add_filter( 'big_image_size_threshold', '__return_false' );

// -----------------------------------------------------------------------

if ( ! function_exists( '__filter_other_image_sizes' ) ) {
	add_action( 'init', '__filter_other_image_sizes' );

	/**
	 * Disable Other Sizes
	 */
	function __filter_other_image_sizes() {
		remove_image_size( '1536x1536' ); // disable 2x medium-large size
		remove_image_size( '2048x2048' ); // disable 2x large size
	}
}

// -------------------------------------------------------------

if ( ! function_exists( '__remove_thumbnail_dimensions' ) ) {

	add_filter( 'post_thumbnail_html', '__remove_thumbnail_dimensions', 10, 1 );
	add_filter( 'image_send_to_editor', '__remove_thumbnail_dimensions', 10, 1 );
	add_filter( 'the_content', '__remove_thumbnail_dimensions', 10, 1 );

	/**
	 * @param $html
	 *
	 * @return string|string[]|null
	 */
	function __remove_thumbnail_dimensions( $html ) {
		return preg_replace( '/(<img[^>]+)(style=\"[^\"]+\")([^>]+)(>)/', '${1}${3}${4}', $html );
	}
}