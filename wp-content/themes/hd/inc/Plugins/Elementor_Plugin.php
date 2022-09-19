<?php

namespace Webhd\Plugins;

\defined( '\WPINC' ) || die;

// If plugin - 'Elementor' not exist then return.
if ( ! class_exists( '\Elementor\Plugin' ) ) {
    return;
}

use Elementor\Plugin;

if ( ! class_exists( 'Elementor_Plugin' ) ) {
    class Elementor_Plugin {
        public function __construct() {

            // load google fonts later
            add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );
            add_action( 'elementor/theme/register_locations', [ &$this, 'register_locations' ] );

            add_filter( 'elementor_page_title', [ &$this, 'check_hide_title' ], 10, 1 );
            add_action( "wp_enqueue_scripts", [ &$this, 'enqueue_scripts' ], 100 );
        }

        /**
         * Elementor Enqueue styles and scripts
         */
        public function enqueue_scripts() {

            wp_enqueue_style( "elementor-style", get_template_directory_uri() . '/assets/css/elementor.css', [ "layout-style" ], W_THEME_VERSION );

            // load awesome font later
            wp_deregister_style( "elementor-icons-fa-solid" );
            wp_deregister_style( "elementor-icons-fa-regular" );
            wp_deregister_style( "elementor-icons-fa-brands" );

            wp_deregister_style( "fontawesome" );
        }

        /**
         * Register Elementor Locations
         *
         * @param object $manager Location manager
         * @return void
         */
        public function register_locations( $manager ) {
            $manager->register_all_core_location();
        }

        /**
         * @param $val
         *
         * @return false|mixed
         */
        public function check_hide_title( $val ) {
            $current_doc = Plugin::instance()->documents->get( get_the_ID() );
            if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
                $val = false;
            }

            return $val;
        }

        /**
         * Check is elementor activated
         *
         * @param int $id Post/Page Id
         * @return boolean
         */
        public function is_elementor_activated( $id ) {
            return Plugin::$instance->documents->get( $id )->is_built_with_elementor();
        }

        /**
         * Check if Elementor Editor is open.
         * @return boolean True IF Elementor Editor is loaded, False If Elementor Editor is not loaded.
         *
         */
        public function is_elementor_editor() {
            if ( ( isset( $_REQUEST['action'] ) && 'elementor' == $_REQUEST['action'] ) || isset( $_REQUEST['elementor-preview'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                return true;
            }

            return false;
        }
    }
}