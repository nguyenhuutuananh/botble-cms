/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 55);
/******/ })
/************************************************************************/
/******/ ({

/***/ 55:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(56);


/***/ }),

/***/ 56:
/***/ (function(module, exports) {

$(document).ready(function () {
    $('#change_slug').click(function () {
        $('.default-slug').unwrap();
        $('#editable-post-name').html('<input type="text" id="new-post-slug" class="form-control" value="' + $('#editable-post-name').text() + '" autocomplete="off">');
        $('#edit-slug-box .cancel').show();
        $('#edit-slug-box .save').show();
        $(this).hide();
    });

    $('#edit-slug-box .cancel').click(function () {
        var currentSlug = $('#current-slug').val();
        $('#sample-permalink').html('<a class="permalink" href="' + $('#slug_id').data('view') + '/' + currentSlug + '">' + $('#sample-permalink').html() + '</a>');
        $('#editable-post-name').text(currentSlug);
        $('#edit-slug-box .cancel').hide();
        $('#edit-slug-box .save').hide();
        $('#change_slug').show();
    });

    $('#edit-slug-box .save').click(function () {
        var name = $('#new-post-slug').val();
        var id = $('#slug_id').data('id');
        if (id == null) {
            id = 0;
        }
        if (name != null && name != '') {
            createSlug(name, id, false);
        } else {
            $('#new-post-slug').closest('.form-group').addClass('has-error');
        }
    });

    $('#name').blur(function () {
        if ($('#edit-slug-box').hasClass('hidden')) {
            var name = $('#name').val();

            if (name !== null && name !== '') {
                createSlug(name, 0, true);
            }
        }
    });

    var createSlug = function createSlug(name, id, exist) {
        $.ajax({
            url: $('#slug_id').data('url'),
            type: 'POST',
            data: {
                name: name,
                slug_id: id
            },
            success: function success(data) {
                if (exist) {
                    $('#sample-permalink .permalink').prop('href', $('#slug_id').data('view') + '/' + data);
                } else {
                    $('#sample-permalink').html('<a class="permalink" target="_blank" href="' + $('#slug_id').data('view') + '/' + data + '">' + $('#sample-permalink').html() + '</a>');
                }

                $('#editable-post-name').text(data);
                $('#current-slug').val(data);
                $('#edit-slug-box .cancel').hide();
                $('#edit-slug-box .save').hide();
                $('#change_slug').show();
                $('#edit-slug-box').removeClass('hidden');
            },
            error: function error(data) {
                Botble.handleError(data);
            }
        });
    };
});

/***/ })

/******/ });