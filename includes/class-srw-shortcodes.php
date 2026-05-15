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
     * Register public shortcodes.
     */
    public static function register() {
        add_shortcode('randomizer_wheel', [__CLASS__, 'render_randomizer_wheel']);
        add_shortcode('randomizer_wheel_hero', [__CLASS__, 'render_wheel_hero']);
    }

    /**
     * Render the full interactive Randomizer Wheel.
     *
     * Shortcode: [randomizer_wheel]
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
    public static function render_randomizer_wheel($atts = []) {
        rwp_enqueue_assets();

        $settings = RWP_Settings::get_settings();
        $raw_atts = (array) $atts;
        $atts = shortcode_atts([
            'title' => null,
            'placeholder' => null,
            'logo' => null,
            'logo_alt' => null,
            'show_presentation' => null,
            'show_remove_winner' => null,
            'min_items' => null,
            'accent_color' => null,
            'wheel_palette' => null,
        ], $atts, 'randomizer_wheel');

        $title = self::sanitize_text(self::attribute_or_setting($raw_atts, $atts, 'title', $settings, 'full_title'));
        $placeholder = self::sanitize_textarea(self::attribute_or_setting($raw_atts, $atts, 'placeholder', $settings, 'full_placeholder'));
        $logo_alt = self::sanitize_text(self::attribute_or_setting($raw_atts, $atts, 'logo_alt', $settings, 'full_logo_alt'));

        if (!$logo_alt) {
            $logo_alt = RWP_Settings::defaults()['full_logo_alt'];
        }

        $show_presentation = array_key_exists('show_presentation', $raw_atts)
            ? self::sanitize_bool($atts['show_presentation'])
            : (bool) $settings['full_show_presentation'];

        $show_remove_winner = array_key_exists('show_remove_winner', $raw_atts)
            ? self::sanitize_bool($atts['show_remove_winner'])
            : (bool) $settings['full_show_remove_winner'];

        $min_items = array_key_exists('min_items', $raw_atts)
            ? max(1, absint($atts['min_items']))
            : max(1, absint($settings['full_min_items']));

        $default_logo_url_black = RWP_PLUGIN_URL . 'assets/images/randomizer-wheel-logo-dark.svg';
        $default_logo_url_white = RWP_PLUGIN_URL . 'assets/images/randomizer-wheel-logo-light.svg';
        $custom_logo_url = self::sanitize_url(self::attribute_or_setting($raw_atts, $atts, 'logo', $settings, 'full_logo'));

        $logo_url_black = $custom_logo_url ? $custom_logo_url : $default_logo_url_black;
        $logo_url_white = $custom_logo_url ? $custom_logo_url : $default_logo_url_white;
        $presentation_title = $title ? $title : 'Randomizer Wheel';
        $accent_color = self::resolve_accent_color($raw_atts, $atts, $settings);
        $theme_style = self::theme_style_attribute($accent_color);
        $wheel_palette = self::resolve_wheel_palette($raw_atts, $atts, $settings);

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
     * This is decorative/marketing-focused and links users to the full wheel page.
     *
     * @param array $atts Shortcode attributes.
     */
    public static function render_wheel_hero($atts = []) {
        rwp_enqueue_assets();

        $settings = RWP_Settings::get_settings();
        $raw_atts = (array) $atts;
        $atts = shortcode_atts([
            'title' => null,
            'items' => null,
            'link' => null,
            'cta_text' => null,
            'logo' => null,
            'logo_alt' => null,
            'accent_color' => null,
            'wheel_palette' => null,
        ], $atts, 'randomizer_wheel_hero');

        $title = self::sanitize_text(self::attribute_or_setting($raw_atts, $atts, 'title', $settings, 'hero_title'));
        $link = self::sanitize_url(self::attribute_or_setting($raw_atts, $atts, 'link', $settings, 'hero_link'));
        $cta_text = self::sanitize_text(self::attribute_or_setting($raw_atts, $atts, 'cta_text', $settings, 'hero_cta_text'));
        $logo_alt = self::sanitize_text(self::attribute_or_setting($raw_atts, $atts, 'logo_alt', $settings, 'hero_logo_alt'));
        $logo_url_black = self::sanitize_url(self::attribute_or_setting($raw_atts, $atts, 'logo', $settings, 'hero_logo'));

        $demo_items = array_key_exists('items', $raw_atts)
            ? self::sanitize_items($atts['items'])
            : self::sanitize_items($settings['hero_items']);

        $defaults = RWP_Settings::defaults();

        if (!$link) {
            $link = $defaults['hero_link'];
        }

        if (!$cta_text) {
            $cta_text = $defaults['hero_cta_text'];
        }

        if (!$logo_alt) {
            $logo_alt = $defaults['hero_logo_alt'];
        }

        if (!$logo_url_black) {
            $logo_url_black = RWP_PLUGIN_URL . 'assets/images/randomizer-wheel-logo-dark.svg';
        }

        if (!$demo_items) {
            $demo_items = self::sanitize_items($defaults['hero_items']);
        }

        $hero_title = $title ? $title : $cta_text;
        $accent_color = self::resolve_accent_color($raw_atts, $atts, $settings);
        $theme_style = self::theme_style_attribute($accent_color);
        $wheel_palette = self::resolve_wheel_palette($raw_atts, $atts, $settings);
        $wheel_id = 'srw-hero-' . wp_rand(1000, 999999);

        ob_start();
        require RWP_PLUGIN_DIR . 'public/templates/hero.php';
        return ob_get_clean();
    }


    /**
     * Resolve the accent color using shortcode attribute, saved setting, bundled default order.
     *
     * @param array $raw_atts Raw shortcode attributes.
     * @param array $atts Normalized shortcode attributes.
     * @param array $settings Saved settings.
     * @return string
     */
    private static function resolve_accent_color($raw_atts, $atts, $settings) {
        $defaults = RWP_Settings::defaults();
        $fallback = $settings['accent_color'] ?? $defaults['accent_color'];

        if (array_key_exists('accent_color', $raw_atts)) {
            return self::sanitize_hex_color_value($atts['accent_color'], $fallback);
        }

        return self::sanitize_hex_color_value($fallback, $defaults['accent_color']);
    }

    /**
     * Resolve the wheel palette using shortcode attribute, saved setting, bundled default order.
     *
     * @param array $raw_atts Raw shortcode attributes.
     * @param array $atts Normalized shortcode attributes.
     * @param array $settings Saved settings.
     * @return string
     */
    private static function resolve_wheel_palette($raw_atts, $atts, $settings) {
        $defaults = RWP_Settings::defaults();
        $fallback = RWP_Settings::sanitize_palette($settings['wheel_palette'] ?? $defaults['wheel_palette'], $defaults['wheel_palette']);

        if (array_key_exists('wheel_palette', $raw_atts)) {
            return RWP_Settings::sanitize_palette($atts['wheel_palette'], $fallback);
        }

        return $fallback;
    }

    /**
     * Build an escaped CSS custom property declaration string for one shortcode instance.
     *
     * @param string $accent_color Resolved accent color.
     * @return string
     */
    private static function theme_style_attribute($accent_color) {
        return sprintf(
            '--rwp-accent-color:%1$s;',
            esc_attr($accent_color)
        );
    }

    /**
     * Sanitize a hex color value with a fallback.
     *
     * @param mixed  $value Raw value.
     * @param string $fallback Fallback color.
     * @return string
     */
    private static function sanitize_hex_color_value($value, $fallback) {
        $color = sanitize_hex_color((string) $value);

        return $color ? $color : $fallback;
    }

    /**
     * Resolve a shortcode attribute or saved setting value.
     *
     * @param array  $raw_atts Raw shortcode attributes.
     * @param array  $atts Normalized shortcode attributes.
     * @param string $attribute Attribute name.
     * @param array  $settings Saved settings.
     * @param string $setting Setting key.
     * @return mixed
     */
    private static function attribute_or_setting($raw_atts, $atts, $attribute, $settings, $setting) {
        if (array_key_exists($attribute, $raw_atts)) {
            return $atts[$attribute];
        }

        return $settings[$setting] ?? RWP_Settings::defaults()[$setting];
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
     * Sanitize separated hero items.
     *
     * @param mixed $value Raw value.
     * @return array
     */
    private static function sanitize_items($value) {
        if ('' === trim((string) $value)) {
            return [];
        }

        $items = preg_split('/\r\n|\r|\n|\|/', (string) $value);
        $items = array_map('trim', $items);
        $items = array_map(static function ($item) {
            return self::sanitize_text($item);
        }, $items);

        return array_values(array_filter($items, static function ($item) {
            return '' !== $item;
        }));
    }
}

if (!function_exists('rwp_render_randomizer_wheel')) {
    /**
     * Procedural wrapper for rendering the full wheel shortcode.
     *
     * @param array $atts Shortcode attributes.
     */
    function rwp_render_randomizer_wheel($atts = []) {
        return RWP_Shortcodes::render_randomizer_wheel($atts);
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
