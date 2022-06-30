<?php

/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if (!defined('ABSPATH')) exit;

use Directorist_Advanced_Fields\Helper;

//e_var_dump($data);

?>

<div class="atbd_content_module directorist-repeater">

    <div class="directorist-form-group directorist-form-repeater-group">
        <div>
            <?php \Directorist\Directorist_Listing_Form::instance()->field_label_template($data); ?>
            <?php \Directorist\Directorist_Listing_Form::instance()->field_description_template($data); ?>
        </div>
        <input type="text" name="<?php echo esc_attr($data['field_key']); ?>" id="<?php echo esc_attr($data['field_key']); ?>" class="directorist-form-element" value="<?php echo esc_attr($data['value']); ?>" placeholder="<?php echo esc_attr($data['placeholder']); ?>" <?php Directorist\Directorist_Listing_Form::instance()->required($data); ?> />
        <div class="directorist-repeater-field-body">
            <div class="repeater-fieldset" data-id="1">
                <div class="repeater-fieldset-header">
                    <div class="fieldset-title"><?php echo $data['label']; ?></div>
                    <div class="fieldset-actions">
                        <span><a href="#" class="action-minus"><i class="las la-minus"></i></a></span>
                        <span><a href="#" class="action-plus"><i class="las la-plus"></i></a></span>
                    </div>
                </div>
                <div class="repeater-fieldset-body">
                    <?php if (isset($data['options']) && count($data['options']) > 0) : ?>
                        <?php foreach ($data['options'] as $field) : ?>
                            <div class="directorist-repeater-field">
                                <div class="directorist-repeater-field-label">
                                    <?php echo isset($field['field_label']) ? $field['field_label'] : ''; ?>
                                </div>
                                <?php Helper::display_repeater_field($field); ?>
                                <div class="directorist-repeater-field-description">
                                    <?php echo isset($field['field_description']) ? $field['field_description'] : ''; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>