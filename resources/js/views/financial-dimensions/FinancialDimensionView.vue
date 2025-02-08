<template>
 <div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>Financial Dimension</b></h3>

			            <div class="card-tools">
<!-- 			              	<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
				            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button> -->
				        </div>
				    </div>
				    <div class="card-body">
						<div class="row">
				    		<div class="form-group col-sm-6">
				    			<label>Use value from</label>
				    			<select name="use_value_from" v-model="item.use_value_from" class="form-control">
				    			    <option v-for="from in values_from" :value="from.label">{{ from.label }}</option>
				    			</select>
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<label>Dimension code</label>
				                <input name="dimension_code" type="text" v-model="item.dimension_code" class="form-control">
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<label>Dimension name</label>
				                <input name="dimension_name" type="text" v-model="item.dimension_name" class="form-control">
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<label>Report column name</label>
				                <input name="report_column_name" type="text" v-model="item.report_column_name" class="form-control">
				    		</div>
				    		<div class="form-group col-sm-12">
				    			<label>Administration</label>
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<label>Dimension value mask</label>
				                <input name="dimension_value_mask" type="text" v-model="item.dimension_value_mask" class="form-control">
				    		</div>
<!-- 				    		<div class="form-group col-sm-6">
				    			<label>Dimension value mask</label>
				    			<select class="form-control" name="require_values_for_the_dimension_to_be_balanced_with" v-model="item.require_values_for_the_dimension_to_be_balanced_with">
				    				<option></option>
				    			</select>
				    		</div> -->
				    		<div class="form-group col-sm-12">
				    			<p><label style="margin-right: 0.5em;"> Require the dimensions to be balanced </label><input name="require_balanced_dimension" v-model="item.require_balanced_dimension" type="checkbox" class=""></p>
				    		</div>				    		

						</div>
					</div>
				</div>

				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>Audit Trail</b></h3>

			            <div class="card-tools">
<!-- 			              	<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
				            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button> -->
				        </div>
				    </div>
				    <div class="card-body">
				    	<div class="row">
					    		<div class="form-group col-sm-6">
	    		    			<label>Created By</label>
									<input readonly v-model="created_by" type="text" class="form-control">
							</div>
					    		<div class="form-group col-sm-6">
	    		    			<label>Created On</label>
								<input readonly v-model="item.formatted_created_at" type="text" class="form-control">
							</div>
    		    		
					    		<div class="form-group col-sm-6">
	    		    			<label>Updated By</label>
								<input readonly v-model="updated_by" type="text" class="form-control">
							</div>    		    	
					    		<div class="form-group col-sm-6">
	    		    			<label>Updated on</label>
	    		                <input readonly v-model="item.formatted_updated_at" type="text" class="form-control">
	    		            </div>				    		
				    	</div>
				    </div>
				</div>				
				<template v-slot:footer>
					<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
				</template>
			</card>
			
		</form-request>
 </div>
</template>

<script>
	import CrudMixin from 'Mixins/crud.js';

	import Card from 'Components/containers/Card.vue';
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';

	export default {

		components: {
			Card,
			'text-editor': TextEditor,
			'form-request': FormRequest,
			'action-button': ActionButton,
		},

		data() {
			return {
				item: {},
				created_by : null,
				updated_by : null,				
				values_from: [
					{
						label: 'Bank account',
					},
					{
						label: 'Business units',
					},
					{
						label: 'Campaigns',
					},
					{
						label: 'Cost centers',
					},
					{
						label: 'Customer groups',
					},
					{
						label: 'Customers',
					},
					{
						label: 'Departments',
					},
					{
						label: 'Expense purposes',
					},
					{
						label: 'Fixed asset groups',
					},
					{
						label: 'Fixed assets',
					},
					{
						label: 'Item groups',
					},
					{
						label: 'Items',
					},
					{
						label: 'Jobs',
					},
					{
						label: 'Legal entities',
					},
					{
						label: 'Positions',
					},
					{
						label: 'Project contracts',
					},
					{
						label: 'Project groups',
					},
					{
						label: 'Projects',
					},
					{
						label: 'Propects',
					},
					{
						label: 'Resource groups',
					},
					{
						label: 'Resources',
					},
					{
						label: 'Value streams',
					},
					{
						label: 'Vendor groups',
					},
					{
						label: 'Vendor',
					},
					{
						label: 'Worker',
					},
				]
			}
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
			},
		},

		mixins: [ CrudMixin ],

		watch: {

			'item.created_by'(val) {
				this.created_by = val.fullname;
			},

			'item.updated_by'(val) {
				this.updated_by = val.fullname;
			},

			'item.confirm_by'(val) {
				if(val) {
					this.confirm_by = val.fullname ? val.fullname : null;
				}
			},
			
		},		
	}
</script>