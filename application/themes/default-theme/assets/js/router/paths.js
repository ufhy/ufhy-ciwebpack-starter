import Dashboard from '../pages/dashboard/router';
import Settings from '../pages/settings/router';

export default [
  {
    path: '/',
    redirect: '/dashboard',
  },
  Dashboard, Settings
];