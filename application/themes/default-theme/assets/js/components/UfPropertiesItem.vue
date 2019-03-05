<template>
	<v-list-tile class="uf-properties__item" v-if="title || hasSlotTitle">
		<v-list-tile-avatar size="">
			{{label}}
		</v-list-tile-avatar>
		<v-list-tile-content>
			<v-list-tile-title>
				<template v-if="$slots.title">
					<slot name="title"></slot>
				</template>
				<template v-else>
					{{title}}
				</template>
			</v-list-tile-title>
		</v-list-tile-content>
	</v-list-tile>
</template>

<script>
export default {
	name: 'uf-properties-item',
	props: {
		label: String,
		title: String,
	},
	computed: {
		hasSlotTitle() {
			if (this.$slots.title) {
				return true;
			}
			return false;
		}
	}
}
</script>
