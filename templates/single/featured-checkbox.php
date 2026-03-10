<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

if (!defined('ABSPATH')) exit;

$values = isset($data['value']) ? $data['value'] : array();

if (!$values || count($values) < 1) return;

$options = isset($data['options']) ? $data['options'] : array();

// Create a mapping of option values to labels
$option_map = array();
if (!empty($options)) {
    foreach ($options as $option) {
        $option_map[$option['option_value']] = $option['option_label'];
    }
}

$widget_label = isset($data['label']) ? $data['label'] : '';
?>

<div class="directorist-single-info directorist-single-info-featured-checkbox directorist-single-info__list">

    <?php if ($widget_label && (isset($data['label_enabled']) && $data['label_enabled'] === true)) : ?>
        <div class="directorist-single-info__label">
            <span class="directorist-single-info__label-icon"><?php directorist_icon($data['icon']); ?></span>
            <span class="directorist-single-info__label--text"><?php echo esc_html($widget_label); ?></span>
        </div>
    <?php endif; ?>

    <div class="directorist-single-info__value">
        <ul class="directorist-featured-checkbox-list">
            <?php foreach ($values as $value) : 
                $label = isset($option_map[$value]) ? $option_map[$value] : $value;
            ?>
                <li class="directorist-featured-checkbox-item"><i class="las la-check-circle"></i>
                    <span class="directorist-featured-checkbox-item-label"><?php echo esc_html($label); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

</div>

