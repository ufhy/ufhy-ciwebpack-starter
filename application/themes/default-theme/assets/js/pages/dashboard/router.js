const Index = () => import('./index.vue');

export default {
	path: '/dashboard',
	meta: {
		title: 'Dashboard',
	},
	name: 'dashboard.index',
	component: Index,
};