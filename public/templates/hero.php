<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

    <div id="<?php echo esc_attr($wheel_id); ?>" class="srw-hero-wrapper" style="<?php echo esc_attr($theme_style); ?>" data-items="<?php echo esc_attr(wp_json_encode($demo_items)); ?>">
        <a href="<?php echo esc_url($link); ?>" class="srw-hero-link" aria-label="<?php echo esc_attr($cta_text); ?>" title="<?php echo esc_attr($hero_title); ?>">
            <div class="srw-hero-stage">
                <div class="srw-hero-pointer"></div>
                <canvas class="srw-hero-canvas" width="1400" height="1400"></canvas>
                <img class="srw-hero-logo" src="<?php echo esc_url($logo_url_black); ?>" alt="<?php echo esc_attr($logo_alt); ?>"/>
            </div>
        </a>
    </div>
