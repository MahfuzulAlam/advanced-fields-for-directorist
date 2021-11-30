<?php

/**
 * Plugin Name: Directorist - Advanced Fields
 * Plugin URI: #
 * Description: This is an extension for Directorist Plugin.
 * Version: 1.0.0
 * Author: M Alam
 * Author URI: #
 * License: GPLv2 or later
 * Text Domain: directorist-advanced-fields
 * Domain Path: /languages
 */

// prevent direct access to the file
defined('ABSPATH') || die('No direct script access allowed!');

add_filter('atbdp_form_custom_widgets', 'directorist_advanced_fields_custom_widgets');
add_filter('atbdp_single_listing_content_widgets', 'directorist_advanced_fields_single_listing_content_widgets');
add_filter('directorist_field_template', 'directorist_advanceed_fields_template', 10, 2);
add_filter('directorist_single_item_template', 'directorist_advanceed_fields_single_template', 10, 2);

function directorist_advanced_fields_custom_widgets($widgets)
{
    $widgets['iframe'] = array(
        'label' => 'iFrame',
        'icon' => 'uil uil-window-maximize
        ',
        'options' => [
            'type' => [
                'type'  => 'hidden',
                'value' => 'iframe',
            ],
            'label' => [
                'type'  => 'text',
                'label' => __('Label', 'directorist'),
                'value' => 'iFrame',
            ],
            'field_key' => apply_filters('directorist_custom_field_meta_key_field_args', [
                'type'  => 'hidden',
                'label' => __('Key', 'directorist'),
                'value' => 'custom-iframe',
                'rules' => [
                    'unique' => true,
                    'required' => true,
                ]
            ]),
            'placeholder' => [
                'type'  => 'text',
                'label' => __('Placeholder', 'directorist'),
                'value' => '',
            ],
            'description' => [
                'type'  => 'text',
                'label' => __('Description', 'directorist'),
                'value' => '',
            ],
            'required' => [
                'type'  => 'toggle',
                'label'  => __('Required', 'directorist'),
                'value' => false,
            ],
            'only_for_admin' => [
                'type'  => 'toggle',
                'label'  => __('Only For Admin Use', 'directorist'),
                'value' => false,
            ],
            /* 'assign_to' => [
                'type' => 'radio',
                'label' => __('Assign to', 'directorist'),
                'value' => 'form',
                'options' => [
                    [
                        'label' => __('Form', 'directorist'),
                        'value' => 'form',
                    ],
                    [
                        'label' => __('Category', 'directorist'),
                        'value' => 'category',
                    ],
                ],
            ] */
        ]

    );
    return $widgets;
}

function directorist_advanced_fields_single_listing_content_widgets($widgets)
{
    $widgets['iframe'] = [
        'options' => [
            'icon' => [
                'type'  => 'icon',
                'label' => 'Icon',
                'value' => 'las la-window-maximize',
            ],
        ]
    ];
    return $widgets;
}

function directorist_advanceed_fields_template($template, $field_data)
{
    if ('iframe' === $field_data['widget_name']) {
?>
        <div class="atbd_content_module directorist-iframe">
            <div class="atbdb_content_module_contents">
                <div class="directorist-form-group directorist-custom-field-text">

                    <?php \Directorist\Directorist_Listing_Form::instance()->field_label_template($field_data); ?>

                    <input type="text" name="<?php echo esc_attr($field_data['field_key']); ?>" id="<?php echo esc_attr($field_data['field_key']); ?>" class="directorist-form-element" value="<?php echo esc_attr($field_data['value']); ?>" placeholder="<?php echo esc_attr($field_data['placeholder']); ?>" <?php Directorist\Directorist_Listing_Form::instance()->required($field_data); ?>>

                    <?php \Directorist\Directorist_Listing_Form::instance()->field_description_template($field_data); ?>

                </div>

            </div>
        </div>
    <?php
    }
    return $template;
}


function directorist_advanceed_fields_single_template($template, $field_data)
{
    if ('iframe' === $field_data['widget_name']) {
    ?>
        <div class="directorist-single-info directorist-single-info-iframe">

            <div class="directorist-single-info__label">
                <span class="directorist-single-info__label-icon"><?php directorist_icon($field_data['icon']); ?></span>
                <span class="directorist-single-info__label--text"><?php echo esc_html($field_data['label']); ?></span>
            </div>

            <div class="directorist-single-info__value"><?php echo $field_data['value']; ?></div>

        </div>
<?php
    }
    return $template;
}
