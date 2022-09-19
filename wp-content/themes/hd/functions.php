<?php

/**
 * Theme functions and definitions
 *
 * @package hd
 */

use Webhd\Themes\Theme;

// This theme requires WordPress 5.3 or later.
if (version_compare($GLOBALS['wp_version'], '5.3', '<')) {
    require __DIR__ . 'inc/back-compat.php';
}

$theme_version = ($theme_version = wp_get_theme()->get('Version')) ? $theme_version : false;
$theme_author = ($theme_author = wp_get_theme()->get('Author')) ? $theme_author : 'WebHD Team';

defined('W_THEME_VERSION') || define('W_THEME_VERSION', $theme_version);
defined('W_AUTHOR') || define('W_AUTHOR', $theme_author);
defined('W_THEME_PATH') || define('W_THEME_PATH', untrailingslashit(get_template_directory()));
defined('W_THEME_URL') || define('W_THEME_URL', untrailingslashit(esc_url(get_template_directory_uri())));

if (!file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
    wp_die(__('Error locating autoloader. Please run <code>composer install</code>.', 'hd'));
}

require $composer;

// Initialize theme settings.
( new Theme )->init();