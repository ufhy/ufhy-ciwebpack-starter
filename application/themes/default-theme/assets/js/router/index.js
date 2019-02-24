import Vue from 'vue';
import Router from 'vue-router';
import NProgress from 'nprogress/nprogress';
import store from '../store';
import api from '../utils/api';
import 'nprogress/nprogress.css';

import paths from './paths';

Vue.use(Router);

const router = new Router({
  base: '/',
  mode: 'hash',
  routes: paths,
});

const loadI18n = (module) => {
  return api().get('addons/i18n', {
    params: {
      module: module
    }
  }).then(response => {
    const { data } = response;
    Vue.i18n.add(ufhy.LANG, data);
  })
}

const beforeEach = async (to, from, next) => {
  NProgress.start();
  await loadI18n(to.meta.module);
  next();
}

const afterEach = (to, from) => {
  document.title = VUE.$t(to.meta.title) + ' - ' + ufhy.SITE_TITLE_FULL;
  NProgress.done();
}

router.beforeEach(beforeEach);
router.afterEach(afterEach);

export default router;