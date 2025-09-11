<?php
/**
 * @author  mahfuz
 * @since   2.1.0
 * @version 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
extract( $data );
$limit = isset($data['limit']) && !empty($data['limit']) ? $data['limit']: 0;
?>

<div class="directorist-form-group directorist-form-multi-address-field">

    <label class="directorist-form-label" for="addresses"><?php echo $data['label'];?></label>

    <input type="hidden" id="addresses_limit" value="<?php echo esc_attr( $limit ); ?>" />

    <!-- Holder for all address items -->
    <div class="address_field_holder">
        <?php if ( ! empty( $addresses ) ) : ?>
            <?php foreach ( $addresses as $index => $address ) : ?>
                <div class="address_item">
                    <?php if ( isset($data['is_label']) && $data['is_label'] ) : ?>
                        <input 
                            type="text" 
                            autocomplete="off" 
                            name="address_labels[]" 
                            class="directorist-form-element address_label" 
                            placeholder="Enter label (e.g., Main Branch)"
                            value="<?php echo esc_attr( $address['label'] ?? '' ); ?>"
                        >
                    <?php endif; ?>
                    <input 
                        type="text" 
                        autocomplete="off" 
                        name="addresses[]" 
                        class="directorist-form-element google_addresses" 
                        placeholder="Enter address"
                        value="<?php echo esc_attr( $address['address'] ?? '' ); ?>"
                    >
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
                        class="remove_address_btn" 
                        style="<?php echo $index === 0 ? 'display:none;' : ''; ?>"
                    >
                        X
                    </button>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <!-- Default empty item if no saved addresses -->
            <div class="address_item">
                <?php if ( isset($data['is_label']) && $data['is_label'] ) : ?>
                    <input type="text" autocomplete="off" name="address_labels[]" class="directorist-form-element address_label" placeholder="Enter label (e.g., Main Branch)">
                <?php endif; ?>
                <input type="text" autocomplete="off" name="addresses[]" class="directorist-form-element google_addresses" placeholder="Enter address">
                <input type="hidden" class="google_addresses_lat" name="latitude[]" value="">
                <input type="hidden" class="google_addresses_lng" name="longitude[]" value="">
                <button type="button" class="remove_address_btn" style="display:none;">X</button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Add new address button -->
    <button type="button" class="add_address_btn">+ Add Address</button>

    <!-- Hidden field for storing JSON encoded addresses -->
    <input 
        type="hidden" 
        name="<?php echo esc_attr( $data['field_key'] ); ?>" 
        class="google_addresses_json" 
        value="<?php echo esc_attr( $data['value'] ); ?>"
    >

</div>