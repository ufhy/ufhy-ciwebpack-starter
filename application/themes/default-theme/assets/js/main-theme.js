if (ENV === 'production') {
  const url = new URL(document.currentScript.src);
  __webpack_public_path__ = (url.origin + __webpack_public_path__);
}


import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import router from './router';
import '../scss/default-theme.scss';

Vue.use(BootstrapVue);

import Toasted from 'vue-toasted';
Vue.use(Toasted, {
  position: 'top-center',
  duration: 5000,
  theme: 'ufhy',
  action: {
    text: 'Close',
    onClick: (e, toastObject) => {
      toastObject.goAway(0);
    }
  },
});

import api from './utils/api';
Vue.prototype.$axios = api();

window.VUE = new Vue({
  el: "#root",
  router,
});