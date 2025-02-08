<template>
	<div>
		<select class="form-control" v-model="selected" @change="change">
		    <option value="All">All</option>
		    <option value="Open">Open</option>
		    <option value="Posted">Posted</option>
		</select>
	</div>
</template>

<script>
	export default {
		methods: {
		    change() {
		        this.$emit('change', this.selected);
		    },
		},

		data() {
			return {
				selected: 'All'
			}
		}

	}
</script>