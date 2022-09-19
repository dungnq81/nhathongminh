<?php

\defined( '\WPINC' ) || die;

use Webhd\Helpers\Url;

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @since 1.0.0
 */

wp_safe_redirect(Url::home());