window.$ = window.jQuery = require('jquery')
require('bootstrap-sass');
require('bootstrap-submenu');
require('bootstrap-datepicker');
require('bootstrap-datepicker/js/locales/bootstrap-datepicker.es.js');
require('bootstrap-fileinput');
require('bootstrap-fileinput/js/locales/es.js');
require('sweetalert');
require('jquery-treegrid/js/jquery.treegrid.js');
window._ = require('underscore');
require('./scripts');
window.Vue = require('vue/dist/vue.min.js');
require('vue-resource');
Vue.http.headers.common['X-CSRF-TOKEN'] = App.csrfToken;
//require('./vue-directives');
if ($('#app').length) {
    new Vue({
        el: '#app',
        components: require('./vue-components')
    });   
    
}