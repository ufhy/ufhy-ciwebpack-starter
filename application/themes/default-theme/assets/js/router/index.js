import Vue from 'vue';
import Router from 'vue-router';

import paths from './paths';

Vue.use(Router);
const router = new Router({
  base: '/',
  mode: 'hash',
  routes: paths,
});

router.beforeEach((to, from, next) => {
  next();
});
router.afterEach((to, from) => {
	document.title = to.meta.title + ' - CI Starter Kit';
});

export default router;