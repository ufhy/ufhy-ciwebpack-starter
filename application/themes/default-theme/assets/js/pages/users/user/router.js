const Index = () => import('./Index.vue');
const Form = () => import('./Form.vue');

export default {
	path: 'user',
	name: 'users.user.index',
	meta: {
		title: 'menu::user',
		breadcrumb: true,
		module: 'users/user',
		role: 'read'
	},
	component: Index,
	children: [
		{
			path: 'create',
			name: 'users.user.create',
			meta: {
				title: 'users::user:heading_create',
				breadcrumb: true,
				shortcut: [],
				module: 'users/user',
				role: 'create'
			},
			component: Form
		},
		{
			path: 'edit/:id',
			name: 'users.user.edit',
			meta: {
				title: 'lb::edit',
				breadcrumb: true,
				module: 'users/user',
				role: 'edit'
			},
			component: Form
		},
	]
}