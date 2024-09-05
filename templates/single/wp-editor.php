<?php

/**
 * @author  mahfuz
 * @since   6.7
 * @version 6.7
 */

if (!defined('ABSPATH')) exit;

if( ! $data['value'] ) return;

?>

<div class="directorist-single-info directorist-single-info-wp-editor  <?php echo esc_attr( $data['form_data']['class'] ); ?>">

    <div class="directorist-single-info__label">
        <span class="directorist-single-info__label-icon"><?php directorist_icon($data['icon']); ?></span>
        <span class="directorist-single-info__label--text"><?php echo esc_html($data['label']); ?></span>
    </div>

    <div class="directorist-single-info__value">
        <?php echo wp_kses_post( $data['value'] );?>
    </div>

</div>