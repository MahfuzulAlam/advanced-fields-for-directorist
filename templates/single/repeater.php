<?php

/**
 * Repeater field — single listing template.
 *
 * @author  wpWax
 * @since   2.1.0
 * @version 2.3.0
 */

if (!defined('ABSPATH')) exit;

// Saved value may arrive as a PHP array or as a JSON string depending on the save path.
$field_value = array();
if (!empty($data['value'])) {
    if (is_array($data['value'])) {
        $field_value = $data['value'];
    } elseif (is_string($data['value'])) {
        $decoded     = json_decode($data['value'], true);
        $field_value = is_array($decoded) ? $decoded : array();
    }
}

// Sub-field definitions: top-level in form context, under form_data on the single page.
$field_options = array();
if (!empty($data['options']) && is_array($data['options'])) {
    $field_options = $data['options'];
} elseif (!empty($data['form_data']['options']) && is_array($data['form_data']['options'])) {
    $field_options = $data['form_data']['options'];
}

// Keep only rows that contain at least one non-empty value ("0" counts as a value).
$items = array();
foreach ($field_value as $item_values) {
    if (!is_array($item_values)) {
        continue;
    }

    foreach ($item_values as $key => $value) {
        // The custom item title alone doesn't make a row worth displaying.
        if ('daf_title' === $key) {
            continue;
        }

        if (is_array($value)) {
            $value = implode('', array_map('strval', $value));
        }

        if ('' !== trim((string) $value)) {
            $items[] = $item_values;
            break;
        }
    }
}

if (empty($items) || empty($field_options)) {
    return;
}

$wrapper_class = isset($data['form_data']['class']) ? $data['form_data']['class'] : '';
$widget_label  = isset($data['label']) ? $data['label'] : '';
$show_numbers  = count($items) > 1;

// "Display Label" toggle from the single-page widget; defaults to showing it.
$label_enabled = isset($data['label_enabled']) ? !empty($data['label_enabled']) : true;

?>

<div class="directorist-single-info directorist-single-info-repeater <?php echo esc_attr($wrapper_class); ?>">

    <?php if (!empty($widget_label) && $label_enabled) : ?>
        <div class="directorist-single-info__label">
            <?php if (!empty($data['icon']) && function_exists('directorist_icon')) : ?>
                <span class="directorist-single-info__label-icon"><?php directorist_icon($data['icon']); ?></span>
            <?php endif; ?>
            <span class="directorist-single-info__label--text"><?php echo esc_html($widget_label); ?></span>
        </div>
    <?php endif; ?>

    <div class="directorist-single-info__value">
        <div class="directorist-repeater-display">
            <?php foreach ($items as $index => $item_values) : ?>
                <div class="directorist-repeater-item">

                    <?php
                    $custom_title = isset($item_values['daf_title']) ? trim((string) $item_values['daf_title']) : '';
                    $item_title   = '' !== $custom_title ? $custom_title : trim($widget_label . ' #' . ($index + 1));
                    ?>
                    <?php if ($show_numbers || '' !== $custom_title) : ?>
                        <div class="directorist-repeater-item-header">
                            <?php if ($show_numbers) : ?>
                                <span class="directorist-repeater-item-badge"><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
                            <?php endif; ?>
                            <h4 class="directorist-repeater-item-title"><?php echo esc_html($item_title); ?></h4>
                        </div>
                    <?php endif; ?>

                    <div class="directorist-repeater-item-content">
                        <?php foreach ($field_options as $field) : ?>
                            <?php
                            $field_key          = isset($field['field_key']) ? $field['field_key'] : '';
                            $field_label        = isset($field['field_label']) ? $field['field_label'] : '';
                            $field_type         = isset($field['field_type']) ? $field['field_type'] : 'text';
                            $field_options_item = isset($field['field_options']) && is_array($field['field_options']) ? $field['field_options'] : array();
                            $field_value_item   = ('' !== $field_key && isset($item_values[$field_key])) ? $item_values[$field_key] : '';

                            $is_empty = is_array($field_value_item)
                                ? array() === array_filter($field_value_item, static function ($value) { return '' !== trim((string) $value); })
                                : '' === trim((string) $field_value_item);

                            if ($is_empty) {
                                continue;
                            }
                            ?>
                            <div class="directorist-repeater-field-display directorist-repeater-field-display--<?php echo esc_attr($field_type); ?>">

                                <?php if ('' !== $field_label) : ?>
                                    <div class="directorist-repeater-field-label"><?php echo esc_html($field_label); ?></div>
                                <?php endif; ?>

                                <div class="directorist-repeater-field-value">
                                    <?php
                                    switch ($field_type) {
                                        case 'select':
                                        case 'radio':
                                            $display_label = (string) $field_value_item;
                                            foreach ($field_options_item as $option) {
                                                if (isset($option['option_value']) && (string) $option['option_value'] === (string) $field_value_item) {
                                                    $display_label = isset($option['option_label']) ? $option['option_label'] : $display_label;
                                                    break;
                                                }
                                            }
                                            echo esc_html($display_label);
                                            break;

                                        case 'checkbox':
                                            $selected_values = is_array($field_value_item) ? $field_value_item : array($field_value_item);
                                            $selected_labels = array();

                                            foreach ($selected_values as $selected_value) {
                                                $display_label = (string) $selected_value;
                                                foreach ($field_options_item as $option) {
                                                    if (isset($option['option_value']) && (string) $option['option_value'] === (string) $selected_value) {
                                                        $display_label = isset($option['option_label']) ? $option['option_label'] : $display_label;
                                                        break;
                                                    }
                                                }
                                                if ('' !== trim($display_label)) {
                                                    $selected_labels[] = $display_label;
                                                }
                                            }

                                            if (!empty($selected_labels)) {
                                                echo '<ul class="directorist-repeater-checkbox-list">';
                                                foreach ($selected_labels as $selected_label) {
                                                    echo '<li>' . esc_html($selected_label) . '</li>';
                                                }
                                                echo '</ul>';
                                            }
                                            break;

                                        case 'url':
                                            echo '<a href="' . esc_url($field_value_item) . '" target="_blank" rel="noopener noreferrer">' . esc_html($field_value_item) . '</a>';
                                            break;

                                        case 'email':
                                            echo '<a href="mailto:' . esc_attr(antispambot($field_value_item)) . '">' . esc_html(antispambot($field_value_item)) . '</a>';
                                            break;

                                        case 'textarea':
                                            echo '<div class="directorist-repeater-textarea-content">' . wp_kses_post(nl2br($field_value_item)) . '</div>';
                                            break;

                                        case 'color':
                                            echo '<span class="directorist-repeater-color-display">';
                                            echo '<span class="directorist-color-swatch" style="background-color: ' . esc_attr($field_value_item) . ';"></span>';
                                            echo '<span class="directorist-color-value">' . esc_html($field_value_item) . '</span>';
                                            echo '</span>';
                                            break;

                                        default:
                                            echo esc_html($field_value_item);
                                            break;
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>
