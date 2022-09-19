<?php
/**
 * register_widget functions
 *
 * @author   WEBHD
 */

use Webhd\PostTypes\Banner_PostType;
use Webhd\PostTypes\SEOProductCat_PostType;

\defined( '\WPINC' ) || die;

class_exists( Banner_PostType::class ) && ( new Banner_PostType );
class_exists( SEOProductCat_PostType::class ) && ( new SEOProductCat_PostType );