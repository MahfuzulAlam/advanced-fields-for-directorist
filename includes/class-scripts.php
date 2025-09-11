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
        add_action('wp_enqueue_scripts', array($this, 'daf_enqueue_scripts'), 999999);
        add_action('admin_enqueue_scripts', array($this, 'daf_enqueue_scripts'));

        //add_action('wp_footer', array($this, 'address_autocomplete'), 99);
    }

    public function daf_enqueue_scripts()
    {
        wp_enqueue_style('daf-style',  helper::get_file_uri('assets/css/base.css'));

        wp_register_script(
            'daf-address',
            helper::get_file_uri('assets/js/address.js'),
            array('google-map-api'), // dependency: loads after Directorist JS
            '1.0.0',
            true
        );

        wp_enqueue_script('daf-address');

        // Enqueue addresses map scripts for single listing pages
        if (is_singular('at_biz_dir')) {
            $map_type = get_directorist_option('select_listing_map');
            
            if ($map_type === 'openstreet') {
                wp_register_script(
                    'daf-openstreet-map',
                    helper::get_file_uri('assets/js/openstreet-map.js'),
                    array('jquery'),
                    '1.0.0',
                    true
                );
                wp_enqueue_script('daf-openstreet-map');
            } elseif ($map_type === 'google') {
                wp_register_script(
                    'daf-google-map',
                    helper::get_file_uri('assets/js/google-map.js'),
                    array('jquery', 'google-map-api'),
                    '1.0.0',
                    true
                );
                wp_enqueue_script('daf-google-map');
            }
        }
    }

    public function address_autocomplete()
    {
        ?>
        <script type="text/javascript">
            function initAutocomplete() {
                const input = document.getElementsByClassName("directorist-address-js");
                new google.maps.places.Autocomplete(input, {
                    types: ["geocode"], // restricts to geographical location types
                    componentRestrictions: { country: directorist.restricted_countries } // optional
                });
            }

            // Initialize when DOM is ready
            document.addEventListener("DOMContentLoaded", initAutocomplete);
        </script>
        <?php
    }
}

new DAF_Scripts;
