<template>
	<div class="form-group col-sm-3">
	    <label>{{ label }}</label>
	    <div class="input-group mb-2">
	        <div class="input-group-prepend">
	            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
	        </div>
	        <input ref="elem" type="text" class="form-control calendar-form" v-model="value" @change="change">
	    </div>
	</div>
</template>

<script type="text/javascript">
	export default {
		props: {
			label: String
		},

		data() {
			return {
				value: null
			}
		},

		mounted() {
			flatpickr(this.$refs.elem)
		},


		methods: {
			change() {
				setTimeout(() => {
			        this.$emit('change', this.value);
				})
			}
		}
	}
</script>