'use strict';

window._ = require('lodash');
import Vue from 'vue'
import 'babel-polyfill'

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    $ = window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

require('datatables.net-bs4');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.events = new Vue();

window.toastSuccess = function(title, body) {
    window.events.$emit('toast-success', title, body);
};

window.toastError = function(title, body) {
    window.events.$emit('toast-error', title, body);
};

window.toastStack = function(title, body) {
    window.events.$emit('toast-stack', title, body);
};
