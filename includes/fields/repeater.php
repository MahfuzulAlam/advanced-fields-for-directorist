<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

namespace Directorist_Advanced_Fields;

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
            'icon' => 'las la-list-alt
        ',
            'options' => [
                'type' => [
                    'type'  => 'hidden',
                    'value' => 'repeater',
                ],
                'label' => [
                    'type'  => 'text',
                    'label' => __('Label', 'directorist'),
                    'value' => 'Repeater',
                ],
                'field_key' => apply_filters('directorist_custom_field_meta_key_field_args', [
                    'type'  => 'hidden',
                    'label' => __('Key', 'directorist'),
                    'value' => 'custom-repeater',
                    'rules' => [
                        'unique' => true,
                        'required' => true,
                    ]
                ]),
                'options' => [
                    'type' => 'multi-fields',
                    'label' => __('Fields', 'directorist'),
                    'add-new-button-label' => __('Add Field', 'directorist'),
                    'options' => [
                        'field_type' => [
                            'type'  => 'select',
                            'label' => 'Field Type',
                            'value' => 'text',
                            'options' => [
                                [
                                    'label' => __('Text', 'directorist'),
                                    'value' => 'text',
                                ],
                                [
                                    'label' => __('Textarea', 'directorist'),
                                    'value' => 'textarea',
                                ],
                                [
                                    'label' => __('Email', 'directorist'),
                                    'value' => 'email',
                                ],
                                [
                                    'label' => __('Date', 'directorist'),
                                    'value' => 'date',
                                ],
                                [
                                    'label' => __('Time', 'directorist'),
                                    'value' => 'time',
                                ],
                                [
                                    'label' => __('Color', 'directorist'),
                                    'value' => 'color',
                                ],
                                [
                                    'label' => __('Number', 'directorist'),
                                    'value' => 'number',
                                ],
                                [
                                    'label' => __('URL', 'directorist'),
                                    'value' => 'url',
                                ],
                                [
                                    'label' => __('Radio', 'directorist'),
                                    'value' => 'radio',
                                ],
                                [
                                    'label' => __('Select', 'directorist'),
                                    'value' => 'select',
                                ],
                                [
                                    'label' => __('Checkbox', 'directorist'),
                                    'value' => 'checkbox',
                                ],
                            ],
                        ],
                        'field_label' => [
                            'type'  => 'text',
                            'label' => 'Field Label',
                            'value' => 'text',
                        ],
                        'field_placeholder' => [
                            'type'  => 'text',
                            'label' => __('Field Placeholder', 'directorist'),
                            'value' => '',
                        ],
                        'field_description' => [
                            'type'  => 'text',
                            'label' => __('Field Description', 'directorist'),
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
                            'label' => __('Options', 'directorist'),
                            'add-new-button-label' => __('Add Option', 'directorist'),
                            'value' => '',
                            'options' => [
                                'option_value' => [
                                    'type'  => 'text',
                                    'label' => __('Option Value', 'directorist'),
                                    'value' => '',
                                ],
                                'option_label' => [
                                    'type'  => 'text',
                                    'label' => __('Option Label', 'directorist'),
                                    'value' => '',
                                ],
                            ],
                        ],
                    ]
                ],
                'class' => [
                    'type'  => 'text',
                    'label' => __('Class', 'directorist'),
                    'value' => 'directorist-field-feature',
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
                'assign_to' => [
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
                ]
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
