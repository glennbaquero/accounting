<template>
	<div class="form-group" :class="column">
		<label>{{ label }} <b class="text-danger" v-if="required">*</b></label>
		<input :type="type" class="form-control" :value="value" :name="name" :readonly="readonly">
	</div>
</template>

<script>
	export default {
		props: {
			name: String,
			label: String,
			type: {
				default: 'text',
			},
			column: {
				default: 'col-md-6',
				type: String
			},
			readonly: {
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

		watch: {
            value() {
                this.$emit('input', this.value);
            }
        }
	}
</script>