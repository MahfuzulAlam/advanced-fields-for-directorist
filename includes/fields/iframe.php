<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

namespace Directorist_Advanced_Fields;

class Advanced_Fields_Iframe
{

    public function __construct()
    {
        add_filter('atbdp_form_custom_widgets', array($this, 'atbdp_form_custom_widgets'));
        add_filter('atbdp_single_listing_content_widgets', array($this, 'atbdp_single_listing_content_widgets'));
        add_filter('directorist_field_template', array($this, 'directorist_field_template'), 10, 2);
        add_filter('directorist_single_item_template', array($this, 'directorist_single_item_template'), 10, 2);
    }

    public function atbdp_form_custom_widgets($widgets)
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
                'class' => [
                    'type'  => 'text',
                    'label' => __('Class', 'directorist'),
                    'value' => 'directorist-field-iframe',
                ],
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

    public function directorist_field_template($template, $field_data)
    {
        if ('iframe' === $field_data['widget_name']) {
            Helper::get_template_part('listing-form/iframe', $field_data);
        }
        return $template;
    }


    public function directorist_single_item_template($template, $field_data)
    {
        if ('iframe' === $field_data['widget_name']) {
            Helper::get_template_part('single/iframe', $field_data);
        }
        return $template;
    }
}

new Advanced_Fields_Iframe;
