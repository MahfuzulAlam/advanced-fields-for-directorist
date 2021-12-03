<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

namespace Directorist_Advanced_Fields;

class DAF_Scripts
{

    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'daf_enqueue_scripts'));
    }

    public function daf_enqueue_scripts()
    {
        wp_enqueue_style('daf-style',  helper::get_file_uri('/assets/css/base.css'));
    }
}

new DAF_Scripts;
