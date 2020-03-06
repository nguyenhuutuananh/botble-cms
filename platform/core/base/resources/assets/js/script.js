class Botble {
    constructor() {
        this.countCharacter();
        this.manageSidebar();
        this.handleWayPoint();
        this.handlePortletTools();
        Botble.initResources();
        Botble.handleCounterUp();
        Botble.initMediaIntegrate();
    }

    static blockUI(options) {
        options = $.extend(true, {}, options);
        let html = '';
        if (options.animate) {
            html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '">' + '<div class="block-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>' + '</div>';
        } else if (options.iconOnly) {
            html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="/vendor/core/images/loading-spinner-blue.gif"></div>';
        } else if (options.textOnly) {
            html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
        } else {
            html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="/vendor/core/images/loading-spinner-blue.gif"><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
        }

        if (options.target) { // element blocking
            let el = $(options.target);
            if (el.height() <= ($(window).height())) {
                options.cenrerY = true;
            }
            el.block({
                message: html,
                baseZ: options.zIndex ? options.zIndex : 1000,
                centerY: options.cenrerY !== undefined ? options.cenrerY : false,
                css: {
                    top: '10%',
                    border: '0',
                    padding: '0',
                    backgroundColor: 'none'
                },
                overlayCSS: {
                    backgroundColor: options.overlayColor ? options.overlayColor : '#555',
                    opacity: options.boxed ? 0.05 : 0.1,
                    cursor: 'wait'
                }
            });
        } else { // page blocking
            $.blockUI({
                message: html,
                baseZ: options.zIndex ? options.zIndex : 1000,
                css: {
                    border: '0',
                    padding: '0',
                    backgroundColor: 'none'
                },
                overlayCSS: {
                    backgroundColor: options.overlayColor ? options.overlayColor : '#555',
                    opacity: options.boxed ? 0.05 : 0.1,
                    cursor: 'wait'
                }
            });
        }
    }

    static unblockUI(target) {
        if (target) {
            $(target).unblock({
                onUnblock: () => {
                    $(target).css('position', '');
                    $(target).css('zoom', '');
                }
            });
        } else {
            $.unblockUI();
        }
    }

    static showNotice(messageType, message) {
        toastr.clear();

        toastr.options = {
            closeButton: true,
            positionClass: 'toast-bottom-right',
            onclick: null,
            showDuration: 1000,
            hideDuration: 1000,
            timeOut: 10000,
            extendedTimeOut: 1000,
            showEasing: 'swing',
            hideEasing: 'linear',
            showMethod: 'fadeIn',
            hideMethod: 'fadeOut'
        };

        let messageHeader = '';

        switch (messageType) {
            case 'error':
                messageHeader = BotbleVariables.languages.notices_msg.error;
                break;
            case 'success':
                messageHeader = BotbleVariables.languages.notices_msg.success;
                break;
        }
        toastr[messageType](message, messageHeader);
    }

    static showError(message) {
        this.showNotice('error', message);
    }

    static showSuccess(message) {
        this.showNotice('success', message);
    }

    static handleError(data) {
        if (typeof (data.errors) !== 'undefined' && !_.isArray(data.errors)) {
            Botble.handleValidationError(data.errors);
        } else {
            if (typeof (data.responseJSON) !== 'undefined') {
                if (typeof (data.responseJSON.errors) !== 'undefined') {
                    if (data.status === 422) {
                        Botble.handleValidationError(data.responseJSON.errors);
                    }
                } else if (typeof (data.responseJSON.message) !== 'undefined') {
                    Botble.showError(data.responseJSON.message);
                } else {
                    $.each(data.responseJSON, (index, el) => {
                        $.each(el, (key, item) => {
                            Botble.showError(item);
                        });
                    });
                }
            } else {
                Botble.showError(data.statusText);
            }
        }
    }

    static handleValidationError(errors) {
        let message = '';
        $.each(errors, (index, item) => {
            message += item + '<br />';

                let $input = $('*[name="' + index + '"]');
                if ($input.closest('.next-input--stylized').length) {
                    $input.closest('.next-input--stylized').addClass('field-has-error');
                } else {
                    $input.addClass('field-has-error');
                }

                let $input_array = $('*[name$="[' + index + ']"]');

                if ($input_array.closest('.next-input--stylized').length) {
                    $input_array.closest('.next-input--stylized').addClass('field-has-error');
                } else {
                    $input_array.addClass('field-has-error');
                }
        });
        Botble.showError(message);
    }

    countCharacter() {
        $.fn.charCounter = function (max, settings) {
            max = max || 100;
            settings = $.extend({
                container: '<span></span>',
                classname: 'charcounter',
                format: '(%1 ' + BotbleVariables.languages.system.character_remain + ')',
                pulse: true,
                delay: 0
            }, settings);
            let p, timeout;

            let count = (el, container) => {
                el = $(el);
                if (el.val().length > max) {
                    el.val(el.val().substring(0, max));
                    if (settings.pulse && !p) {
                        pulse(container, true);
                    }
                }
                if (settings.delay > 0) {
                    if (timeout) {
                        window.clearTimeout(timeout);
                    }
                    timeout = window.setTimeout(() => {
                        container.html(settings.format.replace(/%1/, (max - el.val().length)));
                    }, settings.delay);
                } else {
                    container.html(settings.format.replace(/%1/, (max - el.val().length)));
                }
            };

            let pulse = (el, again) => {
                if (p) {
                    window.clearTimeout(p);
                    p = null;
                }
                el.animate({
                    opacity: 0.1
                }, 100, () => {
                    $(el).animate({
                        opacity: 1.0
                    }, 100);
                });
                if (again) {
                    p = window.setTimeout(() => {
                        pulse(el)
                    }, 200);
                }
            };

            return this.each((index, el) => {
                let container;
                if (!settings.container.match(/^<.+>$/)) {
                    // use existing element to hold counter message
                    container = $(settings.container);
                } else {
                    // append element to hold counter message (clean up old element first)
                    $(el).next('.' + settings.classname).remove();
                    container = $(settings.container)
                        .insertAfter(el)
                        .addClass(settings.classname);
                }
                $(el)
                    .unbind('.charCounter')
                    .bind('keydown.charCounter', () => {
                        count(el, container);
                    })
                    .bind('keypress.charCounter', () => {
                        count(el, container);
                    })
                    .bind('keyup.charCounter', () => {
                        count(el, container);
                    })
                    .bind('focus.charCounter', () => {
                        count(el, container);
                    })
                    .bind('mouseover.charCounter', () => {
                        count(el, container);
                    })
                    .bind('mouseout.charCounter', () => {
                        count(el, container);
                    })
                    .bind('paste.charCounter', () => {
                        setTimeout(() => {
                            count(el, container);
                        }, 10);
                    });
                if (el.addEventListener) {
                    el.addEventListener('input', () => {
                        count(el, container);
                    }, false);
                }
                count(el, container);
            });
        };

        $(document).on('click', 'input[data-counter], textarea[data-counter]', (event) => {
            $(event.currentTarget).charCounter($(event.currentTarget).data('counter'), {
                container: '<small></small>'
            });
        });
    }

    manageSidebar() {
        let body = $('body');
        let navigation = $('.navigation');
        let sidebar_content = $('.sidebar-content');

        navigation.find('li.active').parents('li').addClass('active');
        navigation.find('li').has('ul').children('a').parent('li').addClass('has-ul');


        $(document).on('click', '.sidebar-toggle.d-none', (event) => {
            event.preventDefault();

            body.toggleClass('sidebar-narrow');
            body.toggleClass('page-sidebar-closed');

            if (body.hasClass('sidebar-narrow')) {
                navigation.children('li').children('ul').css('display', '');

                sidebar_content.delay().queue(() => {
                    $(event.currentTarget).show().addClass('animated fadeIn').clearQueue();
                });
            } else {
                navigation.children('li').children('ul').css('display', 'none');
                navigation.children('li.active').children('ul').css('display', 'block');

                sidebar_content.delay().queue(() => {
                    $(event.currentTarget).show().addClass('animated fadeIn').clearQueue();
                });
            }
        });
    }

    static initDatePicker(element) {
        if (jQuery().bootstrapDP) {
            let format = $(document).find(element).data('date-format');
            if (!format) {
                format = 'yyyy-mm-dd';
            }
            $(document).find(element).bootstrapDP({
                maxDate: 0,
                changeMonth: true,
                changeYear: true,
                autoclose: true,
                dateFormat: format,
            });
        }
    }

    static initResources() {
        if (jQuery().select2) {
            $(document).find('.select-multiple').select2({
                width: '100%',
                allowClear: true,
            });
            $(document).find('.select-search-full').select2({
                width: '100%'
            });
            $(document).find('.select-full').select2({
                width: '100%',
                minimumResultsForSearch: -1
            });
        }

        if (jQuery().timepicker) {
            if (jQuery().timepicker) {

                $('.timepicker-default').timepicker({
                    autoclose: true,
                    showSeconds: true,
                    minuteStep: 1,
                    defaultTime: false
                });

                $('.timepicker-no-seconds').timepicker({
                    autoclose: true,
                    minuteStep: 5,
                    defaultTime: false,
                });

                $('.timepicker-24').timepicker({
                    autoclose: true,
                    minuteStep: 5,
                    showSeconds: false,
                    showMeridian: false,
                    defaultTime: false
                });
            }
        }

        if (jQuery().inputmask) {
            $(document).find('.input-mask-number').inputmask({
                alias: 'numeric',
                rightAlign: false,
                digits: 2,
                groupSeparator: ',',
                placeholder: '0',
                autoGroup: true,
                autoUnmask: true,
                removeMaskOnSubmit: true,
            });
        }

        if (jQuery().colorpicker) {
            $('.color-picker').colorpicker({});
        }

        if (jQuery().fancybox) {
            $('.iframe-btn').fancybox({
                width: '900px',
                height: '700px',
                type: 'iframe',
                autoScale: false,
                openEffect: 'none',
                closeEffect: 'none',
                overlayShow: true,
                overlayOpacity: 0.7
            });
            $('.fancybox').fancybox({
                openEffect: 'none',
                closeEffect: 'none',
                overlayShow: true,
                overlayOpacity: 0.7,
                helpers: {
                    media: {}
                }
            });
        }
        $('[data-toggle="tooltip"]').tooltip({placement: 'top'});

        if (jQuery().areYouSure) {
            $('form').areYouSure();
        }

        Botble.initDatePicker('.datepicker');
        if (jQuery().mCustomScrollbar) {
            Botble.callScroll($('.list-item-checkbox'));
        }

        if (jQuery().textareaAutoSize) {
            $('textarea.textarea-auto-height').textareaAutoSize();
        }
    }

    static numberFormat(number, decimals, dec_point, thousands_sep) {
        // *     example 1: number_format(1234.56);
        // *     returns 1: '1,235'
        // *     example 2: number_format(1234.56, 2, ',', ' ');
        // *     returns 2: '1 234,56'
        // *     example 3: number_format(1234.5678, 2, '.', '');
        // *     returns 3: '1234.57'
        // *     example 4: number_format(67, 2, ',', '.');
        // *     returns 4: '67,00'
        // *     example 5: number_format(1000);
        // *     returns 5: '1,000'
        // *     example 6: number_format(67.311, 2);
        // *     returns 6: '67.31'
        // *     example 7: number_format(1000.55, 1);
        // *     returns 7: '1,000.6'
        // *     example 8: number_format(67000, 5, ',', '.');
        // *     returns 8: '67.000,00000'
        // *     example 9: number_format(0.9, 0);
        // *     returns 9: '1'
        // *    example 10: number_format('1.20', 2);
        // *    returns 10: '1.20'
        // *    example 11: number_format('1.20', 4);
        // *    returns 11: '1.2000'
        // *    example 12: number_format('1.2000', 3);
        // *    returns 12: '1.200'
        let n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            toFixedFix = (n, prec) => {
                // Fix for IE parseFloat(0.55).toFixed(0) = 0;
                let k = Math.pow(10, prec);
                return Math.round(n * k) / k;
            },
            s = (prec ? toFixedFix(n, prec) : Math.round(n)).toString().split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    static callScroll(obj) {
        obj.mCustomScrollbar({
            axis: 'yx',
            theme: 'minimal-dark',
            scrollButtons: {
                enable: true
            },
            callbacks: {
                whileScrolling: function () {
                    obj.find('.tableFloatingHeaderOriginal').css({
                        'top': -this.mcs.top + 'px'
                    });
                }
            }
        });
        obj.stickyTableHeaders({scrollableArea: obj, 'fixedOffset': 2});
    }

    handleWayPoint() {
        if ($('#waypoint').length > 0) {
            new Waypoint({
                element: document.getElementById('waypoint'),
                handler: (direction) => {
                    if (direction === 'down') {
                        $('.form-actions-fixed-top').removeClass('hidden');
                    } else {
                        $('.form-actions-fixed-top').addClass('hidden');
                    }
                }
            });
        }
    };

    static handleCounterUp() {
        if (!$().counterUp) {
            return;
        }

        $('[data-counter="counterup"]').counterUp({
            delay: 10,
            time: 1000
        });
    }

    static initMediaIntegrate() {

        if (jQuery().rvMedia) {

            $('[data-type="rv-media-standard-alone-button"]').rvMedia({
                multiple: false,
                onSelectFiles: (files, $el) => {
                    $($el.data('target')).val(files[0].url);
                }
            });

            $(document).find('.btn_gallery').rvMedia({
                multiple: false,
                onSelectFiles: (files, $el) => {
                    switch ($el.data('action')) {
                        case 'media-insert-ckeditor':
                            $.each(files, (index, file) => {
                                let link = file.url;
                                if (file.type === 'youtube') {
                                    link = link.replace('watch?v=', 'embed/');
                                    CKEDITOR.instances[$el.data('result')].insertHtml('<iframe width="420" height="315" src="' + link + '" frameborder="0" allowfullscreen></iframe>');
                                } else if (file.type === 'image') {
                                    CKEDITOR.instances[$el.data('result')].insertHtml('<img src="' + link + '" alt="' + file.name + '" />');
                                } else {
                                    CKEDITOR.instances[$el.data('result')].insertHtml('<a href="' + link + '">' + file.name + '</a>');
                                }
                            });

                            break;
                        case 'media-insert-tinymce':
                            $.each(files, (index, file) => {
                                let link = file.url;
                                let html = '';
                                if (file.type === 'youtube') {
                                    link = link.replace('watch?v=', 'embed/');
                                    html = '<iframe width="420" height="315" src="' + link + '" frameborder="0" allowfullscreen></iframe>';
                                } else if (file.type === 'image') {
                                    html = '<img src="' + link + '" alt="' + file.name + '" />';
                                } else {
                                    html = '<a href="' + link + '">' + file.name + '</a>';
                                }
                                tinymce.activeEditor.execCommand('mceInsertContent', false, html);
                            });
                            break;
                        case 'select-image':
                            let firstImage = _.first(files);
                            $el.closest('.image-box').find('.image-data').val(firstImage.url);
                            $el.closest('.image-box').find('.preview_image').attr('src', firstImage.thumb);
                            $el.closest('.image-box').find('.preview-image-wrapper').show();
                            break;
                        case 'attachment':
                            let firstAttachment = _.first(files);
                            $el.closest('.attachment-wrapper').find('.attachment-url').val(firstAttachment.url);
                            $('.attachment-details').html('<a href="' + firstAttachment.url + '" target="_blank">' + firstAttachment.url + '</a>');
                            break;
                    }
                }
            });

            $(document).on('click', '.btn_remove_image', (event) => {
                event.preventDefault();
                $(event.currentTarget).closest('.image-box').find('.preview-image-wrapper').hide();
                $(event.currentTarget).closest('.image-box').find('.image-data').val('');
            });

            $(document).on('click', '.btn_remove_attachment', (event) => {
                event.preventDefault();
                $(event.currentTarget).closest('.attachment-wrapper').find('.attachment-details a').remove();
                $(event.currentTarget).closest('.attachment-wrapper').find('.attachment-url').val('');
            });
        }
    }

    static getViewPort() {
        let e = window,
            a = 'inner';
        if (!('innerWidth' in window)) {
            a = 'client';
            e = document.documentElement || document.body;
        }

        return {
            width: e[a + 'Width'],
            height: e[a + 'Height']
        };
    }

    handlePortletTools() {
        // handle portlet remove

        // handle portlet fullscreen
        $('body').on('click', '.portlet > .portlet-title .fullscreen', (event) => {
            event.preventDefault();
            let _self = $(event.currentTarget);
            let portlet = _self.closest('.portlet');
            if (portlet.hasClass('portlet-fullscreen')) {
                _self.removeClass('on');
                portlet.removeClass('portlet-fullscreen');
                $('body').removeClass('page-portlet-fullscreen');
                portlet.children('.portlet-body').css('height', 'auto');
            } else {
                let height = Botble.getViewPort().height -
                    portlet.children('.portlet-title').outerHeight() -
                    parseInt(portlet.children('.portlet-body').css('padding-top')) -
                    parseInt(portlet.children('.portlet-body').css('padding-bottom'));

                _self.addClass('on');
                portlet.addClass('portlet-fullscreen');
                $('body').addClass('page-portlet-fullscreen');
                portlet.children('.portlet-body').css('height', height);
            }
        });

        $('body').on('click', '.portlet > .portlet-title > .tools > .collapse, .portlet .portlet-title > .tools > .expand', (event) => {
            event.preventDefault();
            let _self = $(event.currentTarget);
            let el = _self.closest('.portlet').children('.portlet-body');
            if (_self.hasClass('collapse')) {
                _self.removeClass('collapse').addClass('expand');
                el.slideUp(200);
            } else {
                _self.removeClass('expand').addClass('collapse');
                el.slideDown(200);
            }
        });
    }

    static initCodeEditor(id) {
        $(document).find('#' + id).wrap('<div id="wrapper_' + id + '"><div class="container_content_codemirror"></div> </div>');
        $('#wrapper_' + id).append('<div class="handle-tool-drag" id="tool-drag_' + id + '"></div>');
        CodeMirror.fromTextArea(document.getElementById(id), {
            extraKeys: {'Ctrl-Space': 'autocomplete'},
            lineNumbers: true,
            mode: 'css',
            autoRefresh: true,
            lineWrapping: true,
        });

        $('.handle-tool-drag').mousedown((event) => {
            let _self = $(event.currentTarget);
            _self.attr('data-start_h', _self.parent().find('.CodeMirror').height()).attr('data-start_y', event.pageY);
            $('body').attr('data-dragtool', _self.attr('id')).on('mousemove', Botble.onDragTool);
            $(window).on('mouseup', Botble.onReleaseTool);
        });
    }

    static onDragTool(e) {
        let ele = '#' + $('body').attr('data-dragtool');
        let start_h = parseInt($(ele).attr('data-start_h'));

        $(ele).parent().find('.CodeMirror').css('height', Math.max(200, start_h + e.pageY - $(ele).attr('data-start_y')));
    }

    static onReleaseTool() {
        $('body').off('mousemove', Botble.onDragTool);
        $(window).off('mouseup', Botble.onReleaseTool);
    }
}

if (jQuery().datepicker && jQuery().datepicker.noConflict) {
    $.fn.bootstrapDP = $.fn.datepicker.noConflict();
}

$(document).ready(() => {
    new Botble();
    window.Botble = Botble;
});
