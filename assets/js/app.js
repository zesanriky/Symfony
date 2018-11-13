/*
 * Welcome to your app's main JavaScript file!
 *
 * Note: Note This UI Support is just for prove of concept there is some refactoring need to take place
 *
 * // TODO Add some refactoring
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import 'vuetify/dist/vuetify.min.css' // Ensure you are using css-loader
import 'material-design-icons-iconfont/dist/material-design-icons.css' // Ensure you are using css-loader

require('../css/app.css');

import Vue from 'vue';

import Vuetify from 'vuetify';

window.axios = require('axios');

import Welcome from './components/Welcome.vue';

window.moment = require('moment');

Vue.use(Vuetify);

const app = new Vue({
    el:"#app",
    render(h) {
        return h(Welcome)
    }
});