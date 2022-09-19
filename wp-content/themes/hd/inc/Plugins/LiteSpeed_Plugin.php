<?php

namespace Webhd\Plugins;

/**
 * litespeed Plugins
 * @author   WEBHD
 */
\defined( '\WPINC' ) || die;

//LSCWP_BASENAME='litespeed-cache/litespeed-cache.php'
if ( ! defined( 'LSCWP_BASENAME' ) ) {
	exit();
}

if ( ! class_exists( 'LiteSpeed_Plugin' ) ) {
	class LiteSpeed_Plugin {
		public function __construct() {}
	}
}