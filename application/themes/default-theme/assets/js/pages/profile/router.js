const Index = () => import('./Index.vue');

export default {
	path: '/profile',
	meta: {
		title: 'menu::profile',
		breadcrumb: false,
		module: 'profile',
		role: 'read'
	},
	name: 'profile.index',
	component: Index,
}