(function ($) {

    //Tooltips
    $('#vc_ui-panel-edit-element').on('vcPanel.shown', function () {
        var $tooltips = $('.basel-css-tooltip');

        $tooltips.each(function () {
            var $label = $(this).find('.basel-tooltip-label');

            $label.remove();
            $(this).addClass('basel-tltp').prepend('<span class="basel-tooltip-label">' + $(this).data('text') + '</span>');
            $label.trigger('mouseover');
        })

        .off('mouseover.tooltips')

        .on('mouseover.tooltips', function () {
            var $label = $(this).find('.basel-tooltip-label'),
                width = $label.outerWidth();

            if ($('body').hasClass('rtl')) {
                $label.css({
                    marginRight: - parseInt(width / 2)
                })
            } else {
                $label.css({
                    marginLeft: - parseInt(width / 2)
                })
            }
        });
    });

    //Hint
    $('#vc_ui-panel-edit-element').on('vcPanel.shown', function () {
        var $panel = $(this);

        $panel.find('.vc_shortcode-param').each(function () {
            var $this = $(this);
            var settings = $this.data('param_settings');

            if (typeof settings != 'undefined' && typeof settings.hint != 'undefined') {
                $this.find('.wpb_element_label').addClass('basel-with-hint').append('<div class="basel-hint">?<div class="basel-hint-content">' + settings.hint + '</div></div>');
            }
        });

        $('.basel-hint').on('hover', function () {
            var $hint = $(this);
            $hint.removeClass('basel-hint-right basel-hint-left');

            var hintPos = $hint.offset().left + $hint.find('.basel-hint-content').outerWidth();
            var panelPos = $panel.offset().left + $panel.find('.vc_edit_form_elements').width();

            if (hintPos > panelPos) {
                $hint.addClass('basel-hint-right')
            } else {
                $hint.addClass('basel-hint-left');
            }
        });

    });

})(jQuery);
