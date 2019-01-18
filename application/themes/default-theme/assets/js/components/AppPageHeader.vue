<template>
	<div class="page-header mb-3">
		<div class="page-header-title">
			{{title}}
		</div>
		<b-breadcrumb v-if="breadcrumb" :items="itemBreadcrumbs" class="mb-0 mt-1 page-header-breadcrumb"/>
	</div>
</template>

<script>
export default {
	name: 'app-page-header',
	props: {
		title: String,
		breadcrumb: {
			type: Boolean,
			default: true
		}
	},
	computed: {
		itemBreadcrumbs() {
			if (!this.breadcrumb) {
				return [];
			}

			let breadcrumbs = [{text: 'Dashbard', to: {name: 'dashboard.index'}}];
			this.$route.matched.forEach(element => {
				breadcrumbs.push({
					text: element.meta 
						? element.meta.title 
							? element.meta.title
							: element.name
						: element.path,
					to: element.path
				});
			});
			return breadcrumbs;
		}
	}
}
</script>

<style lang="scss">
.page-header {
	border-bottom: 1px solid #f0f0f0;
	padding-bottom: 10px;
}
.page-header-title {
	font-size: 20px;
}
.page-header-breadcrumb {
	background: transparent;
	padding: 0;
}
</style>
