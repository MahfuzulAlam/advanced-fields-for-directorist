<?php

/**
 * Plugin Name: Advanced Fields for Directorist
 * Plugin URI: https://github.com/MahfuzulAlam/advanced-fields-for-directorist
 * Description: This is an extension for Directorist Plugin.
 * Version: 1.0.0
 * Author: M Alam
 * Author URI: https://github.com/MahfuzulAlam
 * License: GPLv2 or later
 * Text Domain: directorist-advanced-fields
 * Domain Path: /languages
 */

// prevent direct access to the file
defined('ABSPATH') || die('No direct script access allowed!');

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
            add_action('plugins_loaded', array($this, 'load_textdomain'), 20);

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
            require_once self::$base_dir . '/includes/class-helper.php';
            require_once self::$base_dir . '/includes/class-advanced-fields.php';
            require_once self::$base_dir . '/includes/class-scripts.php';

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

    // Instantiate Directorist_Advanced_Fields, when Directorist plugin is active
    if (in_array('directorist/directorist-base.php', (array) get_option('active_plugins'))) {
        Directorist_Advanced_Fields();
    }
}
