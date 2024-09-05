<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

namespace Directorist_Advanced_Fields;

class Advanced_Fields_WP_Editor
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
        $widgets['wp-editor'] = array(
            'label' => 'WP Editor',
            'icon' => 'uil uil-align-left',
            'options' => [
                'type' => [
                    'type'  => 'hidden',
                    'value' => 'text',
                ],
                'field_key' => apply_filters('directorist_custom_field_meta_key_field_args', [
                    'type'  => 'hidden',
                    'label' => __('Key', 'directorist'),
                    'value' => 'custom-wp-editor',
                    'rules' => [
                        'unique' => true,
                        'required' => true,
                    ]
                ]),
                'class' => [
                    'type'  => 'text',
                    'label' => __('Class', 'directorist'),
                    'value' => 'directorist-field-wp-editor',
                ],
                'label' => [
                    'type'  => 'text',
                    'label' => __('Label', 'directorist'),
                    'value' => 'WP Editor',
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
            ],
        );
        return $widgets;
    }

    public function atbdp_single_listing_content_widgets($widgets)
    {
        $widgets['wp-editor'] = [
            'options' => [
                'icon' => [
                    'type'  => 'icon',
                    'label' => 'Icon',
                    'value' => 'uil uil-align-left',
                ],
            ]
        ];
        return $widgets;
    }

    public function directorist_field_template($template, $field_data)
    {
        if ('wp-editor' === $field_data['widget_name']) {
            Helper::get_template_part('listing-form/wp-editor', $field_data);
        }
        return $template;
    }


    public function directorist_single_item_template($template, $field_data)
    {
        if ('wp-editor' === $field_data['widget_name']) {
            if (!empty($field_data['value'])) {
                Helper::get_template_part('single/wp-editor', $field_data);
            }
        }
        return $template;
    }
}

new Advanced_Fields_WP_Editor;
