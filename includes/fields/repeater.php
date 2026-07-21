<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

namespace Directorist_Advanced_Fields;

defined( 'ABSPATH' ) || exit;

use Directorist_Advanced_Fields\Helper;

class Advanced_Fields_Repeater
{

    public function __construct()
    {
        add_filter('atbdp_form_advanced_widgets', array($this, 'atbdp_form_advanced_widgets'));
        add_filter('atbdp_single_listing_content_widgets', array($this, 'atbdp_single_listing_content_widgets'));
        add_filter('directorist_field_template', array($this, 'directorist_field_template'), 10, 2);
        add_filter('directorist_single_item_template', array($this, 'directorist_single_item_template'), 10, 2);
    }

    public function atbdp_form_advanced_widgets($widgets)
    {
        $widgets['repeater'] = array(
            'label' => 'Repeater',
            'icon' => 'las la-list-alt',
            'options' => [
                'type' => [
                    'type'  => 'hidden',
                    'value' => 'repeater',
                ],
                'label' => [
                    'type'  => 'text',
                    'label' => __('Label', 'directorist-advanced-fields'),
                    'value' => 'Repeater',
                ],
                'show_label' => [
                    'type'  => 'toggle',
                    'label' => __('Show Label', 'directorist-advanced-fields'),
                    'value' => true,
                ],
                'field_key' => apply_filters('directorist_custom_field_meta_key_field_args', [
                    'type'  => 'hidden',
                    'label' => __('Key', 'directorist-advanced-fields'),
                    'value' => 'custom-repeater',
                    'rules' => [
                        'unique' => true,
                        'required' => true,
                    ],
                ]),
                'options' => [
                    'type' => 'multi-fields',
                    'label' => __('Fields', 'directorist-advanced-fields'),
                    'add-new-button-label' => __('Add Field', 'directorist-advanced-fields'),
                    'options' => [
                        'field_type' => [
                            'type'  => 'select',
                            'label' => 'Field Type',
                            'value' => 'text',
                            'options' => [
                                [
                                    'label' => __('Text', 'directorist-advanced-fields'),
                                    'value' => 'text',
                                ],
                                [
                                    'label' => __('Textarea', 'directorist-advanced-fields'),
                                    'value' => 'textarea',
                                ],
                                [
                                    'label' => __('Email', 'directorist-advanced-fields'),
                                    'value' => 'email',
                                ],
                                [
                                    'label' => __('Date', 'directorist-advanced-fields'),
                                    'value' => 'date',
                                ],
                                [
                                    'label' => __('Time', 'directorist-advanced-fields'),
                                    'value' => 'time',
                                ],
                                [
                                    'label' => __('Color', 'directorist-advanced-fields'),
                                    'value' => 'color',
                                ],
                                [
                                    'label' => __('Number', 'directorist-advanced-fields'),
                                    'value' => 'number',
                                ],
                                [
                                    'label' => __('URL', 'directorist-advanced-fields'),
                                    'value' => 'url',
                                ],
                                [
                                    'label' => __('Radio', 'directorist-advanced-fields'),
                                    'value' => 'radio',
                                ],
                                [
                                    'label' => __('Select', 'directorist-advanced-fields'),
                                    'value' => 'select',
                                ],
                                [
                                    'label' => __('Checkbox', 'directorist-advanced-fields'),
                                    'value' => 'checkbox',
                                ],
                            ],
                        ],
                        'field_key' => [
                            'type'  => 'text',
                            'label' => 'field_key',
                            'value' => 'repeater_field_key',
                            'rules' => [
                                'unique' => true,
                                'required' => true,
                            ],
                        ],
                        'field_label' => [
                            'type'  => 'text',
                            'label' => 'Field Label',
                            'value' => 'Label',
                        ],
                        'field_placeholder' => [
                            'type'  => 'text',
                            'label' => __('Field Placeholder', 'directorist-advanced-fields'),
                            'value' => '',
                        ],
                        'field_description' => [
                            'type'  => 'text',
                            'label' => __('Field Description', 'directorist-advanced-fields'),
                            'value' => '',
                        ],
                        'field_class' => [
                            'type'  => 'text',
                            'label' => 'Field Class',
                            'value' => '',
                        ],
                        'field_options' => [
                            'type'  => 'multi-fields',
                            'show_if' => [
                                'where' => "self.field_type",
                                'compare' => 'or',
                                'conditions' => [
                                    ['key' => 'value', 'compare' => '=', 'value' => 'select'],
                                    ['key' => 'value', 'compare' => '=', 'value' => 'checkbox'],
                                    ['key' => 'value', 'compare' => '=', 'value' => 'radio'],
                                ],
                            ],
                            'label' => __('Options', 'directorist-advanced-fields'),
                            'add-new-button-label' => __('Add Option', 'directorist-advanced-fields'),
                            'value' => '',
                            'options' => [
                                'option_value' => [
                                    'type'  => 'text',
                                    'label' => __('Option Value', 'directorist-advanced-fields'),
                                    'value' => '',
                                ],
                                'option_label' => [
                                    'type'  => 'text',
                                    'label' => __('Option Label', 'directorist-advanced-fields'),
                                    'value' => '',
                                ],
                            ],
                        ],
                    ]
                ],
                'class' => [
                    'type'  => 'text',
                    'label' => __('Class', 'directorist-advanced-fields'),
                    'value' => 'directorist-field-repeater',
                ],
                'description' => [
                    'type'  => 'text',
                    'label' => __('Description', 'directorist-advanced-fields'),
                    'value' => '',
                ],
                'required' => [
                    'type'  => 'toggle',
                    'label'  => __('Required', 'directorist-advanced-fields'),
                    'value' => false,
                ],
                'only_for_admin' => [
                    'type'  => 'toggle',
                    'label'  => __('Only For Admin Use', 'directorist-advanced-fields'),
                    'value' => false,
                ],
                'conditional_logic' => Helper::get_conditional_logic_field(),
            ]

        );
        return $widgets;
    }

    public function atbdp_single_listing_content_widgets($widgets)
    {
        $widgets['repeater'] = [
            'options' => [
                'icon' => [
                    'type'  => 'icon',
                    'label' => 'Icon',
                    'value' => 'las la-list-alt',
                ],
                'label_enabled' => [
                    'type'  => 'toggle',
                    'label' => __('Display Label', 'directorist-advanced-fields'),
                    'value' => true,
                ],
            ]
        ];
        return $widgets;
    }

    public function directorist_field_template($template, $field_data)
    {
        if ('repeater' === $field_data['widget_name']) {
            Helper::get_template_part('listing-form/repeater', $field_data);
        }
        return $template;
    }

    public function directorist_single_item_template($template, $field_data)
    {
        if ('repeater' === $field_data['widget_name']) {
            Helper::get_template_part('single/repeater', $field_data);
        }
        return $template;
    }
}

new Advanced_Fields_Repeater;
