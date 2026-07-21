<?php

/**
 * YouTube video field — single listing template.
 *
 * @author  mahfuz
 * @since   1.0
 * @version 2.2.0
 */

if (!defined('ABSPATH')) exit;

use Directorist_Advanced_Fields\Helper;

if( ! $data['value'] ) return;

$embed_url = Helper::parse_youtube( $data['value'] );

if ( '' === $embed_url ) {
    return;
}

?>

<div class="directorist-single-info directorist-single-info-youtube <?php echo esc_attr( $data['form_data']['class'] ); ?>">

    <?php if (!isset($data['label_enabled']) || !empty($data['label_enabled'])) : ?>
        <div class="directorist-single-info__label">
            <span class="directorist-single-info__label-icon"><?php directorist_icon($data['icon']); ?></span>
            <span class="directorist-single-info__label--text"><?php echo esc_html($data['label']); ?></span>
        </div>
    <?php endif; ?>

    <div class="directorist-single-info__value">
        <iframe class="directorist-embaded-video embed-responsive-item" src="<?php echo esc_url( $embed_url ); ?>" title="<?php echo esc_attr( $data['label'] ); ?>" loading="lazy" allowfullscreen></iframe>
    </div>

</div>
