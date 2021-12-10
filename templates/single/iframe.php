<?php

/**
 * @author  mahfuz
 * @since   1.0
 * @version 1.0
 */

?>

<div class="directorist-single-info directorist-single-info-iframe <?php echo $data['form_data']['class']; ?>">

    <div class="directorist-single-info__label">
        <span class="directorist-single-info__label-icon"><?php directorist_icon($data['icon']); ?></span>
        <span class="directorist-single-info__label--text"><?php echo esc_html($data['label']); ?></span>
    </div>

    <div class="directorist-single-info__value"><?php echo $data['value']; ?></div>

</div>