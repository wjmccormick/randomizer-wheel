<?php
/**
 * Documentation page view.
 */

if (!defined('ABSPATH')) {
    exit;
}

$palette_previews = [
    'classic' => [
        'label' => 'Classic',
        'colors' => ['#8b5a2b', '#c28a2c', '#2f2f2f', '#d7b377', '#5c4033', '#f3e1b6', '#9c6a2f', '#3a3a3a', '#b8860b', '#6b4423'],
    ],
    'bourbon' => [
        'label' => 'Bourbon',
        'colors' => ['#4a2511', '#7a3f17', '#a95f1e', '#c98b3a', '#e4b35d', '#5f3215', '#8f4b1a', '#2c1a12', '#d6a04a', '#6f4525'],
    ],
    'bright' => [
        'label' => 'Bright',
        'colors' => ['#ef476f', '#ffd166', '#06d6a0', '#118ab2', '#8338ec', '#ff9f1c', '#2ec4b6', '#e71d36', '#3a86ff', '#fb5607'],
    ],
    'muted' => [
        'label' => 'Muted',
        'colors' => ['#6d6875', '#b5838d', '#e5989b', '#ffb4a2', '#9a8c98', '#c9ada7', '#4a4e69', '#8d99ae', '#a5a58d', '#b7b7a4'],
    ],
    'monochrome' => [
        'label' => 'Monochrome',
        'colors' => ['#111111', '#2a2a2a', '#444444', '#5e5e5e', '#777777', '#919191', '#ababab', '#c5c5c5', '#dfdfdf', '#f3f3f3'],
    ],
];

$shortcode_examples = [
    '[randomizer_wheel]',
    '[randomizer_wheel wheel_palette="bourbon"]',
    '[randomizer_wheel min_items="3"]',
    '[randomizer_wheel accent_color="#00aaff"]',
    '[randomizer_wheel_hero]',
];
?>
<div class="wrap rwp-docs-wrap">
    <h1><?php echo esc_html('Randomizer Wheel Documentation'); ?></h1>
    <p class="description"><?php echo esc_html('Use this guide to copy shortcodes, review available attributes, and preview bundled wheel palettes without leaving WordPress.'); ?></p>

    <div class="rwp-docs-grid">
        <section class="rwp-docs-card">
            <h2><?php echo esc_html('Public shortcode reference'); ?></h2>
            <table class="widefat striped rwp-docs-table">
                <thead>
                    <tr>
                        <th><?php echo esc_html('Shortcode'); ?></th>
                        <th><?php echo esc_html('Purpose'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>[randomizer_wheel]</code></td>
                        <td><?php echo esc_html('Renders the full interactive wheel with visitor-entered items, spin controls, result modal, and optional presentation mode.'); ?></td>
                    </tr>
                    <tr>
                        <td><code>[randomizer_wheel_hero]</code></td>
                        <td><?php echo esc_html('Renders a decorative linked hero/demo wheel using configured or shortcode-provided demo items.'); ?></td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="rwp-docs-card">
            <h2><?php echo esc_html('Usage examples'); ?></h2>
            <p><?php echo esc_html('Copy a shortcode and paste it into a page, post, or shortcode-capable widget.'); ?></p>
            <div class="rwp-shortcode-examples">
                <?php foreach ($shortcode_examples as $shortcode_example) : ?>
                    <div class="rwp-shortcode-example">
                        <code><?php echo esc_html($shortcode_example); ?></code>
                        <button type="button" class="button button-secondary rwp-copy-shortcode" data-shortcode="<?php echo esc_attr($shortcode_example); ?>">
                            <?php echo esc_html('Copy shortcode'); ?>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <section class="rwp-docs-card">
        <h2><?php echo esc_html('Attribute reference'); ?></h2>
        <table class="widefat striped rwp-docs-table">
            <thead>
                <tr>
                    <th><?php echo esc_html('Attribute'); ?></th>
                    <th><?php echo esc_html('Shortcode'); ?></th>
                    <th><?php echo esc_html('Default'); ?></th>
                    <th><?php echo esc_html('Notes'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>title</code></td>
                    <td><code>[randomizer_wheel]</code>, <code>[randomizer_wheel_hero]</code></td>
                    <td><?php echo esc_html('Admin default, then empty'); ?></td>
                    <td><?php echo esc_html('Optional heading/title text. Hero title falls back to the CTA text when empty.'); ?></td>
                </tr>
                <tr>
                    <td><code>placeholder</code></td>
                    <td><code>[randomizer_wheel]</code></td>
                    <td><?php echo esc_html('Admin default'); ?></td>
                    <td><?php echo esc_html('Textarea placeholder shown before visitors add wheel entries.'); ?></td>
                </tr>
                <tr>
                    <td><code>min_items</code></td>
                    <td><code>[randomizer_wheel]</code></td>
                    <td><code>2</code></td>
                    <td><?php echo esc_html('Affects spinning only. Visitors can type fewer items, but the wheel will not spin until the minimum is met.'); ?></td>
                </tr>
                <tr>
                    <td><code>show_presentation</code></td>
                    <td><code>[randomizer_wheel]</code></td>
                    <td><code>true</code></td>
                    <td><?php echo esc_html('Set to false to hide presentation mode controls for one wheel.'); ?></td>
                </tr>
                <tr>
                    <td><code>show_remove_winner</code></td>
                    <td><code>[randomizer_wheel]</code></td>
                    <td><code>true</code></td>
                    <td><?php echo esc_html('Set to false to hide remove-winner controls for one wheel.'); ?></td>
                </tr>
                <tr>
                    <td><code>wheel_palette</code></td>
                    <td><code>[randomizer_wheel]</code>, <code>[randomizer_wheel_hero]</code></td>
                    <td><code>classic</code></td>
                    <td><?php echo esc_html('Controls canvas slice colors. Supported values are classic, bourbon, bright, muted, and monochrome.'); ?></td>
                </tr>
                <tr>
                    <td><code>accent_color</code></td>
                    <td><code>[randomizer_wheel]</code>, <code>[randomizer_wheel_hero]</code></td>
                    <td><code>#b8860b</code></td>
                    <td><?php echo esc_html('Controls pointer and modal accent styling, including winner accent text and close buttons.'); ?></td>
                </tr>
                <tr>
                    <td><code>items</code></td>
                    <td><code>[randomizer_wheel_hero]</code></td>
                    <td><?php echo esc_html('Admin default'); ?></td>
                    <td><?php echo esc_html('Pipe-separated hero demo items, for example: Option A|Option B|Option C.'); ?></td>
                </tr>
                <tr>
                    <td><code>link</code>, <code>cta_text</code></td>
                    <td><code>[randomizer_wheel_hero]</code></td>
                    <td><?php echo esc_html('Admin defaults'); ?></td>
                    <td><?php echo esc_html('Control where the hero wheel links and the accessible call-to-action label.'); ?></td>
                </tr>
                <tr>
                    <td><code>logo</code>, <code>logo_alt</code></td>
                    <td><code>[randomizer_wheel]</code>, <code>[randomizer_wheel_hero]</code></td>
                    <td><?php echo esc_html('Admin defaults, then bundled logo'); ?></td>
                    <td><?php echo esc_html('Override the center logo URL and accessible alt text for one rendered wheel.'); ?></td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="rwp-docs-card">
        <h2><?php echo esc_html('Palette preview'); ?></h2>
        <p><?php echo esc_html('The wheel_palette attribute controls the canvas slice colors. These previews show the bundled palette colors.'); ?></p>
        <div class="rwp-palette-grid">
            <?php foreach ($palette_previews as $palette_slug => $palette_preview) : ?>
                <div class="rwp-palette-preview">
                    <h3><?php echo esc_html($palette_preview['label']); ?></h3>
                    <div class="rwp-palette-swatches" aria-label="<?php echo esc_attr($palette_preview['label'] . ' palette colors'); ?>">
                        <?php foreach ($palette_preview['colors'] as $color) : ?>
                            <span class="rwp-palette-swatch" style="background-color: <?php echo esc_attr($color); ?>;" title="<?php echo esc_attr($color); ?>" aria-label="<?php echo esc_attr($color); ?>"></span>
                        <?php endforeach; ?>
                    </div>
                    <code><?php echo esc_html('[randomizer_wheel wheel_palette="' . $palette_slug . '"]'); ?></code>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="rwp-docs-card">
        <h2><?php echo esc_html('Behavioral notes'); ?></h2>
        <ul class="rwp-docs-list">
            <li><?php echo esc_html('Shortcode attributes override saved admin defaults for only that rendered wheel instance.'); ?></li>
            <li><?php echo esc_html('Saved admin defaults are used when a shortcode attribute is omitted. Bundled defaults are used when no saved value exists.'); ?></li>
            <li><?php echo esc_html('The min_items attribute affects spinning only; it does not prevent visitors from typing or editing fewer entries.'); ?></li>
            <li><?php echo esc_html('The wheel_palette attribute controls canvas slice colors for both the full wheel and hero wheel.'); ?></li>
            <li><?php echo esc_html('The accent_color attribute controls pointer and modal accent styling, not the canvas slice palette.'); ?></li>
            <li><?php echo esc_html('Multiple wheels on one page can use different shortcode attributes and palettes.'); ?></li>
        </ul>
    </section>
</div>
