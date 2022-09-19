<?php

\defined( '\WPINC' ) || die;

global $post;

if (function_exists('get_field') && $up_seo_list = get_field('up_seo', $post->ID)) :
	echo '<ul class="upseo-list">';
	foreach ($up_seo_list as $up_id) :
		$post_title = get_the_title($up_id);
		$title      = (!empty($post_title)) ? $post_title : __('(no title)', 'hd');
    ?>
    <li>
        <a title="<?php echo esc_attr($title); ?>" class="post-title" href="<?php the_permalink($up_id); ?>"><?php echo $title; ?></a>
        <span class="post-date"><?php echo humanize_time(); ?></span>
    </li>
    <?php
	    endforeach;
	    wp_reset_postdata();
	echo '</ul>';
endif;