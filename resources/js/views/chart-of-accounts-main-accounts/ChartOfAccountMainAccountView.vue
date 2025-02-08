<template>
 <div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>Financial Dimension Value Information</b></h3>
				    </div>
				    <div class="card-body">
						<div class="row">
				    		<div class="form-group col-sm-6">
				    			<label>Chart of accounts - Main account ID</label>
				                <input type="text" name="coa_id" v-model="item.coa_id" class="form-control" readonly>
				    		</div>

				    		<div class="form-group col-sm-6">
				    			<label>Chart of accounts - Main account Code</label>
				                <input type="text" name="coa_code" v-model="item.coa_code" class="form-control">
				    		</div>

				    		<div class="form-group col-sm-6">
				    			<label>Chart of accounts - Main account Name</label>
				                <input type="text" name="coa_name" v-model="item.coa_name" class="form-control">
				    		</div>

				    		<div class="form-group col-sm-6">				    		
			    				<label>Main account type</label>
			    	            <select name="main_account_type" v-model="item.main_account_type" class="form-control">
			    	            	<option value="Profit and loss">Profit and loss</option>
			    	            	<option value="Revenue">Revenue</option>
			    	            	<option value="Expense">Expense</option>
			    	            	<option value="Balance sheet">Balance sheet</option>
			    	            	<option value="Asset">Asset</option>
			    	            	<option value="Liability">Liability</option>
			    	            	<option value="Equity">Equity</option>
			    	            	<option value="Total">Total</option>
			    	            	<option value="Reporting">Reporting</option>
			    	            	<option value="Common">Common</option>
			    	            </select>
			    	        </div>

				    		<div class="form-group col-sm-6">
				    			<label>Chart of accounts code</label>
				                <input type="text" name="coa_code" v-model="item.coa_code" class="form-control">
				                <input type="text" name="coa_id" v-model="item.coa_id" class="form-control" readonly>

				    		</div>

				    		<div class="form-group col-sm-6">
				    			<label>Chart of accounts</label>
				                <input type="text" name="coa_name" v-model="item.coa_name" class="form-control">
				    		</div>

				    		<div class="form-group col-sm-6">
				    			<label>Description</label>
				                <input type="text"  name="description" v-model="item.description" class="form-control">
				    		</div>		    

							<!-- <div class="form-group col-md-12">
								<div class="custom-control custom-switch">
									<input
									v-model="item.ledger_status"
									name="ledger_status" :checked="item.ledger_status" type="checkbox" class="custom-control-input" id="ledger_status" :true-value="1" :false-value="0">
									<label class="custom-control-label" for="ledger_status">Ledger Status</label>
								</div>
							</div> -->

						</div>
					</div>
				</div>

				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>Audit Trail</b></h3>

			            <div class="card-tools">
							<!-- <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
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
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';

	export default {
		props: {
			coamaId: String,
			submitUrl: String
		},

		components: {
			'text-editor': TextEditor,
			'form-request': FormRequest,
			'action-button': ActionButton
		},

		data() {
			var item = {};
			if (this.coamaId) {
				item['coa_id'] = this.coamaId; 
			}

			return {
				item: item
			}
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
			},
		},

		mixins: [ CrudMixin ]
	}
</script>