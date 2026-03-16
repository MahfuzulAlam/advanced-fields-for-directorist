<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 2.1.0
 */

namespace Directorist_Advanced_Fields;

class DAF_Scripts
{

	public function __construct()
	{
		add_action( 'wp_enqueue_scripts', array( $this, 'daf_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'daf_admin_enqueue_scripts' ) );
	}

	private function get_asset_version( $relative_path )
	{
		$full_path = Helper::get_file_dir() . $relative_path;

		if ( file_exists( $full_path ) ) {
			return (string) filemtime( $full_path );
		}

		if ( defined( 'DIRECTORIST_ADVANCED_FIELDS_VERSION' ) ) {
			return DIRECTORIST_ADVANCED_FIELDS_VERSION;
		}

		return '2.1.0';
	}

	private function localize_repeater_script()
	{
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

	public function daf_enqueue_scripts()
	{
		$base_css_version     = $this->get_asset_version( 'assets/css/base.css' );
		$repeater_css_version = $this->get_asset_version( 'assets/css/repeater.css' );
		$repeater_js_version  = $this->get_asset_version( 'assets/js/repeater.js' );

		wp_enqueue_style( 'daf-style', Helper::get_file_uri( 'assets/css/base.css' ), array(), $base_css_version );
		wp_enqueue_style( 'daf-repeater-style', Helper::get_file_uri( 'assets/css/repeater.css' ), array( 'daf-style' ), $repeater_css_version );
		wp_enqueue_script( 'daf-repeater-script', Helper::get_file_uri( 'assets/js/repeater.js' ), array( 'jquery' ), $repeater_js_version, true );

		$this->localize_repeater_script();
	}

	public function daf_admin_enqueue_scripts()
	{
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		$screen_post_type = ( $screen && isset( $screen->post_type ) ) ? $screen->post_type : '';
		$is_listing_edit_screen = $screen && 'post' === $screen->base && 'at_biz_dir' === $screen_post_type;

		if ( ! $is_listing_edit_screen ) {
			return;
		}

		$base_css_version     = $this->get_asset_version( 'assets/css/base.css' );
		$repeater_css_version = $this->get_asset_version( 'assets/css/repeater.css' );
		$repeater_js_version  = $this->get_asset_version( 'assets/js/repeater.js' );

		wp_enqueue_style( 'daf-style', Helper::get_file_uri( 'assets/css/base.css' ), array(), $base_css_version );
		wp_enqueue_style( 'daf-repeater-style', Helper::get_file_uri( 'assets/css/repeater.css' ), array( 'daf-style' ), $repeater_css_version );
		wp_enqueue_script( 'daf-repeater-script', Helper::get_file_uri( 'assets/js/repeater.js' ), array( 'jquery' ), $repeater_js_version, true );

		$this->localize_repeater_script();
	}
}

new DAF_Scripts;
