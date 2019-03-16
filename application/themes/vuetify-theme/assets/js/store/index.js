import Vue from 'vue';
import Vuex from 'vuex';
import localisation from './modules/localisation';
Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';
export default new Vuex.Store({
  modules: {
    localisation
  },
  strict: debug,
})