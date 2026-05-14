<?php
/**
 * Compatibility loader for tooling that expects randomizer-wheel.php.
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/simple-randomized-wheel.php';
