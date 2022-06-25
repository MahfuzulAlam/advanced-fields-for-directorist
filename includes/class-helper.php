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

        $is_youtube = preg_match('/youtu\.be/i', $url) || preg_match('/youtube\.com\/watch/i', $url);
        if ($is_youtube) {
            $pattern = '/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/';
            preg_match($pattern, $url, $matches);
            if (count($matches) && strlen($matches[7]) == 11) {
                $embeddable_url = 'https://www.youtube.com/embed/' . $matches[7];
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
}
