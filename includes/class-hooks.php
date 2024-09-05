<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

namespace Directorist_Advanced_Fields;

class Daf_Hooks
{

    public function __construct()
    {
        add_filter( 'sanitize_text_field', array( $this, 'sanitize_text_field' ), 10, 2 );
    }
    
    public function sanitize_text_field( $filtered, $str ){
        if ( preg_match( '/<iframe\b[^>]*>(.*?)<\/iframe>/i', $str ) ) {
            $allowed_tags = array(
                'iframe' => array(
                    'title'             => array(),
                    'src'             	=> array(),
                    'width'           	=> array(),
                    'height'          	=> array(),
                    'frameborder'     	=> array(),
                    'allowfullscreen' 	=> array(),
                    'referrerpolicy'	=> array(),
                    'allow'				=> array(),
                ),
            );
            return wp_kses( $str, $allowed_tags);
        }
        return $filtered;
    }
}

new Daf_Hooks;
