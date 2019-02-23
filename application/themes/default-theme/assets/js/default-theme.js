if (ENV === 'production') {
  const url = new URL(document.currentScript.src);
  __webpack_public_path__ = (url.origin + __webpack_public_path__);
}

import Vue from 'vue';
import router from './router';
import store from './store';
import Uftify from './layouts/Uftify.vue'
import '../scss/default-theme.scss';

import api from './utils/api';
import _ from 'lodash';
Vue.prototype.$axios = api();
Vue.prototype.$lodash = _;

window.VUE = new Vue({
  router,
  store,
  created() {
    this.$store.dispatch('localisation/getI18n');
  },
  render: h => h(Uftify)
}).$mount('#root');