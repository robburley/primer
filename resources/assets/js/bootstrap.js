window.axios = require('axios')

window.axiosRetry = require('axios-retry')

window.collect = require('collect.js')

window.moment = require('moment')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

let token = document.head.querySelector('meta[name="csrf-token"]')

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token')
}

window.Vue = require('vue')
