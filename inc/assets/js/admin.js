var baselAdminModule, basel_media_init;

(function($) {
    "use strict";
    // Class for import box element.
    var ImportBox = function( $form ) {

        this.form = $form;

        this.interval = 0;

        this.sequence = false;

        this.mainArea = $('.basel-main-import-area');

        this.responseArea = this.form.find('.basel-response');
        
        this.progressBar = this.form.find('.basel-import-progress');

        this.verSelect = this.form.find('.basel_version');

        this.pagePreviews();

        // Events 
        $form.on('submit', { importBox: this}, this.formSubmit )

    };

    ImportBox.prototype.formSubmit = function(e) {
        e.preventDefault();

        var importBox = e.data.importBox;

        var $form = importBox.form;

        if( $form.hasClass('form-in-action') ) return;

        $form.addClass('form-in-action');

        var version = $form.find('.basel_version').val();

        if( $form.find('#full_import').prop('checked') == true ) {
            importBox.sequence = true;
            version = $form.find('.basel_versions').val();

            var subLenght = 3;

            var ajaxSuccess = function( response ) {

                if( ! versions[i] ) {
                    // importBox.handleResponse(response);
                    // importBox.responseArea.html( '' ).fadeIn();
                }
            };

            var ajaxComplete = function() {

                if( ! versions[i] ) {

                    importBox.form.removeClass('form-in-action');

                    importBox.updateProgress( importBox.progressBar, 100, 0 );

                    importBox.initialClearer = setTimeout(function() {
                        importBox.destroyProgressBar(200);
                    }, 2000 );

                    importBox.mainArea.addClass( "imported-full right-after-import imported-" +  versions.join(" imported-") );
                    importBox.mainArea.find('.full-import-box').remove();
                } else {
                    importBox.updateProgress( importBox.progressBar, progressSteps * i, 350 );
                    importBox.callImportAJAX( versions.slice(i, i + subLenght).join(','), ajaxSuccess, ajaxComplete );
                    i = i + subLenght;
                }
            };

            var versions = version.split(',');
            var i = 0;
            var progressSteps = 95 / versions.length;

            importBox.callImportAJAX( versions[i++], ajaxSuccess, ajaxComplete );

            importBox.updateProgress( importBox.progressBar, progressSteps, 350 );

            return;
        }

        clearInterval( importBox.initialClearer );

        importBox.fakeLoading( 30, 50, 70 );

        importBox.clearResponseArea();

        importBox.callImportAJAX( version, function(response) {

            importBox.clearResponseArea();
            importBox.handleResponse(response);

        }, function() {

            importBox.clearFakeLoading();

            importBox.form.removeClass('form-in-action');

            importBox.updateProgress( importBox.progressBar, 100, 0 );

            importBox.progressBar.parent().find('.basel-notice').remove();

            importBox.mainArea.addClass( "right-after-import imported-" +  version );
            // importBox.mainArea.removeClass( "imported-full");

            importBox.initialClearer = setTimeout(function() {
                importBox.destroyProgressBar(200);
            }, 2000 );
        } );
    };


    ImportBox.prototype.callImportAJAX = function( version, success, complete ) {
        var box = this;

        $.ajax({
            url: baselConfig.ajax,
            data: {
                basel_version: version,
                action: "basel_import_data",
				sequence: box.sequence,
				security: baselConfig.import_nonce,
            },
            timeout: 1000000,
            success: function( response ) {

                if( success ) success( response );

            },
            error: function( response ) {
                box.responseArea.html( '<div class="basel-warning">Import AJAX problem. Please, try import data manually.</div>' ).fadeIn();
                console.log('import ajax ERROR');
            },
            complete: function() {

                if( complete ) complete();

                //console.log('import ajax complete');
            },
        });
    };

    ImportBox.prototype.handleResponse = function( response ) {
        var rJSON = { status: '', message: '' };

        try {
            rJSON = JSON.parse(response);
        } catch( e ) {}           

        if( ! response ) {
            this.responseArea.html( '<div class="basel-warning">Empty AJAX response, please try again.</div>' ).fadeIn();
        } else if( rJSON.status == 'success' ) {
            console.log(rJSON.message);
            this.responseArea.html( '<div class="basel-success">All data imported successfully!</div>' ).fadeIn();
        } else if( rJSON.status == 'fail' ) {
            this.responseArea.html( '<div class="basel-error">' + rJSON.message + '</div>' ).fadeIn();
        } else {
            this.responseArea.html( '<div class="">' + response + '</div>' ).fadeIn();
        }

    };


    ImportBox.prototype.fakeLoading = function(fake1progress, fake2progress, noticeProgress) {
        var that = this;
        
        this.destroyProgressBar(0);

        this.updateProgress( this.progressBar, fake1progress, 350 );

        this.fake2timeout = setTimeout( function() {
            that.updateProgress( that.progressBar, fake2progress, 100 );
        }, 25000 );

        this.noticeTimeout = setTimeout( function() {
            that.updateProgress( that.progressBar, noticeProgress, 100 );
            that.progressBar.after( '<p class="basel-notice small">Please, wait. Theme needs much time to download all attachments</p>' );
        }, 60000 );

        this.errorTimeout = setTimeout( function() {
            that.progressBar.parent().find('.basel-notice').remove();
            that.progressBar.after( '<p class="basel-notice small">Something wrong with import. Please, try to import data manually</p>' );
        }, 3100000 );
    };

    ImportBox.prototype.clearFakeLoading = function() {
        clearTimeout( this.fake2timeout );
        clearTimeout( this.noticeTimeout );                          
        clearTimeout( this.errorTimeout );
    };

    ImportBox.prototype.destroyProgressBar = function( hide ) {
        this.progressBar.hide( hide ).attr('data-progress', 0).find('div').width(0);
    };

    ImportBox.prototype.clearResponseArea = function() {
        this.responseArea.fadeOut(200, function() {
            $(this).html( '' );
        });
    };

    ImportBox.prototype.updateProgress = function( el, to, interval ) {
        el.show();
        var box = this;

        clearInterval( box.interval );

        var from = el.attr('data-progress'),
            i = from;

        if( interval == 0 ) {
            el.attr('data-progress', 100).find('div').width(el.attr('data-progress') + '%');
        } else {
            box.interval = setInterval(function() {
                i++;
                el.attr('data-progress', i).find('div').width(el.attr('data-progress') + '%');
                if( i >= to ) clearInterval( box.interval );
            }, interval);
        }

    };

    ImportBox.prototype.pagePreviews = function() {
        var preview = this.form.find('.page-preview'),
            image = preview.find('img'),
            dir = image.data('dir'),
            newImage = '';

        image.on('load', function() {
          // do stuff on success
            $(this).removeClass('loading-image');
        }).on('error', function() {
          // do stuff on smth wrong (error 404, etc.)
            $(this).removeClass('loading-image');
        }).each(function() {
            if(this.complete) {
              $(this).load();
            } else if(this.error) {
              $(this).error();
            }
        });

        this.verSelect.on('change', function() {
            var page = $(this).val();

            if( page == '' || page == '--select--' ) page = 'base';

            newImage = dir + '/' + page + '/preview.jpg';

            image.addClass('loading-image').attr('src', newImage);
        });
    };


    $.fn.import_box = function() {
        new ImportBox( this );
        return this;
    };

    baselAdminModule = (function() {

        var baselAdmin = {

            listElement : function(){
                var $editor = $( '#vc_ui-panel-edit-element' );

                $editor.on( 'vcPanel.shown', function() {
                    if( $editor.attr( 'data-vc-shortcode' ) != 'basel_list' ) return;

                    var $groupField = $editor.find( '[data-param_type="param_group"]' ),
                        $groupFieldOpenBtn = $groupField.find( '.column_toggle:first' );

                    setTimeout( function() {
                        $groupFieldOpenBtn.click();
                    }, 300 );
                } );
            },
            
            sizeGuideInit : function(){
                if ( $.fn.editTable ) {
                    $( '.basel-sguide-table-edit' ).each( function() {
                        $( this ).editTable(); 
                    } );
                }
            },

            importAction: function() {

                $('.basel-import-form').each(function() {
                    $(this).import_box();
                })
            },

            attributesMetaboxes: function() {

                if( ! $('body').hasClass('product_page_product_attributes') ) return;

                var orderByRow = $('#attribute_orderby').parent(),
                    orderByTableRow = $('#attribute_orderby').parents('tr'),
                    //Select swatch size
                    selectedSize = ( baselConfig.attributeSwatchSize != undefined && baselConfig.attributeSwatchSize.length > 1 ) ? baselConfig.attributeSwatchSize : '',
                    labelSelectSize = '<label for="attribute_swatch_size">Attributes swatch size</label>',
                    descriptionSelectSize = '<p class="description">If you will set color or images swatches for terms of this attribute.</p>',
                    selectSize = [
                        '<select name="attribute_swatch_size" id="attribute_swatch_size">',
                            '<option value="default"' + (( selectedSize == 'default' ) ?  ' selected="selected"' : '') + '>Default</option>',
                            '<option value="large"' + (( selectedSize == 'large' ) ?  ' selected="selected"' : '') + '>Large</option>',
                            '<option value="xlarge"' + (( selectedSize == 'xlarge' ) ?  ' selected="selected"' : '') + '>Extra large</option>',
                        '</select>',
                    ].join(''),
                    //Checkbox show attribute on product
                    showOnProduct = ( baselConfig.attributeShowOnProduct != undefined && baselConfig.attributeShowOnProduct.length > 1 ) ? baselConfig.attributeShowOnProduct : '',
                    labelShowAttr = '<label for="attribute_show_on_product">Show attribute label on products</label>',
                    checkboxShowAttr = '<input' + ( ( showOnProduct == 'on' ) ?  ' checked="checked"' : '' ) + ' name="attribute_show_on_product" id="attribute_show_on_product" type="checkbox">',
                    descriptionShowAttr = '<p class="description">Enable this if you want to show this attribute label on products in your store.</p>',
                    metaHTMLTable = [
                        //Select swatch size
                        '<tr class="form-field form-required">',
                            '<th scope="row" valign="top">',
                                labelSelectSize,
                            '</th>',
                            '<td>',
                                selectSize,
                                descriptionSelectSize,
                            '</td>',
                        '</tr>',

                        //Checkbox show attribute on product
                        '<tr class="form-field form-required">',
                            '<th scope="row" valign="top">',
                                labelShowAttr,
                            '</th>',
                            '<td>',
                                checkboxShowAttr,
                                descriptionShowAttr,
                            '</td>',
                        '</tr>',
                    ].join(''),

                    metaHTMLParagraph = [
                        //Select swatch size
                        '<div class="form-field">',
                            labelSelectSize,
                            selectSize,
                            descriptionSelectSize,
                        '</div>',

                        //Checkbox show attribute on product
                        '<div class="form-field">',
                            labelShowAttr,
                            checkboxShowAttr,
                            descriptionShowAttr,
                        '</div>',
                    ].join('');

                if( orderByTableRow.length > 0 ) {
                    orderByTableRow.after( metaHTMLTable );
                } else {
                    orderByRow.after( metaHTMLParagraph );
                }
            },

            product360ViewGallery: function() {

                // Product gallery file uploads.
                var product_gallery_frame;
                var $image_gallery_ids = $( '#product_360_image_gallery' );
                var $product_images    = $( '#product_360_images_container' ).find( 'ul.product_360_images' );

                $( '.add_product_360_images' ).on( 'click', 'a', function( event ) {
                    var $el = $( this );

                    event.preventDefault();

                    // If the media frame already exists, reopen it.
                    if ( product_gallery_frame ) {
                        product_gallery_frame.open();
                        return;
                    }

                    // Create the media frame.
                    product_gallery_frame = wp.media.frames.product_gallery = wp.media({
                        // Set the title of the modal.
                        title: $el.data( 'choose' ),
                        button: {
                            text: $el.data( 'update' )
                        },
                        states: [
                            new wp.media.controller.Library({
                                title: $el.data( 'choose' ),
                                filterable: 'all',
                                multiple: true
                            })
                        ]
                    });

                    // When an image is selected, run a callback.
                    product_gallery_frame.on( 'select', function() {
                        var selection = product_gallery_frame.state().get( 'selection' );
                        var attachment_ids = $image_gallery_ids.val();

                        selection.map( function( attachment ) {
                            attachment = attachment.toJSON();

                            if ( attachment.id ) {
                                attachment_ids   = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
                                var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

                                $product_images.append( '<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '" /><ul class="actions"><li><a href="#" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></li></ul></li>' );
                            }
                        });

                        $image_gallery_ids.val( attachment_ids );
                    });

                    // Finally, open the modal.
                    product_gallery_frame.open();
                });

				// Image ordering.
				if (typeof $product_images.sortable !== 'undefined') {
					$product_images.sortable({
						items: 'li.image',
						cursor: 'move',
						scrollSensitivity: 40,
						forcePlaceholderSize: true,
						forceHelperSize: false,
						helper: 'clone',
						opacity: 0.65,
						placeholder: 'wc-metabox-sortable-placeholder',
						start: function( event, ui ) {
							ui.item.css( 'background-color', '#f6f6f6' );
						},
						stop: function( event, ui ) {
							ui.item.removeAttr( 'style' );
						},
						update: function() {
							var attachment_ids = '';

							$( '#product_360_images_container' ).find( 'ul li.image' ).css( 'cursor', 'default' ).each( function() {
								var attachment_id = $( this ).attr( 'data-attachment_id' );
								attachment_ids = attachment_ids + attachment_id + ',';
							});

							$image_gallery_ids.val( attachment_ids );
						}
					});
				}

                // Remove images.
                $( '#product_360_images_container' ).on( 'click', 'a.delete', function() {
                    $( this ).closest( 'li.image' ).remove();

                    var attachment_ids = '';

                    $( '#product_360_images_container' ).find( 'ul li.image' ).css( 'cursor', 'default' ).each( function() {
                        var attachment_id = $( this ).attr( 'data-attachment_id' );
                        attachment_ids = attachment_ids + attachment_id + ',';
                    });

                    $image_gallery_ids.val( attachment_ids );

                    // Remove any lingering tooltips.
                    $( '#tiptip_holder' ).removeAttr( 'style' );
                    $( '#tiptip_arrow' ).removeAttr( 'style' );

                    return false;
                });
            },
            
            settingsSearch: function() {

                var $reduxHeader = $('#redux-header');

                if( $reduxHeader.length == 0 ) return;

                var $searchForm = $('<div class="xtemos-settings-search"><form><input id="xtemos-settings-search-input" placeholder="' +  baselConfig.searchOptionsPlaceholder + '" type="text" /></form></div>'),
                    $searchInput = $searchForm.find('input');

                $reduxHeader.find('.display_header').after($searchForm);

                $searchForm.find('form').submit(function(e) {
                    e.preventDefault();
                });

                var $autocomplete = $searchInput.autocomplete({
                    source: function( request, response ) {
                        response( baselConfig.reduxOptions.filter(function( value ) {
                            return value.text.search(new RegExp(request.term, "i")) != -1
                        }) );
                    },

                    select: function( event, ui ) {
                        var $field = $('[data-id="' + ui.item.id+ '"]');

                        $('#' + ui.item.section_id + '_section_group_li_a').click();

                        $('.highlight-field').removeClass('highlight-field');
                        $field.parent().parent().find('.redux_field_th').addClass('highlight-field');

                        setTimeout(function() {
                            if( ! isInViewport( $field ) ) {
                                $('html, body').animate({
                                    scrollTop: $field.offset().top - 200
                                }, 400);
                            }
                        }, 300);
                    }

                }).data( "ui-autocomplete" );

                $autocomplete._renderItem = function( ul, item ) {
                    var $itemContent = '<i class="el ' + item.icon + '"></i><span class="setting-title">' + item.title + '</span><br><span class="settting-path">' + item.path + '</span>'
                    return $( "<li>" )
                        .append( $itemContent )
                        .appendTo( ul );
                };

                $autocomplete._renderMenu = function( ul, items ) {
                    var that = this;

                    $.each( items, function( index, item ) {
                        that._renderItemData( ul, item );
                    });

                    $( ul ).addClass( "xtemos-settings-result" );
                };

                var isInViewport = function( $el ) {
                    var elementTop = $el.offset().top;
                    var elementBottom = elementTop + $el.outerHeight();
                    var viewportTop = $(window).scrollTop();
                    var viewportBottom = viewportTop + $(window).height();
                    return elementBottom > viewportTop && elementTop < viewportBottom;
                };
            },

            multiFonts: function () {
                $('.redux-container-basel_multi_fonts').each(function () {
                    var el = $(this);

                    redux.field_objects.media.init();

                    el.find('.basel-multi-fonts-remove').live('click', function () {
                        $(this).parents('.basel-miltifont-repeater').slideUp('medium', function () {
                            $(this).remove();
                        });
                        redux.field_objects.media.init();
                    });

                    el.find('.basel-multi-fonts-add').click(function () {
                        var _this = $(this);
                        var id = _this.attr('data-id');
                        var order = parseInt(_this.attr('data-order')) + 1;
                        var name = _this.attr('data-name');
                        var mediaId = '_' + Math.random().toString(36).substr(2, 9);
                        var i = 0;
                        var fields = [
                            '[font-name]',
                            '[font-woff]',
                            '[font-woff2]',
                            '[font-ttf]',
                            '[font-svg]',
                            '[font-eot]',
                        ];
                        var cloneStandart = $('#' + id + ' .basel-miltifont-repeater:last-child');
                        el.find('#' + id).append(cloneStandart.clone());

                        //Сleaning clone element after clone
                        var lastElement = el.find('#' + id + ' .basel-miltifont-repeater:last-child');
                        lastElement.removeAttr('style');
                        lastElement.find('select').attr('name', name + '[' + order + '][font-weight]');
                        lastElement.find('input[type="text"]').each(function () {
                            var url = (fields[i] != '[font-name]') ? '[url]' : '';
                            $(this).parent().attr('id', mediaId);
                            $(this).attr('name', name + '[' + order + ']' + fields[i] + url);
                            $(this).siblings('.upload-id').attr('name', name + '[' + order + ']' + fields[i] + '[id]');
                            $(this).val('');
                            i++;
                        });

                        //Accordion after clone
                        lastElement.siblings().removeClass('active');
                        lastElement.addClass('active');

                        _this.attr('data-order', order);
                        redux.field_objects.media.init();
                    });
                });
            },

            multiFontsAccordion: function () {
                $(document).on('click', '.basel-miltifonts-accordion', function () {
                    var $parent = $(this).parent();
                    $parent.toggleClass('active');
                    $parent.siblings().removeClass('active');
                });
            },

            cmb2ImagesSelect: function () {
                $('.basel-images-select-field').each(function () {
                    var $field = $(this),
                        $input = $(this).find('.basel-image-select-input');

                    $field.on('click', '.basel-image-opt', function () {
                        var $opt = $(this);
                        var val = $opt.data('val');

                        $opt.siblings('.active').removeClass('active');
                        $opt.addClass('active');

                        $input.val(val);

                    });
                });
            },

            cmb2SliderField: function () {

                // Init slider at start
                $('.cmb-type-basel-slider').each(function () {

                    initRow($(this));

                });


                // When a group row is shifted, reinitialise slider value
                $('.cmb-repeatable-group').on('cmb2_shift_rows_complete', function (event, instance) {

                    var shiftedGroup = $(instance).closest('.cmb-repeatable-group');

                    shiftedGroup.find('.cmb-type-basel-slider').each(function () {

                        $(this).find('.basel-slider-field').slider('value', $(this).find('.basel-slider-field-value').val());
                        $(this).find('.basel-slider-field-value-text').text($(this).find('.basel-slider-field-value').val());

                    });

                    return false;
                });


                // When a group row is added, reset slider
                $('.cmb-repeatable-group').on('cmb2_add_row', function (event, newRow) {

                    $(newRow).find('.cmb-type-basel-slider').each(function () {

                        initRow($(this));

                        $(this).find('.ui-slider-range').css('width', 0);
                        $(this).find('.basel-slider-field').slider('value', 0);
                        $(this).find('.basel-slider-field-value-text').text('0');
                    });

                    return false;
                });


                // Init slider  
                function initRow(row) {

                    // Loop through all cmb-type-slider-field instances and instantiate the slider UI
                    row.each(function () {
                        var $this = $(this);
                        var $value = $this.find('.basel-slider-field-value');
                        var $slider = $this.find('.basel-slider-field');
                        var $text = $this.find('.basel-slider-field-value-text');
                        var slider_data = $value.data();

                        $slider.slider({
                            range: 'min',
                            value: slider_data.start,
                            min: slider_data.min,
                            max: slider_data.max,
                            step: slider_data.step,
                            slide: function (event, ui) {
                                $value.val(ui.value);
                                $text.text(ui.value);
                            }
                        });

                        // Initiate the display
                        $value.val($slider.slider('value'));
                        $text.text($slider.slider('value'));
                    });
                }

            },

            advancedTypography: function () {
                var isSelecting = false,
                    selVals = [],
                    select2Defaults = {
                        width: '100%',
                        triggerChange: true,
                        allowClear: true
                    },
                    defaultVariants = {
                        '100': 'Thin 100',
                        '200': 'Light 200',
                        '300': 'Regular 300',
                        '400': 'Medium 400',
                        '500': 'Normal 500',
                        '600': 'Normal 600',
                        '700': 'Bold 700',
                        '800': 'Black 800',
                        '900': 'Black 900',
                        '100italic': 'Thin 100 Italic',
                        '200italic': 'Light 200 Italic',
                        '300italic': 'Regular 300 Italic',
                        '400italic': 'Medium 400 Italic',
                        '500italic': 'Normal 500 Italic',
                        '600italic': 'Normal 600 Italic',
                        '700italic': 'Bold 700 Italic',
                        '800italic': 'Black 800 Italic',
                        '900italic': 'Black 900 Italic',
                    };

                $('.basel-advanced-typography-field').each(function () {
                    var $parent = $(this);

                    $parent.find('.basel-atf-section:not(.basel-atf-template)').each(function () {
                        var $section = $(this),
                            id = $section.data('id');

                        initTypographySection($parent, id);
                    });

                    $parent.on('click', '.basel-atf-btn-add', function (e) {
                        e.preventDefault();

                        var $template = $parent.find('.basel-atf-template').clone(),
                            key = $parent.data('key') + 1;

                        $parent.find('.basel-atf-sections').append($template);
                        var regex = /{{index}}/gi;

                        $template
                            .removeClass('basel-atf-template hide')
                            .html($template.html().replace(regex, key))
                            .attr('data-id', $template.attr('data-id').replace(regex, key));

                        $parent.data('key', key);

                        initTypographySection($parent, $template.attr('data-id'));
                    });

                    $parent.on('click', '.basel-atf-btn-remove', function (e) {
                        e.preventDefault();

                        $(this).parent().remove();
                    });
                });



                function initTypographySection($parent, id) {
                    var $section = $parent.find('[data-id="' + id + '"]'),
                        $family = $section.find('.basel-atf-family'),
                        $familyInput = $section.find('.basel-atf-family-input'),
                        $googleInput = $section.find('.basel-atf-google-input'),
                        $customInput = $section.find('.basel-atf-custom-input'),
                        $customSelector = $section.find('.basel-atf-custom-selector'),
                        $selector = $section.find('.basel-atf-selector'),
                        $color = $section.find('.basel-atf-color'),
                        $colorHover = $section.find('.basel-atf-color-hover'),
                        $responsiveControls = $section.find('.basel-atf-responsive-controls');

                    if ($family.data('value') !== "") {
                        $family.val($family.data('value'));
                    }

                    syncronizeFontVariants($section, true, false);

                    //init when value is changed
                    $section.find('.basel-atf-family, .basel-atf-style, .basel-atf-subset').on(
                        'change', function () {
                            syncronizeFontVariants($section, false, false);
                        }
                    );

                    var data = [{ id: 'none', text: 'none' }];

                    $family.select2(
                        {
                            matcher: function (term, text) {
                                return text.toUpperCase().indexOf(term.toUpperCase()) === 0;
                            },

                            query: function (query) {
                                return window.Select2.query.local(data)(query);
                            },

                            initSelection: function (element, callback) {
                                var data = { id: element.val(), text: element.val() };
                                callback(data);
                            },
                            allowClear: true,
                            // when one clicks on the font-family select box
                        }
                    ).on(
                        "select2-opening", function (e) {

                            // Google font isn use?
                            var usingGoogleFonts = true;

                            // Set up data array
                            var buildData = [];

                            // If custom fonts, push onto array
                            if (redux.customfonts !== undefined) {
                                buildData.push(redux.customfonts);
                            }

                            // If standard fonts, push onto array
                            if (redux.stdfonts !== undefined) {
                                buildData.push(redux.stdfonts);
                            }

                            // If googfonts on and had data, push into array
                            if (usingGoogleFonts == 1 || usingGoogleFonts === true && redux.googlefonts !== undefined) {
                                buildData.push(redux.googlefonts);
                            }

                            // output data to drop down
                            data = buildData;

                            // get placeholder
                            var selFamily = $familyInput.attr('value');
                            if (!selFamily) {
                                selFamily = null;
                            }

                            // select current font
                            $family.select2('val', selFamily);

                        }
                    ).on(
                        'select2-selecting', function (val, object) {
                            var fontName = val.object.text;

                            $familyInput.attr('value', fontName);

                            // option values
                            selVals = val;
                            isSelecting = true;

                            syncronizeFontVariants($section, false, true);
                        }
                    ).on(
                        'select2-clearing', function (val, choice) {
                            $family
                                .attr('data-value', '')
                                .attr('placeholder', 'Font Family');

                            $familyInput.val('');

                            $googleInput.val('false');

                            syncronizeFontVariants($section, false, true);
                        }
                    );

                    // CSS selector multi select field
                    $selector.select2(select2Defaults)
                        .on(
                            'select2-selecting', function (e) {
                                if (e.val != 'custom') return;
                                $customInput.val(true);
                                $customSelector.removeClass('hide');

                            }
                        ).on(
                            'select2-removing', function (e) {
                                if (e.val != 'custom') return;
                                $customInput.val('');
                                $customSelector.val('').addClass('hide');
                            }
                        );

                    // Color picker fields
                    $color.wpColorPicker({
                        change: function (event, ui) {
                            // needed for palette click
                            setTimeout(function () {
                                updatePreview($section);
                            }, 5)
                        }
                    });
                    $colorHover.wpColorPicker();

                    // Responsive font size and line height
                    $responsiveControls.on('click', '.basel-atf-responsive-opener', function () {
                        var $this = $(this);
                        $this.parent().find('.basel-atf-control-tablet, .basel-atf-control-mobile').toggleClass('show hide');
                    })
                        .on('change', 'input', function () {
                            updatePreview($section);
                        });
                }

                function updatePreview($section) {
                    var sectionFields = {
                        familyInput: $section.find('.basel-atf-family-input'),
                        weightInput: $section.find('.basel-atf-weight-input'),
                        preview: $section.find('.basel-atf-preview'),
                        sizeInput: $section.find('.basel-atf-size-container .basel-atf-control-desktop input'),
                        heightInput: $section.find('.basel-atf-height-container .basel-atf-control-desktop input'),
                        colorInput: $section.find('.basel-atf-color')
                    }

                    var size = sectionFields.sizeInput.val(),
                        height = sectionFields.heightInput.val(),
                        weight = sectionFields.weightInput.val(),
                        color = sectionFields.colorInput.val(),
                        family = sectionFields.familyInput.val();


                    if (!height) {
                        height = size;
                    }

                    //show in the preview box the font
                    sectionFields.preview
                        .css('font-weight', weight)
                        .css('font-family', family + ', sans-serif')
                        .css('font-size', size + 'px')
                        .css('line-height', height + 'px');

                    if (family === 'none' && family === '') {
                        //if selected is not a font remove style "font-family" at preview box
                        sectionFields.preview.css('font-family', 'inherit');
                    }

                    if (color) {
                        sectionFields.preview
                            .css('color', color)
                            .css('background-color', redux.field_objects.typography.contrastColour(color));
                    }


                    sectionFields.preview.slideDown();
                }

                function loadGoogleFont(family, style, script) {

                    if (family == null || family == "inherit") return;

                    //add reference to google font family
                    //replace spaces with "+" sign
                    var link = family.replace(/\s+/g, '+');

                    if (style && style !== "") {
                        link += ':' + style.replace(/\-/g, " ");
                    }

                    if (script && script !== "") {
                        link += '&subset=' + script;
                    }

                    if (typeof (WebFont) !== "undefined" && WebFont) {
                        WebFont.load({ google: { families: [link] } });
                    }
                }

                function syncronizeFontVariants($section, init, changeFamily) {
                    var data = [];

                    var sectionFields = {
                        family: $section.find('.basel-atf-family'),
                        familyInput: $section.find('.basel-atf-family-input'),
                        style: $section.find('select.basel-atf-style'),
                        styleInput: $section.find('.basel-atf-style-input'),
                        weightInput: $section.find('.basel-atf-weight-input'),
                        subsetInput: $section.find('.basel-atf-subset-input'),
                        subset: $section.find('select.basel-atf-subset'),
                        googleInput: $section.find('.basel-atf-google-input'),
                        preview: $section.find('.basel-atf-preview'),
                        sizeInput: $section.find('.basel-atf-size-container .basel-atf-control-desktop input'),
                        heightInput: $section.find('.basel-atf-height-container .basel-atf-control-desktop input'),
                        colorInput: $section.find('.basel-atf-color')
                    }

                    // Set all the variables to be checked against
                    var family = sectionFields.familyInput.val();

                    if (!family) {
                        family = null; //"inherit";
                    }

                    var style = sectionFields.style.val();
                    var script = sectionFields.subset.val();


                    // Is selected font a google font?
                    var google;
                    if (isSelecting === true) {
                        google = redux.field_objects.typography.makeBool(selVals.object['data-google']);
                        sectionFields.googleInput.val(google);
                    } else {
                        google = redux.field_objects.typography.makeBool(
                            sectionFields.googleInput.val()
                        ); // Check if font is a google font
                    }

                    // Page load. Speeds things up memory wise to offload to client
                    if (init) {
                        style = sectionFields.style.data('value');
                        script = sectionFields.subset.data('value');

                        if (style !== "") {
                            style = String(style);
                        }

                        if (typeof (script) !== undefined) {
                            script = String(script);
                        }
                    }

                    // Something went wrong trying to read google fonts, so turn google off
                    if (redux.fonts.google === undefined) {
                        google = false;
                    }

                    // Get font details
                    var details = '';
                    if (google === true && (family in redux.fonts.google)) {
                        details = redux.fonts.google[family];
                    } else {
                        details = defaultVariants;
                    }

                    sectionFields.subsetInput.val(script);

                    // If we changed the font. Selecting variable is set to true only when family field is opened
                    if (isSelecting || init || changeFamily) {
                        var html = '<option value=""></option>';

                        // Google specific stuff
                        if (google === true) {

                            // STYLES
                            var selected = "";
                            $.each(
                                details.variants, function (index, variant) {
                                    if (variant.id === style || redux.field_objects.typography.size(details.variants) === 1) {
                                        selected = ' selected="selected"';
                                        style = variant.id;
                                    } else {
                                        selected = "";
                                    }

                                    html += '<option value="' + variant.id + '"' + selected + '>' + variant.name.replace(
                                        /\+/g, " "
                                    ) + '</option>';
                                }
                            );


                            // destroy select2
                            sectionFields.style.select2("destroy");

                            // Instert new HTML
                            sectionFields.style.html(html);

                            // Init select2
                            sectionFields.style.select2(select2Defaults);

                            // SUBSETS
                            selected = "";
                            html = '<option value=""></option>';

                            $.each(
                                details.subsets, function (index, subset) {
                                    if (subset.id === script || redux.field_objects.typography.size(details.subsets) === 1) {
                                        selected = ' selected="selected"';
                                        script = subset.id;
                                        sectionFields.subset.val(script);
                                    } else {
                                        selected = "";
                                    }
                                    html += '<option value="' + subset.id + '"' + selected + '>' + subset.name.replace(
                                        /\+/g, " "
                                    ) + '</option>';
                                }
                            );

                            // Destroy select2
                            sectionFields.subset.select2("destroy");

                            // Inset new HTML
                            sectionFields.subset.html(html);

                            // Init select2
                            sectionFields.subset.select2(select2Defaults);

                            sectionFields.subset.parent().fadeIn('fast');
                            // $( '#' + mainID + ' .typography-family-backup' ).fadeIn( 'fast' );
                        } else {
                            if (details) {
                                $.each(
                                    details, function (index, value) {
                                        if (index === style || index === "normal") {
                                            selected = ' selected="selected"';
                                            sectionFields.style.find('.select2-chosen').text(value);
                                        } else {
                                            selected = "";
                                        }

                                        html += '<option value="' + index + '"' + selected + '>' + value.replace(
                                            '+', ' '
                                        ) + '</option>';
                                    }
                                );

                                // Destory select2
                                sectionFields.style.select2("destroy");

                                // Insert new HTML
                                sectionFields.style.html(html);

                                // Init select2
                                sectionFields.style.select2(select2Defaults);

                                // Prettify things
                                sectionFields.subset.parent().fadeOut('fast');
                            }
                        }

                        sectionFields.familyInput.val(family);
                    }

                    // Check if the selected value exists. If not, empty it. Else, apply it.
                    if (sectionFields.style.find("option[value='" + style + "']").length === 0) {
                        style = "";
                        sectionFields.style.select2('val', '');
                    } else if (style === "400") {
                        sectionFields.style.select2('val', style);
                    }

                    // Weight and italic
                    if (style.indexOf("italic") !== -1) {
                        sectionFields.preview.css('font-style', 'italic');
                        sectionFields.styleInput.val('italic');
                        style = style.replace('italic', '');
                    } else {
                        sectionFields.preview.css('font-style', "normal");
                        sectionFields.styleInput.val('');
                    }

                    sectionFields.weightInput.val(style);

                    // Handle empty subset select
                    if (sectionFields.subset.find("option[value='" + script + "']").length === 0) {
                        script = "";
                        sectionFields.subset.select2('val', '');
                        sectionFields.subsetInput.val(script);
                    }

                    if (google) loadGoogleFont(family, style, script);

                    if (!init) updatePreview($section);

                    isSelecting = false;
                }
			},

			variationGallery: function () {

				$('#woocommerce-product-data').on('woocommerce_variations_loaded', function () {

					$('.basel-variation-gallery-wrapper').each(function () {

						var $this = $(this);
						var $galleryImages = $this.find('.basel-variation-gallery-images');
						var $imageGalleryIds = $this.find('.variation-gallery-ids');
						var galleryFrame;

						$this.find('.basel-add-variation-gallery-image').on('click', function (event) {
							event.preventDefault();

							// If the media frame already exists, reopen it.
							if (galleryFrame) {
								galleryFrame.open();
								return;
							}

							// Create the media frame.
							galleryFrame = wp.media.frames.product_gallery = wp.media({
								states: [
									new wp.media.controller.Library({
										filterable: 'all',
										multiple: true
									})
								]
							});

							// When an image is selected, run a callback.
							galleryFrame.on('select', function () {
								var selection = galleryFrame.state().get('selection');
								var attachment_ids = $imageGalleryIds.val();

								selection.map(function (attachment) {
									attachment = attachment.toJSON();

									if (attachment.id) {
										var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
										attachment_ids = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;

										$galleryImages.append('<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '"><a href="#" class="delete basel-remove-variation-gallery-image"><span class="dashicons dashicons-dismiss"></span></a></li>');

										$this.trigger('basel_variation_gallery_image_added');
									}
								});

								$imageGalleryIds.val(attachment_ids);

								$this.parents('.woocommerce_variation').eq(0).addClass('variation-needs-update');
								$('#variable_product_options').find('input').eq(0).change();

							});

							// Finally, open the modal.
							galleryFrame.open();
						});

						// Image ordering.
						if (typeof $galleryImages.sortable !== 'undefined') {
							$galleryImages.sortable({
								items: 'li.image',
								cursor: 'move',
								scrollSensitivity: 40,
								forcePlaceholderSize: true,
								forceHelperSize: false,
								helper: 'clone',
								opacity: 0.65,
								placeholder: 'wc-metabox-sortable-placeholder',
								start: function (event, ui) {
									ui.item.css('background-color', '#f6f6f6');
								},
								stop: function (event, ui) {
									ui.item.removeAttr('style');
								},
								update: function () {
									var attachment_ids = '';

									$galleryImages.find('li.image').each(function () {
										var attachment_id = $(this).attr('data-attachment_id');
										attachment_ids = attachment_ids + attachment_id + ',';
									});

									$imageGalleryIds.val(attachment_ids);

									$this.parents('.woocommerce_variation').eq(0).addClass('variation-needs-update');
									$('#variable_product_options').find('input').eq(0).change();
								}
							});
						}

						// Remove images.
						$(document).on('click', '.basel-remove-variation-gallery-image', function (event) {
							event.preventDefault();
							$(this).parent().remove();

							var attachment_ids = '';

							$galleryImages.find('li.image').each(function () {
								var attachment_id = $(this).attr('data-attachment_id');
								attachment_ids = attachment_ids + attachment_id + ',';
							});

							$imageGalleryIds.val(attachment_ids);

							$this.parents('.woocommerce_variation').eq(0).addClass('variation-needs-update');
							$('#variable_product_options').find('input').eq(0).change();
						});

					});

				});
			},
        };
        
        return {
            init: function() {

                baselAdmin.importAction();

                $(document).ready(function() {
                    baselAdmin.listElement();
                    baselAdmin.sizeGuideInit();
                    baselAdmin.attributesMetaboxes();
                    baselAdmin.product360ViewGallery();
					baselAdmin.settingsSearch();
					baselAdmin.variationGallery();


                    baselAdmin.cmb2ImagesSelect();
                    baselAdmin.cmb2SliderField();

                    //Redux fields
                    baselAdmin.multiFonts();
                    baselAdmin.multiFontsAccordion();
                    baselAdmin.advancedTypography();
                });

            },

            mediaInit: function(selector, button_selector, image_selector)  {
                var clicked_button = false;
                $(selector).each(function (i, input) {
                    var button = $(input).next(button_selector);
                    button.click(function (event) {
                        event.preventDefault();
                        var selected_img;
                        clicked_button = $(this);
             
                        // // check for media manager instance
                        // if(wp.media.frames.gk_frame) {
                        //     wp.media.frames.gk_frame.open();
                        //     return;
                        // }
                        // configuration of the media manager new instance
                        wp.media.frames.gk_frame = wp.media({
                            title: 'Select image',
                            multiple: false,
                            library: {
                                type: 'image'
                            },
                            button: {
                                text: 'Use selected image'
                            }
                        });
             
                        // Function used for the image selection and media manager closing
                        var gk_media_set_image = function() {
                            var selection = wp.media.frames.gk_frame.state().get('selection');
             
                            // no selection
                            if (!selection) {
                                return;
                            }
             
                            // iterate through selected elements
                            selection.each(function(attachment) {
                                var url = attachment.attributes.url;
                                clicked_button.prev(selector).val(attachment.attributes.id);
                                $(image_selector).attr('src', url).show();
                            });
                        };
             
                        // closing event for media manger
                        wp.media.frames.gk_frame.on('close', gk_media_set_image);
                        // image selection event
                        wp.media.frames.gk_frame.on('select', gk_media_set_image);
                        // showing media manager
                        wp.media.frames.gk_frame.open();
                    });
               });
            }

        }

    }());

})(jQuery);

basel_media_init = baselAdminModule.mediaInit;

jQuery(document).ready(function() {
    baselAdminModule.init();
});
