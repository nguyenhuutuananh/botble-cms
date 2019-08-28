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
/******/ 	return __webpack_require__(__webpack_require__.s = 61);
/******/ })
/************************************************************************/
/******/ ({

/***/ 61:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(62);


/***/ }),

/***/ 62:
/***/ (function(module, exports) {

function overrideCss(id) {
    $('#' + id).wrap('<div id="wrapper_' + id + '"><div class="container_content_codemirror"></div> </div>');
    $('#wrapper_' + id).append('<div class="handle-tool-drag" id="tool-drag_' + id + '"></div>');
    CodeMirror.fromTextArea(document.getElementById(id), {
        extraKeys: { 'Ctrl-Space': 'autocomplete' },
        lineNumbers: true,
        mode: 'css',
        autoRefresh: true,
        lineWrapping: true
    });
}

function onDragTool(e) {
    ele = '#' + $('body').attr('data-dragtool');
    container = $(ele).parent().find('.CodeMirror');
    start_h = parseInt($(ele).attr('data-start_h'));

    $(ele).parent().find('.CodeMirror').css('height', Math.max(200, start_h + e.pageY - $(ele).attr('data-start_y')));
}

function onReleaseTool() {
    $('body').off('mousemove', onDragTool);
    $(window).off('mouseup', onReleaseTool);
}

$(document).ready(function () {
    overrideCss('custom_css');

    $('.handle-tool-drag').mousedown(function (e) {
        $(this).attr('data-start_h', $(this).parent().find('.CodeMirror').height()).attr('data-start_y', e.pageY);
        $('body').attr('data-dragtool', $(this).attr('id')).on('mousemove', onDragTool);
        $(window).on('mouseup', onReleaseTool);
    });
});

/***/ })

/******/ });