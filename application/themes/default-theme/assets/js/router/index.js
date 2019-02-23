import Vue from 'vue';
import Router from 'vue-router';
import NProgress from 'nprogress/nprogress';
import 'nprogress/nprogress.css';

import paths from './paths';

Vue.use(Router);
const router = new Router({
  base: '/',
  mode: 'hash',
  routes: paths,
});

router.beforeEach((to, from, next) => {
  NProgress.start();
  next();
});
router.afterEach((to, from) => {
  document.title = to.meta.title + ' - ' + ufhy.SITE_TITLE_FULL;
  NProgress.done();
});

export default router;