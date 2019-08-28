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
/******/ 	return __webpack_require__(__webpack_require__.s = 98);
/******/ })
/************************************************************************/
/******/ ({

/***/ 98:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(99);


/***/ }),

/***/ 99:
/***/ (function(module, exports) {

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var MaintenanceMode = function () {
    function MaintenanceMode() {
        _classCallCheck(this, MaintenanceMode);
    }

    _createClass(MaintenanceMode, [{
        key: 'init',
        value: function init() {
            $(document).on('click', '#btn-maintenance', function (event) {
                event.preventDefault();
                var _self = $(event.currentTarget);
                _self.addClass('button-loading');

                $.ajax({
                    type: 'POST',
                    url: route('system.maintenance.run'),
                    cache: false,
                    data: _self.closest('form').serialize(),
                    success: function success(res) {
                        if (!res.error) {
                            Botble.showNotice('success', res.message);
                            _self.text(res.data.message);
                            if (!res.data.is_down) {
                                _self.removeClass('btn-warning').addClass('btn-info');
                            } else {
                                _self.addClass('btn-warning').removeClass('btn-info');
                            }

                            if (res.data.is_down) {
                                _self.closest('form').find('.maintenance-mode-notice div span').addClass('text-danger').removeClass('text-success').text(res.data.notice);
                            } else {
                                _self.closest('form').find('.maintenance-mode-notice div span').removeClass('text-danger').addClass('text-success').text(res.data.notice);
                            }
                        } else {
                            Botble.showNotice('error', res.message);
                        }
                        _self.removeClass('button-loading');
                    },
                    error: function error(res) {
                        Botble.handleError(res);
                        _self.removeClass('button-loading');
                    }
                });
            });
        }
    }]);

    return MaintenanceMode;
}();

$(document).ready(function () {
    new MaintenanceMode().init();
});

/***/ })

/******/ });