import * as toastr from 'toastr';

export default class Botble {
    static showNotice(messageType, message, messageHeader) {
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
        toastr[messageType](message, messageHeader);
    };

    static handleError(data) {
        if (typeof (data.responseJSON) !== 'undefined') {
            if (typeof (data.responseJSON.message) !== 'undefined') {
                Botble.showNotice('error', data.responseJSON.message, 'Error!');
            } else {
                $.each(data.responseJSON, function (index, el) {
                    $.each(el, function (key, item) {
                        Botble.showNotice('error', item, 'Error!');
                    });
                });
            }
        } else {
            Botble.showNotice('error', data.statusText, 'Error!');
        }
    }
}
