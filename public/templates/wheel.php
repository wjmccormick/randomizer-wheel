<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
    <div
        id="<?php echo esc_attr($wheel_id); ?>"
        class="srw-wrapper"
        style="<?php echo esc_attr($theme_style); ?>"
        data-logo-url="<?php echo esc_url($logo_url_black); ?>"
        data-min-items="<?php echo esc_attr($min_items); ?>"
        data-show-presentation="<?php echo esc_attr($show_presentation ? 'true' : 'false'); ?>"
        data-show-remove-winner="<?php echo esc_attr($show_remove_winner ? 'true' : 'false'); ?>"
    >
        <div class="srw-input-panel">
            <?php if ($title) : ?>
                <h3 class="srw-title"><?php echo esc_html($title); ?></h3>
            <?php endif; ?>

            <div class="srw-input-header">
                <div class="srw-label">
                    <?php echo esc_html('Add items to the wheel, one per line:'); ?>
                </div>

                <div class="srw-entry-count"></div>
            </div>

            <textarea
                id="<?php echo esc_attr($wheel_id); ?>-items"
                class="srw-textarea"
                rows="8"
                placeholder="<?php echo esc_attr($placeholder); ?>"
                ></textarea>

                <?php if ($show_presentation) : ?>
                    <div class="srw-options">
                        <span class="srw-option">
                            <input type="checkbox" class="srw-presentation-toggle">
                            <?php echo esc_html('Presentation Mode'); ?>
                        </span>
                    </div>
                <?php endif; ?>

                <div class="srw-buttons">
                    <button type="button" class="srw-button srw-build"><?php echo esc_html('Update Wheel'); ?></button>
                    <button type="button" class="srw-button srw-clear"><?php echo esc_html('Clear'); ?></button>
                    <button type="button" class="srw-button srw-spin"><?php echo esc_html('Spin'); ?></button>
                    <?php if ($show_remove_winner) : ?>
                        <button type="button" class="srw-button srw-remove" disabled><?php echo esc_html('Remove Winner'); ?></button>
                    <?php endif; ?>
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
                            aria-label="<?php echo esc_attr('Randomized selection wheel'); ?>"
                        ></canvas>
                    </div>

                    <img
                        class="srw-center-logo"
                        src="<?php echo esc_url($logo_url_black); ?>"
                        alt="<?php echo esc_attr($logo_alt); ?>"
                    />
                </div>

                <div class="srw-result" aria-live="polite"><?php echo esc_html('Add items, then spin.'); ?></div>

                <?php if ($show_presentation) : ?>
                    <div class="srw-presentation-modal" hidden>
                        <div class="srw-presentation-window">
                            <div class="srw-presentation-titlebar">
                                <img src="<?php echo esc_url($logo_url_white); ?>" alt="<?php echo esc_attr($logo_alt); ?>">
                                <div><?php echo esc_html($presentation_title); ?></div>
                                <button type="button" class="srw-presentation-close" aria-label="<?php echo esc_attr('Close presentation mode'); ?>">&times;</button>
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
                                        alt="<?php echo esc_attr($logo_alt); ?>"
                                    />
                                </div>

                                <div class="srw-presentation-result"><?php echo esc_html('Spinning...'); ?></div>

                                <button type="button" class="srw-button srw-presentation-spin-again" hidden>
                                    <?php echo esc_html('Spin Again'); ?>
                                </button>
                            </div>

                        </div>
                    </div>
                <?php endif; ?>

                <div class="srw-winner-modal" hidden>
                    <div class="srw-winner-card" role="dialog" aria-modal="true" aria-labelledby="<?php echo esc_attr($winner_title_id); ?>">
                        <span class="srw-winner-close" role="button" tabindex="0" aria-label="<?php echo esc_attr('Close winner popup'); ?>">&times;</span>
                        <div class="srw-winner-kicker"><?php echo esc_html('The wheel has spoken'); ?></div>

                        <div id="<?php echo esc_attr($winner_title_id); ?>" class="srw-winner-title">
                            <?php echo esc_html('Winner'); ?>
                        </div>

                        <div class="srw-winner-name"></div>

                        <div class="srw-winner-actions">
                            <?php if ($show_remove_winner) : ?>
                                <button type="button" class="srw-button srw-winner-remove">
                                    <?php echo esc_html('Remove Winner'); ?>
                                </button>
                            <?php endif; ?>

                            <button type="button" class="srw-button srw-winner-spin-again">
                                <?php echo esc_html('Spin Again'); ?>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
