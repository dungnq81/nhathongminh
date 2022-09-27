<?php

\defined( '\WPINC' ) || die;

/**
 * Template part for displaying posts with excerpts
 *
 * @package WordPress
 * @since   1.0.0
 */

$post_name   = get_the_title();
$ratio       = get_theme_mod_ssl('news_menu_setting');
$ratio_class = $ratio;
if ('default' == $ratio or !$ratio) {
	$ratio_class = '3-2';
}

global $post;

$thumbnail_size = $args['thumbnail_size'] ?? 'medium';
$ratio_class = $args['ratio'] ?? $ratio_class;
$scale = isset($args['no_scale']) ? '' : ' scale';
$post_thumbnail = get_the_post_thumbnail($post, $thumbnail_size);

$hide_thumb = false;
$hide_meta = false;
$hide_desc = false;
$hide_view_detail = false;

if (isset($args['hide_thumb']) && true === $args['hide_thumb'] ) $hide_thumb = true;
if (isset($args['hide_meta']) && true === $args['hide_meta'] ) $hide_meta = true;
if (isset($args['hide_desc']) && true === $args['hide_desc'] ) $hide_desc = true;
if (isset($args['hide_view_detail']) && true === $args['hide_view_detail'] ) $hide_view_detail = true;

?>
<article class="item cell">
    <h6><a href="<?= get_permalink(); ?>" title="<?php echo esc_attr($post_name); ?>"><?= $post_name; ?></a></h6>
    <div class="meta">
        <?php if (!$hide_meta) echo '<div class="time">' . humanize_time() . '</div>'; ?>
        <?php if (!$hide_meta) echo get_primary_term($post); ?>
    </div>
    <?php if (!$hide_thumb && $post_thumbnail) : ?>
	<a class="d-block" href="<?= get_permalink(); ?>" aria-label="<?php echo esc_attr($post_name); ?>" tabindex="0">
		<div class="cover">
			<span class="after-overlay res<?=$scale?> ar-<?= $ratio_class ?>"><?php echo $post_thumbnail; ?></span>
		</div>
	</a>
    <?php endif; ?>
	<div class="cover-content">
        <?php if (!$hide_desc) echo loop_excerpt($post); ?>
        <?php if (!$hide_view_detail) : ?>
		<a class="view-detail" href="<?= get_permalink(); ?>" title="<?php echo esc_attr($post_name); ?>" data-glyph="">
			<span><?php echo __('Đọc tiếp', 'hd'); ?></span>
		</a>
        <?php endif; ?>
	</div>
</article>