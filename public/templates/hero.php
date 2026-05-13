<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

    <div id="<?php echo esc_attr($wheel_id); ?>" class="srw-hero-wrapper" data-items="<?php echo esc_attr(wp_json_encode($demo_items)); ?>">
        <a href="<?php echo esc_url($atts['link']); ?>" class="srw-hero-link" aria-label="Create Your Randomizer Wheel" title="Create Your Randomizer Wheel">
            <div class="srw-hero-stage">
                <div class="srw-hero-pointer"></div>
                <canvas class="srw-hero-canvas" width="1400" height="1400"></canvas>
                <img class="srw-hero-logo" src="<?php echo esc_url($logo_url_black); ?>" alt="Randomizer Wheel logo"/>
            </div>
        </a>
    </div>

