<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Directorist_Advanced_Fields\Helper;

if( ! $data['value'] ) return;

$shortcode_output = Helper::render_allowed_shortcode( $data['value'] );

if ( '' === trim( $shortcode_output ) ) {
    return;
}

?>

<div class="directorist-single-info directorist-single-info-shortcode <?php echo esc_attr( $data['form_data']['class'] ); ?>">

    <?php if (!isset($data['label_enabled']) || !empty($data['label_enabled'])) : ?>
        <div class="directorist-single-info__label">
            <span class="directorist-single-info__label-icon"><?php directorist_icon($data['icon']); ?></span>
            <span class="directorist-single-info__label--text"><?php echo esc_html($data['label']); ?></span>
        </div>
    <?php endif; ?>

    <div class="directorist-single-info__value"><?php echo $shortcode_output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output of do_shortcode() restricted to the daf_allowed_shortcode_tags allowlist. ?></div>

</div>
