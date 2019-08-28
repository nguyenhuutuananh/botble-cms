var BDashboard = BDashboard || {};

BDashboard.loadWidget = function (el, url, data, callback) {
    Botble.blockUI({
        target: el,
        iconOnly: true,
        overlayColor: 'none'
    });

    if (typeof data == 'undefined') {
        data = {};
    }

    $.ajax({
        type: 'GET',
        cache: false,
        url: url,
        data: data,
        success: function (res) {
            Botble.unblockUI(el);
            if (!res.error) {
                el.html(res.data);
                if (typeof (callback) != 'undefined') {
                    callback();
                }
                if (el.find('.scroller').length != 0) {
                    Botble.callScroll(el.find('.scroller'));
                }
                $('.equal-height').equalHeights();

                BDashboard.initSortable();
            } else {
                el.html('<div class="dashboard_widget_msg"><p>' + res.message + '</p>');
            }
        },
        error: function (res) {
            Botble.unblockUI(el);
            Botble.handleError(res);
        }
    });
};

BDashboard.initSortable = function () {
    var el = document.getElementById('list_widgets');
    Sortable.create(el, {
        group: 'widgets', // or { name: "...", pull: [true, false, clone], put: [true, false, array] }
        sort: true, // sorting inside list
        delay: 0, // time in milliseconds to define when the sorting should start
        disabled: false, // Disables the sortable if set to true.
        store: null, // @see Store
        animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
        handle: '.portlet-title',
        ghostClass: 'sortable-ghost', // Class name for the drop placeholder
        chosenClass: 'sortable-chosen', // Class name for the chosen item
        dataIdAttr: 'data-id',

        forceFallback: false, // ignore the HTML5 DnD behaviour and force the fallback to kick in
        fallbackClass: 'sortable-fallback', // Class name for the cloned DOM Element when using forceFallback
        fallbackOnBody: false,  // Appends the cloned DOM Element into the Document's Body

        scroll: true, // or HTMLElement
        scrollSensitivity: 30, // px, how near the mouse must be to an edge to start scrolling.
        scrollSpeed: 10, // px

        // dragging ended
        onEnd: function () {
            var items = [];
            $.each($('.widget_item'), function (index, widget) {
                items.push($(widget).prop('id'));
            });
            $.ajax({
                type: 'POST',
                cache: false,
                url: BDashboard.routes.update_widget_order,
                data: {
                    items: items
                },
                success: function (res) {
                    if (!res.error) {
                        Botble.showNotice('success', res.message, Botble.languages.notices_msg.success);
                    } else {
                        Botble.showNotice('error', res.message, Botble.languages.notices_msg.error);
                    }
                },
                error: function (data) {
                    Botble.handleError(data);
                }
            });
        }
    });
};

BDashboard.init = function () {
    var list_widgets = $('#list_widgets');

    $(document).on('click', '.portlet > .portlet-title > .tools > a.remove', function (e) {
        e.preventDefault();
        $('#hide-widget-confirm-bttn').data('id', $(this).closest('.widget_item').prop('id'));
        $('#hide_widget_modal').modal('show');
    });

    list_widgets.on('click', '.page_next, .page_previous', function (e) {
        e.preventDefault();
        BDashboard.loadWidget($(this).closest('.portlet').find('.portlet-body'), $(this).prop('href'));
    });

    list_widgets.on('change', '.number_record .numb', function (e) {
        e.preventDefault();
        var paginate = $('.number_record .numb').val();
        if (!isNaN(paginate)) {
            BDashboard.loadWidget($(this).closest('.portlet').find('.portlet-body'), $(this).closest('.widget_item').attr('data-url'), {paginate: paginate});
        } else {
            Botble.showNotice('error', 'Please input a number!', Botble.languages.notices_msg.error)
        }

    });

    list_widgets.on('click', '.btn_change_paginate', function (e) {
        e.preventDefault();
        var numb = $('.number_record .numb');
        var paginate = parseInt(numb.val());
        if ($(this).hasClass('btn_up')) {
            paginate += 5;
        }
        if ($(this).hasClass('btn_down')) {
            if (paginate - 5 > 0) {
                paginate -= 5;
            } else {
                paginate = 0;
            }
        }
        numb.val(paginate);
        BDashboard.loadWidget($(this).closest('.portlet').find('.portlet-body'), $(this).closest('.widget_item').attr('data-url'), {paginate: paginate});
    });

    $('#hide-widget-confirm-bttn').click(function (event) {
        event.preventDefault();
        var name = $(this).data('id');
        $.ajax({
            type: 'GET',
            cache: false,
            url: BDashboard.routes.hide_widget + '?name=' + name,
            success: function (res) {
                if (!res.error) {
                    $('#' + name).fadeOut();
                    Botble.showNotice('success', res.message, Botble.languages.notices_msg.success);
                } else {
                    Botble.showNotice('error', res.message, Botble.languages.notices_msg.error);
                }
                $('#hide_widget_modal').modal('hide');
                var portlet = $(this).closest(".portlet");

                if ($(document).hasClass('page-portlet-fullscreen')) {
                    $(document).removeClass('page-portlet-fullscreen');
                }

                portlet.find('.portlet-title .fullscreen').tooltip('destroy');
                portlet.find('.portlet-title > .tools > .reload').tooltip('destroy');
                portlet.find('.portlet-title > .tools > .remove').tooltip('destroy');
                portlet.find('.portlet-title > .tools > .config').tooltip('destroy');
                portlet.find('.portlet-title > .tools > .collapse, .portlet > .portlet-title > .tools > .expand').tooltip('destroy');

                portlet.remove();
            },
            error: function (data) {
                Botble.handleError(data);
            }
        });
    });

    $(document).on('click', '.portlet > .portlet-title > .tools > a.reload', function (e) {
        e.preventDefault();
        BDashboard.loadWidget($(this).closest('.portlet').find('.portlet-body'), $(this).closest('.widget_item').attr('data-url'));
    });


    $(document).on('click', '.portlet > .portlet-title > .tools > .collapse, .portlet .portlet-title > .tools > .expand', function (e) {
        e.preventDefault();
        var state = 'expand';
        if ($.trim($(this).data('state')) === 'collapse') {
            $(this).closest('.portlet').find('.portlet-body').removeClass('collapse');
            state = 'collapse';
            BDashboard.loadWidget($(this).closest('.portlet').find('.portlet-body'), $(this).closest('.widget_item').attr('data-url'));
        } else {
            $(this).closest('.portlet').find('.portlet-body').removeClass('expand');
        }

        $(this).prop('data-state', state);

        $.ajax({
            type: 'POST',
            cache: false,
            url: BDashboard.routes.edit_widget_item,
            data: {
                name: $(this).closest('.widget_item').prop('id'),
                setting_name: 'state',
                setting_value: state
            },
            success: function () {
                console.log('Save setting item successfully!');
            },
            error: function (data) {
                Botble.handleError(data);
            }
        });

    });

    var manage_widget_modal = $('#manage_widget_modal');
    $(document).on('click', '.manage-widget', function (e) {
        e.preventDefault();
        manage_widget_modal.modal('show');
    });

    manage_widget_modal.on('change', '.swc_wrap input', function () {
        $(this).closest('section').find('i').toggleClass('widget_none_color');
    });

};

$(document).ready(function () {
    BDashboard.init();
});