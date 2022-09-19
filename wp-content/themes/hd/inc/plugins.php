<?php
/**
 * Plugins functions
 * @author WEBHD
 */

\defined( '\WPINC' ) || die;

use Webhd\Plugins\ACF\ACF_Plugin;
use Webhd\Plugins\CF7_Plugin;
use Webhd\Plugins\RankMath_Plugin;
use Webhd\Plugins\LiteSpeed_Plugin;
use Webhd\Plugins\Elementor_Plugin;
use Webhd\Plugins\Woocommerce\Woocommerce_Plugin;

class_exists( '\ACF' ) && class_exists( ACF_Plugin::class ) && ( new ACF_Plugin );
class_exists( '\WPCF7' ) && class_exists( CF7_Plugin::class ) && ( new CF7_Plugin );
class_exists( '\RankMath' ) && class_exists( RankMath_Plugin::class ) && ( new RankMath_Plugin );
class_exists( '\Elementor\Plugin' ) && class_exists( Elementor_Plugin::class ) && ( new Elementor_Plugin );
class_exists( '\WooCommerce' ) && class_exists( Woocommerce_Plugin::class ) && ( new Woocommerce_Plugin );

defined( 'LSCWP_BASENAME' ) && class_exists( LiteSpeed_Plugin::class ) && ( new LiteSpeed_Plugin );