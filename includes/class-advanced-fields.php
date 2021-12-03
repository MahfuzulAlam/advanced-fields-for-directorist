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
    }
}

new Advanced_Fields;
