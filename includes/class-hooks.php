<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 2.2.0
 */

namespace Directorist_Advanced_Fields;

defined( 'ABSPATH' ) || exit;

class Daf_Hooks {

    public function __construct() {
        add_filter( 'atbdp_listing_meta_admin_submission', array( $this, 'sanitize_admin_listing_meta' ), 10, 2 );
        add_filter( 'atbdp_ultimate_listing_meta_user_submission', array( $this, 'sanitize_user_listing_meta' ), 10, 2 );
    }

    public function sanitize_admin_listing_meta( $meta_data, $posted_data ) {
        return $this->sanitize_plugin_listing_meta( $meta_data, $posted_data );
    }

    public function sanitize_user_listing_meta( $meta_data, $posted_data ) {
        return $this->sanitize_plugin_listing_meta( $meta_data, $posted_data );
    }

    private function sanitize_plugin_listing_meta( $meta_data, $posted_data ) {
        if ( ! is_array( $meta_data ) || ! function_exists( 'directorist_get_listing_form_fields' ) ) {
            return $meta_data;
        }

        $directory_id = $this->get_directory_id( $meta_data, $posted_data );
        if ( ! $directory_id ) {
            return $meta_data;
        }

        $form_fields = directorist_get_listing_form_fields( $directory_id );
        if ( ! is_array( $form_fields ) ) {
            return $meta_data;
        }

        foreach ( $form_fields as $field ) {
            $field_key   = isset( $field['field_key'] ) ? (string) $field['field_key'] : '';
            $widget_name = isset( $field['widget_name'] ) ? (string) $field['widget_name'] : '';

            if ( '' === $field_key || '' === $widget_name ) {
                continue;
            }

            $meta_key = '_' . $field_key;
            if ( ! array_key_exists( $meta_key, $meta_data ) ) {
                continue;
            }

            $raw_value = $this->get_submitted_value( $posted_data, $field_key, $meta_data[ $meta_key ] );

            switch ( $widget_name ) {
                case 'iframe':
                    $meta_data[ $meta_key ] = Helper::sanitize_iframe_html( $raw_value );
                    break;

                case 'wp-editor':
                    $meta_data[ $meta_key ] = wp_kses_post( (string) $raw_value );
                    break;

                case 'addresses':
                    $meta_data[ $meta_key ] = $this->sanitize_addresses_value( $raw_value );
                    break;

                case 'repeater':
                    $meta_data[ $meta_key ] = $this->sanitize_repeater_value( $raw_value, $field );
                    break;

                case 'youtube-video':
                case 'vimeo-video':
                    $meta_data[ $meta_key ] = esc_url_raw( (string) $raw_value );
                    break;
            }
        }

        return $meta_data;
    }

    private function get_directory_id( $meta_data, $posted_data ) {
        if ( isset( $posted_data['directory_id'] ) ) {
            return absint( wp_unslash( $posted_data['directory_id'] ) );
        }

        if ( isset( $meta_data['_directory_type'] ) ) {
            return absint( $meta_data['_directory_type'] );
        }

        if ( empty( $posted_data['directory_type'] ) ) {
            return 0;
        }

        $directory_type = wp_unslash( $posted_data['directory_type'] );

        if ( is_numeric( $directory_type ) ) {
            return absint( $directory_type );
        }

        if ( defined( 'ATBDP_TYPE' ) ) {
            $directory_term = get_term_by( 'slug', sanitize_title( (string) $directory_type ), ATBDP_TYPE );
            if ( $directory_term && ! is_wp_error( $directory_term ) ) {
                return absint( $directory_term->term_id );
            }
        }

        return 0;
    }

    private function get_submitted_value( $posted_data, $field_key, $default = '' ) {
        if ( is_array( $posted_data ) && array_key_exists( $field_key, $posted_data ) ) {
            return wp_unslash( $posted_data[ $field_key ] );
        }

        return $default;
    }

    private function sanitize_addresses_value( $value ) {
        $addresses = $this->decode_json_array( $value );
        if ( empty( $addresses ) ) {
            return '';
        }

        $sanitized_addresses = array();

        foreach ( $addresses as $address ) {
            if ( ! is_array( $address ) ) {
                continue;
            }

            $label     = isset( $address['label'] ) ? sanitize_text_field( (string) $address['label'] ) : '';
            $text      = isset( $address['address'] ) ? sanitize_text_field( (string) $address['address'] ) : '';
            $latitude  = $this->sanitize_coordinate( $address['latitude'] ?? '' );
            $longitude = $this->sanitize_coordinate( $address['longitude'] ?? '' );

            if ( '' === $label && '' === $text && '' === $latitude && '' === $longitude ) {
                continue;
            }

            $sanitized_addresses[] = array(
                'label'     => $label,
                'address'   => $text,
                'latitude'  => $latitude,
                'longitude' => $longitude,
            );
        }

        return ! empty( $sanitized_addresses ) ? wp_json_encode( $sanitized_addresses ) : '';
    }

    private function sanitize_coordinate( $value ) {
        $value = trim( (string) $value );

        if ( '' === $value ) {
            return '';
        }

        return preg_match( '/^-?\d+(?:\.\d+)?$/', $value ) ? $value : '';
    }

    private function sanitize_repeater_value( $value, $field ) {
        $items = $this->decode_json_array( $value );
        if ( empty( $items ) ) {
            return '';
        }

        $sub_fields       = isset( $field['options'] ) && is_array( $field['options'] ) ? $field['options'] : array();
        $sanitized_values = array();

        foreach ( $items as $item ) {
            if ( ! is_array( $item ) ) {
                continue;
            }

            $sanitized_item = array();

            foreach ( $sub_fields as $sub_field ) {
                $sub_field_key = isset( $sub_field['field_key'] ) ? (string) $sub_field['field_key'] : '';

                if ( '' === $sub_field_key || ! array_key_exists( $sub_field_key, $item ) ) {
                    continue;
                }

                $sanitized_field_value = $this->sanitize_repeater_field_value( $item[ $sub_field_key ], $sub_field );

                if ( '' === $sanitized_field_value || array() === $sanitized_field_value ) {
                    continue;
                }

                $sanitized_item[ $sub_field_key ] = $sanitized_field_value;
            }

            if ( ! empty( $sanitized_item ) ) {
                $sanitized_values[] = $sanitized_item;
            }
        }

        return ! empty( $sanitized_values ) ? wp_json_encode( $sanitized_values ) : '';
    }

    private function sanitize_repeater_field_value( $value, $field ) {
        $field_type            = isset( $field['field_type'] ) ? (string) $field['field_type'] : 'text';
        $allowed_option_values = $this->get_allowed_option_values( $field );

        switch ( $field_type ) {
            case 'textarea':
                return sanitize_textarea_field( (string) $value );

            case 'email':
                return sanitize_email( (string) $value );

            case 'url':
                return esc_url_raw( (string) $value );

            case 'color':
                return sanitize_hex_color( (string) $value ) ?: '';

            case 'number':
                $value = trim( (string) $value );
                return is_numeric( $value ) ? $value : '';

            case 'select':
            case 'radio':
                $value = sanitize_text_field( (string) $value );

                if ( ! empty( $allowed_option_values ) && ! in_array( $value, $allowed_option_values, true ) ) {
                    return '';
                }

                return $value;

            case 'checkbox':
                $values = is_array( $value ) ? $value : array( $value );
                $values = array_map(
                    static function( $item ) {
                        return sanitize_text_field( (string) $item );
                    },
                    $values
                );
                $values = array_filter( $values, 'strlen' );

                if ( ! empty( $allowed_option_values ) ) {
                    $values = array_values( array_intersect( $values, $allowed_option_values ) );
                }

                return $values;

            case 'date':
            case 'time':
            case 'text':
            default:
                return sanitize_text_field( (string) $value );
        }
    }

    private function get_allowed_option_values( $field ) {
        if ( empty( $field['field_options'] ) || ! is_array( $field['field_options'] ) ) {
            return array();
        }

        $values = array();

        foreach ( $field['field_options'] as $option ) {
            if ( ! isset( $option['option_value'] ) ) {
                continue;
            }

            $values[] = sanitize_text_field( (string) $option['option_value'] );
        }

        return array_filter( $values, 'strlen' );
    }

    private function decode_json_array( $value ) {
        if ( is_array( $value ) ) {
            return $value;
        }

        if ( ! is_string( $value ) || '' === trim( $value ) ) {
            return array();
        }

        $decoded = json_decode( $value, true );

        return is_array( $decoded ) ? $decoded : array();
    }
}

new Daf_Hooks();
