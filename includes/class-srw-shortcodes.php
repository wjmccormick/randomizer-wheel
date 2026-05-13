<?php
/**
 * Shortcode registration and rendering.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handles Randomizer Wheel shortcodes.
 */
class RWP_Shortcodes {
    /**
     * Register preferred and legacy shortcodes.
     */
    public static function register() {
        add_shortcode('randomizer_wheel', [__CLASS__, 'render_randomized_wheel']);
        add_shortcode('randomized_wheel', [__CLASS__, 'render_randomized_wheel']);
        add_shortcode('randomizer_wheel_hero', [__CLASS__, 'render_wheel_hero']);
        add_shortcode('whiskey_wheel_hero', [__CLASS__, 'render_wheel_hero']);
    }

    /**
     * Render the full interactive randomized wheel.
     *
     * Shortcodes: [randomizer_wheel], [randomized_wheel]
     *
     * Includes:
     * - User textarea input
     * - Entry count
     * - Standard wheel
     * - Presentation mode
     * - Winner announcement modal
     */
    public static function render_randomized_wheel() {
        rwp_enqueue_assets();

        $wheel_id = 'srw-wheel-' . wp_rand(1000, 999999);
        $winner_title_id = $wheel_id . '-winner-title';
        $logo_url_black = RWP_PLUGIN_URL . 'assets/images/randomizer-wheel-logo-dark.svg';
        $logo_url_white = RWP_PLUGIN_URL . 'assets/images/randomizer-wheel-logo-light.svg';

        ob_start();
        require RWP_PLUGIN_DIR . 'public/templates/wheel.php';
        return ob_get_clean();
    }

    /**
     * Render the homepage hero/demo wheel.
     *
     * Shortcode: [randomizer_wheel_hero link="/randomizer-wheel/"]
     *
     * The legacy [whiskey_wheel_hero] shortcode remains registered as a backward-compatible alias.
     * This is decorative/marketing-focused and links users to the full wheel page.
     *
     * @param array $atts Shortcode attributes.
     */
    public static function render_wheel_hero($atts = []) {
        rwp_enqueue_assets();

        $wheel_id = 'srw-hero-' . wp_rand(1000, 999999);
        $atts = shortcode_atts(['link' => '/randomizer-wheel/'], $atts, 'randomizer_wheel_hero');
        $logo_url_black = RWP_PLUGIN_URL . 'assets/images/randomizer-wheel-logo-dark.svg';

        $demo_items = [
            'Option A',
            'Option B',
            'Option C',
            'Option D',
            'Option E',
            'Option F',
            'Option G',
            'Option H',
            'Option I',
            'Option J',
            'Option K',
            'Option L',
            'Option M',
            'Option N',
            'Option O',
            'Option P',
            'Option Q',
            'Option R',
            'Option S',
            'Option T'
        ];

        ob_start();
        require RWP_PLUGIN_DIR . 'public/templates/hero.php';
        return ob_get_clean();
    }
}

if (!function_exists('rwp_render_randomized_wheel')) {
    /**
     * Procedural wrapper for rendering the full wheel shortcode.
     */
    function rwp_render_randomized_wheel() {
        return RWP_Shortcodes::render_randomized_wheel();
    }
}

if (!function_exists('rwp_render_wheel_hero')) {
    /**
     * Procedural wrapper for rendering the hero shortcode.
     *
     * @param array $atts Shortcode attributes.
     */
    function rwp_render_wheel_hero($atts = []) {
        return RWP_Shortcodes::render_wheel_hero($atts);
    }
}
