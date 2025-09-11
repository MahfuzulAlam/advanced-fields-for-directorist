<?php

/**
 * @author  wpWax
 * @since   2.1.0
 * @version 2.1.0
 */

if (! defined('ABSPATH')) exit;
extract($data);
$is_label = isset($data['form_data']['is_label']) && $data['form_data']['is_label'] ? true : false;
$is_map = isset($data['form_data']['is_map']) && $data['form_data']['is_map'] ? true : false;
$map_type = get_directorist_option('select_listing_map');
$google_api = get_directorist_option('map_api_key');
?>

<div class="directorist-single-info directorist-single-info-address">

	<div class="directorist-single-info__label">
		<span class="directorist-single-info__label-icon"><?php directorist_icon($data['icon']); ?></span>
		<span class="directorist-single-info__label__text"><?php echo (isset($data['label'])) ? esc_html($data['label']) : ''; ?></span>
	</div>
	<div class="directorist-single-info__value directorist-single-info__addresses">
		<?php if ($addresses && !empty($addresses)): ?>

			<!-- Address List -->
			<div class="addresses-list">
				<?php foreach ($addresses as $index => $address): ?>

					<!-- Option 1: Use latitude & longitude (recommended for accuracy) -->
					<?php $mapUrl = "https://www.google.com/maps?q={$address['latitude']},{$address['longitude']}"; ?>

					<?php if ($is_label && isset($address['label']) && !empty($address['label'])): ?>
						<!-- Display label if available -->
						<div class="address-item" data-lat="<?php echo esc_attr($address['latitude']); ?>" data-lng="<?php echo esc_attr($address['longitude']); ?>" data-label="<?php echo esc_attr($address['label']); ?>">
							<span class="address-label"><?php echo htmlspecialchars($address['label']); ?></span>
							<?php if(!$is_map): ?>
							<a href="<?php echo $mapUrl; ?>" target="_blank" class="address-link">
								<?php echo htmlspecialchars($address['address']); ?>
							</a>
							<?php else: ?>
							<span class="address-link">
								<?php echo htmlspecialchars($address['address']); ?>
							</span>
							<?php endif; ?>
						</div>
					<?php else: ?>
						<!-- Display address only if no label -->
						<div class="address-item" data-lat="<?php echo esc_attr($address['latitude']); ?>" data-lng="<?php echo esc_attr($address['longitude']); ?>" data-label="<?php echo esc_attr($address['address']); ?>">
							<?php if(!$is_map): ?>
							<a href="<?php echo $mapUrl; ?>" target="_blank" class="address-link">
								<?php echo htmlspecialchars($address['address']); ?>
							</a>
							<?php else: ?>
							<span class="address-link">
								<?php echo htmlspecialchars($address['address']); ?>
							</span>
							<?php endif; ?>
						</div>
					<?php endif; ?>

				<?php endforeach; ?>
			</div>

			<?php if ($is_map && ( $map_type === 'openstreet' || ( $map_type === 'google' && $google_api ) )): ?>
				<!-- Map Container -->
				<div class="addresses-map-container">
					<div id="addresses-map" class="addresses-map" data-map-type="<?php echo esc_attr($map_type); ?>"></div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>