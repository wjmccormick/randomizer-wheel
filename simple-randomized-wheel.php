<?php
/**
 * Plugin Name: Simple Randomized Wheel
 * Description: Adds Randomizer Wheel shortcodes for user-entered randomized selections.
 * Version: 1.0.0
 * Author: Wm. James McCormick
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('SRW_VERSION')) {
    define('SRW_VERSION', '1.0.0');
}

if (!defined('SRW_PLUGIN_FILE')) {
    define('SRW_PLUGIN_FILE', __FILE__);
}

if (!defined('SRW_PLUGIN_DIR')) {
    define('SRW_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

if (!defined('SRW_PLUGIN_URL')) {
    define('SRW_PLUGIN_URL', plugin_dir_url(__FILE__));
}

require_once SRW_PLUGIN_DIR . 'includes/class-srw-assets.php';
require_once SRW_PLUGIN_DIR . 'includes/class-srw-shortcodes.php';

add_action('wp_enqueue_scripts', 'srw_register_assets');
SRW_Shortcodes::register();
