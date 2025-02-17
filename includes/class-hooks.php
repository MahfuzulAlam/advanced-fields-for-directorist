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

        if( directorist_get_page_id( 'form' ) ):
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

            if( $this->contains_html( $str ) ) {
                return wp_kses_post( $str );
            }
        endif;

        return $filtered;
    }

    public function has_only_allowed_elements($value) {
        // Get the allowed HTML tags from wp_kses_post
        $allowed_html = wp_kses_allowed_html('post');
    
        // Create a regex pattern to match all allowed HTML elements
        $allowed_tags = implode('|', array_map('preg_quote', array_keys($allowed_html)));
        $pattern = '~^([^<]*<(?:' . $allowed_tags . ')(?:\s+[^>]*>|>)[^<]*</(?:' . $allowed_tags . ')>[^<]*)*$~is';
    
        // Check if the value contains only allowed elements
        return ! is_array( $value ) ? preg_match($pattern, $value) : false;
    }

    public function contains_html( $string ) {
        // Regular expression to detect HTML tags
        $pattern = '/<[^<]+>/';
    
        // Use preg_match to check if the string contains HTML tags
        return ! is_array( $string ) ? preg_match($pattern, $string) === 1 : false;
    }
}

new Daf_Hooks;
