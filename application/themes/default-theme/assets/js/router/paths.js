import Dashboard from '../pages/dashboard/router';
// import Settings from '../pages/settings/router';
// import Profile from '../pages/profile/router';
// import Users from '../pages/users/router';

export default [
  {
    path: '/',
    redirect: '/dashboard',
  },
  Dashboard, // Profile, Settings, Users
];