<?php

class GannetPost extends Timber\Post {

    var $_format;
    
    public function format() {
        if ( !$this->_format ) {
            $this->_format = get_post_format( $this->ID ) ? : 'standard';
        }
        return $this->_format;
    }
}
