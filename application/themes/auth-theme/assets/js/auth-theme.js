import '@babel/polyfill';

if (ENV === 'production') {
  const url = new URL(document.currentScript.src);
  __webpack_public_path__ = (url.origin + __webpack_public_path__);
}

import Vue from 'vue';
import store from './store';
import '../scss/auth-theme.scss';

import api from './utils/api';
Vue.prototype.$axios = api();

window.VUE = new Vue({
  el: "#root",
  store,
  created() {
    this.reThemeVuetify();

    // this.$store.dispatch('localisation/getI18n');
  },
  methods: {
    reThemeVuetify() {
      //color
      console.log(this.$vuetify);
      this.$vuetify.theme.accent = "#1565C0";
      this.$vuetify.theme.error = "#FF5252";
      this.$vuetify.theme.info = "#2196F3";
      this.$vuetify.theme.primary = "#1565C0";
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
      this.$vuetify.icons.cancel = "la-times-circle";
      this.$vuetify.icons.close = "la-times-circle";
    }
  }
});
