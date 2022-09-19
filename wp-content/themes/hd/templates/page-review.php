<?php
/**
 * The template for displaying review
 * Template Name: Đánh giá
 * Template Post Type: page
 */

\defined( '\WPINC' ) || die;

get_header();

global $post;

if (have_posts()) the_post();
if (post_password_required()) :
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
endif;

?>
<section class="section section-reviews" role="contentinfo">
	<div class="review-header">
		<div class="grid-container">
            <div class="grid-x grid-padding-x align-justify align-middle">
                <div class="cell medium-8">
                    <div class="left">
                        <span class="thumb hide"></span>
                        <div class="info">
                            <h1 class="h4">Đánh giá của khách hàng khi sử dụng dịch vụ</h1>
                            <div class="summary"><?php echo do_shortcode('[site_reviews_summary schema="true" hide="bars,rating"]')?></div>
                        </div>
                    </div>
                </div>
                <div class="cell medium-4">
                    <div class="right summary-bars">
		                <?php echo do_shortcode('[site_reviews_summary hide="rating,stars,summary"]')?>
                    </div>
                </div>
            </div>
        </div>
	</div>
	<div class="review-body">
		<div class="grid-container">
            <div class="grid-x grid-padding-x align-justify">
                <div class="cell wp-8">
                    <div class="left">
                        <div class="body-reviews">
				            <?php echo do_shortcode( '[site_reviews display="10" pagination="true" id="kufk66lp"]' ); ?>
                        </div>
                    </div>
                </div>
                <div class="cell wp-4 right wp-reverse">
                    <div class="sidebar">
                        <div class="review-form">
                            <h3 class="h4">Đánh giá của bạn</h3>
				            <?php echo do_shortcode( '[site_reviews_form hide="email" id="kufjydlo"]' ); ?>
                        </div>
                        <div class="service-menu"></div>
                        <div class="recent-reviews">
				            <?php echo do_shortcode('[site_reviews display="5" rating="4" pagination="false" schema="true" id="kugyhopq" hide="date,response"]');?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</section>
<?php

if ( is_active_sidebar( 'w-review-sidebar' ) ) :
	dynamic_sidebar( 'w-review-sidebar' );
endif;

get_footer();