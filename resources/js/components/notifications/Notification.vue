<!-- for more documentation https://www.npmjs.com/package/vue-notification -->
<template>
	<notifications group="notification" :position="position"/>
</template>
<script>
	import Vue from 'vue';
	export default {
		mounted() {
			this.init();
		},

		data() {
			return {
				position: 'top right'
			}
		},

		methods: {
			init:function() {
				if(typeof(Vue.prototype.notification) !== 'object') {
					Vue.prototype.$notification = {};
				}

				Vue.prototype.$notification = this;
			},

			show(text, title = "Ooops", type = 'success', position = 'top right', options = {}) {
				this.position = position;
				this.$notify({
				  group: 'notification',
				  type: type,
				  title: title,
				  text: text,
				  duration: 10000,
				  speed: 1000,
				  data: {}
				});
			}

		}
	}
</script>