<?php

class GannetPost extends Timber\Post {

    public $_format;

    public $_first_gallery;

    public $_first_link;

    public $_first_video;
    
    public function format() {
        if ( !$this->_format ) {
            $this->_format = get_post_format( $this->ID ) ? : 'standard';
        }
        return $this->_format;
    }

    public function first_gallery() {
        if (!$this->_first_gallery && $this->format() === 'gallery') {
            $this->_first_gallery = get_post_gallery( $this->ID, false );
        }
        return $this->_first_gallery;
    }

    public function gallery_preview() {
        $gallery = $this->first_gallery();
        $preview = [];
        if ( count( $gallery['src'] ) > 0 ) {
            $preview = array_slice( $gallery['src'], 0, 5 );
        }
        return $preview;
    }

    public function first_link() {
        if ( !$this->_first_link ) {
            preg_match( '/<a[\s]+([^>]+)>((?:.(?!\<\/a\>))*.)<\/a>/', $this->post_content, $matches );
            if ( count( $matches ) > 0 ) {
                $this->_first_link = $matches[0];
            } else {
                preg_match( '/@^(https?|ftp)://[^\s/$.?#].[^\s]*$@iS/', $this->post_content, $url_matches );
                if ( count( $url_matches ) > 0 ) {
                    $this->_first_link = '<a href="'. $url_matches[0] .'">' . $this->title . '</a>';
                } else {
                    $this->_first_link = '<a href="' . $this->content . '">' . $this->title . '</a>';
                }
            }
        }
        return $this->_first_link;
    }

    public function first_video() {
        if ( !$this->_first_video && $this->format() === 'video' ) {
            global $shortcode_tags;
            // Make a copy of global shortcode tags - we'll temporarily overwrite it.
            $theme_shortcode_tags = $shortcode_tags;

            // The shortcodes we're interested in.
            $shortcode_tags = array(
                'video' => $theme_shortcode_tags['video'],
                'embed' => $theme_shortcode_tags['embed']
            );
            // Get the absurd shortcode regexp.
            $video_regex = '#' . get_shortcode_regex() . '#i';

            // Restore global shortcode tags.
            $shortcode_tags = $theme_shortcode_tags;

            $pattern_array = array( $video_regex );

            // Get the patterns from the embed object.
            if ( ! function_exists( '_wp_oembed_get_object' ) ) {
                include ABSPATH . WPINC . '/class-oembed.php';
            }
            $oembed = _wp_oembed_get_object();
            $pattern_array = array_merge( $pattern_array, array_keys( $oembed->providers ) );

            // Or all the patterns together.
            $pattern = '#(' . array_reduce( $pattern_array, function ( $carry, $item ) {
                if ( strpos( $item, '#' ) === 0 ) {
                    // Assuming '#...#i' regexps.
                    $item = substr( $item, 1, -2 );
                } else {
                    // Assuming glob patterns.
                    $item = str_replace( '*', '(.+)', $item );
                }
                return $carry ? $carry . ')|('  . $item : $item;
            } ) . ')#is';

            // Simplistic parse of content line by line.
            $lines = explode( "\n", $this->post_content );
            foreach ( $lines as $line ) {
                $line = trim( $line );
                if ( preg_match( $pattern, $line, $matches ) ) {
                    if ( strpos( $matches[0], '[' ) === 0 ) {
                        $ret = do_shortcode( $matches[0] );
                    } else {
                        $ret = wp_oembed_get( $matches[0] );
                    }
                    $this->_first_video = $ret;
                }
            }
        }
        return $this->_first_video;
    }
}
