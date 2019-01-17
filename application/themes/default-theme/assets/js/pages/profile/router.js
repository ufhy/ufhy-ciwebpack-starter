const Index = () => import('./index.vue');

export default {
	path: '/profile',
	meta: {
		title: 'Profile',
	},
	name: 'profile.index',
	component: Index,
}