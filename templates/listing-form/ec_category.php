<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

?>

<?php

$event_category = get_terms(
    'tribe_events_cat',
    array(
        'hide_empty' => false
    )
);

$selected_category = get_post_meta($data['form']->add_listing_id, '_ec_category', true);

?>

<div class="directorist-form-group directorist-custom-field-select <?php echo esc_attr($data['class']) ?>">

    <?php \Directorist\Directorist_Listing_Form::instance()->field_label_template($data); ?>

    <select name="<?php echo esc_attr($data['field_key']); ?>" id="<?php echo esc_attr($data['field_key']); ?>" class="directorist-form-element">

        <?php foreach ($event_category as $key => $value) : ?>

            <option value="<?php echo esc_attr($value->term_id) ?>" <?php selected($selected_category, $value->term_id); ?>><?php echo esc_attr($value->name) ?></option>

        <?php endforeach ?>

    </select>

    <?php \Directorist\Directorist_Listing_Form::instance()->field_description_template($data); ?>

</div>