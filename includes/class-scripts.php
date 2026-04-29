<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 2.2.0
 */

namespace Directorist_Advanced_Fields;

defined( 'ABSPATH' ) || exit;

class DAF_Scripts {
    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'daf_enqueue_scripts' ), 999999 );
        add_action( 'admin_enqueue_scripts', array( $this, 'daf_admin_enqueue_scripts' ) );
    }

    private function get_asset_version( $relative_path ) {
        $full_path = Helper::get_file_dir() . $relative_path;

        if ( file_exists( $full_path ) ) {
            return (string) filemtime( $full_path );
        }

        if ( defined( 'DIRECTORIST_ADVANCED_FIELDS_VERSION' ) ) {
            return DIRECTORIST_ADVANCED_FIELDS_VERSION;
        }

        return '2.2.0';
    }

    private function localize_repeater_script() {
        wp_localize_script(
            'daf-repeater-script',
            'repeaterFieldOptions',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'daf_repeater_nonce' ),
                'strings'  => array(
                    'add_item'       => __( 'Add Item', 'directorist-advanced-fields' ),
                    'remove_item'    => __( 'Remove Item', 'directorist-advanced-fields' ),
                    'confirm_remove' => __( 'Are you sure you want to remove this item?', 'directorist-advanced-fields' ),
                ),
            )
        );
    }

    private function enqueue_shared_styles() {
        wp_enqueue_style(
            'daf-style',
            Helper::get_file_uri( 'assets/css/base.css' ),
            array(),
            $this->get_asset_version( 'assets/css/base.css' )
        );
    }

    private function enqueue_repeater_assets() {
        if ( file_exists( Helper::get_file_dir() . 'assets/css/repeater.css' ) ) {
            wp_enqueue_style(
                'daf-repeater-style',
                Helper::get_file_uri( 'assets/css/repeater.css' ),
                array( 'daf-style' ),
                $this->get_asset_version( 'assets/css/repeater.css' )
            );
        }

        if ( file_exists( Helper::get_file_dir() . 'assets/js/repeater.js' ) ) {
            wp_enqueue_script(
                'daf-repeater-script',
                Helper::get_file_uri( 'assets/js/repeater.js' ),
                array( 'jquery' ),
                $this->get_asset_version( 'assets/js/repeater.js' ),
                true
            );

            $this->localize_repeater_script();
        }
    }

    private function enqueue_address_form_assets() {
        if ( ! file_exists( Helper::get_file_dir() . 'assets/js/address.js' ) ) {
            return;
        }

        $dependencies = wp_script_is( 'google-map-api', 'registered' ) || wp_script_is( 'google-map-api', 'enqueued' )
            ? array( 'google-map-api' )
            : array( 'jquery' );

        wp_enqueue_script(
            'daf-address',
            Helper::get_file_uri( 'assets/js/address.js' ),
            $dependencies,
            $this->get_asset_version( 'assets/js/address.js' ),
            true
        );
    }

    private function is_directorist_page( $page_key ) {
        if ( ! function_exists( 'directorist_get_page_id' ) ) {
            return false;
        }

        $page_id = directorist_get_page_id( $page_key );

        return $page_id ? is_page( $page_id ) : false;
    }

    private function is_frontend_submission_context() {
        return $this->is_directorist_page( 'form' ) || $this->is_directorist_page( 'dashboard' );
    }

    public function daf_enqueue_scripts() {
        $is_single_listing = is_singular( 'at_biz_dir' );
        $is_submission     = $this->is_frontend_submission_context();

        if ( ! $is_single_listing && ! $is_submission ) {
            return;
        }

        $this->enqueue_shared_styles();

        if ( $is_submission ) {
            $this->enqueue_repeater_assets();
            $this->enqueue_address_form_assets();
        }

        if ( ! $is_single_listing ) {
            return;
        }

        $map_type = function_exists( 'get_directorist_option' ) ? get_directorist_option( 'select_listing_map' ) : '';

        if ( 'openstreet' === $map_type && file_exists( Helper::get_file_dir() . 'assets/js/openstreet-map.js' ) ) {
            wp_enqueue_script(
                'daf-openstreet-map',
                Helper::get_file_uri( 'assets/js/openstreet-map.js' ),
                array( 'jquery' ),
                $this->get_asset_version( 'assets/js/openstreet-map.js' ),
                true
            );
        }

        if ( 'google' === $map_type && file_exists( Helper::get_file_dir() . 'assets/js/google-map.js' ) ) {
            wp_enqueue_script(
                'daf-google-map',
                Helper::get_file_uri( 'assets/js/google-map.js' ),
                array( 'jquery', 'google-map-api' ),
                $this->get_asset_version( 'assets/js/google-map.js' ),
                true
            );
        }
    }

    public function daf_admin_enqueue_scripts() {
        $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

        if ( ! $screen ) {
            return;
        }

        $is_listing_edit_screen = in_array( $screen->base, array( 'post', 'post-new' ), true ) && 'at_biz_dir' === $screen->post_type;

        if ( ! $is_listing_edit_screen ) {
            return;
        }

        $this->enqueue_shared_styles();
        $this->enqueue_repeater_assets();
        $this->enqueue_address_form_assets();
    }
}

new DAF_Scripts();
