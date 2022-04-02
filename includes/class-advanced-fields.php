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
        add_filter('atbdp_listing_type_form_fields', array($this, 'atbdp_listing_type_form_fields'));
        $this->advanced_fields();
    }

    public function advanced_fields()
    {
        include_once Helper::get_file_dir() . '/includes/fields/iframe.php';
        include_once Helper::get_file_dir() . '/includes/fields/shortcode.php';
        include_once Helper::get_file_dir() . '/includes/fields/youtube.php';
        include_once Helper::get_file_dir() . '/includes/fields/vimeo.php';
        include_once Helper::get_file_dir() . '/includes/fields/ec_category.php';
        include_once Helper::get_file_dir() . '/includes/fields/ec_vanue.php';
    }

    public function atbdp_listing_type_form_fields($fields)
    {
        $advenced_fields = apply_filters('atbdp_form_advanced_widgets', array());
        if (!empty($advenced_fields)) {
            $fields['widgets']['advanced'] = array(
                'title' => __('Advanced Fields', 'directorist-advanced-fields'),
                'description' => __('Click on a field type to create an advanced field!', 'directorist-advanced-fields'),
                'allowMultiple' => true,
                'widgets' => $advenced_fields,
            );
        }
        return $fields;
    }
}

new Advanced_Fields;
