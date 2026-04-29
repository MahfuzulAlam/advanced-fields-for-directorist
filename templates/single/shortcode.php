<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Directorist_Advanced_Fields\Helper;

if( ! $data['value'] ) return;

$shortcode_output = Helper::render_allowed_shortcode( $data['value'] );

if ( '' === trim( $shortcode_output ) ) {
    return;
}

?>

<div class="directorist-single-info directorist-single-info-iframe <?php echo esc_attr( $data['form_data']['class'] ); ?>">

    <div class="directorist-single-info__label">
        <span class="directorist-single-info__label-icon"><?php directorist_icon($data['icon']); ?></span>
        <span class="directorist-single-info__label--text"><?php echo esc_html($data['label']); ?></span>
    </div>

    <div class="directorist-single-info__value"><?php echo $shortcode_output; ?></div>

</div>
