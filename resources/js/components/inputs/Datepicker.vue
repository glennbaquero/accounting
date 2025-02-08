<template>
	<div class="form-group" :class="column">
		<label>{{ label }} <b class="text-danger" v-if="required">*</b></label>
		<div class="input-group mb-2">
			<div class="input-group-prepend">
				<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
			</div>
			<input ref="elem" type="text" class="form-control" :name="name" :value="value" :disabled="disabled">
		</div>
	</div>
</template>

<script>
	export default {
		props: {
			name: String,
			label: String,
			column: {
				default: 'col-md-6',
				type: String
			},
			disabled: {
				default: false,
				type: Boolean
			},
			value: {},

			required: {
				type: Boolean,
				default: true
			}
		},

		model: {
			prop: 'value',
		},

		mounted() {
			flatpickr(this.$refs.elem)
		},
	}
</script>