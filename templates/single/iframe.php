<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Directorist_Advanced_Fields\Helper;

if( ! $data['value'] ) return;

$iframe_value = Helper::sanitize_iframe_html( $data['value'] );

if ( '' === trim( $iframe_value ) ) {
    return;
}

?>

<div class="directorist-single-info directorist-single-info-iframe  <?php echo esc_attr( $data['form_data']['class'] ); ?>">

    <div class="directorist-single-info__label">
        <span class="directorist-single-info__label-icon"><?php directorist_icon($data['icon']); ?></span>
        <span class="directorist-single-info__label--text"><?php echo esc_html($data['label']); ?></span>
    </div>

    <div class="directorist-single-info__value"><?php echo $iframe_value; ?></div>

</div>
