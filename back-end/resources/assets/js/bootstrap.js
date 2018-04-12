window._ = require('lodash');

try {
    window.$ = window.jQuery = require('jquery');
} catch (e) {
}

require('es6-promise').polyfill();
window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.axios.interceptors.response.use(
    response => response,

    error => {

        if (error.response.status == 419) {

            error.response.data.message = 'Session has expired. Please reload page.';

        }

        return Promise.reject(error);
    }
);

window.qs = require('qs');

