<?php

/**
 * Plugin Name: Directorist - Advanced Fields
 * Plugin URI: https://wpxplore.com/tools/directorist-advanced-fields/
 * Description: Extend Directorist with advanced custom fields including repeater, address list, media, shortcode, iframe, and editor fields for listing forms and single listing display.
 * Version: 2.2.0
 * Author: wpXplore
 * Author URI: https://wpxplore.com/
 * License: GPLv2 or later
 * Text Domain: directorist-advanced-fields
 * Domain Path: /languages
 */

// prevent direct access to the file
defined('ABSPATH') || die('No direct script access allowed!');

if ( ! defined( 'DIRECTORIST_ADVANCED_FIELDS_VERSION' ) ) {
    define( 'DIRECTORIST_ADVANCED_FIELDS_VERSION', '2.2.0' );
}

if (!class_exists('Directorist_Advanced_Fields')) {

    final class Directorist_Advanced_Fields
    {

        private static $instance;
        public static $base_dir;
        public static $base_url;

        public static function instance()
        {

            if (!isset(self::$instance) && !(self::$instance instanceof Directorist_Advanced_Fields)) {
                self::$instance = new Directorist_Advanced_Fields();
                self::$instance->init();
            }

            return self::$instance;
        }

        private function __construct()
        {
        }

        private function init()
        {
            $this->load_textdomain();

            self::$base_dir = plugin_dir_path(__FILE__);
            self::$base_url = plugin_dir_url(__FILE__);

            $this->includes();
        }

        public function load_textdomain()
        {
            load_plugin_textdomain('directorist-advanced-fields', false, dirname(plugin_basename(__FILE__)) . '/languages');
        }

        public function includes()
        {
            require_once self::$base_dir . '/includes/class-hooks.php';
            require_once self::$base_dir . '/includes/class-helper.php';
            require_once self::$base_dir . '/includes/class-advanced-fields.php';
            require_once self::$base_dir . '/includes/class-scripts.php';
            require_once self::$base_dir . '/includes/class-addresses-radius-serach.php';

            require_once self::$base_dir . '/vendor/autoload.php';
        }
    }

    /**
     * @return object|Directorist_Advanced_Fields
     * @since 1.0.0
     */
    function Directorist_Advanced_Fields()
    {
        return Directorist_Advanced_Fields::instance();
    }

    add_action(
        'plugins_loaded',
        static function () {
            if ( class_exists( '\Directorist\Directorist_Listing_Form' ) ) {
                Directorist_Advanced_Fields();
            }
        },
        30
    );
}
