<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

if (!defined('ABSPATH')) exit;
?>

<div class="directorist-form-group directorist-iframe <?php echo esc_attr( $data['class'] ) ?>" <?php echo $conditional_logic_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in get_conditional_logic_attributes() ?>>
    <?php $listing_form->field_label_template($data); ?>

    <textarea name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" rows="8" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php Directorist\Directorist_Listing_Form::instance()->required($data); ?> ><?php echo esc_textarea( $data['value'] ); ?></textarea>

    <?php $listing_form->field_description_template($data); ?>
</div>