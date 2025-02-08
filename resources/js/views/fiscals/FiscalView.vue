<template>
	<div>
		<form-request :submit-url="submitUrl">
			<card>
				<template v-slot:header>Fiscal Year Information</template>
				<div class="row mb-2">
					<div class="form-group col-md-12">
						<div class="custom-control custom-switch">
							<input
							v-model="item.copy_last_fiscal_year"
							name="copy_last_fiscal_year" :checked="item.copy_last_fiscal_year" type="checkbox" class="custom-control-input" id="copy_last_fiscal_year">
							<label class="custom-control-label" for="copy_last_fiscal_year">Copy from fiscal year</label>
						</div>
					</div>
				</div>

				<div class="row">
		    		<div class="form-group col-sm-3">
		    			<label>Fiscal year name</label>
		                <input name="year_name" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-3">
		    			<label>Length of period</label>
		                <input name="length_period" type="number" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-3">
		    			<label>Unit</label>
		    			<select class="form-control">
		    				<option value="Months">Months</option>
		    			</select>
		    		</div>
				</div>

				<div class="row">
		    		<div class="form-group col-sm-6">
		    			<label>Start date</label>
		                <datepicker format="M/dd/yyyy"  v-model="item.start_date" input-class="form-control input-sm"></datepicker>
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>End date</label>
		                <datepicker format="M/dd/yyyy"  v-model="item.end_date" input-class="form-control input-sm"></datepicker>
		    		</div>
				</div>

				<div class="row">
					<text-editor
					v-model="item.description"
					class="col-sm-12"
					label="Description"
					name="Description"
					row="5"
					></text-editor>
				</div>

				<template v-slot:footer>
					<button type="submit" class="btn btn-sm btn-primary">SAVE</button>
				</template>
			</card>
			
		</form-request>
	</div>
</template>

<script>
	import Card from 'Components/containers/Card.vue';
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import Datepicker from 'vuejs-datepicker';

	export default {
		props: {
			submitUrl: String
		},

		components: {
			Card,
			'text-editor': TextEditor,
			'form-request': FormRequest,
		    'datepicker': Datepicker,
		},

		data() {
			return {
				item: {}
			}
		},

		methods: {
			buttonClick(params) {
				console.log(params)	
			}
		}
	}
</script>