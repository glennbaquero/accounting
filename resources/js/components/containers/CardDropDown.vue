<template>
	<div class="card card-default">
        <div class="card-header">
	        <h3 class="card-title"><b><i :class="icon"></i> {{ title }}</b></h3>

            <div class="card-tools">
              	<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
	        </div>
	    </div>
	    <div class="card-body">
	    	<div class="row">
	    		<div class="col-sm-4" v-for="input in inputs">
	    			<label>{{ input.label }}</label>
	    			<input type="text" name="" class="form-control input-sm mb-2" :readonly="!input.editable" v-model="item[input.model]" v-if="!input.isDropdownSelection && !input.isDate  && !input.isCheckbox">
	    			<input type="checkbox" name="" class="mt-4" :readonly="!input.editable" v-model="item[input.model]" v-if="!input.isDropdownSelection && !input.isDate && input.isCheckbox">
	    			<input type="text" class="form-control input-sm calendar-form mb-2" :readonly="!input.editable" v-model="item[input.model]" v-if="!input.isDropdownSelection && input.isDate && !input.isCheckbox" :id="input.ref">

	    			<select class="form-control input-sm" v-model="item[input.model]" v-if="input.isDropdownSelection">
	    				<option v-for="selection in input.selections" :value="selection[input.opt_value]">{{ selection[input.display] }}</option>
	    			</select>
	    		</div>
	    	</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: {
			icon: String,
			title: String,
			inputs: Array,
			selected: Object,
		},

		data() {
			return {
				item : this.selected
			}
		},

		watch: {
			selected(val) {
				this.item = val;
			}
		},

		mounted() {
			_.each(this.inputs, (input) => {
				if(input.isDate) {
					flatpickr('#'+input.ref);
				}
			})
		}

	}
</script>