<template>
	<v-breadcrumbs class="uf-breadcrumb" :items="items">
		<v-icon slot="divider">la-angle-right</v-icon>
	</v-breadcrumbs>
</template>

<script>
export default {
	name: 'uf-breadcrumb',
	computed: {
		items() {
			const that = this;
      let breadcrumbs = [{
				text: 'Home',
				disabled: false,
				to: '/'
      }];

      this.$route.matched.forEach(element => {
        breadcrumbs.push({
					text: element.meta.title,
					disabled: false,
					to: element.path
				})
			});
			breadcrumbs[breadcrumbs.length - 1].disabled = true;
      return breadcrumbs;
		}
	}
}
</script>
