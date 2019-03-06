const Index = () => import('./Index.vue');
const Form = () => import('./Form.vue');
const Permissions = () => import('./Permissions.vue');

export default {
	path: 'group',
	name: 'users.group.index',
	meta: {
		title: 'menu::group',
		breadcrumb: true,
		module: 'users/group',
		role: 'read'
	},
	component: Index,
	children: [
		{
			path: 'create',
			name: 'users.group.create',
			meta: {
				title: 'users::group:heading_create',
				breadcrumb: true,
				module: 'users/group',
				role: 'create'
			},
			component: Form
		},
		{
			path: 'edit/:id',
			name: 'users.group.edit',
			meta: {
				title: 'lb::edit',
				breadcrumb: true,
				module: 'users/group',
				role: 'edit'
			},
			component: Form
		},
		{
			path: 'permissions/:id',
			name: 'users.group.permissions',
			meta: {
				title: 'lb::permissions',
				breadcrumb: true,
				module: 'users/group',
				role: 'change_permission'
			},
			component: Permissions
		},
	]
}