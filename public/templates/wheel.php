<?php
if (!defined('ABSPATH')) {
    exit;
}
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
                Option A
                Option B
                Option C
                Option D"
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
                        alt="Randomizer Wheel logo"
                    />
                </div>

                <div class="srw-result" aria-live="polite">Add items, then spin.</div>

                <div class="srw-presentation-modal" hidden>
                    <div class="srw-presentation-window">
                        <div class="srw-presentation-titlebar">
                            <img src="<?php echo esc_url($logo_url_white); ?>" alt="Randomizer Wheel logo">
                            <div>Randomizer Wheel</div>
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
                                    alt="Randomizer Wheel logo"
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
                    <div class="srw-winner-card" role="dialog" aria-modal="true" aria-labelledby="<?php echo esc_attr($winner_title_id); ?>">
                        <span class="srw-winner-close" role="button" tabindex="0" aria-label="Close winner popup">&times;</span>
                        <div class="srw-winner-kicker">The wheel has spoken</div>

                        <div id="<?php echo esc_attr($winner_title_id); ?>" class="srw-winner-title">
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
