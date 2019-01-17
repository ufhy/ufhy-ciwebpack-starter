import Dashboard from '../pages/dashboard.vue';
import Settings from '../pages/settings.vue';

export default [
  {
    path: '/',
    redirect: '/dashboard',
  },
  {
    path: '/dashboard',
    meta: {
      title: 'Dashboard',
    },
    name: 'dashboard.index',
    component: Dashboard,
  },
  {
    path: '/settings',
    meta: {
      title: 'Settings',
    },
    name: 'settings.index',
    component: Settings,
  },
];