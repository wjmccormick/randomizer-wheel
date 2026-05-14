(function () {
    /**
    * Parse textarea input into a deduplicated, shuffled item list.
    * The shuffle prevents alphabetical or manually grouped lists from appearing ordered on the wheel.
    */
    function parseItems(textarea) {

        var items = textarea.value
            .split(/\r\n|\r|\n/)
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
    * Read the resolved shortcode/admin theme colors from CSS custom properties.
    */
    function getThemeColors(wrapper) {
        var style = window.getComputedStyle(wrapper);
        var defaults = {
            primaryColor: '#8b5a2b',
            secondaryColor: '#c28a2c',
            accentColor: '#b8860b',
            buttonColor: '#222222'
        };

        return {
            primaryColor: style.getPropertyValue('--rwp-primary-color').trim() || defaults.primaryColor,
            secondaryColor: style.getPropertyValue('--rwp-secondary-color').trim() || defaults.secondaryColor,
            accentColor: style.getPropertyValue('--rwp-accent-color').trim() || defaults.accentColor,
            buttonColor: style.getPropertyValue('--rwp-button-color').trim() || defaults.buttonColor
        };
    }

    /**
    * Build a slice palette while preserving the original bundled palette for defaults.
    */
    function getSliceColors(theme) {
        var defaultTheme = theme.primaryColor.toLowerCase() === '#8b5a2b' &&
            theme.secondaryColor.toLowerCase() === '#c28a2c' &&
            theme.accentColor.toLowerCase() === '#b8860b' &&
            theme.buttonColor.toLowerCase() === '#222222';

        if (defaultTheme) {
            return [
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
        }

        return [
            theme.primaryColor,
            theme.secondaryColor,
            theme.buttonColor,
            theme.accentColor,
            mixWithWhite(theme.primaryColor, 0.35),
            mixWithWhite(theme.secondaryColor, 0.45),
            mixWithBlack(theme.primaryColor, 0.25),
            mixWithBlack(theme.secondaryColor, 0.25),
            mixWithWhite(theme.accentColor, 0.35),
            mixWithBlack(theme.buttonColor, 0.15)
        ];
    }

    /**
    * Mix a hex color with white.
    */
    function mixWithWhite(color, weight) {
        return mixColor(color, '#ffffff', weight);
    }

    /**
    * Mix a hex color with black.
    */
    function mixWithBlack(color, weight) {
        return mixColor(color, '#000000', weight);
    }

    /**
    * Mix two hex colors using a simple weighted average.
    */
    function mixColor(color, mix, weight) {
        var base = parseHexColor(color);
        var overlay = parseHexColor(mix);

        if (!base || !overlay) {
            return color;
        }

        var red = Math.round(base.r * (1 - weight) + overlay.r * weight);
        var green = Math.round(base.g * (1 - weight) + overlay.g * weight);
        var blue = Math.round(base.b * (1 - weight) + overlay.b * weight);

        return rgbToHex(red, green, blue);
    }

    /**
    * Convert a hex color into RGB parts.
    */
    function parseHexColor(color) {
        var value = String(color || '').replace('#', '').trim();

        if (value.length === 3) {
            value = value.split('').map(function (part) {
                return part + part;
            }).join('');
        }

        if (!/^[0-9a-fA-F]{6}$/.test(value)) {
            return null;
        }

        return {
            r: parseInt(value.slice(0, 2), 16),
            g: parseInt(value.slice(2, 4), 16),
            b: parseInt(value.slice(4, 6), 16)
        };
    }

    /**
    * Convert RGB parts into a hex color.
    */
    function rgbToHex(red, green, blue) {
        return '#' + [red, green, blue].map(function (part) {
            return part.toString(16).padStart(2, '0');
        }).join('');
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
    function drawWheel(canvas, items, theme) {
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

        var colors = getSliceColors(theme || {
            primaryColor: '#8b5a2b',
            secondaryColor: '#c28a2c',
            accentColor: '#b8860b',
            buttonColor: '#222222'
        });

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

        var hasPresentation = wrapper.dataset.showPresentation !== 'false' &&
            presentationCanvas &&
            presentationResult &&
            presentationToggle &&
            presentationMode &&
            presentationModeClose &&
            presentationSpinAgainButton &&
            presentationWheelStage;

        var winnerModal = wrapper.querySelector('.srw-winner-modal');
        var winnerName = wrapper.querySelector('.srw-winner-name');
        var winnerClose = wrapper.querySelector('.srw-winner-close');
        var winnerRemoveButton = wrapper.querySelector('.srw-winner-remove');
        var winnerSpinAgainButton = wrapper.querySelector('.srw-winner-spin-again');
        var hasRemoveWinner = wrapper.dataset.showRemoveWinner !== 'false' && removeButton && winnerRemoveButton;
        var minItems = parseInt(wrapper.dataset.minItems, 10);
        var currentWinner = null;

        if (!minItems || minItems < 1) {
            minItems = 2;
        }

        var currentRotation = 0;
        var isSpinning = false;
        var items = [];
        var theme = getThemeColors(wrapper);

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

            if (removeButton) {
                removeButton.disabled = true;
            }

            canvas.style.transition = 'none';
            currentRotation = 0;
            canvas.style.transform = 'rotate(0deg)';

            drawWheel(canvas, items, theme);

            result.textContent = items.length ? 'Click the wheel to spin or click the Spin button.' : 'Add items, then spin.';
        }

        /**
        * Open presentation mode and perform a spin inside the modal view.
        */
        function openPresentationMode() {
            if (!hasPresentation || isSpinning) {
                return;
            }

            isSpinning = true;
            wrapper.classList.add('srw-is-spinning');

            presentationSpinAgainButton.hidden = true;
            presentationMode.hidden = false;

            presentationCanvas.style.transition = 'none';
            presentationCanvas.style.transform = 'rotate(0deg)';

            drawWheel(presentationCanvas, items, theme);

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

                    if (removeButton) {
                        removeButton.disabled = false;
                    }

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

            if (items.length < minItems) {
                result.textContent = 'Add at least ' + minItems + ' items before spinning.';
                drawWheel(canvas, items, theme);
                return;
            }

            if (hasPresentation && presentationToggle.checked) {
                openPresentationMode();
                return;
            }

            isSpinning = true;
            wrapper.classList.add('srw-is-spinning');

            drawWheel(canvas, items, theme);

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

                if (removeButton) {
                    removeButton.disabled = false;
                }

                openWinnerModal(currentWinner);
            }, 4100);
        });
        canvas.addEventListener('click', function () {
            spinButton.click();
        });

        if (hasPresentation) {
            presentationWheelStage.addEventListener('click', function () {
                if (presentationToggle.checked && !presentationMode.hidden && items.length >= minItems) {
                    openPresentationMode();
                }
            });
        }

        if (removeButton) {
            removeButton.addEventListener('click', function () {
                if (!currentWinner) {
                    return;
                }

                items = items.filter(function (item) {
                    return item !== currentWinner;
                });

                textarea.value = items.join('\n');

                currentWinner = null;
                removeButton.disabled = true;

                canvas.style.transition = 'none';
                currentRotation = 0;
                canvas.style.transform = 'rotate(0deg)';

                drawWheel(canvas, items, theme);

                result.textContent = items.length
                    ? 'Winner removed.'
                    : 'No items remaining.';
            });
        }

        if (hasPresentation) {
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
                if (items.length < minItems) {
                    presentationResult.textContent = 'Add at least ' + minItems + ' items before spinning.';
                    return;
                }

                openPresentationMode();
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && !presentationMode.hidden) {
                    closePresentationMode();
                }
            });
        }

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

        if (hasRemoveWinner) {
            winnerRemoveButton.addEventListener('click', function () {
                closeWinnerModal();
                removeButton.click();

                if (hasPresentation && presentationToggle.checked && !presentationMode.hidden) {
                    presentationCanvas.style.transition = 'none';
                    presentationCanvas.style.transform = 'rotate(0deg)';

                    drawWheel(presentationCanvas, items, theme);

                    presentationResult.textContent = items.length
                        ? 'Winner removed. Click the wheel to spin again.'
                        : 'No items remaining.';

                    presentationSpinAgainButton.hidden = items.length < minItems;
                }
            });
        }

        winnerSpinAgainButton.addEventListener('click', function () {
            closeWinnerModal();

            if (hasPresentation && presentationToggle.checked) {
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
            var theme = getThemeColors(wrapper);
            var items = [];

            try {
                items = JSON.parse(wrapper.dataset.items || '[]');
            } catch (error) {
                items = [];
            }

            if (!Array.isArray(items) || !canvas) {
                return;
            }

            drawWheel(canvas, items, theme);
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