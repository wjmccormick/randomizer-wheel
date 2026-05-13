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
class SRW_Assets {
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
            SRW_PLUGIN_URL . 'assets/css/randomizer-wheel.css',
            [],
            SRW_VERSION
        );

        wp_register_script(
            self::HANDLE,
            SRW_PLUGIN_URL . 'assets/js/randomizer-wheel.js',
            [],
            SRW_VERSION,
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

if (!function_exists('srw_register_assets')) {
    /**
     * Backward-compatible wrapper for registering frontend assets.
     */
    function srw_register_assets() {
        SRW_Assets::register();
    }
}

if (!function_exists('srw_enqueue_assets')) {
    /**
     * Backward-compatible wrapper for enqueueing frontend assets.
     */
    function srw_enqueue_assets() {
        SRW_Assets::enqueue();
    }
}
