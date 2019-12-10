(function ($) {

    $('#vc_ui-panel-edit-element').on('vcPanel.shown', function () {

        //Color options
        $('.basel-vc-colorpicker').each(function () {
            var $this = $(this);
            var $colorpicker = $this.find('.basel-vc-colorpicker-input');
            var mainInputVal = $this.find('.basel-vc-colorpicker-value').val();
            var id = $this.attr('id');
            var color = '';

            $colorpicker.wpColorPicker({
                change: function (event, ui) {
                    setMainValue(id, ui.color.toString());
                    $this.trigger('change')
                },
                clear: function () {
                    $opacityRange.val(100);
                    $opacityOutput.val("100%");
                    setMainValue(id, '');
                },
            });

            if (mainInputVal && isBase64(mainInputVal)) {
                var parseVal = JSON.parse(window.atob(mainInputVal));
                color = parseVal.data.desktop;
            } else if (mainInputVal) {
                color = mainInputVal;
            }

            $colorpicker.wpColorPicker('color', color);
            setMainValue(id, color);

            //Opacity range add
            var $pickerInput = $this.find('.basel-vc-colorpicker-input');
            var opacityVal = 100;
            var value = $pickerInput.val().replace(/\s+/g, "");

            if (value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)) {
                opacityVal = 100 * parseFloat(value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)[1]);
            }

            $('<div class="basel-opacity-container"><label>Opacity: <output class="rangevalue">' + opacityVal + '%</output></label><input type="range" min="1" max="100" value="' + opacityVal + '" name="opacity" class="basel-opacity-field"></div>').appendTo($this.addClass('basel-opacity-picker').find('.iris-picker'));

            var $opacityRange = $this.find('.basel-opacity-field');
            var $opacityOutput = $this.find('.basel-opacity-container output');

            $opacityRange.on('change', function () {
                opacityVal = parseFloat($opacityRange.val());
                $opacityOutput.val($opacityRange.val() + '%');

                var iris = $pickerInput.data('a8c-iris');
                var colorPicker = $pickerInput.data('wp-wpColorPicker');

                iris._color._alpha = opacityVal / 100;
                $pickerInput.val(iris._color.toString());
                colorPicker = $pickerInput.data('wp-wpColorPicker');

                colorPicker.toggler.css({
                    backgroundColor: $pickerInput.val()
                })
                $colorpicker.wpColorPicker('color', $pickerInput.val());

            }).val(opacityVal).trigger('change');

        });

        function setMainValue(id, color) {
            var $mainInput = $('#' + id).find('.basel-vc-colorpicker-value');
            var $pickerInput = $('#' + id).find('.basel-vc-colorpicker-input');
            var results = {
                param_type: 'basel_colorpicker',
                css_args: $mainInput.data('css_args'),
                selector_id: $('.basel-css-id').val(),
                data: {
                    desktop: color
                },
            };

            if (!results.data.desktop) {
                results = '';
            } else {
                $pickerInput.val(results.data.desktop).trigger('change');
                results = window.btoa(JSON.stringify(results));
            }

            $mainInput.val(results).trigger('change');
        }

        function isBase64(str) {
            try {
                return btoa(atob(str)) == str;
            } catch (err) {
                return false;
            }
        }

    });

})(jQuery);
