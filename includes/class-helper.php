<?php

/**
 * @author  wpWax
 * @since   1.0
 * @version 1.0
 */

namespace Directorist_Advanced_Fields;

use Directorist_Advanced_Fields;

class Helper
{

    public static function get_file_uri($path)
    {
        $file = Directorist_Advanced_Fields::$base_url . $path;

        return $file;
    }

    public static function get_file_dir()
    {
        $file = Directorist_Advanced_Fields::$base_dir;

        return $file;
    }

    public static function get_template_part($template, $data = array())
    {
        $listing_form = isset($data['form']) ? $data['form'] : \Directorist\Directorist_Listing_Form::instance();

        $conditional_logic_attr = isset($listing_form) ? $listing_form->get_conditional_logic_attributes( $data ) : '';

        

        $template = '/templates/' . $template . '.php';

        $file = self::get_file_dir() . $template;

        require $file;
    }

    public static function get_directorist_option($name, $default = false, $force_default = false)
    {
        // at first get the group of options from the database.
        // then check if the data exists in the array and if it exists then return it
        // if not, then return false
        if (empty($name)) {
            return $default;
        }
        // get the option from the database and return it if it is not a null value. Otherwise, return the default value
        $options = (array) get_option('atbdp_option');
        $v       = (array_key_exists($name, $options))
            ? $v     = $options[sanitize_key($name)]
            : null;

        $newvalue = apply_filters('directorist_option', $v, $name);

        if ($newvalue != $v) {
            return $newvalue;
        }

        // use default only when the value of the $v is NULL
        if (is_null($v)) {
            return $default;
        }
        if ($force_default) {
            // use the default value even if the value of $v is falsy value returned from the database
            if (empty($v)) {
                return $default;
            }
        }

        return (isset($v)) ? $v : $default; // return the data if it is anything but NULL.
    }

    public static function parse_youtube($url)
    {
        $embeddable_url = '';

        $is_youtube = preg_match('/youtu\.be/i', $url) || preg_match('/youtube\.com\/watch/i', $url) || preg_match('/youtube\.com\/shorts/i', $url);
        if ($is_youtube) {
            $pattern = '/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(shorts\/)|(watch\?))\??v?=?([^#\&\?]*).*/';
            preg_match($pattern, $url, $matches);
            if (count($matches) && strlen($matches[8]) == 11) {
                $embeddable_url = 'https://www.youtube.com/embed/' . $matches[8];
            }
        }

        return $embeddable_url;
    }

    public static function parse_vimeo($url)
    {
        $embeddable_url = '';

        $is_vimeo = preg_match('/vimeo\.com/i', $url);
        if ($is_vimeo) {
            $pattern = '/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/';
            preg_match($pattern, $url, $matches);
            if (count($matches)) {
                $embeddable_url = 'https://player.vimeo.com/video/' . $matches[2];
            }
        }

        return $embeddable_url;
    }

    public static function feature_get_label($options, $value)
    {
        $result = array_filter($options, function ($option) use ($value) {
            return ($option['option_value'] == $value);
        });
        e_var_dump($result);
        return $result && count($result) > 0 ? $result[0]['option_label'] : '';
    }

    public static function feature_option_list($options)
    {
        $new_options = array();
        if ($options && count($options) > 0) {
            foreach ($options as $option) {
                $new_options[$option['option_value']]['class'] = isset($option['option_class']) && !empty($option['option_class']) ? $option['option_class'] : '';
                $new_options[$option['option_value']]['icon'] = isset($option['option_icon']) && !empty($option['option_icon']) ? $option['option_icon'] : '';
                $new_options[$option['option_value']]['label'] = isset($option['option_label']) && !empty($option['option_label']) ? $option['option_label'] : '';
            }
        }
        return $new_options;
    }

    public static function display_repeater_field($field = array(), $value = '', $parent_key = '', $index = 0)
    {
        $field_key = isset($field['field_key']) ? $field['field_key'] : '';
        $field_type = isset($field['field_type']) ? $field['field_type'] : 'text';
        $field_placeholder = isset($field['field_placeholder']) ? $field['field_placeholder'] : '';
        $field_class = isset($field['field_class']) ? $field['field_class'] : '';
        $field_options = isset($field['field_options']) ? $field['field_options'] : array();
        
        // Generate field name
        $field_name = $parent_key . '[' . $index . '][' . $field_key . ']';
        $field_id = $field_key . '_' . $index;
        
        // Add field class
        $classes = 'directorist-form-element';
        if ($field_class) {
            $classes .= ' ' . $field_class;
        }
        
        switch ($field_type) {
            case 'text':
                echo '<input type="text" name="' . esc_attr($field_name) . '" id="' . esc_attr($field_id) . '" class="' . esc_attr($classes) . '" value="' . esc_attr($value) . '" placeholder="' . esc_attr($field_placeholder) . '" />';
                break;
                
            case 'textarea':
                echo '<textarea name="' . esc_attr($field_name) . '" id="' . esc_attr($field_id) . '" class="' . esc_attr($classes) . '" placeholder="' . esc_attr($field_placeholder) . '">' . esc_textarea($value) . '</textarea>';
                break;
                
            case 'email':
                echo '<input type="email" name="' . esc_attr($field_name) . '" id="' . esc_attr($field_id) . '" class="' . esc_attr($classes) . '" value="' . esc_attr($value) . '" placeholder="' . esc_attr($field_placeholder) . '" />';
                break;
                
            case 'number':
                echo '<input type="number" name="' . esc_attr($field_name) . '" id="' . esc_attr($field_id) . '" class="' . esc_attr($classes) . '" value="' . esc_attr($value) . '" placeholder="' . esc_attr($field_placeholder) . '" />';
                break;
                
            case 'date':
                echo '<input type="date" name="' . esc_attr($field_name) . '" id="' . esc_attr($field_id) . '" class="' . esc_attr($classes) . '" value="' . esc_attr($value) . '" placeholder="' . esc_attr($field_placeholder) . '" />';
                break;
                
            case 'time':
                echo '<input type="time" name="' . esc_attr($field_name) . '" id="' . esc_attr($field_id) . '" class="' . esc_attr($classes) . '" value="' . esc_attr($value) . '" placeholder="' . esc_attr($field_placeholder) . '" />';
                break;
                
            case 'color':
                echo '<input type="color" name="' . esc_attr($field_name) . '" id="' . esc_attr($field_id) . '" class="' . esc_attr($classes) . '" value="' . esc_attr($value) . '" />';
                break;
                
            case 'url':
                echo '<input type="url" name="' . esc_attr($field_name) . '" id="' . esc_attr($field_id) . '" class="' . esc_attr($classes) . '" value="' . esc_attr($value) . '" placeholder="' . esc_attr($field_placeholder) . '" />';
                break;
                
            case 'select':
                echo '<select name="' . esc_attr($field_name) . '" id="' . esc_attr($field_id) . '" class="' . esc_attr($classes) . '" data-options=\'' . json_encode($field_options) . '\'>';
                echo '<option value="">' . esc_html($field_placeholder) . '</option>';
                if (!empty($field_options) && is_array($field_options)) {
                    foreach ($field_options as $option) {
                        $option_value = isset($option['option_value']) ? $option['option_value'] : '';
                        $option_label = isset($option['option_label']) ? $option['option_label'] : $option_value;
                        $selected = ($value == $option_value) ? ' selected="selected"' : '';
                        echo '<option value="' . esc_attr($option_value) . '"' . $selected . '>' . esc_html($option_label) . '</option>';
                    }
                }
                echo '</select>';
                break;
                
            case 'radio':
                if (!empty($field_options) && is_array($field_options)) {
                    echo '<div class="radio-group">';
                    foreach ($field_options as $option) {
                        $option_value = isset($option['option_value']) ? $option['option_value'] : '';
                        $option_label = isset($option['option_label']) ? $option['option_label'] : $option_value;
                        $checked = ($value == $option_value) ? ' checked="checked"' : '';
                        echo '<div class="radio-item">';
                        echo '<input type="radio" name="' . esc_attr($field_name) . '" id="' . esc_attr($field_id . '_' . $option_value) . '" value="' . esc_attr($option_value) . '"' . $checked . ' />';
                        echo '<label for="' . esc_attr($field_id . '_' . $option_value) . '">' . esc_html($option_label) . '</label>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
                break;
                
            case 'checkbox':
                if (!empty($field_options) && is_array($field_options)) {
                    echo '<div class="checkbox-group">';
                    foreach ($field_options as $option) {
                        $option_value = isset($option['option_value']) ? $option['option_value'] : '';
                        $option_label = isset($option['option_label']) ? $option['option_label'] : $option_value;
                        $checked = (is_array($value) && in_array($option_value, $value)) ? ' checked="checked"' : '';
                        echo '<div class="checkbox-item">';
                        echo '<input type="checkbox" name="' . esc_attr($field_name) . '[]" id="' . esc_attr($field_id . '_' . $option_value) . '" value="' . esc_attr($option_value) . '"' . $checked . ' />';
                        echo '<label for="' . esc_attr($field_id . '_' . $option_value) . '">' . esc_html($option_label) . '</label>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
                break;
                
            default:
                echo '<input type="text" name="' . esc_attr($field_name) . '" id="' . esc_attr($field_id) . '" class="' . esc_attr($classes) . '" value="' . esc_attr($value) . '" placeholder="' . esc_attr($field_placeholder) . '" />';
                break;
        }
    }

    public static function get_conditional_logic_field( $field = array() ) {
        return [
            'type'        => 'conditional-logic',
            'label'       => __( 'Conditional Logic', 'directorist' ),
            'description' => __( 'Show or hide this field based on other field values.', 'directorist' ),
            'value'       => [
                'enabled' => false,
                'action'  => 'show',
                'groups'  => [],
            ],
        ];
    }
}
