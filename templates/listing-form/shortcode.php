<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

?>

<div class="atbd_content_module directorist-iframe <?php echo esc_attr($data['class']) ?>">
    <div class="atbdb_content_module_contents">
        <div class="directorist-form-group directorist-custom-field-text">

            <?php \Directorist\Directorist_Listing_Form::instance()->field_label_template($data); ?>

            <input type="text" name="<?php echo esc_attr($data['field_key']); ?>" id="<?php echo esc_attr($data['field_key']); ?>" class="directorist-form-element" value="<?php echo esc_attr($data['value']); ?>" placeholder="<?php echo esc_attr($data['placeholder']); ?>" <?php Directorist\Directorist_Listing_Form::instance()->required($data); ?> />

            <?php \Directorist\Directorist_Listing_Form::instance()->field_description_template($data); ?>

        </div>

    </div>
</div>