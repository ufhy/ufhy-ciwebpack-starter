import Home from '../pages/home.vue';

export default [
  { path: '/', redirect: '/home' },
  {
    path: '/home',
    meta: {
      title: 'Home',
    },
    name: 'home',
    component: Home
  },
];