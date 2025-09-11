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
        wp_enqueue_script('qrcode-script', 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js', [], '1.0.0', true);
        wp_enqueue_script('daf-script', helper::get_file_uri('/assets/js/base.js'), ['jquery'], '1.0.0', true);
    }
}

new DAF_Scripts;
