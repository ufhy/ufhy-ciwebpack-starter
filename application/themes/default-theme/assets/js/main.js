if (ENV === 'production') {
  const url = new URL(document.currentScript.src);
  __webpack_public_path__ = (url.origin + __webpack_public_path__);
}

import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import router from './router';
import '../scss/default-theme.scss';

import api from './utils/api';

Vue.use(BootstrapVue);

import AppNav from './components/Nav.vue';
Vue.component('app-nav', AppNav);
import AppFooter from './components/Footer.vue';
Vue.component('app-footer', AppFooter);

Vue.prototype.$axios = api();

window.VUE = new Vue({
  el: "#root",
  router,
  created() {

  }
});