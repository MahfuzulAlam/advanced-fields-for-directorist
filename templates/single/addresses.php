<?php

/**
 * @author  wpWax
 * @since   2.1.0
 * @version 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

extract($data);

$is_label          = ! empty( $data['form_data']['is_label'] );
$allow_field_label = ! empty( $data['allow_field_label'] );
$is_map            = ! empty( $data['form_data']['is_map'] );
$map_type          = get_directorist_option( 'select_listing_map' );
$google_api        = get_directorist_option( 'map_api_key' );
$show_map          = false;

foreach ( $addresses as $address ) {
	if (
		isset( $address['latitude'], $address['longitude'] ) &&
		is_numeric( $address['latitude'] ) &&
		is_numeric( $address['longitude'] )
	) {
		$show_map = $is_map && ( 'openstreet' === $map_type || ( 'google' === $map_type && $google_api ) );
		break;
	}
}
?>

<div class="directorist-single-info directorist-single-info-address">

	<?php if ( $allow_field_label ) : ?>
	<div class="directorist-single-info__label">
		<span class="directorist-single-info__label-icon"><?php directorist_icon( $data['icon'] ); ?></span>
		<span class="directorist-single-info__label__text"><?php echo isset( $data['label'] ) ? esc_html( $data['label'] ) : ''; ?></span>
	</div>
	<?php endif; ?>

	<div class="directorist-single-info__value directorist-single-info__addresses">
		<?php if ( $addresses && ! empty( $addresses ) ) : ?>
			<div class="directorist-single-info__addresses-layout<?php echo $show_map ? ' has-map' : ''; ?>">
				<div class="addresses-list">
					<?php foreach ( $addresses as $index => $address ) : ?>
						<?php
						$label_text   = isset( $address['label'] ) ? trim( (string) $address['label'] ) : '';
						$address_text = isset( $address['address'] ) ? trim( (string) $address['address'] ) : '';
						$latitude     = isset( $address['latitude'] ) ? (string) $address['latitude'] : '';
						$longitude    = isset( $address['longitude'] ) ? (string) $address['longitude'] : '';
						$has_coords   = is_numeric( $latitude ) && is_numeric( $longitude );
						$title        = ( $is_label && '' !== $label_text )
							? $label_text
							: '';
						$description = $address_text;
						$map_query   = $has_coords ? $latitude . ',' . $longitude : $address_text;
						$map_url     = add_query_arg( 'q', $map_query, 'https://www.google.com/maps' );
						?>

						<article
							class="address-item<?php echo 0 === $index ? ' active' : ''; ?>"
							data-index="<?php echo esc_attr( $index ); ?>"
							data-lat="<?php echo esc_attr( $latitude ); ?>"
							data-lng="<?php echo esc_attr( $longitude ); ?>"
							data-title="<?php echo esc_attr( $title ); ?>"
							data-address="<?php echo esc_attr( $description ); ?>"
							data-map-url="<?php echo esc_url( $map_url ); ?>"
						>
							<div class="address-item__index"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></div>

							<div class="address-item__content">
								<?php if( !empty( $title ) ): ?><h4 class="address-item__title"><?php echo esc_html( $title ); ?></h4><?php endif; ?>
								<?php if ( '' !== $description ) : ?>
									<p class="address-item__text"><?php echo esc_html( $description ); ?></p>
								<?php endif; ?>
							</div>

							<!-- <div class="address-item__actions">
								<?php //if ( $show_map && $has_coords ) : ?>
									<button type="button" class="address-item__action address-item__action--ghost" data-focus-map="true">
										<?php //esc_html_e( 'Focus map', 'directorist-advanced-fields' ); ?>
									</button>
								<?php //endif; ?>

								<a
									href="<?php //echo esc_url( $map_url ); ?>"
									target="_blank"
									rel="noopener noreferrer"
									class="address-item__action"
								>
									<?php //esc_html_e( 'Open in Maps', 'directorist-advanced-fields' ); ?>
								</a>
							</div> -->
						</article>
					<?php endforeach; ?>
				</div>

				<?php if ( $show_map ) : ?>
					<div class="addresses-map-container">
						<div class="addresses-map-container__header">
							<div>
								<span class="addresses-map-container__eyebrow"><?php esc_html_e( 'Location map', 'directorist-advanced-fields' ); ?></span>
								<p class="addresses-map-container__text"><?php esc_html_e( 'Select any card to highlight its pin.', 'directorist-advanced-fields' ); ?></p>
							</div>
						</div>
						<div id="addresses-map" class="addresses-map" data-map-type="<?php echo esc_attr( $map_type ); ?>"></div>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
