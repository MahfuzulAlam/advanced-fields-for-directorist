<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

namespace Directorist_Advanced_Fields;

class Advanced_Fields_Event_Calendar_Vanue
{

    public function __construct()
    {
        if ($this->plugin_exists()) :
            add_filter('atbdp_form_advanced_widgets', array($this, 'atbdp_form_advanced_widgets'));
            add_filter('atbdp_single_listing_content_widgets', array($this, 'atbdp_single_listing_content_widgets'));
            add_filter('directorist_field_template', array($this, 'directorist_field_template'), 10, 2);
        endif;
    }

    public function atbdp_form_advanced_widgets($widgets)
    {
        $widgets['ec_category'] = array(
            'label' => 'Event Calender Category',
            'icon' => 'uil uil-window-maximize',
            'options' => [
                'type' => [
                    'type'  => 'hidden',
                    'value' => 'ec_category',
                ],
                'label' => [
                    'type'  => 'text',
                    'label' => __('Event Calendar Category', 'directorist'),
                    'value' => 'Event Calendar Category',
                ],
                'field_key' => apply_filters('directorist_custom_field_meta_key_field_args', [
                    'type'  => 'hidden',
                    'label' => __('Key', 'directorist'),
                    'value' => 'ec_category',
                    'rules' => [
                        'unique' => true,
                        'required' => true,
                    ]
                ]),
                'class' => [
                    'type'  => 'text',
                    'label' => __('Class', 'directorist'),
                    'value' => 'directorist-field-ec-category',
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
        $widgets['ec_category'] = [
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
        if ('ec_category' === $field_data['widget_name']) {
            Helper::get_template_part('listing-form/ec_category', $field_data);
        }
        return $template;
    }

    public function plugin_exists()
    {
        return is_plugin_active('the-events-calendar/the-events-calendar.php') ? true : false;
    }
}

new Advanced_Fields_Event_Calendar_Vanue;
