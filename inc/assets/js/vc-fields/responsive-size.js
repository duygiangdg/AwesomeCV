(function ($) {

    var $panel = $('#vc_ui-panel-edit-element');

    $panel.on('vcPanel.shown', function () {

        //Size options
        $('.basel-rs-wrapper').each(function () {
            var $this = $(this);
            setInputsValue($this);
            setMainValue($this);
        });

        $('.basel-rs-input').on('change', function () {
            var $wrapper = $(this).parents('.basel-rs-wrapper');
            setMainValue($wrapper);
        });

        $('.basel-rs-trigger').on('click', function () {
            var $wrapper = $(this).parents('.basel-rs-wrapper');
            $wrapper.find('.basel-rs-item.tablet,.basel-rs-item.mobile').toggleClass('hide');
        });

        function setMainValue($this) {
            var $mainInput = $this.find('.basel-rs-value');
            var results = {
                param_type: 'basel_responsive_size',
                css_args: $mainInput.data('css_args'),
                selector_id: $('.basel-css-id').val(),
                data: {}
            };

            $this.find('.basel-rs-input').each(function (index, elm) {
                var value = $(elm).val();
                var responsive = $(elm).data('id');
                if (value) {
                    results.data[responsive] = value + 'px';
                }
            });

            if ($.isEmptyObject(results.data)) {
                results = '';
            } else {
                results = window.btoa(JSON.stringify(results));
            }

            $mainInput.val(results).trigger('change');
        }

        function setInputsValue($this) {
            var $mainInput = $this.find('.basel-rs-value');
            var mainInputVal = $mainInput.val();
            var toggle = {};

            if (mainInputVal) {
                var parseVal = JSON.parse(window.atob(mainInputVal));

                $.each(parseVal.data, function (key, value) {
                    $this.find('.basel-rs-input').each(function (index, element) {
                        var dataid = $(element).data('id');

                        if (dataid == key) {
                            $(element).val(value.replace('px', ''));
                            //Toggle
                            toggle[dataid] = value;
                        }
                    });
                });
            }

            //Toggle
            function size(obj) {
                var size = 0, key;
                for (key in obj) {
                    if (obj.hasOwnProperty(key)) size++;
                }
                return size;
            };

            var size = size(toggle);

            if (size >= 2) {
                $this.find('.basel-rs-item').removeClass('hide');
            }
        }

    });

})(jQuery);
