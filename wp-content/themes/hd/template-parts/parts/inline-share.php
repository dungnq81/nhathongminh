<?php

\defined( '\WPINC' ) || die;

use Webhd\Helpers\Url;

$fb_appid = get_theme_mod_ssl('fb_menu_setting');
$zalo_oaid = get_theme_mod_ssl('zalo_oa_menu_setting');
if (!$zalo_oaid && !$fb_appid)
	return;

?>
<div class="inline-share">
	<label class="title" data-glyph="">
		<?php _e('Share', 'hd') ?>
	</label>
	<div class="inline-share-groups">
		<?php if ($fb_appid) : ?>
        <div class="fb-share-button" data-href="<?php echo Url::current(); ?>" data-layout="button_count" data-size="small"></div>
		<?php endif;
		if ($zalo_oaid) : ?>
        <div class="zalo-share-button" data-href="<?php echo Url::current(); ?>" data-oaid="<?php echo $zalo_oaid; ?>" data-layout="1" data-color="blue" data-customize=false></div>
        <div class="zalo-follow-only-button" data-oaid="<?php echo $zalo_oaid; ?>"></div>
		<?php endif; ?>
	</div>
</div>