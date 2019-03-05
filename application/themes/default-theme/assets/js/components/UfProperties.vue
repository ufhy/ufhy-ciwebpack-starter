<template>
	<v-list dense class="uf-properties">
		<v-list-tile class="uf-properties__heading">
			<v-list-tile-avatar v-if="icon">
				<v-icon>{{icon}}</v-icon>
			</v-list-tile-avatar>
			<v-list-tile-content>
				<v-list-tile-title>{{title}}</v-list-tile-title>
				<v-list-tile-sub-title>{{subtitle}}</v-list-tile-sub-title>
			</v-list-tile-content>
		</v-list-tile>
		<slot></slot>
	</v-list>
</template>

<script>
export default {
	name: 'uf-properties',
	props: {
		title: String,
		subtitle: String,
		icon: String
	}
}
</script>
