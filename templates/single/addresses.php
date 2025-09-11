<?php
/**
 * @author  wpWax
 * @since   2.1.0
 * @version 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
extract($data);
$is_label = isset($data['form_data']['is_label']) && $data['form_data']['is_label'] ? true : false;
?>

<div class="directorist-single-info directorist-single-info-address">

    <div class="directorist-single-info__label">
        <span class="directorist-single-info__label-icon"><?php directorist_icon( $data['icon'] );?></span>
        <span class="directorist-single-info__label__text"><?php echo ( isset( $data['label'] ) ) ? esc_html( $data['label'] ) : ''; ?></span>
    </div>
    <div class="directorist-single-info__value directorist-single-info__addresses">
		<?php if( $addresses && !empty( $addresses ) ): ?>
        <?php foreach( $addresses as $address ): ?>
        
		    <!-- Option 1: Use latitude & longitude (recommended for accuracy) -->
			<?php $mapUrl = "https://www.google.com/maps?q={$address['latitude']},{$address['longitude']}"; ?>

			<?php if( $is_label && isset($address['label']) && !empty($address['label']) ): ?>
				<!-- Display label if available -->
				<div class="address-item">
					<span class="address-label"><?php echo htmlspecialchars($address['label']); ?></span>
					<a href="<?php echo $mapUrl; ?>" target="_blank" class="address-link">
						<?php echo htmlspecialchars($address['address']); ?>
					</a>
				</div>
			<?php else: ?>
				<!-- Display address only if no label -->
				<div class="address-item">
					<a href="<?php echo $mapUrl; ?>" target="_blank" class="address-link">
						<?php echo htmlspecialchars($address['address']); ?>
					</a>
				</div>
			<?php endif; ?>

        <?php endforeach; ?>
		<?php endif; ?>
    </div>
</div>