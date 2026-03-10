<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

if (!defined('ABSPATH')) exit;
?>

<div class="directorist-form-group directorist-form-video-field directorist-form-wp-editor-field <?php echo esc_attr( $data[ 'class' ] ); ?>" <?php echo $conditional_logic_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in get_conditional_logic_attributes() ?>>

    <?php $listing_form->field_label_template( $data ); ?>

    <?php
        wp_editor(
			wp_kses_post( $data['value'] ),
			$data['field_key'],
			apply_filters(
				'atbdp_add_listing_wp_editor_settings',
				array(
					'media_buttons' => false,
					'quicktags'     => true,
					'editor_height' => 200,
				)
			)
		);
    ?>

    <?php $listing_form->field_description_template( $data ); ?>

</div>