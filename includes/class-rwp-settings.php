<?php
/**
 * Admin settings for Randomizer Wheel.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handles plugin settings registration and rendering.
 */
class RWP_Settings {
    /**
     * Option name used to store all plugin settings.
     */
    const OPTION_NAME = 'rwp_settings';

    /**
     * Register admin hooks.
     */
    public static function register_hooks() {
        add_action('admin_menu', [__CLASS__, 'add_settings_page']);
        add_action('admin_init', [__CLASS__, 'register_settings']);
    }

    /**
     * Hardcoded generic defaults.
     *
     * @return array
     */
    public static function defaults() {
        return [
            'full_title' => '',
            'full_placeholder' => "Example:\nOption A\nOption B\nOption C\nOption D",
            'full_logo' => '',
            'full_logo_alt' => 'Randomizer Wheel logo',
            'full_show_presentation' => true,
            'full_show_remove_winner' => true,
            'full_min_items' => 2,
            'hero_title' => '',
            'hero_items' => "Option A\nOption B\nOption C\nOption D\nOption E\nOption F\nOption G\nOption H\nOption I\nOption J\nOption K\nOption L\nOption M\nOption N\nOption O\nOption P\nOption Q\nOption R\nOption S\nOption T",
            'hero_link' => '/randomizer-wheel/',
            'hero_cta_text' => 'Create Your Randomizer Wheel',
            'hero_logo' => '',
            'hero_logo_alt' => 'Randomizer Wheel logo',
        ];
    }

    /**
     * Get merged settings.
     *
     * @return array
     */
    public static function get_settings() {
        $settings = get_option(self::OPTION_NAME, []);

        if (!is_array($settings)) {
            $settings = [];
        }

        return array_merge(self::defaults(), $settings);
    }

    /**
     * Add Settings -> Randomizer Wheel page.
     */
    public static function add_settings_page() {
        add_options_page(
            'Randomizer Wheel',
            'Randomizer Wheel',
            'manage_options',
            'randomizer-wheel',
            [__CLASS__, 'render_settings_page']
        );
    }

    /**
     * Register Settings API settings, sections, and fields.
     */
    public static function register_settings() {
        register_setting(
            'rwp_settings_group',
            self::OPTION_NAME,
            [
                'type' => 'array',
                'sanitize_callback' => [__CLASS__, 'sanitize_settings'],
                'default' => self::defaults(),
            ]
        );

        add_settings_section(
            'rwp_full_wheel_section',
            'Full Wheel Defaults',
            [__CLASS__, 'render_full_wheel_section'],
            'randomizer-wheel'
        );

        add_settings_section(
            'rwp_hero_wheel_section',
            'Hero Wheel Defaults',
            [__CLASS__, 'render_hero_wheel_section'],
            'randomizer-wheel'
        );

        self::add_field('full_title', 'Default title', 'text', 'rwp_full_wheel_section');
        self::add_field('full_placeholder', 'Default placeholder text', 'textarea', 'rwp_full_wheel_section');
        self::add_field('full_logo', 'Default logo URL', 'url', 'rwp_full_wheel_section');
        self::add_field('full_logo_alt', 'Default logo alt text', 'text', 'rwp_full_wheel_section');
        self::add_field('full_show_presentation', 'Show presentation mode by default', 'checkbox', 'rwp_full_wheel_section');
        self::add_field('full_show_remove_winner', 'Show remove winner by default', 'checkbox', 'rwp_full_wheel_section');
        self::add_field('full_min_items', 'Default minimum items', 'number', 'rwp_full_wheel_section');

        self::add_field('hero_title', 'Default hero title', 'text', 'rwp_hero_wheel_section');
        self::add_field('hero_items', 'Default hero items', 'textarea', 'rwp_hero_wheel_section');
        self::add_field('hero_link', 'Default hero link', 'url', 'rwp_hero_wheel_section');
        self::add_field('hero_cta_text', 'Default hero CTA text', 'text', 'rwp_hero_wheel_section');
        self::add_field('hero_logo', 'Default hero logo URL', 'url', 'rwp_hero_wheel_section');
        self::add_field('hero_logo_alt', 'Default hero logo alt text', 'text', 'rwp_hero_wheel_section');
    }

    /**
     * Sanitize settings before saving.
     *
     * @param mixed $input Raw settings input.
     * @return array
     */
    public static function sanitize_settings($input) {
        if (!current_user_can('manage_options')) {
            return self::get_settings();
        }

        if (!is_array($input)) {
            $input = [];
        }

        $defaults = self::defaults();

        return [
            'full_title' => sanitize_text_field($input['full_title'] ?? $defaults['full_title']),
            'full_placeholder' => sanitize_textarea_field($input['full_placeholder'] ?? $defaults['full_placeholder']),
            'full_logo' => esc_url_raw($input['full_logo'] ?? $defaults['full_logo']),
            'full_logo_alt' => sanitize_text_field($input['full_logo_alt'] ?? $defaults['full_logo_alt']),
            'full_show_presentation' => !empty($input['full_show_presentation']),
            'full_show_remove_winner' => !empty($input['full_show_remove_winner']),
            'full_min_items' => max(1, absint($input['full_min_items'] ?? $defaults['full_min_items'])),
            'hero_title' => sanitize_text_field($input['hero_title'] ?? $defaults['hero_title']),
            'hero_items' => sanitize_textarea_field($input['hero_items'] ?? $defaults['hero_items']),
            'hero_link' => esc_url_raw($input['hero_link'] ?? $defaults['hero_link']),
            'hero_cta_text' => sanitize_text_field($input['hero_cta_text'] ?? $defaults['hero_cta_text']),
            'hero_logo' => esc_url_raw($input['hero_logo'] ?? $defaults['hero_logo']),
            'hero_logo_alt' => sanitize_text_field($input['hero_logo_alt'] ?? $defaults['hero_logo_alt']),
        ];
    }

    /**
     * Render settings page.
     */
    public static function render_settings_page() {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to access this page.'));
        }

        require RWP_PLUGIN_DIR . 'admin/views/settings-page.php';
    }

    /**
     * Render full wheel section description.
     */
    public static function render_full_wheel_section() {
        echo '<p>' . esc_html('These defaults apply to [randomizer_wheel] and [randomized_wheel] unless a shortcode attribute overrides them.') . '</p>';
    }

    /**
     * Render hero wheel section description.
     */
    public static function render_hero_wheel_section() {
        echo '<p>' . esc_html('These defaults apply to [randomizer_wheel_hero] and [whiskey_wheel_hero] unless a shortcode attribute overrides them.') . '</p>';
    }

    /**
     * Add a settings field.
     *
     * @param string $key Setting key.
     * @param string $label Field label.
     * @param string $type Field type.
     * @param string $section Section ID.
     */
    private static function add_field($key, $label, $type, $section) {
        add_settings_field(
            'rwp_' . $key,
            $label,
            [__CLASS__, 'render_field'],
            'randomizer-wheel',
            $section,
            [
                'key' => $key,
                'label' => $label,
                'type' => $type,
            ]
        );
    }

    /**
     * Render one settings field.
     *
     * @param array $args Field arguments.
     */
    public static function render_field($args) {
        $settings = self::get_settings();
        $key = $args['key'];
        $type = $args['type'];
        $name = self::OPTION_NAME . '[' . $key . ']';
        $value = $settings[$key] ?? '';

        if ('textarea' === $type) {
            printf(
                '<textarea id="%1$s" name="%2$s" rows="5" class="large-text">%3$s</textarea>',
                esc_attr('rwp_' . $key),
                esc_attr($name),
                esc_textarea($value)
            );
            return;
        }

        if ('checkbox' === $type) {
            printf(
                '<label><input type="checkbox" id="%1$s" name="%2$s" value="1" %3$s> %4$s</label>',
                esc_attr('rwp_' . $key),
                esc_attr($name),
                checked((bool) $value, true, false),
                esc_html('Enabled')
            );
            return;
        }

        if ('number' === $type) {
            printf(
                '<input type="number" id="%1$s" name="%2$s" value="%3$s" min="1" class="small-text">',
                esc_attr('rwp_' . $key),
                esc_attr($name),
                esc_attr($value)
            );
            return;
        }

        printf(
            '<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" class="regular-text">',
            esc_attr($type),
            esc_attr('rwp_' . $key),
            esc_attr($name),
            esc_attr($value)
        );
    }
}
