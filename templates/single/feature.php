<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

if (!defined('ABSPATH')) exit;

use Directorist_Advanced_Fields\Helper;

$values = isset($data['value']) ? $data['value'] : array();

if (!$values || count($values) < 1) return;

$options = isset($data['options']) ? $data['options'] : array();

$default_icon = isset($data['feature_icon']) && !empty($data['feature_icon']) ? $data['feature_icon'] : 'las la-check';
$icon_color = isset($data['feature_color']) && !empty($data['feature_color']) ? $data['feature_color'] : '#000000';
$color = 'style="color:' . $icon_color . '"';

$label_list = Helper::feature_option_list($options);

$ul_html = "<ul>";
foreach ($values as $value) {
    $class = isset($label_list[$value]['class']) && !empty($label_list[$value]['class']) ? $label_list[$value]['class'] : $value;
    $icon = !empty($label_list[$value]['icon']) ? $label_list[$value]['icon'] : $default_icon;
    $ul_html .= "<li class='feature-option feature-" . trim($class) . "'><span class='icon'><i class='" . $icon . "' " . $color . "></i></span><span class='label'>" . $label_list[$value]['label'] . "</span></li>";
}
$ul_html .= "</ul>";

$widget_label = isset($data['label']) ? $data['label'] : '';
?>

<div class="directorist-single-info directorist-single-info__feature__list directorist-single-info__list">

    <?php if ($widget_label) : ?>
        <div class="directorist-single-info__label">
            <span class="directorist-single-info__label-icon"><?php directorist_icon($data['icon']); ?></span>
            <span class="directorist-single-info__label--text"><?php echo esc_html($widget_label); ?></span>
        </div>
    <?php endif; ?>

    <div class="directorist-single-info__value">
        <?php echo $ul_html; ?>
    </div>

</div>