if (ENV === 'production') {
  const url = new URL(document.currentScript.src);
  __webpack_public_path__ = (url.origin + __webpack_public_path__);
}

import Vue from 'vue';
import router from './router';
import store from './store';
import '../scss/default-theme.scss';

import api from './utils/api';
Vue.prototype.$axios = api();

window.VUE = new Vue({
  el: "#root",
  router,
  store,
  created() {
    this.$store.dispatch('localisation/getI18n');
  }
});