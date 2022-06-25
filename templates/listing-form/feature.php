<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

$data['value'] = isset($data['value']) && !empty($data['value']) ? $data['value'] : array();
?>

<div class="directorist-form-group directorist-custom-field-checkbox directorist-feature <?php echo esc_attr($data['class']) ?>">

    <?php \Directorist\Directorist_Listing_Form::instance()->field_label_template($data); ?>
    <?php if (!empty($data['options'])) : ?>

        <?php foreach ($data['options'] as $option) : ?>

            <?php $option_class = isset($option['option_class']) && !empty($option['option_class']) ? $option['option_class'] : $option['option_value']; ?>

            <?php $uniqid = $option['option_value'] . '-' . wp_rand();  ?>

            <div class="directorist-checkbox directorist-mb-10 feature-<?php echo trim($option_class); ?>">
                <input type="checkbox" id="<?php echo esc_attr($uniqid); ?>" name="<?php echo esc_attr($data['field_key']); ?>[]" value="<?php echo esc_attr($option['option_value']); ?>" <?php echo in_array($option['option_value'], $data['value']) ? 'checked="checked"' : ''; ?>>
                <label for="<?php echo esc_attr($uniqid); ?>" class="directorist-checkbox__label"><?php echo esc_html($option['option_label']); ?></label>
            </div>

        <?php endforeach; ?>

        <a href="#" class="directorist-custom-field-btn-more"><?php esc_html_e('See More', 'directorist'); ?></a>

    <?php endif; ?>
    <?php \Directorist\Directorist_Listing_Form::instance()->field_description_template($data); ?>

</div>