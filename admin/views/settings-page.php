<?php
/**
 * Settings page view.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap">
    <h1><?php echo esc_html('Randomizer Wheel Settings'); ?></h1>

    <form action="options.php" method="post">
        <?php
        settings_fields('rwp_settings_group');
        do_settings_sections('randomizer-wheel');
        submit_button();
        ?>
    </form>
</div>
