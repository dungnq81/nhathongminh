<?php

namespace Webhd\Plugins;

/**
 * RankMath Plugins
 *
 * @author   WEBHD
 */

\defined('\WPINC') || die;

// If plugin - 'RankMath' not exist then return.
if (!class_exists('\RankMath')) {
    return;
}

if (!class_exists('RankMath_Plugin')) {
    class RankMath_Plugin
    {
        public function __construct()
        {
            add_action('after_setup_theme', [&$this, 'setup_theme']);
            add_filter('rank_math/frontend/breadcrumb/args', [&$this, 'breadcrumb_args']);
        }

        /** ---------------------------------------- */

        /**
         * @return void
         */
        public function setup_theme()
        {
            // Rank Math Breadcrumb.
            add_theme_support('rank-math-breadcrumbs');
        }

        /** ---------------------------------------- */

        /**
         * @param $args
         *
         * @return string[]
         */
        public function breadcrumb_args($args)
        {
            $args = [
                'delimiter' => '',
                'wrap_before' => '<ul id="crumbs" class="breadcrumbs" aria-label="breadcrumbs">',
                'wrap_after' => '</ul>',
                'before' => '<li><span property="itemListElement" typeof="ListItem">',
                'after' => '</span></li>',
            ];

            return $args;
        }
    }
}