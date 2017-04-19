window.$ = window.jQuery = require('jquery')
require('jquery-ui');
require('jquery-timepicker/jquery.timepicker.js');
require('datepair.js');
require('bootstrap-sass');
require('bootstrap-submenu');
require('bootstrap-datepicker');
require('bootstrap-datepicker/js/locales/bootstrap-datepicker.es.js');
require('bootstrap-fileinput');
require('bootstrap-fileinput/js/locales/es.js');
require('sweetalert');
require('jquery-treegrid/js/jquery.treegrid.js');
require('select2');
window._ = require('underscore');
require('./scripts');





// Production
//window.Vue = require('vue/dist/vue.min.js');

// Development
window.Vue = require('vue/dist/vue.js');

require('vue-resource');
Vue.http.headers.common['X-CSRF-TOKEN'] = App.csrfToken;
//require('./vue-directives');
if ($('#app').length) {
    new Vue({
        el: '#app',
        components: require('./vue-components')
    });
}