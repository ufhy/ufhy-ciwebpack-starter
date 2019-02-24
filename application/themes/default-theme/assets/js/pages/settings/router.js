const Index = () => import('./Index.vue');

export default {
	path: '/settings',
	meta: {
		title: 'Settings',
		breadcrumb: true,
		module: 'settings',
		role: 'read'
	},
	name: 'settings.index',
	component: Index,
}