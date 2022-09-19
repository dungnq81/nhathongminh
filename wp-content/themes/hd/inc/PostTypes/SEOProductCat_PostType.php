<?php

namespace Webhd\PostTypes;

\defined( '\WPINC' ) || die;

if ( ! class_exists( 'SEOProductCat_PostType' ) ) {

    class SEOProductCat_PostType {
        public function __construct() {
            add_action( 'init', [ &$this, 'seo_productcat_post_type' ], 10 );
        }

        /***********************************************/

        /**
         * @return void
         */
        public function seo_productcat_post_type() {
            $labels = [
                'name'                  => __( 'SEO Product Cat', 'hd' ),
                'singular_name'         => __( 'SEO Product Cat', 'hd' ),
                'menu_name'             => __( 'SEO Product Cat', 'hd' ),
                'name_admin_bar'        => __( 'SEO Product Cat', 'hd' ),
                'archives'              => __( 'Item Archives', 'hd' ),
                'attributes'            => __( 'Item Attributes', 'hd' ),
                'parent_item_colon'     => __( 'Parent Item:', 'hd' ),
                'all_items'             => __( 'All SEO Product Cat', 'hd' ),
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
                'label'               => __( 'SEO Product Cat', 'hd' ),
                'description'         => __( 'SEO Product Cat Post Type Description', 'hd' ),
                'labels'              => $labels,
                'supports'            => [ 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ],
                'taxonomies'          => [],
                'hierarchical'        => false,
                'public'              => true,
                'show_ui'             => true,
                'show_in_menu'        => 'edit.php?post_type=product',
                'menu_icon'           => 'dashicons-admin-page',
                'show_in_admin_bar'   => true,
                'show_in_nav_menus'   => true,
                'can_export'          => true,
                'has_archive'         => false,
                'exclude_from_search' => true,
                'publicly_queryable'  => true,
                'capability_type'     => 'post',
                //'rewrite'             => [ 'slug' => 'seo-product-cat' ],
                'show_in_rest'        => true,
            ];

            register_post_type( 'seo-product-cat', $args );
        }
    }
}