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
     * Settings page slug.
     */
    const PAGE_SLUG = 'randomizer-wheel';

    /**
     * Register admin hooks.
     */
    public static function register() {
        add_action('admin_menu', [__CLASS__, 'add_settings_page']);
        add_action('admin_init', [__CLASS__, 'register_settings']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_admin_assets']);
        add_filter('plugin_action_links_' . plugin_basename(RWP_PLUGIN_FILE), [__CLASS__, 'add_settings_link']);
    }

    /**
     * Backward-compatible alias for registering admin hooks.
     */
    public static function register_hooks() {
        self::register();
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
            'accent_color' => '#b8860b',
            'wheel_palette' => 'classic',
        ];
    }


    /**
     * Available wheel palette options.
     *
     * @return array
     */
    public static function palette_options() {
        return [
            'classic' => 'Classic',
            'bourbon' => 'Bourbon',
            'bright' => 'Bright',
            'muted' => 'Muted',
            'monochrome' => 'Monochrome',
        ];
    }

    /**
     * Sanitize a palette slug with a known fallback.
     *
     * @param mixed  $value Raw palette value.
     * @param string $fallback Fallback palette slug.
     * @return string
     */
    public static function sanitize_palette($value, $fallback = 'classic') {
        $palette = sanitize_key((string) $value);
        $options = self::palette_options();

        if (array_key_exists($palette, $options)) {
            return $palette;
        }

        return array_key_exists($fallback, $options) ? $fallback : 'classic';
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

        $defaults = self::defaults();
        $settings = array_intersect_key($settings, $defaults);

        return array_merge($defaults, $settings);
    }

    /**
     * Add Settings -> Randomizer Wheel page.
     */
    public static function add_settings_page() {
        add_options_page(
            'Randomizer Wheel',
            'Randomizer Wheel',
            'manage_options',
            self::PAGE_SLUG,
            [__CLASS__, 'render_settings_page']
        );
    }

    /**
     * Add Settings link to the plugins list table.
     *
     * @param array $links Existing action links.
     * @return array
     */
    public static function add_settings_link($links) {
        $settings_link = sprintf(
            '<a href="%1$s">%2$s</a>',
            esc_url(admin_url('options-general.php?page=' . self::PAGE_SLUG)),
            esc_html('Settings')
        );

        array_unshift($links, $settings_link);

        return $links;
    }

    /**
     * Enqueue admin assets only for this settings page.
     *
     * @param string $hook_suffix Admin page hook suffix.
     */
    public static function enqueue_admin_assets($hook_suffix) {
        if ('settings_page_' . self::PAGE_SLUG !== $hook_suffix) {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        wp_enqueue_style(
            'rwp-admin-settings',
            RWP_PLUGIN_URL . 'assets/css/randomizer-wheel-admin.css',
            [],
            RWP_VERSION
        );

        wp_enqueue_script(
            'rwp-admin-settings',
            RWP_PLUGIN_URL . 'assets/js/randomizer-wheel-admin.js',
            ['jquery', 'wp-color-picker'],
            RWP_VERSION,
            true
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
            self::PAGE_SLUG
        );

        add_settings_section(
            'rwp_hero_wheel_section',
            'Hero Wheel Defaults',
            [__CLASS__, 'render_hero_wheel_section'],
            self::PAGE_SLUG
        );

        add_settings_section(
            'rwp_branding_section',
            'Branding & Theme Defaults',
            [__CLASS__, 'render_branding_section'],
            self::PAGE_SLUG
        );

        self::add_field('full_title', 'Default title', 'text', 'rwp_full_wheel_section', 'Optional heading shown above the full wheel input panel.');
        self::add_field('full_placeholder', 'Default placeholder text', 'textarea', 'rwp_full_wheel_section', 'Shown inside the full wheel textarea before visitors add items.');
        self::add_field('full_logo', 'Default logo URL', 'media', 'rwp_full_wheel_section', 'Choose or paste the default full wheel logo URL.');
        self::add_field('full_logo_alt', 'Default logo alt text', 'text', 'rwp_full_wheel_section', 'Accessible alt text for the full wheel logo.');
        self::add_field('full_show_presentation', 'Show presentation mode by default', 'checkbox', 'rwp_full_wheel_section', 'Shortcode attributes can still hide presentation mode per wheel.');
        self::add_field('full_show_remove_winner', 'Show remove winner by default', 'checkbox', 'rwp_full_wheel_section', 'Shortcode attributes can still hide remove-winner controls per wheel.');
        self::add_field('full_min_items', 'Default minimum items', 'number', 'rwp_full_wheel_section', 'Minimum number of parsed items required before spinning.');

        self::add_field('hero_title', 'Default hero title', 'text', 'rwp_hero_wheel_section', 'Optional link title text; falls back to the CTA text when empty.');
        self::add_field('hero_items', 'Default hero items', 'textarea', 'rwp_hero_wheel_section', 'Enter one item per line for the hero wheel demo.');
        self::add_field('hero_link', 'Default hero link', 'url', 'rwp_hero_wheel_section', 'URL opened when visitors click the hero wheel.');
        self::add_field('hero_cta_text', 'Default hero CTA text', 'text', 'rwp_hero_wheel_section', 'Accessible label for the hero wheel link.');
        self::add_field('hero_logo', 'Default hero logo URL', 'media', 'rwp_hero_wheel_section', 'Choose or paste the default hero logo URL.');
        self::add_field('hero_logo_alt', 'Default hero logo alt text', 'text', 'rwp_hero_wheel_section', 'Accessible alt text for the hero logo.');

        self::add_field('accent_color', 'Accent color', 'color', 'rwp_branding_section', 'Used for wheel pointers, winner modal accent text, and close buttons unless a shortcode overrides it.');
        self::add_field('wheel_palette', 'Wheel palette', 'select', 'rwp_branding_section', 'Controls canvas slice colors. Shortcode attributes can override this per wheel.');
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
            'accent_color' => self::sanitize_hex_color_setting($input['accent_color'] ?? $defaults['accent_color'], $defaults['accent_color']),
            'wheel_palette' => self::sanitize_palette($input['wheel_palette'] ?? $defaults['wheel_palette'], $defaults['wheel_palette']),
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
        echo '<p>' . esc_html('Site-wide defaults for [randomizer_wheel] and [randomized_wheel]. Shortcode attributes override these values for an individual wheel.') . '</p>';
    }

    /**
     * Render hero wheel section description.
     */
    public static function render_hero_wheel_section() {
        echo '<p>' . esc_html('Site-wide defaults for [randomizer_wheel_hero] and [whiskey_wheel_hero]. Shortcode attributes override these values for an individual hero wheel.') . '</p>';
    }

    /**
     * Render branding section description.
     */
    public static function render_branding_section() {
        echo '<p>' . esc_html('These settings theme frontend wheel instances by default. Shortcode attributes override them for one rendered wheel.') . '</p>';
    }

    /**
     * Add a settings field.
     *
     * @param string $key Setting key.
     * @param string $label Field label.
     * @param string $type Field type.
     * @param string $section Section ID.
     * @param string $description Field description.
     */
    private static function add_field($key, $label, $type, $section, $description = '') {
        add_settings_field(
            'rwp_' . $key,
            $label,
            [__CLASS__, 'render_field'],
            self::PAGE_SLUG,
            $section,
            [
                'key' => $key,
                'label' => $label,
                'type' => $type,
                'description' => $description,
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
        $description = $args['description'] ?? '';
        $name = self::OPTION_NAME . '[' . $key . ']';
        $value = $settings[$key] ?? '';

        if ('textarea' === $type) {
            printf(
                '<textarea id="%1$s" name="%2$s" rows="5" class="large-text">%3$s</textarea>',
                esc_attr('rwp_' . $key),
                esc_attr($name),
                esc_textarea($value)
            );
            self::render_description($description);
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
            self::render_description($description);
            return;
        }

        if ('select' === $type) {
            printf(
                '<select id="%1$s" name="%2$s">',
                esc_attr('rwp_' . $key),
                esc_attr($name)
            );

            foreach (self::palette_options() as $option_value => $option_label) {
                printf(
                    '<option value="%1$s" %2$s>%3$s</option>',
                    esc_attr($option_value),
                    selected($value, $option_value, false),
                    esc_html($option_label)
                );
            }

            echo '</select>';
            self::render_description($description);
            return;
        }

        if ('number' === $type) {
            printf(
                '<input type="number" id="%1$s" name="%2$s" value="%3$s" min="1" class="small-text">',
                esc_attr('rwp_' . $key),
                esc_attr($name),
                esc_attr($value)
            );
            self::render_description($description);
            return;
        }

        if ('media' === $type) {
            printf(
                '<div class="rwp-media-field"><input type="url" id="%1$s" name="%2$s" value="%3$s" class="regular-text rwp-media-url"><button type="button" class="button rwp-media-select" data-target="%1$s">%4$s</button></div>',
                esc_attr('rwp_' . $key),
                esc_attr($name),
                esc_attr($value),
                esc_html('Select from Media Library')
            );
            self::render_description($description);
            return;
        }

        if ('color' === $type) {
            printf(
                '<input type="text" id="%1$s" name="%2$s" value="%3$s" class="rwp-color-field" data-default-color="%3$s">',
                esc_attr('rwp_' . $key),
                esc_attr($name),
                esc_attr($value)
            );
            self::render_description($description);
            return;
        }

        printf(
            '<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" class="regular-text">',
            esc_attr($type),
            esc_attr('rwp_' . $key),
            esc_attr($name),
            esc_attr($value)
        );
        self::render_description($description);
    }

    /**
     * Render a field description.
     *
     * @param string $description Description text.
     */
    private static function render_description($description) {
        if (!$description) {
            return;
        }

        echo '<p class="description">' . esc_html($description) . '</p>';
    }

    /**
     * Sanitize a hex color with fallback.
     *
     * @param mixed  $value Raw value.
     * @param string $fallback Fallback value.
     * @return string
     */
    private static function sanitize_hex_color_setting($value, $fallback) {
        $color = sanitize_hex_color((string) $value);

        return $color ? $color : $fallback;
    }
}
