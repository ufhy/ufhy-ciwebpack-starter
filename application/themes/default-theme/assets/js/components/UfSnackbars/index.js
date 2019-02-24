import UfSnackbars from './UfSnackbars.vue';

UfSnackbars.install = (Vue, options) => {
	Vue.prototype.$ufsnackbars = new (Vue.extend(UfSnackbars))({
		propsData: options
	});
	Vue.ufsnackbars = Vue.prototype.$ufsnackbars
}

export default UfSnackbars;