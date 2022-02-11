require('./bootstrap');
import Vue from 'vue'
window.Vue = Vue;

import Gravatar from 'vue-gravatar';
Vue.component('v-gravatar', Gravatar);

const feather = require('feather-icons');
feather.replace();

import VueTheMask from 'vue-the-mask';
Vue.use(VueTheMask);

import VMoney from 'v-money';
Vue.use(VMoney, {precision: 3})

import 'babel-polyfill';

require('./app-style-switcher');
require('./custom');
require('./sidebarmenu');

import PerfectScrollbar from 'perfect-scrollbar';
$.fn.perfectScrollbar = function (options) {
    return this.each((k, elm) => new PerfectScrollbar(elm, options || {}));
};

import 'jquery-ui/ui/widgets/draggable.js';
import 'jquery-ui/ui/widgets/droppable.js';
import 'jquery-ui/ui/widgets/sortable.js';

import vUploader from 'v-uploader';
Vue.use(vUploader);

var bootbox = require('bootbox');

// Register all components
const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
Vue.component('toast-success', require('./components/ToastSuccess.vue').default);
Vue.component('toast-error', require('./components/ToastError.vue').default);
Vue.component('toast-stack', require('./components/ToastStack.vue').default);

const app = new Vue({
    el: '#main-wrapper',
});


(function (exports) {
    function valOrFunction(val, ctx, args) {
        if (typeof val == "function") {
            return val.apply(ctx, args);
        } else {
            return val;
        }
    }

    function InvalidInputHelper(input, options) {
        input.setCustomValidity(valOrFunction(options.defaultText, window, [input]));

        function changeOrInput() {
            if (input.value == "") {
                input.setCustomValidity(valOrFunction(options.emptyText, window, [input]));
            } else {
                input.setCustomValidity("");
            }
        }

        function invalid() {
            if (input.value == "") {
                input.setCustomValidity(valOrFunction(options.emptyText, window, [input]));
            } else {
               input.setCustomValidity(valOrFunction(options.invalidText, window, [input]));
            }
        }

        input.addEventListener("change", changeOrInput);
        input.addEventListener("input", changeOrInput);
        input.addEventListener("invalid", invalid);
    }
    exports.InvalidInputHelper = InvalidInputHelper;
})(window);