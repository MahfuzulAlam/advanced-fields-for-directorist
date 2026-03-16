<?php

/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if (!defined('ABSPATH')) exit;

use Directorist_Advanced_Fields\Helper;

// Get the field value
$field_value = !empty($data['value']) ? $data['value'] : array();
$field_options = isset($data['options']) ? $data['options'] : array();

// Don't display if no value
if (empty($field_value) || !is_array($field_value)) {
    return;
}

?>

<div class="directorist-single-item directorist-single-item-repeater">
    
    <?php if (!empty($data['label'])) : ?>
        <div class="directorist-single-item__label">
            <h3><?php echo esc_html($data['label']); ?></h3>
        </div>
    <?php endif; ?>
    
    <div class="directorist-single-item__content">
        <div class="directorist-repeater-display">
            <?php foreach ($field_value as $index => $item_values) : ?>
                <div class="directorist-repeater-item">
                    <div class="directorist-repeater-item-header">
                        <h4><?php echo esc_html($data['label']); ?> #<?php echo $index + 1; ?></h4>
                    </div>
                    <div class="directorist-repeater-item-content">
                        <?php if (!empty($field_options)) : ?>
                            <?php foreach ($field_options as $field) : ?>
                                <?php 
                                $field_key = isset($field['field_key']) ? $field['field_key'] : '';
                                $field_label = isset($field['field_label']) ? $field['field_label'] : '';
                                $field_value_item = isset($item_values[$field_key]) ? $item_values[$field_key] : '';
                                
                                if (empty($field_value_item)) {
                                    continue;
                                }
                                ?>
                                <div class="directorist-repeater-field-display">
                                    <div class="directorist-repeater-field-label">
                                        <strong><?php echo esc_html($field_label); ?>:</strong>
                                    </div>
                                    <div class="directorist-repeater-field-value">
                                        <?php 
                                        $field_type = isset($field['field_type']) ? $field['field_type'] : 'text';
                                        $field_options_item = isset($field['field_options']) ? $field['field_options'] : array();
                                        
                                        switch ($field_type) {
                                            case 'select':
                                            case 'radio':
                                                if (!empty($field_options_item) && is_array($field_options_item)) {
                                                    foreach ($field_options_item as $option) {
                                                        if (isset($option['option_value']) && $option['option_value'] == $field_value_item) {
                                                            echo esc_html($option['option_label']);
                                                            break;
                                                        }
                                                    }
                                                } else {
                                                    echo esc_html($field_value_item);
                                                }
                                                break;
                                                
                                            case 'checkbox':
                                                if (is_array($field_value_item)) {
                                                    $selected_labels = array();
                                                    foreach ($field_value_item as $value) {
                                                        if (!empty($field_options_item) && is_array($field_options_item)) {
                                                            foreach ($field_options_item as $option) {
                                                                if (isset($option['option_value']) && $option['option_value'] == $value) {
                                                                    $selected_labels[] = $option['option_label'];
                                                                    break;
                                                                }
                                                            }
                                                        } else {
                                                            $selected_labels[] = $value;
                                                        }
                                                    }
                                                    echo esc_html(implode(', ', $selected_labels));
                                                } else {
                                                    echo esc_html($field_value_item);
                                                }
                                                break;
                                                
                                            case 'url':
                                                echo '<a href="' . esc_url($field_value_item) . '" target="_blank" rel="noopener">' . esc_html($field_value_item) . '</a>';
                                                break;
                                                
                                            case 'email':
                                                echo '<a href="mailto:' . esc_attr($field_value_item) . '">' . esc_html($field_value_item) . '</a>';
                                                break;
                                                
                                            case 'textarea':
                                                echo '<div class="directorist-repeater-textarea-content">' . wp_kses_post(nl2br($field_value_item)) . '</div>';
                                                break;
                                                
                                            case 'color':
                                                echo '<div class="directorist-repeater-color-display">';
                                                echo '<span class="directorist-color-swatch" style="background-color: ' . esc_attr($field_value_item) . ';"></span>';
                                                echo '<span class="directorist-color-value">' . esc_html($field_value_item) . '</span>';
                                                echo '</div>';
                                                break;
                                                
                                            default:
                                                echo esc_html($field_value_item);
                                                break;
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
</div>