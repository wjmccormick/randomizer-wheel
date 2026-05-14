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
     *
     * @param array $atts Shortcode attributes.
     */
    public static function render_randomized_wheel($atts = []) {
        rwp_enqueue_assets();

        $defaults = [
            'title' => '',
            'placeholder' => "Example:\nOption A\nOption B\nOption C\nOption D",
            'logo' => '',
            'logo_alt' => 'Randomizer Wheel logo',
            'show_presentation' => 'true',
            'show_remove_winner' => 'true',
            'min_items' => 2,
        ];

        $atts = shortcode_atts($defaults, $atts, 'randomizer_wheel');

        $title = self::sanitize_text($atts['title']);
        $placeholder = self::sanitize_textarea($atts['placeholder']);
        $logo_alt = self::sanitize_text($atts['logo_alt']);

        if (!$logo_alt) {
            $logo_alt = $defaults['logo_alt'];
        }

        $show_presentation = self::sanitize_bool($atts['show_presentation']);
        $show_remove_winner = self::sanitize_bool($atts['show_remove_winner']);
        $min_items = max(1, absint($atts['min_items']));

        $default_logo_url_black = RWP_PLUGIN_URL . 'assets/images/randomizer-wheel-logo-dark.svg';
        $default_logo_url_white = RWP_PLUGIN_URL . 'assets/images/randomizer-wheel-logo-light.svg';
        $custom_logo_url = self::sanitize_url($atts['logo']);

        $logo_url_black = $custom_logo_url ? $custom_logo_url : $default_logo_url_black;
        $logo_url_white = $custom_logo_url ? $custom_logo_url : $default_logo_url_white;
        $presentation_title = $title ? $title : 'Randomizer Wheel';

        $wheel_id = 'srw-wheel-' . wp_rand(1000, 999999);
        $winner_title_id = $wheel_id . '-winner-title';

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

        $default_demo_items = [
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

        $defaults = [
            'title' => '',
            'items' => '',
            'link' => '/randomizer-wheel/',
            'cta_text' => 'Create Your Randomizer Wheel',
            'logo' => '',
            'logo_alt' => 'Randomizer Wheel logo',
        ];

        $atts = shortcode_atts($defaults, $atts, 'randomizer_wheel_hero');

        $title = self::sanitize_text($atts['title']);
        $link = self::sanitize_url($atts['link']);
        $cta_text = self::sanitize_text($atts['cta_text']);
        $logo_alt = self::sanitize_text($atts['logo_alt']);
        $logo_url_black = self::sanitize_url($atts['logo']);
        $demo_items = self::sanitize_pipe_items($atts['items']);

        if (!$link) {
            $link = $defaults['link'];
        }

        if (!$cta_text) {
            $cta_text = $defaults['cta_text'];
        }

        if (!$logo_alt) {
            $logo_alt = $defaults['logo_alt'];
        }

        if (!$logo_url_black) {
            $logo_url_black = RWP_PLUGIN_URL . 'assets/images/randomizer-wheel-logo-dark.svg';
        }

        if (!$demo_items) {
            $demo_items = $default_demo_items;
        }

        $hero_title = $title ? $title : $cta_text;
        $wheel_id = 'srw-hero-' . wp_rand(1000, 999999);

        ob_start();
        require RWP_PLUGIN_DIR . 'public/templates/hero.php';
        return ob_get_clean();
    }

    /**
     * Sanitize a plain text shortcode value.
     *
     * @param mixed $value Raw value.
     * @return string
     */
    private static function sanitize_text($value) {
        return sanitize_text_field((string) $value);
    }

    /**
     * Sanitize a multiline shortcode value.
     *
     * @param mixed $value Raw value.
     * @return string
     */
    private static function sanitize_textarea($value) {
        return sanitize_textarea_field((string) $value);
    }

    /**
     * Sanitize a URL shortcode value.
     *
     * @param mixed $value Raw value.
     * @return string
     */
    private static function sanitize_url($value) {
        return esc_url_raw((string) $value);
    }

    /**
     * Sanitize a boolean-like shortcode value.
     *
     * @param mixed $value Raw value.
     * @return bool
     */
    private static function sanitize_bool($value) {
        if (is_bool($value)) {
            return $value;
        }

        $value = strtolower(trim((string) $value));

        if (in_array($value, ['false', '0', 'no', 'off'], true)) {
            return false;
        }

        return true;
    }

    /**
     * Sanitize pipe-separated hero items.
     *
     * @param mixed $value Raw value.
     * @return array
     */
    private static function sanitize_pipe_items($value) {
        if ('' === trim((string) $value)) {
            return [];
        }

        $items = array_map('trim', explode('|', (string) $value));
        $items = array_map(static function ($item) {
            return self::sanitize_text($item);
        }, $items);

        return array_values(array_filter($items, static function ($item) {
            return '' !== $item;
        }));
    }
}

if (!function_exists('rwp_render_randomized_wheel')) {
    /**
     * Procedural wrapper for rendering the full wheel shortcode.
     *
     * @param array $atts Shortcode attributes.
     */
    function rwp_render_randomized_wheel($atts = []) {
        return RWP_Shortcodes::render_randomized_wheel($atts);
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
