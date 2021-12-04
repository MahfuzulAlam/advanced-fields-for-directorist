<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

namespace Directorist_Advanced_Fields;

class Advanced_Fields_Youtube
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
        $widgets['youtube-video'] = array(
            'label' => 'Youtube Video',
            'icon' => 'uil uil-youtube',
            'options' => [
                'type' => [
                    'type'  => 'hidden',
                    'value' => 'text',
                ],
                'field_key' => [
                    'type'   => 'hidden',
                    'value'  => 'yturl',
                    'rules' => [
                        'unique' => true,
                        'required' => true,
                    ]
                ],
                'label' => [
                    'type'  => 'text',
                    'label' => __('Label', 'directorist'),
                    'value' => 'Youtube Video',
                ],
                'placeholder' => [
                    'type'  => 'text',
                    'label' => __('Placeholder', 'directorist'),
                    'value' => 'Only YouTube URLs.',
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
        $widgets['youtube-video'] = [
            'options' => [
                'icon' => [
                    'type'  => 'icon',
                    'label' => 'Icon',
                    'value' => 'lab la-youtube',
                ],
            ]
        ];
        return $widgets;
    }

    public function directorist_field_template($template, $field_data)
    {
        if ('youtube-video' === $field_data['widget_name']) {
            Helper::get_template_part('listing-form/youtube', $field_data);
        }
        return $template;
    }


    public function directorist_single_item_template($template, $field_data)
    {
        if ('youtube-video' === $field_data['widget_name']) {
            Helper::get_template_part('single/youtube', $field_data);
        }
        return $template;
    }
}

new Advanced_Fields_Youtube;
