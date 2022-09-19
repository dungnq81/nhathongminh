<?php

/**
 * The site's entry point.
 *
 * Loads the relevant template part,
 * the loop is executed (when needed) by the relevant template part.
 */

\defined( '\WPINC' ) || die; // Exit if accessed directly.

get_header();

$queried_object = get_queried_object();

if ( is_singular() ) { // check if singular
	if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {
		if ( is_single() ) {
			$templates = [];
			if ( isset( $queried_object->post_type ) && '' !== $post_type = (string) $queried_object->post_type ) {
				$templates[] = "template-parts/single-" . $post_type . ".php";
			}

			$templates[] = 'template-parts/single.php';
			locate_template( $templates, true );
			unset( $templates );

		} elseif ( is_page() ) {
			get_template_part( 'template-parts/page' );
		}
	}
} elseif ( is_archive() || is_home() ) {  // check if archive page or posts page
	if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) {
		$templates = [];

		// taxonomy
		if ( isset( $queried_object->taxonomy ) && '' !== $taxonomy = (string) $queried_object->taxonomy ) {
			$templates[] = "template-parts/taxonomy-" . $taxonomy . ".php";
			$templates[] = "template-parts/taxonomy.php";
		}

		$templates[] = 'template-parts/archive.php';
		locate_template( $templates, true );
		unset( $templates );
	}
} elseif ( is_search() ) {
	if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) {
		//get_template_part( 'template-parts/search' );

        $templates = [];
        $templates[] = "template-parts/search.php";
        $templates[] = 'template-parts/archive.php';
        locate_template( $templates, true );
        unset( $templates );
	}
} else {
	if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {
		get_template_part( 'template-parts/404' );
	}
}

get_footer();