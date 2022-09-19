<?php

namespace Webhd\PostTypes;

\defined( '\WPINC' ) || die;

if ( ! class_exists( 'Banner_PostType' ) ) {

	class Banner_PostType {
		public function __construct() {
			add_action( 'init', [ &$this, 'banner_post_type' ], 10 );
			add_action( 'init', [ &$this, 'banner_category' ], 10 );

			//$this->_localFields();
		}

        /***********************************************/

        /**
         * @return void
         */
		public function banner_post_type() {
			$labels = [
				'name'                  => __( 'Banners', 'hd' ),
				'singular_name'         => __( 'Banner', 'hd' ),
				'menu_name'             => __( 'Banners', 'hd' ),
				'name_admin_bar'        => __( 'Banners', 'hd' ),
				'archives'              => __( 'Item Archives', 'hd' ),
				'attributes'            => __( 'Item Attributes', 'hd' ),
				'parent_item_colon'     => __( 'Parent Item:', 'hd' ),
				'all_items'             => __( 'All Banners', 'hd' ),
				'add_new_item'          => __( 'Add New Item', 'hd' ),
				'add_new'               => __( 'Add New', 'hd' ),
				'new_item'              => __( 'New Item', 'hd' ),
				'edit_item'             => __( 'Edit Item', 'hd' ),
				'update_item'           => __( 'Update Item', 'hd' ),
				'view_item'             => __( 'View Item', 'hd' ),
				'view_items'            => __( 'View Items', 'hd' ),
				'search_items'          => __( 'Search Items', 'hd' ),
				'not_found'             => __( 'Not found', 'hd' ),
				'not_found_in_trash'    => __( 'Not found in Trash', 'hd' ),
				'featured_image'        => __( 'Featured Image', 'hd' ),
				'set_featured_image'    => __( 'Set featured image', 'hd' ),
				'remove_featured_image' => __( 'Remove featured image', 'hd' ),
				'use_featured_image'    => __( 'Use as featured image', 'hd' ),
				'insert_into_item'      => __( 'Insert Item', 'hd' ),
				'uploaded_to_this_item' => __( 'Uploaded to this item', 'hd' ),
				'items_list'            => __( 'Items list', 'hd' ),
				'items_list_navigation' => __( 'Items list navigation', 'hd' ),
				'filter_items_list'     => __( 'Filter items list', 'hd' ),
			];
			$args   = [
				'label'               => __( 'Banners', 'hd' ),
				'description'         => __( 'Banners Post Type Description', 'hd' ),
				'labels'              => $labels,
				'supports'            => [ 'title', 'editor', 'thumbnail', 'page-attributes' ],
				'taxonomies'          => [ 'banner_cat' ],
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 12,
				'menu_icon'           => 'dashicons-format-image',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				'capability_type'     => 'post',
				'rewrite'             => [ 'slug' => 'banner' ],
				'show_in_rest'        => true,
			];

			register_post_type( 'banner', $args );
		}

        /***********************************************/

        /**
         * @return void
         */
		public function banner_category() {
			$labels  = [
				'name'                       => __( 'Banner Categories', 'hd' ),
				'singular_name'              => __( 'Banner Category', 'hd' ),
				'menu_name'                  => __( 'Banner Categories', 'hd' ),
				'all_items'                  => __( 'All Items', 'hd' ),
				'parent_item'                => __( 'Parent Item', 'hd' ),
				'parent_item_colon'          => __( 'Parent Item:', 'hd' ),
				'new_item_name'              => __( 'New Item Name', 'hd' ),
				'add_new_item'               => __( 'Add New Category', 'hd' ),
				'edit_item'                  => __( 'Edit Item', 'hd' ),
				'update_item'                => __( 'Update Item', 'hd' ),
				'view_item'                  => __( 'View Item', 'hd' ),
				'separate_items_with_commas' => __( 'Separate items with commas', 'hd' ),
				'add_or_remove_items'        => __( 'Add or remove items', 'hd' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'hd' ),
				'popular_items'              => __( 'Popular Items', 'hd' ),
				'search_items'               => __( 'Search Items', 'hd' ),
				'not_found'                  => __( 'Not Found', 'hd' ),
				'no_terms'                   => __( 'No items', 'hd' ),
				'items_list'                 => __( 'Items list', 'hd' ),
				'items_list_navigation'      => __( 'Items list navigation', 'hd' ),
			];
			$rewrite = [
				'slug'         => 'banners',
				'with_front'   => false,
				'hierarchical' => false,
			];
			$args    = [
				'labels'            => $labels,
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_tagcloud'     => false,
				'rewrite'           => $rewrite,
				'show_in_rest'      => true,
			];

			register_taxonomy( 'banner_cat', [ 'banner' ], $args );
		}

        /***********************************************/

		/**
		 * @retun void
		 */
		private function _localFields() {}
	}
}