<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

if (!defined('ABSPATH')) exit;

use Directorist_Advanced_Fields\Helper;

?>
<div class="directorist-single-info directorist-single-info-vimeo">

<div class="directorist-single-info__label">
    <span class="directorist-single-info__label-icon"><?php directorist_icon($data['icon']); ?></span>
    <span class="directorist-single-info__label--text"><?php echo esc_html($data['label']); ?></span>
</div>

<div class="directorist-single-info__value">

    <iframe class="directorist-embaded-video embed-responsive-item" src="<?php echo esc_attr(Helper::parse_vimeo($data['value'])); ?>" allowfullscreen></iframe>
</div>
</div>