<?php

/**
 * @author  mahfuz
 * @since   6.7
 * @version 6.7
 */

if (!defined('ABSPATH')) exit;

use Directorist_Advanced_Fields\Helper;

?>

<iframe class="directorist-embaded-video embed-responsive-item" src="<?php echo esc_attr(Helper::parse_youtube($data['value'])); ?>" allowfullscreen></iframe>