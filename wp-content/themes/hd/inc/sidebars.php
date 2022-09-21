<?php
/**
 * Sidebars functions
 *
 * @author   WEBHD
 */
\defined( '\WPINC' ) || die;

if ( ! function_exists( '__register_sidebars' ) ) {
	/**
	 * Register widget area.
	 *
	 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
	 */
	function __register_sidebars() {

		// homepage
		register_sidebar(
			[
				'container'     => false,
				'id'            => 'w-homepage-sidebar',
				'name'          => __( 'Home Page', 'hd' ),
				'description'   => __( 'Widgets added here will appear in homepage.', 'hd' ),
				'before_widget' => '<div class="%2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<span>',
				'after_title'   => '</span>',
			]
		);

        // trang gioi thieu
        register_sidebar(
            [
                'container'     => false,
                'id'            => 'w-about-us-sidebar',
                'name'          => __( 'About Us', 'hd' ),
                'description'   => __( 'Widgets added here will appear in about us.', 'hd' ),
                'before_widget' => '<div class="%2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<span>',
                'after_title'   => '</span>',
            ]
        );

        register_sidebar(
            [
                'container'     => false,
                'id'            => 'w-header-sidebar',
                'name'          => __( 'Header', 'hd' ),
                'description'   => __( 'Widgets added here will appear in right header.', 'hd' ),
                'before_widget' => '<div class="header-widgets %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<span>',
                'after_title'   => '</span>',
            ]
        );

		// top footer
		register_sidebar(
			[
				'container'     => false,
				'id'            => 'w-topfooter-sidebar',
				'name'          => __( 'Top Footer', 'hd' ),
				'description'   => __( 'Widgets added here will appear in top footer.', 'hd' ),
				'before_widget' => '<div class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<span>',
				'after_title'   => '</span>',
			]
		);

        // shop sidebar
        register_sidebar(
            [
                'container'     => false,
                'id'            => 'w-shop-sidebar',
                'name'        => __('Shop Page', 'hd'),
                'description' => __('Widgets added here will appear in shop sidebar.', 'hd'),
                'before_widget' => '<div class="%2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<span>',
                'after_title'   => '</span>',
            ]
        );

        // Review
        register_sidebar(
            [
                'container'     => false,
                'id'            => 'w-review-sidebar',
                'name'        => __('Review Page', 'hd'),
                'description' => __('Widgets added here will appear in "review" page.', 'hd'),
                'before_widget' => '<div class="%2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<span>',
                'after_title'   => '</span>',
            ]
        );

        // Product Infomations Sidebar
        register_sidebar(
            [
                'container'     => false,
                'id'            => 'w-product-desc-sidebar',
                'name'        => __('Products Infomation', 'hd'),
                'description' => __('Widgets added here will appear in products infomation sidebar.', 'hd'),
                'before_widget' => '<div class="%2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<span>',
                'after_title'   => '</span>',
            ]
        );

        // Product Desc Sidebar
        register_sidebar(
            [
                'container'     => false,
                'id'            => 'w-product-excerpt-sidebar',
                'name'        => __('Products Description', 'hd'),
                'description' => __('Widgets added here will appear in products description sidebar.', 'hd'),
                'before_widget' => '<div class="%2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<span>',
                'after_title'   => '</span>',
            ]
        );

        // Product Attributes
        register_sidebar(
            [
                'container'     => false,
                'id'            => 'w-product-attributes-sidebar',
                'name'        => __('Product Attributes', 'hd'),
                'description' => __('Widgets added here will appear in product archives sidebar.', 'hd'),
                'before_widget' => '<div class="%2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<span>',
                'after_title'   => '</span>',
            ]
        );

        // Fixed sidebar
        register_sidebar(
            [
                'container'     => false,
                'id'            => 'w-fixed-sidebar',
                'name'        => __('Fixed Sidebar', 'hd'),
                'description' => __('Widgets added here will appear in fixed sidebar.', 'hd'),
                'before_widget' => '<div class="%2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<span>',
                'after_title'   => '</span>',
            ]
        );

		// footer columns
		$footer_args = [];

		$rows    = (int) get_theme_mod_ssl( 'footer_row_setting' );
		$regions = (int) get_theme_mod_ssl( 'footer_col_setting' );
		for ( $row = 1; $row <= $rows; $row ++ ) {
			for ( $region = 1; $region <= $regions; $region ++ ) {
				$footer_n = $region + $regions * ( $row - 1 ); // Defines footer sidebar ID.
				$footer   = sprintf( 'footer_%d', $footer_n );
				if ( 1 === $rows ) {

					/* translators: 1: column number */
					$footer_region_name = sprintf( __( 'Footer Column %1$d', 'hd' ), $region );

					/* translators: 1: column number */
					$footer_region_description = sprintf( __( 'Widgets added here will appear in column %1$d of the footer.', 'hd' ), $region );
				} else {

					/* translators: 1: row number, 2: column number */
					$footer_region_name = sprintf( __( 'Footer Row %1$d - Column %2$d', 'hd' ), $row, $region );

					/* translators: 1: column number, 2: row number */
					$footer_region_description = sprintf( __( 'Widgets added here will appear in column %1$d of footer row %2$d.', 'hd' ), $region, $row );
				}

				$footer_args[ $footer ] = [
					'name'        => $footer_region_name,
					'id'          => sprintf( 'w-footer-%d', $footer_n ),
					'description' => $footer_region_description,
				];
			}
		}

		foreach ( $footer_args as $args ) {
			$footer_tags = [
				'container'     => false,
				'before_widget' => '<div class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<p class="widget-title h6">',
				'after_title'   => '</p>',
			];

			register_sidebar( $args + $footer_tags );
		}

        // product sidebar
        register_sidebar(
            [
                'container'     => false,
                'id'            => 'w-product-sidebar',
                'name'        => __('Product Sidebar', 'hd'),
                'description' => __('Widgets added here will appear in product sidebar.', 'hd'),
                'before_widget' => '<aside class="sidebar %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h6 class="sidebar-title">',
                'after_title'   => '</h6>',
            ]
        );

        // news sidebar
        register_sidebar(
            [
                'container'     => false,
                'id'            => 'w-news-sidebar',
                'name'        => __('News Sidebar', 'hd'),
                'description' => __('Widgets added here will appear in news sidebar.', 'hd'),
                'before_widget' => '<aside class="sidebar %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h6 class="sidebar-title">',
                'after_title'   => '</h6>',
            ]
        );
	}

	/** */
	add_action( 'widgets_init', '__register_sidebars', 10 );
}