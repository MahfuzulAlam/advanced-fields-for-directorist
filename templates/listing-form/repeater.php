<?php

/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if (!defined('ABSPATH')) exit;

use Directorist_Advanced_Fields\Helper;

//e_var_dump($data);

// Get existing values
$existing_values = !empty($data['value']) ? $data['value'] : '';
$field_options = isset($data['options']) ? $data['options'] : array();

?>

<div class="atbd_content_module directorist-repeater" data-field-key="<?php echo esc_attr($data['field_key']); ?>">

    <div class="directorist-form-group directorist-form-repeater-group">
        <div>
            <?php \Directorist\Directorist_Listing_Form::instance()->field_label_template($data); ?>
            <?php \Directorist\Directorist_Listing_Form::instance()->field_description_template($data); ?>
        </div>
        
        <!-- Hidden input to store the field key -->
        <input type="hidden" class="directorist-repeater-hidden-input" name="<?php echo esc_attr($data['field_key']); ?>" value="<?php echo json_encode($existing_values); ?>" />
        
        <div class="directorist-repeater-field-body">
            <?php if (!empty($existing_values) && is_array($existing_values)) : ?>
                <?php foreach ($existing_values as $index => $item_values) : ?>
                    <div class="repeater-fieldset" data-id="<?php echo $index + 1; ?>">
                        <div class="repeater-fieldset-header">
                            <div class="fieldset-title" data-label="<?php echo esc_html($data['label']); ?>"><?php echo esc_html($data['label']); ?> #<?php echo $index + 1; ?></div>
                            <div class="fieldset-actions">
                                <span><a href="#" class="action-minus">–</a></span>
                                <span><a href="#" class="action-plus">+</a></span>
                            </div>
                        </div>
                        <div class="repeater-fieldset-body">
                            <?php if (!empty($field_options)) : ?>
                                <?php foreach ($field_options as $field) : ?>
                                    <div class="directorist-repeater-field">
                                        <div class="directorist-repeater-field-label">
                                            <?php echo isset($field['field_label']) ? esc_html($field['field_label']) : ''; ?>
                                        </div>
                                        <?php 
                                        $field_value = isset($item_values[$field['field_key']]) ? $item_values[$field['field_key']] : '';
                                        Helper::display_repeater_field($field, $field_value, $data['field_key'], $index);
                                        ?>
                                        <div class="directorist-repeater-field-description">
                                            <?php echo isset($field['field_description']) ? esc_html($field['field_description']) : ''; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <!-- Default empty fieldset -->
                <div class="repeater-fieldset" data-id="1">
                    <div class="repeater-fieldset-header">
                        <div class="fieldset-title" data-label="<?php echo esc_html($data['label']); ?>"><?php echo esc_html($data['label']); ?> #1</div>
                        <div class="fieldset-actions">
                            <span><a href="#" class="action-minus">–</a></span>
                            <span><a href="#" class="action-plus">+</a></span>
                        </div>
                    </div>
                    <div class="repeater-fieldset-body">
                        <?php if (!empty($field_options)) : ?>
                            <?php foreach ($field_options as $field) : ?>
                                <div class="directorist-repeater-field">
                                    <div class="directorist-repeater-field-label">
                                        <?php echo isset($field['field_label']) ? esc_html($field['field_label']) : ''; ?>
                                    </div>
                                    <?php Helper::display_repeater_field($field, '', $data['field_key'], 0); ?>
                                    <div class="directorist-repeater-field-description">
                                        <?php echo isset($field['field_description']) ? esc_html($field['field_description']) : ''; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>