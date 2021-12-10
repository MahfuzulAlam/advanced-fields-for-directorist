<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

if (!defined('ABSPATH')) exit;

use Directorist_Advanced_Fields\Helper;

?>

<iframe class="directorist-embaded-video embed-responsive-item" src="<?php echo esc_attr(Helper::parse_vimeo($data['value'])); ?>" allowfullscreen></iframe>