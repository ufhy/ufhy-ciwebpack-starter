import Dashboard from '../pages/dashboard/router';
import Settings from '../pages/settings/router';
import Profile from '../pages/profile/router';
import Users from '../pages/users/router';
import ErrorPage from '../pages/errors/router';

export default [
  {
    path: '/',
    redirect: '/dashboard',
  },
  ErrorPage, Dashboard, Profile, Settings, 
  Users
];