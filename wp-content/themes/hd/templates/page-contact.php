<?php

/**
 * The template for displaying contact page
 * Template Name: Liên hệ
 * Template Post Type: page
 */

\defined( '\WPINC' ) || die;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;

get_header();

global $post;

if (have_posts()) the_post();
if (post_password_required()) :
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
endif;

// template-parts/parts/page-title.php
the_page_title_theme();

$ID = $post->ID ?? false;
$ACF = Cast::toObject( get_fields( $ID ) );

$css_class = '';
if (Str::stripSpace( $ACF->css_class ))
    $css_class .= ' ' .  $ACF->css_class;

?>
<section class="section single-post single-page single-contactpage<?=$css_class?>">
    <div class="grid-container width-extra">
        <div class="col-content"><!---->
            <?php if ( Str::stripSpace( $ACF->contact_title ) ) : ?>
            <h2 class="heading-title"><?php echo $ACF->contact_title?></h2>
            <?php endif; ?>
            <div class="content clearfix">
                <?php if ( Str::stripSpace( $ACF->contact_desc ) ) : ?>
                <div class="content-html">
                    <?php echo $ACF->contact_desc; ?>
                </div>
                <?php endif; ?>
                <?php if ( Str::stripSpace( $ACF->contact_form ) ) : ?>
                <div class="content-form">
                    <?php
                    $form = get_post( $ACF->contact_form );
                    echo do_shortcode( '[contact-form-7 id="' . $form->ID . '" title="' . esc_attr( $form->post_title ) . '"]' );
                    ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if ( Str::stripSpace( $ACF->contact_map ) ) : ?>
    <div class="gmap-section">
        <div class="res res-map">
            <?php echo $ACF->contact_map; ?>
        </div>
    </div>
    <?php endif; ?>
</section>
<?php
get_footer();