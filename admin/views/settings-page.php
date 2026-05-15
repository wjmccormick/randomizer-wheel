<?php
/**
 * Settings page view.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap rwp-settings-wrap">
    <h1><?php echo esc_html('Randomizer Wheel Settings'); ?></h1>

    <div class="notice notice-info inline rwp-settings-notice">
        <p><?php echo esc_html('Shortcode attributes override these admin defaults. Use the Documentation page for shortcode examples, palette previews, and attribute reference.'); ?></p>
    </div>

    <form action="options.php" method="post" class="rwp-settings-form">
        <?php
        settings_fields('rwp_settings_group');
        do_settings_sections('randomizer-wheel');
        submit_button();
        ?>
    </form>
</div>
