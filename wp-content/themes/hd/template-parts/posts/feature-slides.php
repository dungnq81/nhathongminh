<?php

use Webhd\Helpers\Cast;

if ( have_posts() ) :
?>
<div class="swiper-section carousel-posts">
	<?php
    $swiper_class = ' autoview';
    $_data = [];

    $_data["autoview"] = true;
    $_data["autoplay"] = true;
    $_data["loop"] = true;

    $_data = json_encode($_data, JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);

	?>
    <div class="w-swiper swiper">
        <div class="swiper-wrapper<?= $swiper_class ?>" data-options='<?= $_data;?>'>
		    <?php
		    $i = 0;

		    // Load slides loop.
		    while ( have_posts() && $i < 2) : the_post();
                echo '<div class="swiper-slide">';
                get_template_part('template-parts/posts/loop', null, [ 'thumbnail_size' => 'post-thumbnail', 'ratio' => '21-9', 'no_scale' => true ]);
			    echo '</div>';
			    ++ $i;
		    endwhile;
		    wp_reset_postdata();
		    ?>
        </div>
    </div>
</div>
<?php endif;