window._ = require("lodash");

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

// JQuery
window.$ = window.jQuery = require("jquery");

// Axios
window.axios = require("axios");
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Bootstrap framework
window.Popper = require("popper.js").default;
require("bootstrap");

window.lozad = require('lozad');
const observer  = lozad('.lozad', {
    loaded: function(el) {
        // Custom implementation on a loaded element
        el.classList.add('loaded');
    }
});
observer.observe();

// CRSF Token
let token = document.head.querySelector('meta[name="csrf-token"]');


import Echo from "laravel-echo";
window.Pusher = require("pusher-js");

window.Echo = new Echo({
    broadcaster: "pusher",
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    wsHost: window.location.hostname.replace("www.", ""),
    forceTLS: true,
    disableStats: true,
    wsPort: 6001,
    wssPort: 6001,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
    auth: {
        headers: {
            "X-CSRF-TOKEN": token
        }
    }
});
