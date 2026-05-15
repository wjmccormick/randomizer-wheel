(function ($) {
    $(function () {
        var colorFields = $('.rwp-color-field');
        var colorPicker = $.fn && $.fn.wpColorPicker;

        if (colorFields.length > 0 && typeof colorPicker === 'function') {
            colorFields.wpColorPicker();
        }

        $('.rwp-media-select').on('click', function (event) {
            event.preventDefault();

            var button = $(this);
            var target = $('#' + button.data('target'));
            var frame = wp.media({
                title: 'Select Randomizer Wheel Logo',
                button: {
                    text: 'Use this logo'
                },
                multiple: false
            });

            frame.on('select', function () {
                var attachment = frame.state().get('selection').first().toJSON();

                if (attachment && attachment.url) {
                    target.val(attachment.url).trigger('change');
                }
            });

            frame.open();
        });

        $('.rwp-copy-shortcode').on('click', function () {
            var button = $(this);
            var shortcode = String(button.data('shortcode') || '');
            var originalText = button.text();

            function showCopiedState() {
                button.text('Copied');

                window.setTimeout(function () {
                    button.text(originalText);
                }, 1600);
            }

            if (window.navigator && window.navigator.clipboard && window.navigator.clipboard.writeText) {
                window.navigator.clipboard.writeText(shortcode).then(showCopiedState, function () {
                    fallbackCopy(shortcode, showCopiedState);
                });
                return;
            }

            fallbackCopy(shortcode, showCopiedState);
        });

        function fallbackCopy(text, callback) {
            var textarea = $('<textarea readonly></textarea>')
                .val(text)
                .css({
                    position: 'absolute',
                    left: '-9999px'
                })
                .appendTo('body');

            textarea[0].select();
            document.execCommand('copy');
            textarea.remove();
            callback();
        }
    });
})(jQuery);
