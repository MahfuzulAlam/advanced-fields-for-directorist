<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 2.3.0
 */

if (!defined('ABSPATH')) exit;
?>

<div class="directorist-form-group directorist-custom-field-text <?php echo esc_attr($data['class']) ?>" <?php echo $conditional_logic_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in get_conditional_logic_attributes() ?>>

    <?php if (!isset($data['show_label']) || !empty($data['show_label'])) : ?>
        <?php $listing_form->field_label_template($data); ?>
    <?php endif; ?>

    <input type="text" name="<?php echo esc_attr($data['field_key']); ?>" id="<?php echo esc_attr($data['field_key']); ?>" class="directorist-form-element" value="<?php echo esc_attr($data['value']); ?>" placeholder="<?php echo esc_attr($data['placeholder']); ?>" <?php Directorist\Directorist_Listing_Form::instance()->required($data); ?> />

    <?php $listing_form->field_description_template($data); ?>

</div>