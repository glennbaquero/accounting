<template>
 <div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>Financial Dimension Value Information</b></h3>

			            <div class="card-tools">
<!-- 			              	<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
				            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button> -->
				        </div>
				    </div>
				    <div class="card-body">
						<div class="row">
				    		<div class="form-group col-sm-6">
				    			<label>Dimension Id</label>
				                <input type="text" name="financial_dimension" v-model="dimensionValue.financial_dimension" class="form-control" readonly>
							</div>
				    		<div class="form-group col-sm-6">
				    			<label>Dimension code</label>
				                <input type="text" name="dimension_code" v-model="dimensionValue.dimension_code" class="form-control" readonly>
							</div>										
				    		<div class="form-group col-sm-6">
				    			<label>Dimension code number</label>
				                <input type="text" name="dimension_code_number" v-model="dimensionValue.dimension_code_number" class="form-control" readonly>
							</div>														
				    		<div class="form-group col-sm-6">
				    			<label>Dimension name</label>
				                <input type="text" name="dimension_name" v-model="dimensionValue.dimension_name" class="form-control" readonly>
							</div>																				
				    		<div class="form-group col-sm-6">
				    			<label>Dimension value id</label>
				                <input type="text" name="financial_dimension_value_code" v-model="item.financial_dimension_value_code" class="form-control" readonly>
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<label>Dimension value name</label>
				                <input type="text" name="dimension_value_name" v-model="item.dimension_value_name" class="form-control">
				    		</div>		    		
				    		<div class="form-group col-sm-6">
				    			<label>Description</label>
				                <input type="text" name="description" v-model="item.description" class="form-control">
				    		</div>
				    		<div class="form-group col-sm-12">
				    			<label>Totals</label>
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<p>Active from</p>
				                <input name="active_from" type="text" v-model="item.active_from" class="form-control" ref="active_from">
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<p>Active to</p>
				                <input name="active_to" type="text" v-model="item.active_to" class="form-control" ref="active_to">
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<p>Suspended <input name="suspended_checkbox" v-model="item.suspended_checkbox" type="checkbox" class=""></p>
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<p>Calculate total from multiple dimension values <input name="calculate_total_from_multiple_dimension_values" v-model="item.calculate_total_from_multiple_dimension_values" type="checkbox" class=""></p>
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

	import flatpickr from 'flatpickr';
	import 'flatpickr/dist/flatpickr.css';

	export default {
		props: {
			dimensionValue: Object,
			fdvId: Number,
		},

		components: {
			Card,
			'text-editor': TextEditor,
			'form-request': FormRequest,
			'action-button': ActionButton,
		},

		data() {
			var item = {};
			if (this.fdvId) {
				item['financial_dimension_value_code'] = this.fdvId; 
			}			

			return {
				item: item,		
				created_by : null,
				updated_by : null,							

			}
		},

		mounted() {
			flatpickr(this.$refs.active_from)
			flatpickr(this.$refs.active_to)
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