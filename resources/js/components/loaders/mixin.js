export default {
	methods: {
		load(value) {
			this.$loading.show(value);
		},
	},

	data() {
		return {
			loading: false,
		}
	},
}