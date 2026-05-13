<?php
/**
 * Plugin Name: Simple Randomized Wheel
 * Description: Adds a shortcode [randomized_wheel] for a user-entered randomized wheel.
 * Version: 1.0.0
 * Author: Wm. James McCormick
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render the full interactive randomized wheel.
 *
 * Shortcode: [randomized_wheel]
 *
 * Includes:
 * - User textarea input
 * - Entry count
 * - Standard wheel
 * - Presentation mode
 * - Winner announcement modal
 */
function srw_render_randomized_wheel() {
    $wheel_id = 'srw-wheel-' . wp_rand(1000, 999999);
    $logo_url_black = plugin_dir_url(__FILE__) . 'whiskey-whiskers-wheel-logo-black-text.png';
    $logo_url_white = plugin_dir_url(__FILE__) . 'whiskey-whiskers-wheel-logo-white-text.png';

    ob_start();
    ?>
    <div
        id="<?php echo esc_attr($wheel_id); ?>"
        class="srw-wrapper"
        data-logo-url="<?php echo esc_url($logo_url_black); ?>"
    >
        <div class="srw-input-panel">
            <div class="srw-input-header">
                <div class="srw-label">
                    Add items to the wheel, one per line:
                </div>

                <div class="srw-entry-count"></div>
            </div>

            <textarea
                id="<?php echo esc_attr($wheel_id); ?>-items"
                class="srw-textarea"
                rows="8"
                placeholder="Example:
                Buffalo Trace
                Elijah Craig
                Old Forester
                Wild Turkey"
                ></textarea>

                <div class="srw-options">
                    <span class="srw-option">
                        <input type="checkbox" class="srw-presentation-toggle">
                        Presentation Mode
                    </span>
                </div>

                <div class="srw-buttons">
                    <button type="button" class="srw-button srw-build">Update Wheel</button>
                    <button type="button" class="srw-button srw-clear">Clear</button>
                    <button type="button" class="srw-button srw-spin">Spin</button>
                    <button type="button" class="srw-button srw-remove" disabled>Remove Winner</button>
                </div>
            </div>

            <div class="srw-wheel-panel">
                <div class="srw-wheel-stage">
                    <div class="srw-pointer"></div>

                    <div class="srw-wheel-clip">
                        <canvas
                            class="srw-canvas"
                            width="420"
                            height="420"
                            aria-label="Randomized selection wheel"
                        ></canvas>
                    </div>

                    <img
                        class="srw-center-logo"
                        src="<?php echo esc_url($logo_url_black); ?>"
                        alt="Whiskey Whiskers logo"
                    />
                </div>

                <div class="srw-result" aria-live="polite">Add items, then spin.</div>

                <div class="srw-presentation-modal" hidden>
                    <div class="srw-presentation-window">
                        <div class="srw-presentation-titlebar">
                            <img src="<?php echo esc_url($logo_url_white); ?>" alt="Whiskey Whiskers logo">
                            <div>The Whiskey Wheel</div>
                            <button type="button" class="srw-presentation-close" aria-label="Close presentation mode">&times;</button>
                        </div>

                        <div class="srw-presentation-body">
                            <div class="srw-presentation-wheel-stage">
                                <div class="srw-presentation-pointer"></div>

                                <canvas
                                    class="srw-presentation-canvas"
                                    width="900"
                                    height="900"
                                ></canvas>

                                <img
                                    class="srw-presentation-logo"
                                    src="<?php echo esc_url($logo_url_black); ?>"
                                    alt="Whiskey Whiskers logo"
                                />
                            </div>

                            <div class="srw-presentation-result">Spinning...</div>

                            <button type="button" class="srw-button srw-presentation-spin-again" hidden>
                                Spin Again
                            </button>
                        </div>

                    </div>
                </div>

                <div class="srw-winner-modal" hidden>
                    <div class="srw-winner-card" role="dialog" aria-modal="true" aria-labelledby="srw-winner-title">
                        <span class="srw-winner-close" role="button" tabindex="0" aria-label="Close winner popup">&times;</span>
                        <div class="srw-winner-kicker">The wheel has spoken</div>

                        <div id="srw-winner-title" class="srw-winner-title">
                            Winner
                        </div>

                        <div class="srw-winner-name"></div>

                        <div class="srw-winner-actions">
                            <button type="button" class="srw-button srw-winner-remove">
                                Remove Winner
                            </button>

                            <button type="button" class="srw-button srw-winner-spin-again">
                                Spin Again
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    <?php
    return ob_get_clean();
}
add_shortcode('randomized_wheel', 'srw_render_randomized_wheel');

/**
 * Render the homepage hero/demo wheel.
 *
 * Shortcode: [whiskey_wheel_hero link="/whiskey-wheel/"]
 *
 * This is decorative/marketing-focused and links users to the full wheel page.
 */
function srw_render_wheel_hero($atts) {
    $wheel_id = 'srw-hero-' . wp_rand(1000, 999999);
    $atts = shortcode_atts(['link' => '/whiskey-wheel/'], $atts, 'whiskey_wheel_hero');
    $logo_url_black = plugin_dir_url(__FILE__) . 'whiskey-whiskers-wheel-logo-black-text.png';

    $demo_items = [
        'Weller Antique 107',
        'Stagg Jr.',
        'EH Taylor Barrel Proof',
        'Blanton\'s Gold',
        'Wild Turkey Rare Breed',
        'Old Forester 1920',
        'Booker\'s',
        'Russell\'s Reserve',
        'Angel\'s Envy',
        'Elijah Craig Barrel Proof',
        'Smoke Wagon Uncut',
        'Peerless Double Oak',
        'Found North Batch',
        'Michter\'s Rye',
        'Jack Daniel\'s SBBP',
        'Barrell Seagrass',
        'Dark Arts',
        'Penelope Toasted',
        'Still Austin Cask Strength',
        'Balcones Lineage'
    ];

    ob_start();
    ?>

    <div id="<?php echo esc_attr($wheel_id); ?>" class="srw-hero-wrapper" data-items="<?php echo esc_attr(wp_json_encode($demo_items)); ?>">
        <a href="<?php echo esc_url($atts['link']); ?>" class="srw-hero-link" aria-label="Create Your Whiskey Wheel" title="Create Your Whiskey Wheel">
            <div class="srw-hero-stage">
                <div class="srw-hero-pointer"></div>
                <canvas class="srw-hero-canvas" width="1400" height="1400"></canvas>
                <img class="srw-hero-logo" src="<?php echo esc_url($logo_url_black); ?>" alt="Whiskey Whiskers logo"/>
            </div>
        </a>
    </div>

    <?php
    return ob_get_clean();
}
add_shortcode('whiskey_wheel_hero', 'srw_render_wheel_hero');

/**
 * Register inline CSS and JavaScript for both wheel shortcodes.
 *
 * This keeps the plugin self-contained without requiring separate asset files.
 */
function srw_enqueue_assets() {
    $css = <<<CSS
/* Base wheel layout */
.srw-wrapper,
.srw-input-panel,
.srw-wheel-panel,
.srw-wheel-stage,
.srw-textarea,
.srw-canvas {
    box-sizing: border-box;
}

.srw-wrapper {
    width: 100%;
    max-width: 960px;
    margin: 2rem auto;
    display: grid;
    grid-template-columns: minmax(260px, 1fr) minmax(320px, 1fr);
    gap: 2rem;
    align-items: center;
    overflow-x: hidden;
    font-family: inherit;
}

.srw-is-spinning .srw-canvas,
.srw-is-spinning .srw-presentation-wheel-stage {
    cursor: wait;
    pointer-events: none;
}

/* Input panel */
.srw-input-panel,
.srw-wheel-panel {
    width: 100%;
    max-width: 100%;
    background: #fff;
    border: 1px solid rgba(0,0,0,0.12);
    border-radius: 16px;
    padding: 1.25rem;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
}

.srw-input-panel {
    overflow: hidden;
}

.srw-input-header {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.srw-label, .srw-option {
    display: block;
    font-weight: 700;
    color: #222;
}

.srw-entry-count {
    flex: 0 0 auto;
    font-size: 0.85rem;
    font-weight: 700;
    color: #555;
}

.srw-textarea {
    width: 100%;
    resize: vertical;
    border: 1px solid rgba(0,0,0,0.2);
    border-radius: 12px;
    padding: 0.8rem;
    font: inherit;
}

.srw-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 0.6rem;
    margin-top: 0.8rem;
}

.srw-button {
    border: 0;
    border-radius: 999px;
    padding: 0.7rem 1rem;
    cursor: pointer;
    font-weight: 700;
    background: #222;
    color: #fff;
}

.srw-button:hover,
.srw-button:focus {
    opacity: 0.9;
}

.srw-button:disabled {
    opacity: 0.45;
    cursor: not-allowed;
}

/* Standard wheel display */
.srw-wheel-panel {
    position: relative;
    text-align: center;
    overflow: hidden;
}

.srw-wheel-stage {
    position: relative;
    width: min(420px, 100%);
    max-width: 100%;
    margin: 0 auto;
    overflow: visible;
}

.srw-wheel-clip {
    position: relative;
    width: 100%;
    aspect-ratio: 1 / 1;
    border-radius: 50%;
    overflow: hidden;
    isolation: isolate;
    background: transparent;
}

.srw-canvas {
    display: block;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: transparent;
    transition: transform 4s cubic-bezier(.12,.67,.16,1);
    transform-origin: 50% 50%;
    backface-visibility: hidden;
    will-change: transform;
    cursor: pointer;
}

.srw-center-logo {
    position: absolute;
    height: auto;
    aspect-ratio: 1 / 1;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    object-fit: contain;
    border-radius: 50%;
    z-index: 5;
    pointer-events: none;
}

.srw-pointer {
    position: absolute;
    top: 0;
    left: 50%;
    width: 0;
    height: 0;
    border-left: 16px solid transparent;
    border-right: 16px solid transparent;
    border-top: 32px solid var(--ast-global-color-0);
    transform: translateX(-50%);
    z-index: 10;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.35));
}

.srw-center-logo,
.srw-presentation-logo {
    width: 16%;
}

/* Presentation mode modal */
.srw-presentation-modal {
    position: fixed;
    inset: 0;
    z-index: 10000;
    background: rgba(8, 14, 26, 0.92);
    display: grid;
    place-items: center;
    padding: 1rem;
}

.srw-presentation-modal[hidden] {
    display: none !important;
}

.srw-presentation-window {
    width: min(1100px, 96vw);
    height: min(900px, 92vh);
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 30px 90px rgba(0,0,0,0.55);
}

.srw-presentation-titlebar {
    height: 58px;
    display: grid;
    grid-template-columns: 58px 1fr 58px;
    align-items: center;
    background: #202a36;
    color: #fff;
    font-weight: 700;
    text-align: center;
}

.srw-presentation-titlebar img {
    width: 38px;
    height: 38px;
    margin-left: 12px;
    object-fit: contain;
    aspect-ratio: 1 / 1;
}

.srw-presentation-body {
    position: relative;
    height: calc(100% - 58px);
    display: grid;
    grid-template-rows: auto 1fr;
    align-items: start;
    justify-items: center;
    padding: 1.5rem 2rem 2rem;
    overflow: hidden;
}

.srw-presentation-wheel-stage {
    position: relative;
    width: min(54vh, 62vw);
    aspect-ratio: 1 / 1;
    margin-top: 0;
    cursor: pointer;
}

.srw-presentation-canvas {
    display: block;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    transform-origin: 50% 50%;
    backface-visibility: hidden;
    will-change: transform;
}

.srw-presentation-logo {
    position: absolute;
    height: auto;
    aspect-ratio: 1 / 1;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    object-fit: contain;
    border-radius: 50%;
    z-index: 5;
    pointer-events: none;
}

.srw-presentation-pointer {
    position: absolute;
    top: 0;
    left: 50%;
    width: 0;
    height: 0;
    border-left: 22px solid transparent;
    border-right: 22px solid transparent;
    border-top: 42px solid var(--ast-global-color-0);
    transform: translateX(-50%);
    z-index: 10;
    filter:
        drop-shadow(0 2px 3px rgba(0,0,0,0.35))
        drop-shadow(0 0 10px rgba(0,136,255,0.25));
}

.srw-presentation-close {
    width: 42px;
    height: 42px;
    padding: 0;
    margin-right: 12px;
    display: grid;
    place-items: center;
    justify-self: end;
    border: 0;
    border-radius: 50%;
    background: var(--ast-global-color-0);
    color: #fff;
    font-size: 28px;
    line-height: 1;
    cursor: pointer;
    transition: background 0.2s ease, transform 0.15s ease;
}

.srw-is-spinning .srw-presentation-close {
    opacity: 0.45;
    cursor: wait;
    pointer-events: none;
}

.srw-presentation-close:hover,
.srw-presentation-close:focus-visible {
    background: #b9372b;
    transform: scale(1.05);
}

.srw-presentation-result {
    width: 100%;
    max-width: 92%;
    min-height: 10rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    margin-top: 1rem;
    color: #111;
    font-weight: 700;
}

.srw-presentation-spin-again {
    margin-top: 1rem;
}

.srw-result {
    min-height: 1.8em;
    margin-top: 1rem;
    font-size: 1.25rem;
    font-weight: 800;
    color: #222;
}

.srw-result-label {
    display: block;
    font-size: 0.95rem;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--ast-global-color-0);
    font-weight: 800;
    margin-bottom: 0.25rem;
}

.srw-result-value {
    display: block;
    font-size: clamp(1.8rem, 3.2vw, 3rem);
    line-height: 1.05;
    font-weight: 900;
    color: #111;
}

/* Winner announcement modal */
.srw-winner-modal {
    position: fixed;
    inset: 0;
    display: grid;
    place-items: center;
    padding: 1rem;
    background: rgba(8, 14, 26, 0.72);
    backdrop-filter: blur(4px);
    z-index: 11000;
}

.srw-winner-modal[hidden] {
    display: none;
}

.srw-winner-card {
    position: relative;
    width: min(560px, 100%);
    padding: 2rem;
    border-radius: 20px;
    background:
        radial-gradient(circle at top, rgba(255,255,255,0.12), transparent 38%),
        #ffffff;
    color: #222;
    text-align: center;
    box-shadow: 0 24px 70px rgba(0,0,0,0.38);
    animation: srwWinnerPop 260ms ease-out;
}

.srw-winner-kicker {
    margin-bottom: 0.4rem;
    font-size: 0.85rem;
    font-weight: 800;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--ast-global-color-0);
}

.srw-winner-title {
    font-size: 1.25rem;
    font-weight: 800;
    color: #222;
}

.srw-winner-name {
    margin: 1rem auto 1.25rem;
    font-size: clamp(1.5rem, 4vw, 2.6rem);
    line-height: 1.08;
    font-weight: 800;
    color: #111;
    overflow-wrap: break-word;
    max-width: 18ch;
}

.srw-winner-name.is-long {
    font-size: clamp(1.2rem, 3vw, 2rem);
    max-width: 26ch;
    line-height: 1.12;
}

.srw-winner-actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.75rem;
}

.srw-winner-actions .srw-button {
    min-width: 0;
}

.srw-winner-close {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    width: 48px;
    height: 48px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    background: var(--ast-global-color-0);
    color: #fff;
    font-size: 28px;
    line-height: 1;
    transform: translateY(-2px);
    cursor: pointer;
    user-select: none;
    z-index: 30;
    -webkit-tap-highlight-color: transparent;
    outline: none;
    touch-action: manipulation;
}

.srw-winner-close:hover {
    background: #222;
    color: #fff;
}

.srw-winner-close:focus-visible {
    background: #222;
    color: #fff;
    outline: 2px solid var(--ast-global-color-0);
    outline-offset: 3px;
}

.srw-winner-close:focus {
    outline: none;
    box-shadow: none;
}

/* Homepage hero wheel */
.srw-hero-wrapper {
    position: relative;
    width: 100%;
    height: 420px;
    overflow: hidden;
    background: #111822;
    border-radius: 18px;
}

.srw-hero-stage {
    position: absolute;
    right: -180px;
    top: 50%;
    width: 900px;
    height: 900px;
    transform: translateY(-50%);
}

.srw-hero-canvas {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    animation: srwHeroSpin 28s linear infinite;
}

.srw-hero-logo {
    position: absolute;
    width: 12%;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    pointer-events: none;
}

.srw-hero-pointer {
    position: absolute;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 26px solid transparent;
    border-right: 26px solid transparent;
    border-top: 52px solid var(--ast-global-color-0);
    z-index: 20;
}

.srw-hero-link {
    display: block;
    width: 100%;
    height: 100%;
    text-decoration: none;
    cursor: pointer;
}

.srw-hero-link canvas,
.srw-hero-link img {
    pointer-events: none;
}

@keyframes srwHeroSpin {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

@keyframes srwWinnerPop {
    from {
        opacity: 0;
        transform: translateY(10px) scale(0.96);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Mobile layout */
@media (max-width: 760px) {
    .srw-wrapper {
        grid-template-columns: 1fr;
        max-width: 100%;
        margin: 1rem 0;
        padding: 0;
    }

    .srw-input-panel,
    .srw-wheel-panel {
        padding: 1rem;
    }

    .srw-wheel-panel {
        order: 1;
    }

    .srw-input-panel {
        order: 2;
    }

    .srw-buttons {
        width: 100%;
    }

    .srw-button {
        flex: 1 1 auto;
    }

    .srw-winner-close {
        width: 42px;
        height: 42px;
        font-size: 24px;
        top: 0.65rem;
        right: 0.65rem;
    }

    .srw-winner-kicker {
        font-size: 0.72rem;
        letter-spacing: 0.08em;
    }

    .srw-winner-card {
        padding: 1.5rem 1.25rem;
    }

    .srw-presentation-modal {
        padding: 0.5rem;
    }

    .srw-presentation-window {
        width: 100%;
        height: 92vh;
        border-radius: 14px;
    }

    .srw-presentation-titlebar {
        height: 56px;
        grid-template-columns: 48px 1fr 48px;
    }

    .srw-presentation-titlebar img {
        width: 34px;
        height: 34px;
        margin-left: 8px;
    }

    .srw-presentation-close {
        width: 38px;
        height: 38px;
        margin-right: 8px;
        font-size: 24px;
    }

    .srw-presentation-body {
        padding: 1rem 1rem 1.5rem;
        display: grid;
        grid-template-rows: auto auto 1fr;
        align-items: start;
        justify-items: center;
        gap: 1.25rem;
    }

    .srw-presentation-wheel-stage {
        width: min(68vw, 38vh);
    }

    .srw-presentation-pointer {
        border-left-width: 18px;
        border-right-width: 18px;
        border-top-width: 34px;
    }

    .srw-result-value {
        font-size: clamp(1.6rem, 8vw, 2.4rem);
        max-width: 12ch;
    }

    .srw-hero-wrapper {
        height: 400px;
    }

    .srw-hero-stage {
        width: 520px;
        height: 520px;
        right: -180px;
        top: 72%;
    }

    .srw-hero-pointer {
        display: none;
    }

    .srw-hero-logo {
        width: 12%;
    }
}
CSS;
wp_register_style('srw-inline-style', false);
wp_enqueue_style('srw-inline-style');
wp_add_inline_style('srw-inline-style', $css);

    $js = <<<JS
(function () {
    /**
    * Parse textarea input into a deduplicated, shuffled item list.
    * The shuffle prevents alphabetical or manually grouped lists from appearing ordered on the wheel.
    */
    function parseItems(textarea) {

        var items = textarea.value
            .split('\\n')
            .map(function (item) {
                return item.trim();
            })
            .filter(function (item, index, arr) {
                return item.length > 0 && arr.indexOf(item) === index;
            });

        // Fisher-Yates shuffle
        for (var i = items.length - 1; i > 0; i--) {
            var j = Math.floor(Math.random() * (i + 1));

            var temp = items[i];
            items[i] = items[j];
            items[j] = temp;
        }

        return items;
    }

    /**
    * Draw the wheel onto a canvas.
    *
    * Handles:
    * - Slice colors
    * - Slice dividers
    * - Responsive label sizing
    * - Dense-wheel label suppression
    * - Center hub background
    */
    function drawWheel(canvas, items) {
        var ctx = canvas.getContext('2d');
        var width = canvas.width;
        var height = canvas.height;
        var radius = Math.min(width, height) / 2 - 3;
        var centerX = width / 2;
        var centerY = height / 2;

        ctx.clearRect(0, 0, width, height);

        if (!items.length) {
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, 0, Math.PI * 2);
            ctx.fillStyle = '#f2f2f2';
            ctx.fill();
            ctx.strokeStyle = '#ddd';
            ctx.lineWidth = 2;
            ctx.stroke();
            return;
        }

        var slice = (Math.PI * 2) / items.length;

        var isLargeWheel = items.length > 100;
        var isDenseWheel = items.length > 70;

        var fontSize;
        if (items.length <= 12) {
            fontSize = 15;
        } else if (items.length <= 24) {
            fontSize = 13;
        } else if (items.length <= 40) {
            fontSize = 11;
        } else {
            fontSize = 9;
        }

        var colors = [
            '#8b5a2b',
            '#c28a2c',
            '#2f2f2f',
            '#d7b377',
            '#5c4033',
            '#f3e1b6',
            '#9c6a2f',
            '#3a3a3a',
            '#b8860b',
            '#6b4423'
        ];

        var hubRadius = radius * 0.18;
        
        items.forEach(function (item, index) {
            var start = index * slice - Math.PI / 2;
            var end = start + slice;

            ctx.beginPath();
            ctx.moveTo(centerX, centerY);
            ctx.arc(centerX, centerY, radius, start, end);
            ctx.closePath();
            ctx.fillStyle = colors[index % colors.length];
            ctx.fill();

            if (isLargeWheel) {
                ctx.strokeStyle = 'rgba(255,255,255,0.18)';
                ctx.lineWidth = 0.6;
            } else if (isDenseWheel) {
                ctx.strokeStyle = 'rgba(255,255,255,0.45)';
                ctx.lineWidth = 0.75;
            } else {
                ctx.strokeStyle = '#fff';
                ctx.lineWidth = 2;
            }

            ctx.stroke();

            if (!isLargeWheel) {
                ctx.save(); // saves translate/rotate state

                ctx.translate(centerX, centerY);
                ctx.rotate(start + slice / 2);

                ctx.textAlign = 'right';
                ctx.fillStyle = '#fff';

                var scale = width / 420;

                ctx.font = '700 ' + Math.round(fontSize * scale) + 'px sans-serif';

                var label = item.length > 22
                    ? item.substring(0, 19) + '...'
                    : item;

                var outerPadding = 12 * scale;

                var inwardFactor;

                if (items.length <= 12) {
                    inwardFactor = 0.18;
                } else if (items.length <= 24) {
                    inwardFactor = 0.28;
                } else if (items.length <= 40) {
                    inwardFactor = 0.42;
                } else if (items.length <= 70) {
                    inwardFactor = 0.50;
                } else {
                    inwardFactor = 0.50;
                }

                var smallWheelPush = items.length <= 12
                    ? 6 * scale
                    : 0;

                var textRadius = radius - outerPadding + smallWheelPush;

                ctx.save(); // saves translate/rotate state

                ctx.beginPath();
                ctx.rect(
                    0,
                    -22 * scale,
                    textRadius,
                    44 * scale
                );
                ctx.clip();

                ctx.fillText(label, textRadius, 5 * scale);

                ctx.restore();  // restores clipping state
                ctx.restore();  // restores translate/rotate state
            }
        });
        ctx.globalAlpha = 1;

        if (isLargeWheel) {
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, 0, Math.PI * 2);

            ctx.strokeStyle = 'rgba(255,255,255,0.35)';
            ctx.lineWidth = 2;

            ctx.stroke();
        }

        ctx.save();

        ctx.beginPath();
        ctx.arc(centerX, centerY, hubRadius, 0, Math.PI * 2);
        ctx.closePath();

        ctx.fillStyle = '#ffffff';
        ctx.fill();

        ctx.strokeStyle = '#2a2a2a';
        ctx.lineWidth = 2;
        ctx.stroke();

        ctx.restore();
    }

    /**
    * Pick a random winner using crypto-safe randomness when available.
    */
    function secureRandomIndex(max) {
        if (window.crypto && window.crypto.getRandomValues) {
            var array = new Uint32Array(1);
            window.crypto.getRandomValues(array);
            return array[0] % max;
        }
        return Math.floor(Math.random() * max);
    }

    /**
    * Initialize one full interactive wheel instance.
    */
    function initWheel(wrapper) {
        var textarea = wrapper.querySelector('.srw-textarea');
        var canvas = wrapper.querySelector('.srw-canvas');
        var result = wrapper.querySelector('.srw-result');
        var buildButton = wrapper.querySelector('.srw-build');
        var spinButton = wrapper.querySelector('.srw-spin');
        var clearButton = wrapper.querySelector('.srw-clear');
        var removeButton = wrapper.querySelector('.srw-remove');

        var presentationCanvas = wrapper.querySelector('.srw-presentation-canvas');
        var presentationResult = wrapper.querySelector('.srw-presentation-result');
        var presentationToggle = wrapper.querySelector('.srw-presentation-toggle');
        var presentationMode = wrapper.querySelector('.srw-presentation-modal');
        var presentationModeClose = wrapper.querySelector('.srw-presentation-close');
        var presentationSpinAgainButton = wrapper.querySelector('.srw-presentation-spin-again');
        var presentationWheelStage = wrapper.querySelector('.srw-presentation-wheel-stage');

        var winnerModal = wrapper.querySelector('.srw-winner-modal');
        var winnerName = wrapper.querySelector('.srw-winner-name');
        var winnerClose = wrapper.querySelector('.srw-winner-close');
        var winnerRemoveButton = wrapper.querySelector('.srw-winner-remove');
        var winnerSpinAgainButton = wrapper.querySelector('.srw-winner-spin-again');
        var currentWinner = null;

        var currentRotation = 0;
        var isSpinning = false;
        var items = [];

        /**
        * Rebuild the wheel from the textarea and reset winner state.
        */
        function rebuild() {
            items = parseItems(textarea);

            var entryCount = wrapper.querySelector('.srw-entry-count');

            entryCount.textContent = items.length
                ? items.length + ' entries'
                : '';     

            currentWinner = null;
            removeButton.disabled = true;

            canvas.style.transition = 'none';
            currentRotation = 0;
            canvas.style.transform = 'rotate(0deg)';

            drawWheel(canvas, items);
            
            result.textContent = items.length ? 'Click the wheel to spin or click the Spin button.' : 'Add items, then spin.';
        }

        /**
        * Open presentation mode and perform a spin inside the modal view.
        */
        function openPresentationMode() {
            if (isSpinning) {
                return;
            }
            isSpinning = true;
            wrapper.classList.add('srw-is-spinning');

            presentationSpinAgainButton.hidden = true;
            presentationMode.hidden = false;

            presentationCanvas.style.transition = 'none';
            presentationCanvas.style.transform = 'rotate(0deg)';

            drawWheel(presentationCanvas, items);

            presentationResult.textContent = 'Spinning...';

            window.setTimeout(function () {
                presentationCanvas.style.transition = 'transform 4s cubic-bezier(.12,.67,.16,1)';

                var winnerIndex = secureRandomIndex(items.length);
                var sliceDegrees = 360 / items.length;
                var winnerCenterDegrees = winnerIndex * sliceDegrees + sliceDegrees / 2;
                var targetAtPointer = 360 - winnerCenterDegrees;
                var presentationRotation = 360 * 6 + targetAtPointer;

                presentationCanvas.style.transform = 'rotate(' + presentationRotation + 'deg)';

                window.setTimeout(function () {
                    wrapper.classList.remove('srw-is-spinning');
                    isSpinning = false;
                    currentWinner = items[winnerIndex];

                    presentationResult.innerHTML =
                        '<span class="srw-result-label">Selected</span>' +
                        '<span class="srw-result-value"></span>';

                    presentationResult.querySelector('.srw-result-value').textContent = currentWinner;

                    result.textContent = 'WINNER: ' + currentWinner;

                    removeButton.disabled = false;

                    // Show consistent winner popup after presentation spin
                    openWinnerModal(currentWinner);

                }, 4100);
            }, 50);
        }

        /**
        * Close presentation mode.
        */
        function closePresentationMode() {
            if (isSpinning) {
                return;
            }
            presentationMode.hidden = true;
        }

        /**
        * Display the winner announcement popup.
        */
        function openWinnerModal(winner) {
            winnerName.textContent = winner;

            winnerName.classList.toggle(
                'is-long',
                winner.length > 45
            );

            winnerModal.hidden = false;
            winnerClose.focus();
        }

        /**
        * Close winner announcement popup.
        */
        function closeWinnerModal() {
            winnerModal.hidden = true;
        }

        buildButton.addEventListener('click', rebuild);

        clearButton.addEventListener('click', function () {
            textarea.value = '';
            rebuild();
        });

        spinButton.addEventListener('click', function () {
            if (isSpinning) {
                return;
            }

            items = parseItems(textarea);

            if (items.length < 2) {
                result.textContent = 'Add at least 2 items before spinning.';
                drawWheel(canvas, items);
                return;
            }

            if (presentationToggle.checked) {
                openPresentationMode();
                return;
            }

            isSpinning = true;
            wrapper.classList.add('srw-is-spinning');

            drawWheel(canvas, items);

            var winnerIndex = secureRandomIndex(items.length);
            var sliceDegrees = 360 / items.length;
            var winnerCenterDegrees = winnerIndex * sliceDegrees + sliceDegrees / 2;
            var targetAtPointer = 360 - winnerCenterDegrees;
            var extraSpins = 360 * 6;

            var currentNormalizedRotation = currentRotation % 360;
            var rotationNeeded = targetAtPointer - currentNormalizedRotation;

            if (rotationNeeded < 0) {
                rotationNeeded += 360;
            }

            currentRotation += extraSpins + rotationNeeded;

            canvas.style.transition = 'transform 4s cubic-bezier(.12,.67,.16,1)';
            canvas.style.transform = 'rotate(' + currentRotation + 'deg)';
            result.textContent = 'Spinning...';

            window.setTimeout(function () {
                wrapper.classList.remove('srw-is-spinning');
                isSpinning = false;
                currentWinner = items[winnerIndex];
                result.textContent = 'WINNER: ' + currentWinner;
                removeButton.disabled = false;
                openWinnerModal(currentWinner);
            }, 4100);
        });
        canvas.addEventListener('click', function () {
            spinButton.click();
        });

        presentationWheelStage.addEventListener('click', function () {
            if (presentationToggle.checked && !presentationMode.hidden && items.length >= 2) {
                openPresentationMode();
            }
        });

        removeButton.addEventListener('click', function () {
            if (!currentWinner) {
                return;
            }

            items = items.filter(function (item) {
                return item !== currentWinner;
            });

            textarea.value = items.join('\\n');

            currentWinner = null;
            removeButton.disabled = true;

            canvas.style.transition = 'none';
            currentRotation = 0;
            canvas.style.transform = 'rotate(0deg)';

            drawWheel(canvas, items);

            result.textContent = items.length
                ? 'Winner removed.'
                : 'No items remaining.';
        });

        presentationModeClose.addEventListener('click', closePresentationMode);

        presentationModeClose.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                closePresentationMode();
            }
        });

        presentationMode.addEventListener('click', function (event) {
            if (event.target === presentationMode) {
                closePresentationMode();
            }
        });

        presentationSpinAgainButton.addEventListener('click', function () {
            if (items.length < 2) {
                presentationResult.textContent = 'Add at least 2 items before spinning.';
                return;
            }

            openPresentationMode();
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !presentationMode.hidden) {
                closePresentationMode();
            }
        });

        winnerClose.addEventListener('click', closeWinnerModal);

        winnerClose.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                closeWinnerModal();
            }
        });

        winnerModal.addEventListener('click', function (event) {
            if (event.target === winnerModal) {
                closeWinnerModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !winnerModal.hidden) {
                closeWinnerModal();
            }
        });

        winnerRemoveButton.addEventListener('click', function () {
            closeWinnerModal();
            removeButton.click();

            if (presentationToggle.checked && !presentationMode.hidden) {
                presentationCanvas.style.transition = 'none';
                presentationCanvas.style.transform = 'rotate(0deg)';

                drawWheel(presentationCanvas, items);

                presentationResult.textContent = items.length
                    ? 'Winner removed. Click the wheel to spin again.'
                    : 'No items remaining.';

                presentationSpinAgainButton.hidden = items.length < 2;
            }
        });

        winnerSpinAgainButton.addEventListener('click', function () {
            closeWinnerModal();

            if (presentationToggle.checked) {
                openPresentationMode();
            } else {
                spinButton.click();
            }
        });

        rebuild();
    }

    /**
    * Initialize all full wheel instances on the page.
    */
    function startRandomizedWheels() {
        document.querySelectorAll('.srw-wrapper').forEach(function (wrapper) {
            if (!wrapper.dataset.srwInitialized) {
                wrapper.dataset.srwInitialized = 'true';
                initWheel(wrapper);
            }
        });
    }

    /**
    * Initialize all homepage hero/demo wheel instances on the page.
    */
    function startHeroWheels() {
        document.querySelectorAll('.srw-hero-wrapper').forEach(function (wrapper) {
            if (wrapper.dataset.srwHeroInitialized) {
                return;
            }
            wrapper.dataset.srwHeroInitialized = 'true';
            var canvas = wrapper.querySelector('.srw-hero-canvas');
            var items = JSON.parse(wrapper.dataset.items);
            drawWheel(canvas, items);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            startRandomizedWheels();
            startHeroWheels();
        });
    } else {
        startRandomizedWheels();
        startHeroWheels();
    }
})();
JS;

    wp_register_script(
        'srw-inline-script',
        plugins_url('srw-inline.js', __FILE__),
        [],
        '1.0.0',
        true
    );

    wp_enqueue_script('srw-inline-script');

    wp_add_inline_script(
        'srw-inline-script',
        $js,
        'before'
    );
}
add_action('wp_enqueue_scripts', 'srw_enqueue_assets');