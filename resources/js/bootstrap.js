/**
 * lodash
 *
 */
window._ = require('lodash');

/**
 * moment
 *
 */
window.moment = require('moment');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/**
 * We'll load the client (axios HTTP library) which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import { axios, xhttp } from './clients';

if(axios) window.client = axios;
else window.client = xhttp;

/**
 * Load the Vuejs
 */
window.Vue = require('vue');
