import Home from '../pages/home.vue';
import DefaultLayout from '../layouts/default.vue';

export default [
  { path: '/', redirect: '/home' },
  {
    path: '/home',
    meta: {
      title: 'Home',
    },
    name: 'home',
    component: DefaultLayout,
    children: [{
      path: '/',
      meta: {
        title: 'Home',
      },
      name: 'home.index',
      component: Home,
    }]
  },
];