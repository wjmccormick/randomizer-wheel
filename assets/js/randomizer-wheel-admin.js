(function ($) {
    $(function () {
        $('.rwp-color-field').wpColorPicker();

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
    });
})(jQuery);
