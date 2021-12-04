<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

namespace Directorist_Advanced_Fields;

class Advanced_Fields
{

    public function __construct()
    {
        $this->inc();
    }

    public function inc()
    {
        include_once Helper::get_file_dir() . '/includes/fields/iframe.php';
        include_once Helper::get_file_dir() . '/includes/fields/shortcode.php';
        include_once Helper::get_file_dir() . '/includes/fields/youtube.php';
        include_once Helper::get_file_dir() . '/includes/fields/vimeo.php';
    }
}

new Advanced_Fields;
