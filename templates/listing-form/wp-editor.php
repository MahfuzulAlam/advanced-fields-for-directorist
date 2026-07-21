<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 2.3.0
 */

if (!defined('ABSPATH')) exit;
?>

<div class="directorist-form-group directorist-form-video-field directorist-form-wp-editor-field <?php echo esc_attr( $data[ 'class' ] ); ?>" <?php echo $conditional_logic_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in get_conditional_logic_attributes() ?>>

    <?php if (!isset($data['show_label']) || !empty($data['show_label'])) : ?>
        <?php $listing_form->field_label_template( $data ); ?>
    <?php endif; ?>

    <?php
        // Editor IDs may only contain lowercase letters, digits, and underscores;
        // textarea_name keeps the submitted field name identical to the field key.
        $daf_editor_id = 'daf_' . preg_replace( '/[^a-z0-9_]/', '_', strtolower( (string) $data['field_key'] ) );

        wp_editor(
			wp_kses_post( $data['value'] ),
			$daf_editor_id,
			apply_filters(
				'atbdp_add_listing_wp_editor_settings',
				array(
					'media_buttons' => false,
					'quicktags'     => true,
					'editor_height' => 200,
					'textarea_name' => $data['field_key'],
				)
			)
		);
    ?>

    <?php $listing_form->field_description_template( $data ); ?>

</div>