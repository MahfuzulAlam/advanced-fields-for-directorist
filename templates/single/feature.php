<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

if (!defined('ABSPATH')) exit;

use Directorist_Advanced_Fields\Helper;

$values = isset($data['value']) ? $data['value'] : array();

if (!$values || count($values) < 1) return;

$options = isset($data['options']) ? $data['options'] : array();

$default_icon = isset($data['feature_icon']) && !empty($data['feature_icon']) ? $data['feature_icon'] : 'las la-check';
$icon_color = isset($data['feature_color']) && !empty($data['feature_color']) ? sanitize_hex_color( $data['feature_color'] ) : '#000000';
$icon_color = $icon_color ? $icon_color : '#000000';

$label_list = Helper::feature_option_list($options);

$widget_label = isset($data['label']) ? $data['label'] : '';
?>

<div class="directorist-single-info directorist-single-info__feature__list directorist-single-info__list">

    <?php if ($widget_label) : ?>
        <div class="directorist-single-info__label">
            <span class="directorist-single-info__label-icon"><?php directorist_icon($data['icon']); ?></span>
            <span class="directorist-single-info__label--text"><?php echo esc_html($widget_label); ?></span>
        </div>
    <?php endif; ?>

    <div class="directorist-single-info__value">
        <ul>
            <?php foreach ( $values as $value ) : ?>
                <?php
                $option = isset( $label_list[ $value ] ) && is_array( $label_list[ $value ] ) ? $label_list[ $value ] : array();
                $label  = isset( $option['label'] ) ? (string) $option['label'] : '';

                if ( '' === $label ) {
                    continue;
                }

                $icon = ! empty( $option['icon'] ) ? sanitize_text_field( (string) $option['icon'] ) : sanitize_text_field( $default_icon );
                $class_list = ! empty( $option['class'] ) ? preg_split( '/\s+/', trim( (string) $option['class'] ) ) : array( (string) $value );
                $class_list = array_filter(
                    array_map(
                        static function ( $class_name ) {
                            return sanitize_html_class( $class_name );
                        },
                        (array) $class_list
                    )
                );
                $item_classes = array( 'feature-option' );

                foreach ( $class_list as $class_name ) {
                    $item_classes[] = 'feature-' . $class_name;
                }
                ?>
                <li class="<?php echo esc_attr( implode( ' ', $item_classes ) ); ?>">
                    <span class="icon">
                        <i class="<?php echo esc_attr( $icon ); ?>" style="<?php echo esc_attr( 'color:' . $icon_color . ';' ); ?>"></i>
                    </span>
                    <span class="label"><?php echo esc_html( $label ); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

</div>
