<?php

require_once( __DIR__ . '/GannetPost.php' );

add_filter( 'Timber\PostClassMap', 'gannet_timber_post_class_map', 10, 1 );
function gannet_timber_post_class_map ( $post_class ) {
   return 'GannetPost';
}