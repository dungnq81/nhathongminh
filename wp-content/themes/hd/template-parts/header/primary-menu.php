<?php

\defined( '\WPINC' ) || die;

/**
 * Displays main navigation
 *
 * @package WordPress
 */

if (!has_nav_menu('main-nav')) :
	menu_fallback(null);
	return;
endif;

?>
<nav id="primary-nav" class="navigation" role="navigation" aria-label="<?php echo esc_attr__('Primary Navigation', 'hd'); ?>">
	<?php echo main_nav(); ?>
</nav>