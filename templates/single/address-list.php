<?php
/**
 * @author  wpWax
 * @since   2.1.0
 * @version 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

e_var_dump( $value );
?>

<div class="directorist-single-info directorist-single-info-address <?php echo esc_attr( $data['form_data']['class'] ); ?>">

	<div class="directorist-single-info__label">
		<span class="directorist-single-info__label-icon"><?php directorist_icon($data['icon']); ?></span>
		<span class="directorist-single-info__label__text"><?php echo esc_html($data['label']); ?></span>
	</div>
	
	<div class="directorist-single-info__value"><?php echo $data['value']; ?></div>

</div>