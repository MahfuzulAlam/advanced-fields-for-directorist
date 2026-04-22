<?php
/**
 * @author  mahfuz
 * @since   2.1.0
 * @version 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

extract($data);
$limit           = ! empty( $data['limit'] ) ? absint( $data['limit'] ) : 0;
$has_label       = ! empty( $data['is_label'] );
$field_id_prefix = sanitize_html_class( $data['field_key'] );
$helper_text     = $has_label
    ? __( 'Add a short branch name, then choose the address from autocomplete so the map can store accurate coordinates.', 'directorist-advanced-fields' )
    : __( 'Choose each address from autocomplete so the map can store accurate coordinates.', 'directorist-advanced-fields' );
?>

<div
    class="directorist-form-group directorist-form-multi-address-field"
    data-address-limit="<?php echo esc_attr( $limit ); ?>"
    data-has-label="<?php echo $has_label ? '1' : '0'; ?>"
>
    <div class="directorist-form-multi-address-field__header">
        <label class="directorist-form-label" for="<?php echo esc_attr( $field_id_prefix . '-0-address' ); ?>">
            <?php echo esc_html( $data['label'] ); ?>
        </label>
        <p class="directorist-form-multi-address-field__hint"><?php echo esc_html( $helper_text ); ?></p>
    </div>

    <input type="hidden" class="addresses_limit" value="<?php echo esc_attr( $limit ); ?>" />

    <div class="address_field_holder">
        <?php if ( ! empty( $addresses ) ) : ?>
            <?php foreach ( $addresses as $index => $address ) : ?>
                <div class="address_item" data-index="<?php echo esc_attr( $index ); ?>">
                    <div class="address_item__index"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></div>

                    <div class="address_item__content">
                        <?php if ( $has_label ) : ?>
                            <div class="address_item__input-group">
                                <label class="address_item__input-label" for="<?php echo esc_attr( $field_id_prefix . '-' . $index . '-label' ); ?>">
                                    <?php esc_html_e( 'Label', 'directorist-advanced-fields' ); ?>
                                </label>
                                <input
                                    type="text"
                                    id="<?php echo esc_attr( $field_id_prefix . '-' . $index . '-label' ); ?>"
                                    autocomplete="off"
                                    name="address_labels[]"
                                    class="directorist-form-element address_label"
                                    placeholder="<?php echo esc_attr__( 'Main Branch', 'directorist-advanced-fields' ); ?>"
                                    value="<?php echo esc_attr( $address['label'] ?? '' ); ?>"
                                >
                            </div>
                        <?php endif; ?>

                        <div class="address_item__input-group address_item__input-group--address">
                            <label class="address_item__input-label" for="<?php echo esc_attr( $field_id_prefix . '-' . $index . '-address' ); ?>">
                                <?php esc_html_e( 'Address', 'directorist-advanced-fields' ); ?>
                            </label>
                            <input
                                type="text"
                                id="<?php echo esc_attr( $field_id_prefix . '-' . $index . '-address' ); ?>"
                                autocomplete="street-address"
                                name="addresses[]"
                                class="directorist-form-element google_addresses"
                                placeholder="<?php echo esc_attr__( 'Search for a location', 'directorist-advanced-fields' ); ?>"
                                value="<?php echo esc_attr( $address['address'] ?? '' ); ?>"
                            >
                        </div>
                    </div>

                    <input
                        type="hidden"
                        class="google_addresses_lat"
                        name="latitude[]"
                        value="<?php echo esc_attr( $address['latitude'] ?? '' ); ?>"
                    >
                    <input
                        type="hidden"
                        class="google_addresses_lng"
                        name="longitude[]"
                        value="<?php echo esc_attr( $address['longitude'] ?? '' ); ?>"
                    >

                    <button
                        type="button"
                        class="remove_address_btn<?php echo count( $addresses ) === 1 ? ' is-hidden' : ''; ?>"
                        aria-label="<?php esc_attr_e( 'Remove location', 'directorist-advanced-fields' ); ?>"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="address_item" data-index="0">
                <div class="address_item__index">01</div>

                <div class="address_item__content">
                    <?php if ( $has_label ) : ?>
                        <div class="address_item__input-group">
                            <label class="address_item__input-label" for="<?php echo esc_attr( $field_id_prefix . '-0-label' ); ?>">
                                <?php esc_html_e( 'Label', 'directorist-advanced-fields' ); ?>
                            </label>
                            <input
                                type="text"
                                id="<?php echo esc_attr( $field_id_prefix . '-0-label' ); ?>"
                                autocomplete="off"
                                name="address_labels[]"
                                class="directorist-form-element address_label"
                                placeholder="<?php echo esc_attr__( 'Main Branch', 'directorist-advanced-fields' ); ?>"
                            >
                        </div>
                    <?php endif; ?>

                    <div class="address_item__input-group address_item__input-group--address">
                        <label class="address_item__input-label" for="<?php echo esc_attr( $field_id_prefix . '-0-address' ); ?>">
                            <?php esc_html_e( 'Address', 'directorist-advanced-fields' ); ?>
                        </label>
                        <input
                            type="text"
                            id="<?php echo esc_attr( $field_id_prefix . '-0-address' ); ?>"
                            autocomplete="street-address"
                            name="addresses[]"
                            class="directorist-form-element google_addresses"
                            placeholder="<?php echo esc_attr__( 'Search for a location', 'directorist-advanced-fields' ); ?>"
                        >
                    </div>
                </div>

                <input type="hidden" class="google_addresses_lat" name="latitude[]" value="">
                <input type="hidden" class="google_addresses_lng" name="longitude[]" value="">

                <button
                    type="button"
                    class="remove_address_btn is-hidden"
                    aria-label="<?php esc_attr_e( 'Remove location', 'directorist-advanced-fields' ); ?>"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
    </div>

    <button type="button" class="add_address_btn">
        <span aria-hidden="true">+</span>
        <span><?php esc_html_e( 'Add another location', 'directorist-advanced-fields' ); ?></span>
    </button>

    <input
        type="hidden"
        name="<?php echo esc_attr( $data['field_key'] ); ?>"
        class="google_addresses_json"
        value="<?php echo esc_attr( $data['value'] ); ?>"
    >
</div>
