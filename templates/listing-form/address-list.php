<?php
/**
 * @author  mahfuz
 * @since   2.1.0
 * @version 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$latitude = get_post_meta( get_the_ID(), $data['field_key'] . '_lat', true );
$longitude = get_post_meta( get_the_ID(), $data['field_key'] . '_lng', true );

?>

<div class="directorist-form-group directorist-form-address-field">

	<?php \Directorist\Directorist_Listing_Form::instance()->field_label_template( $data );?>

	<input type="text" autocomplete="off" name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element directorist-address-js" value="<?php echo esc_attr( $data['value'] ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php \Directorist\Directorist_Listing_Form::instance()->required( $data ); ?>>

	<div class="address_result"><ul></ul></div>

    <input type="hidden" name="<?php echo esc_attr( $data['field_key'] ); ?>_lat" id="<?php echo esc_attr( $data['field_key'] ); ?>_lat" class="directorist-form-element" value="<?php echo $latitude; ?>">

    <input type="hidden" name="<?php echo esc_attr( $data['field_key'] ); ?>_lng" id="<?php echo esc_attr( $data['field_key'] ); ?>_lng" class="directorist-form-element" value="<?php echo $longitude; ?>">

</div>