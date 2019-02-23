if (ENV === 'production') {
  const url = new URL(document.currentScript.src);
  __webpack_public_path__ = (url.origin + __webpack_public_path__);
}

import Vue from 'vue';
import vuexI18n from 'vuex-i18n';
import _ from 'lodash';

import router from './router';
import store from './store';
import api from './utils/api';
import Uftify from './layouts/Uftify.vue'
import '../scss/default-theme.scss';

Vue.use(vuexI18n.plugin, store, {
  translateFilterName: 't'
});
Vue.i18n.add(ufhy.LANG, ufhy.LANGS);
Vue.i18n.set(ufhy.LANG);

Vue.prototype.$axios = api();
Vue.prototype.$lodash = _;

window.VUE = new Vue({
  router,
  store,
  created() {
    
  },
  render: h => h(Uftify)
}).$mount('#root');