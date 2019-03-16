const Index = () => import('./index.vue');

export default {
	path: '/dashboard',
	meta: {
		title: 'menu::dashboard',
		module: 'dashboard',
		role: 'read'
	},
	name: 'dashboard.index',
	component: Index,
};