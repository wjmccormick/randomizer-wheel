<?php
/**
 * Plugin Name: Randomizer Wheel
 * Description: Adds Randomizer Wheel shortcodes for user-entered randomized selections.
 * Version: 1.0.0
 * Author: Wm. James McCormick
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('RWP_VERSION')) {
    define('RWP_VERSION', '1.0.0');
}

if (!defined('RWP_PLUGIN_FILE')) {
    define('RWP_PLUGIN_FILE', __FILE__);
}

if (!defined('RWP_PLUGIN_DIR')) {
    define('RWP_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

if (!defined('RWP_PLUGIN_URL')) {
    define('RWP_PLUGIN_URL', plugin_dir_url(__FILE__));
}

require_once RWP_PLUGIN_DIR . 'includes/class-srw-assets.php';
require_once RWP_PLUGIN_DIR . 'includes/class-srw-shortcodes.php';

add_action('wp_enqueue_scripts', 'rwp_register_assets');
RWP_Shortcodes::register();
