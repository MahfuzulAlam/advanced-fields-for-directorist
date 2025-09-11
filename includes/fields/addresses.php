<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

namespace Directorist_Advanced_Fields;

class Advanced_Fields_Address_List
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
        $widgets['addresses'] = [
                'label'   => __('Address List', 'directorist-advanced-fields'),
                'icon'    => 'la la-map',
                'options' => [
                    'type' => [
                        'type'  => 'hidden',
                        'value' => 'text',
                    ],
                    'field_key' => [
                        'type'  => 'hidden',
                        'value' => 'addresses',
                        'rules' => [
                            'unique'   => true,
                            'required' => true,
                        ]
                    ],
                    'label' => [
                        'type'  => 'text',
                        'label' => __('Label', 'directorist-advanced-fields'),
                        'value' => 'Address List',
                    ],
                    'placeholder' => [
                        'type'  => 'text',
                        'label' => __('Placeholder', 'directorist-advanced-fields'),
                        'value' => __('Select a place from google', 'directorist-advanced-fields'),
                    ],
                    // 'required' => [
                    //     'type'  => 'toggle',
                    //     'label' => __('Required', 'directorist-advanced-fields'),
                    //     'value' => false,
                    // ],
                    'limit' => [
                        'type'  => 'number',
                        'label' => __('Limit', 'directorist-advanced-fields'),
                        'description' => __('Set a limit on the address list field. 0 or empty means unlimited addresses', 'directorist-advanced-fields'),
                    ],
                     'is_label' => [
                        'type'  => 'toggle',
                        'label' => __('Allow Label', 'directorist-advanced-fields'),
                        'value' => false,
                    ],
                    'only_for_admin' => [
                        'type'  => 'toggle',
                        'label' => __('Admin Only', 'directorist-advanced-fields'),
                        'value' => false,
                    ],
                ],
            ];

            return $widgets;
    }

    public function atbdp_single_listing_content_widgets($widgets)
    {
        $widgets['addresses'] = [
            'options' => [
                'icon' => [
                    'type'  => 'icon',
                    'label' => 'Icon',
                    'value' => 'la la-map',
                ],
            ]
        ];
        return $widgets;
    }

    public function directorist_field_template($template, $field_data)
    {
        if ($field_data['widget_name'] == 'addresses') {
            $addresses = isset($field_data['value']) && !empty($field_data['value']) ? json_decode($field_data['value'], true) : [];
            Helper::get_template_part('listing-form/addresses', ['data' => $field_data, 'addresses'=> $addresses]);
        }
        return $template;
    }


    public function directorist_single_item_template($template, $field_data)
    {
        if ($field_data['widget_name'] == 'addresses') {
            $addresses = isset($field_data['value']) && !empty($field_data['value']) ? json_decode($field_data['value'], true) : [];
            if( count( $addresses ) > 0 ){
                Helper::get_template_part('single/addresses', ['data' => $field_data, 'addresses'=> $addresses]);
            }
        }
        return $template;
    }
}

new Advanced_Fields_Address_List;
