<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Gannet
 */

$templates = array( 'search.twig', 'archive.twig', 'index.twig' );
$context          = Timber::context();
$context['title'] = 'Search results for ' . get_search_query();
$context['posts'] = new Timber\PostQuery();
Timber::render( $templates, $context );