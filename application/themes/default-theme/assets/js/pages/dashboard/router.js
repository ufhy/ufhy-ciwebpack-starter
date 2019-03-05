const Index = () => import('./index.vue');

export default {
	path: '/dashboard',
	meta: {
		title: 'menu::dashboard',
	},
	name: 'dashboard.index',
	component: Index,
};