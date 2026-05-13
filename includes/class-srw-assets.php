<?php
/**
 * Frontend asset registration and enqueueing.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handles Randomizer Wheel frontend assets.
 */
class RWP_Assets {
    /**
     * Shared style/script handle.
     */
    const HANDLE = 'srw-randomizer-wheel';

    /**
     * Register frontend assets.
     */
    public static function register() {
        wp_register_style(
            self::HANDLE,
            RWP_PLUGIN_URL . 'assets/css/randomizer-wheel.css',
            [],
            RWP_VERSION
        );

        wp_register_script(
            self::HANDLE,
            RWP_PLUGIN_URL . 'assets/js/randomizer-wheel.js',
            [],
            RWP_VERSION,
            true
        );
    }

    /**
     * Enqueue frontend assets, registering first if needed.
     */
    public static function enqueue() {
        if (!wp_style_is(self::HANDLE, 'registered') || !wp_script_is(self::HANDLE, 'registered')) {
            self::register();
        }

        wp_enqueue_style(self::HANDLE);
        wp_enqueue_script(self::HANDLE);
    }
}

if (!function_exists('rwp_register_assets')) {
    /**
     * Procedural wrapper for registering frontend assets.
     */
    function rwp_register_assets() {
        RWP_Assets::register();
    }
}

if (!function_exists('rwp_enqueue_assets')) {
    /**
     * Procedural wrapper for enqueueing frontend assets.
     */
    function rwp_enqueue_assets() {
        RWP_Assets::enqueue();
    }
}
