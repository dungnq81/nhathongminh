<?php

\defined( '\WPINC' ) || die;

/**
 * Displays the next and previous post navigation in single posts.
 *
 * @package WordPress
 */

$next_post = get_next_post();
$prev_post = get_previous_post();

if ( ! $next_post && ! $prev_post)
	return;

$pagination_classes = '';
if (!$next_post) {
	$pagination_classes = ' only-one only-prev';
} elseif (!$prev_post) {
	$pagination_classes = ' only-one only-next';
}

?>
<nav class="pagination-single section-inner<?php echo esc_attr($pagination_classes); ?>" aria-label="<?php echo esc_attr__('Post'); ?>" role="navigation">
    <div class="pagination-single-inner">
        <?php if ($prev_post) : ?>
		<a class="previous-post cover" href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>">
			<span class="res ar-16-9">
				<?php echo get_the_post_thumbnail( $prev_post, 'medium' ); ?>
				<span class="arrow" aria-hidden="true"></span>
			</span>
			<span class="title"><span class="title-inner"><?php echo wp_kses_post(get_the_title($prev_post->ID)); ?></span></span>
		</a>
        <?php endif; ?>
        <?php if ($next_post) : ?>
		<a class="next-post cover" href="<?php echo esc_url(get_permalink($next_post->ID)); ?>">
			<span class="res ar-16-9">
				<?php echo get_the_post_thumbnail( $next_post, 'medium' ); ?>
				<span class="arrow" aria-hidden="true"></span>
			</span>
			<span class="title"><span class="title-inner"><?php echo wp_kses_post(get_the_title($next_post->ID)); ?></span></span>
		</a>
        <?php endif; ?>
    </div>
</nav>