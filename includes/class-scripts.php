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
        wp_enqueue_style('daf-style',  Helper::get_file_uri('assets/css/base.css'));
        wp_enqueue_style('daf-repeater-style', Helper::get_file_uri('assets/css/repeater.css'), array('daf-style'), '1.0.0');
        wp_enqueue_script('daf-repeater-script', Helper::get_file_uri('assets/js/repeater.js'), array('jquery'), '1.0.0', true);
        
        //Localize script for repeater field options
        wp_localize_script('daf-repeater-script', 'repeaterFieldOptions', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('daf_repeater_nonce'),
            'strings' => array(
                'add_item' => __('Add Item', 'directorist-advanced-fields'),
                'remove_item' => __('Remove Item', 'directorist-advanced-fields'),
                'confirm_remove' => __('Are you sure you want to remove this item?', 'directorist-advanced-fields'),
            )
        ));
    }
}

new DAF_Scripts;
