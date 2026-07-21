<?php

/**
 * Repeater field — Add Listing form template.
 *
 * @author  wpWax
 * @since   2.1.0
 * @version 2.2.0
 */

if (!defined('ABSPATH')) exit;

use Directorist_Advanced_Fields\Helper;

// Saved value may arrive as a PHP array or as a JSON string depending on the save path.
$existing_values = array();
if (!empty($data['value'])) {
    if (is_array($data['value'])) {
        $existing_values = $data['value'];
    } elseif (is_string($data['value'])) {
        $decoded         = json_decode($data['value'], true);
        $existing_values = is_array($decoded) ? $decoded : array();
    }
    $existing_values = array_values(array_filter($existing_values, 'is_array'));
}

$field_options = isset($data['options']) && is_array($data['options']) ? $data['options'] : array();

// Always render at least one (empty) row so the user has something to fill in.
$rows = !empty($existing_values) ? $existing_values : array(array());

// "Show Label" toggle; fields saved before the option existed default to showing it.
$show_label = isset($data['show_label']) ? !empty($data['show_label']) : true;

?>

<div class="directorist-repeater" <?php echo $conditional_logic_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in get_conditional_logic_attributes() ?>>

    <div class="directorist-form-group directorist-form-repeater-group">
        <div class="directorist-form-group-header">
            <?php if ($show_label) : ?>
                <?php $listing_form->field_label_template($data); ?>
            <?php endif; ?>
            <?php $listing_form->field_description_template($data); ?>
        </div>

        <!-- Hidden input carrying the serialized repeater value; kept in sync by repeater.js -->
        <input type="hidden" class="directorist-repeater-hidden-input" name="<?php echo esc_attr($data['field_key']); ?>" value="<?php echo esc_attr(wp_json_encode($existing_values)); ?>" />

        <div class="directorist-repeater-field-body">
            <?php foreach ($rows as $index => $item_values) : ?>
                <?php $item_title = isset($item_values['daf_title']) ? (string) $item_values['daf_title'] : ''; ?>
                <div class="repeater-fieldset" data-id="<?php echo esc_attr($index + 1); ?>">
                    <div class="repeater-fieldset-header">
                        <input
                            type="text"
                            class="fieldset-title"
                            name="<?php echo esc_attr($data['field_key'] . '[' . $index . '][daf_title]'); ?>"
                            value="<?php echo esc_attr($item_title); ?>"
                            placeholder="<?php echo esc_attr($data['label'] . ' #' . ($index + 1)); ?>"
                            data-label="<?php echo esc_attr($data['label']); ?>"
                            autocomplete="off"
                            aria-label="<?php esc_attr_e('Item title', 'directorist-advanced-fields'); ?>"
                        />
                        <div class="fieldset-actions">
                            <span><a href="#" class="action-minus" role="button" aria-label="<?php esc_attr_e('Remove item', 'directorist-advanced-fields'); ?>" title="<?php esc_attr_e('Remove item', 'directorist-advanced-fields'); ?>">&ndash;</a></span>
                            <span><a href="#" class="action-plus" role="button" aria-label="<?php esc_attr_e('Add item', 'directorist-advanced-fields'); ?>" title="<?php esc_attr_e('Add item', 'directorist-advanced-fields'); ?>">+</a></span>
                        </div>
                    </div>
                    <div class="repeater-fieldset-body">
                        <?php foreach ($field_options as $field) : ?>
                            <?php
                            $sub_field_key  = isset($field['field_key']) ? $field['field_key'] : '';
                            $sub_field_type = isset($field['field_type']) ? $field['field_type'] : 'text';
                            $sub_value      = ('' !== $sub_field_key && isset($item_values[$sub_field_key])) ? $item_values[$sub_field_key] : '';
                            ?>
                            <div class="directorist-repeater-field directorist-repeater-field--<?php echo esc_attr($sub_field_type); ?>">
                                <?php if (!empty($field['field_label'])) : ?>
                                    <div class="directorist-repeater-field-label"><?php echo esc_html($field['field_label']); ?></div>
                                <?php endif; ?>

                                <?php Helper::display_repeater_field($field, $sub_value, $data['field_key'], $index); ?>

                                <?php if (!empty($field['field_description'])) : ?>
                                    <div class="directorist-repeater-field-description"><?php echo esc_html($field['field_description']); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>
