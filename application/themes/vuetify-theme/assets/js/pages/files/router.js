const Index = () => import('./Index.vue');

export default {
	path: '/files',
	meta: {
		title: 'menu::files',
		breadcrumb: true,
		module: 'files',
		role: 'read'
	},
	name: 'files.index',
	component: Index,
}