<?php

/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 */

use Webhd\Themes\SVG_Icons;

if ( post_password_required() ) {
    return;
}

$comment_count = get_comments_number();
?>
<div class="wp-comments-area comments-area">
    <h5 class="comments-title"><?php echo __( 'Comments', 'hd' ) ?></h5>
    <?php if ( have_comments() ) : ?>
    <span class="fs-16 medium block">
        <?php if ( '1' === $comment_count ) : ?>
            <?php esc_html_e( '1 comment', 'hd' ); ?>
        <?php else : ?>
            <?php
            printf(
            /* translators: %s: Comment count number. */
                esc_html( _nx( '%s comment', '%s comments', $comment_count, 'Comments title', 'hd' ) ),
                esc_html( number_format_i18n( $comment_count ) )
            );
            ?>
        <?php endif; ?>
    </span><!-- .comments-title -->
    <ol class="comment-list">
        <?php
        wp_list_comments(
            [
                'avatar_size' => 45,
                'style'       => 'ol',
                'short_ping'  => true,
            ]
        );
        ?>
    </ol><!-- .comment-list -->
    <?php
    the_comments_pagination(
        [
            'before_page_number' => esc_html__( 'Page', 'hd' ) . ' ',
            'mid_size'           => 0,
            'prev_text'          => sprintf(
                '%s <span class="nav-prev-text">%s</span>',
                is_rtl() ? SVG_Icons::get_svg( 'ui', 'arrow_right' ) : SVG_Icons::get_svg( 'ui', 'arrow_left' ),
                esc_html__( 'Older comments', 'hd' )
            ),
            'next_text'          => sprintf(
                '<span class="nav-next-text">%s</span> %s',
                esc_html__( 'Newer comments', 'hd' ),
                is_rtl() ? SVG_Icons::get_svg( 'ui', 'arrow_left' ) : SVG_Icons::get_svg( 'ui', 'arrow_right' )
            ),
        ]
    );
    ?>
    <?php if ( ! comments_open() ) : ?>
    <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'hd' ); ?></p>
    <?php endif; ?>
    <?php endif; ?>

    <?php
    comment_form(
        array(
            'logged_in_as'       => null,
            'title_reply'        => esc_html__( 'Leave a comment', 'hd' ),
            'title_reply_before' => '<h6 id="reply-title" class="comment-reply-title">',
            'title_reply_after'  => '</h6>',
        )
    );
    ?>
</div>