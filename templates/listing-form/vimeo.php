<?php

/**
 * @author  mahfuz
 * @since   6.6
 * @version 6.7
 */

if (!defined('ABSPATH')) exit;
?>

<div class="directorist-form-group directorist-form-video-field directorist-form-youtube-field <?php echo $data['class']; ?>">

    <?php \Directorist\Directorist_Listing_Form::instance()->field_label_template($data); ?>

    <input type="url" name="<?php echo esc_attr($data['field_key']); ?>" id="<?php echo esc_attr($data['field_key']); ?>" class="directorist-form-element" value="<?php echo esc_attr($data['value']); ?>" placeholder="<?php echo esc_attr($data['placeholder']); ?>" <?php \Directorist\Directorist_Listing_Form::instance()->required($data); ?>>

    <?php \Directorist\Directorist_Listing_Form::instance()->field_description_template($data); ?>

</div>