if (ENV === 'production') {
  const url = new URL(document.currentScript.src);
  __webpack_public_path__ = (url.origin + __webpack_public_path__);
}

import Vue from 'vue';
import _ from 'lodash';

import router from './router';
import store from './store';
import api from './utils/api';
import Uftify from './layouts/Uftify.vue'
import '../scss/default-theme.scss';

import vuexI18n from 'vuex-i18n';
Vue.use(vuexI18n.plugin, store, {
  translateFilterName: 't'
});
Vue.i18n.add(ufhy.LANG, ufhy.LANGS);
Vue.i18n.set(ufhy.LANG);

import UfSnackbars from './components/UfSnackbars'
Vue.use(UfSnackbars);

import VuetifyConfirm from 'vuetify-confirm/src/index';
Vue.use(VuetifyConfirm);

import { abilitiesPlugin } from '@casl/vue';
import ability from './utils/ability';
Vue.use(abilitiesPlugin, ability);

Vue.prototype.$axios = api();
Vue.prototype.$lodash = _;

window.VUE = new Vue({
  router,
  store,
  created() {
    this.reThemeVuetify();
  },
  methods: {
    reThemeVuetify() {
      //color
      console.log(this.$vuetify);
      this.$vuetify.theme.accent = "#1565C0";
      this.$vuetify.theme.error = "#FF5252";
      this.$vuetify.theme.info = "#2196F3";
      this.$vuetify.theme.primary = "#039BE5";
      this.$vuetify.theme.secondary = "#424242";
      this.$vuetify.theme.success = "#4CAF50";
      this.$vuetify.theme.warning = "#FB8C00";

      // icons
      this.$vuetify.icons.next = 'la-angle-right';
      this.$vuetify.icons.prev = 'la-angle-left';
      this.$vuetify.icons.dropdown = 'la-angle-down';
      this.$vuetify.icons.sort = 'la-arrow-up';
      this.$vuetify.icons.checkboxIndeterminate = "la-minus-square";
      this.$vuetify.icons.checkboxOff = "la-square-o";
      this.$vuetify.icons.checkboxOn = "la-check-square";
      this.$vuetify.icons.error = "la-warning";
      this.$vuetify.icons.success = "la-check-circle";
      this.$vuetify.icons.cancel = "la-times-circle";
      this.$vuetify.icons.close = "la-times-circle";
    },
    confirmDanger(message) {
      const that = this;
      return new Promise((resolve, reject) => {
        that.$confirm(message, {
          title: 'Danger',
          buttonTrueText: 'Ok',
          buttonTrueColor: 'error',
          buttonFalseText: 'Cancel',
          color: 'error',
          icon: 'la-exclamation-triangle'
        }).then(res => {
          resolve(res);
        })
      });
    }
  },
  render: h => h(Uftify)
}).$mount('#root');