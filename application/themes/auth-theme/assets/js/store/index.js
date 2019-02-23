import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import localisation from './modules/localisation';

const debug = process.env.NODE_ENV !== 'production';
export default new Vuex.Store({
  modules: {
    localisation
  },
  strict: debug,
})