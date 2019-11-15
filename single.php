<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Gannet
 */

$context         = Timber::context();
$timber_post     = Timber::query_post();
$comment_count = $timber_post->comment_count;
$context['post'] = $timber_post;
$context['comments_navigation'] = get_the_comments_navigation();

if ($comment_count === '1') {
	$context['comments_title'] = sprintf(
		/* translators: 1: title. */
		esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'gannet' ),
		'<span>' . get_the_title() . '</span>'
	);
} else {
	$context['comments_title'] = sprintf( // WPCS: XSS OK.
		/* translators: 1: comment count number, 2: title. */
		esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $comment_count, 'comments title', 'gannet' ) ),
		number_format_i18n( $comment_count ),
		'<span>' . get_the_title() . '</span>'
	);
}

if ( post_password_required( $timber_post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	Timber::render( array(
		'single-' . $timber_post->ID . '.twig',
		'single-' . $timber_post->post_type . '.twig',
		'single-' . $timber_post->slug . '.twig',
		'single.twig'
	), $context );
}