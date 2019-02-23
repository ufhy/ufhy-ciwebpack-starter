import api from '../../utils/api';

const state = {
  i18n: [],
};

const getters = {};

const actions = {
  async getI18n({commit}) {
    await api().get('addons/i18n').then(response => {
      const { data } = response;
      commit('setI18n', data);
    }).catch(error => {
      console.error('getI18n - localisation', error);
    });
  },
};

const mutations = {
  setI18n (state, i18n) {
    state.i18n = i18n;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}