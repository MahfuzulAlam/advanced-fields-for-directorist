<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

if (!defined('ABSPATH')) exit;

use Directorist_Advanced_Fields\Helper;

$lis = isset($data['value']) ? $data['value'] : array();

if (!$lis || count($lis) < 1) return;

$options = isset($data['options']) ? $data['options'] : array();

$label_list = Helper::feature_option_list($options);

$ul_html = "<ul>";
foreach ($lis as $li) {
    $ul_html .= "<li class='feature-option " . $label_list[$li]['class'] . " feature-option-" . $li . "'<span><i class='" . $label_list[$li]['icon'] . "'></i></span><span class='label'>" . $label_list[$li]['label'] . "</span></li>";
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