<?php

\defined( '\WPINC' ) || die;

use Webhd\Helpers\Url;

/**
 * Displays navigation mobile
 *
 * @package WordPress
 */

$txt_logo = get_option('blogname');
$img_logo = get_theme_mod_ssl('alternative_logo');

$image = ($image = wp_get_attachment_image($img_logo, 'thumbnail')) ? $image : null;
if (empty($image)) :
    $html = sprintf('<a href="%1$s" class="mobile-logo-link" rel="home">%2$s</a>', Url::home(), $txt_logo);
else :
    $html = sprintf('<a href="%1$s" class="mobile-logo-link" rel="home">%2$s</a>', Url::home(), $image);
endif;

?>
<div class="off-canvas position-right" id="offCanvasMenu" data-off-canvas>
    <button class="menu-lines" aria-label="Close" type="button" data-close>
        <span class="menu-txt">Menu</span>
        <span class="line line-1"></span>
        <span class="line line-2"></span>
    </button>
    <div class="title-bar-title"><?php echo $html; ?></div>
    <?php echo mobile_nav(); ?>
</div>