<template>
	<div class="v-snackbars">
		<v-snackbar
			v-model="item.snackbar"
			:color="item.color"
			:bottom="item.y === 'bottom'"
			:left="item.x === 'left'"
			:multi-line="item.mode === 'multi-line'"
			:right="item.x === 'right'"
			:timeout="item.timeout"
			:top="item.y === 'top'"
			:vertical="item.vertical">
			{{ item.text }}
			<v-btn
				flat
				@click="item.snackbar = false">
				Close
			</v-btn>
		</v-snackbar>
	</div>
</template>

<script>
export default {
	data () {
		return {
			item: {
				snackbar: false,
        y: 'top',
        x: null,
				timeout: 6000,
				color: 'primary',
				text: 'Hello, I\'m a snackbar',
				vertical: false
			}
		}
	},
	methods: {
		open (message, color, options) {
			if (!this.$parent) {
				this.$mount();
				document.querySelector("#app").appendChild(this.$el);
			};
			if (message) {
				this.item.text = message;
			}

			if (color) {
				this.item.color = color;
			}

			if (typeof options !== "undefined") {
				if (options.hasOwnProperty('timeout')) {
					this.item.timeout = options.timeout;
				}
			}
			this.item.snackbar = true;
		},
		success (message, options) {
			this.open(message, 'success', options);
		},
		error (message, options) {
			this.open(message, 'error', options);
		},
		warning (message, options) {
			this.open(message, 'warning', options);
		},
		show(message, options) {
			if (options.type && options.type === 'success') {
				this.success(message, options);
			} else if (options.type && options.type === 'error') {
				this.error(message, options);
			} else if (options.type && options.type === 'warning') {
				this.warning(message, options);
			}
		},
		hide() {
			this.snackbar = false;
		}
	}
}
</script>
