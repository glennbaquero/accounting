<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<template v-slot:header>Main Account Information</template>

				<div class="row">
					<div class="form-group col-sm-3">
	    				<label>Client</label>
						<v-select @search:focus="clear()" disabled v-model="item.client_id" :reduce="item => item.id" name="client_id" label="name" placeholder="Select Client" :options="clients"></v-select>
						<input type="hidden" name="client_id" v-model="item.client_id"> 	
	    			</div>
		    		<div class="form-group col-sm-3">
		    			<label>Chart of accounts ID</label>
		                <input name="coa_id" v-model="coaId.coa_id" class="form-control"readonly >
		            </div>
		    		<div class="form-group col-sm-3">
		    			<label>Chart of Accounts Code</label>
		                <input name="coa_code" v-model="coaId.coa_code" class="form-control" readonly >
		            </div>
		    		<div class="form-group col-sm-3">
		    			<label>Chart of Accounts</label>
		                <input name="coa_name" v-model="coaId.coa_name" class="form-control" readonly >
						<input name="chart_of_account_id" v-model="coaId.id" class="form-control" hidden >
		            </div>

		    		<div class="form-group col-sm-6">
		    			<label>Main Account ID</label>
		                <input name="main_account_id" v-model="item.main_account_id" class="form-control" readonly >
		            </div>
		    		<div class="form-group col-sm-6">
		    			<label>Main Account Code Number</label>
		                <input name="main_account_code_number" type="text" v-model="item.main_account_code_number" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Main Account Code</label>
		                <input name="main_account_code" type="text" v-model="item.main_account_code" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-6">
		    			<label>Main Account Name</label>
		                <input name="main_account_name" type="text" v-model="item.main_account_name" class="form-control">
		    		</div>
					<!--<div class="form-group col-sm-12">
		    			<label>Description</label>
 		                <textarea name="description" type="text" v-model="item.description" class="form-control" />
		    		</div>		    		 -->
						<text-editor
							v-model="item.description"
							class="col-sm-12"
							label="Description"
							name="description"
							row="5"
						></text-editor>

				</div>

				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>General</b></h3>

			            <div class="card-tools">
			              	<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
				            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
				        </div>
				    </div>
				    <div class="card-body">
				    	<div class="row">
				    		<div class="col-sm-6" style="margin-left: -13px;">
				    			<div class="form-group col-sm-12">
				    				<label>Ledger</label>
				    			</div>
				    			<div class="form-group col-sm-12">
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
				    			<div class="form-group col-sm-12">
				    				<label>Reporting type</label>
				    	            <select name="reporting_type" v-model="item.reporting_type" class="form-control">
				    	            	<option value="Debit">Debit</option>
				    	            	<option value="Credit">Credit</option>
				    	            </select>
				    			</div>				    		

<!-- 				    			<div class="form-group col-sm-12">
				    				<label>Main account category</label>
									<model-list-select :list="main_account_categories"
									class="main_account_category"
									label="Main account category"		
									name="main_account_category"					
									v-model="item.main_account_category"
									option-value="id"
									option-text="main_account_category"
									placeholder="Please select a Main account category"
									>
									</model-list-select>	
									<input name="main_account_category" hidden v-model="item.main_account_category"> 

								</div>	 -->


				    			<div class="form-group col-sm-12">
				    				<label>Main account category</label>
				    	            <select name="main_account_category_id" v-model="item.main_account_category" class="form-control">
		    			    		<option v-for="mac in main_account_categories" :value="mac.id">{{ mac.main_account_category }}</option>
				    	            </select>
				    			</div>	
				    			<div class="form-group col-sm-12">
				    				<label>DB/CR proposal</label>
				    	            <select name="db_cr_proposal" v-model="item.db_cr_proposal" class="form-control">
				    	            	<option value="Debit">Debit</option>
				    	            	<option value="Credit">Credit</option>
				    	            </select>
				    			</div>
				    			<div class="form-group col-sm-12">
				    				<label>DB/CR requirements</label>
				    	            <select name="db_cr_requirement" v-model="item.db_cr_requirement" class="form-control">
				    	            	<option value="Debit">Debit</option>
				    	            	<option value="Credit">Credit</option>
				    	            </select>
				    			</div>
				    			<div class="form-group col-sm-12">
				    				<label>Balance control</label>
				    	            <select name="balance_control" v-model="item.balance_control" class="form-control">
				    	            	<option value="Debit">Debit</option>
				    	            	<option value="Credit">Credit</option>
				    	            </select>
				    			</div>


				    			<div class="form-group col-sm-12 mt-3">
				    				<label>Related accounts</label>
				    			</div>
				    			<div class="form-group col-sm-12">
				    				<label>Offset account</label>
				    	            <select name="offset_account" v-model="item.offset_account" class="form-control">
				    	            	<option value="Debit">Debit</option>
				    	            	<option value="Credit">Credit</option>
				    	            </select>
				    			</div>
				    			<div class="form-group col-sm-12">
				    				<label>Opening account</label>
				    	            <select name="opening_account" v-model="item.opening_account" class="form-control">
				    	            	<option value="Debit">Debit</option>
				    	            	<option value="Credit">Credit</option>
				    	            </select>
				    			</div>
<!-- 				    			<div class="form-group col-sm-12">
				    				<label>SRU code</label>
				    	            <input name="sru_code" v-model="item.sru_code" class="form-control">
				    			</div> -->
				    		</div>
				    		<div class="col-sm-6">
				    			<div class="form-group col-sm-12">
				    				<label>Administration</label>
				    			</div>
				    			<div class="form-group col-sm-12">
				    				<label>Do not allow manual entry <input type="checkbox" :true-value="1" :false-value="0" name="do_not_allow_manual_entry" v-model="item.do_not_allow_manual_entry"></label>
				    			</div>
				    			<div class="form-group col-sm-12">
				    				<label>Active from</label>
									<input ref="active_from" type="text" class="form-control calendar-form" name="active_from" v-model="item.active_from" readonly>				    				
				    			</div>
				    			<div class="form-group col-sm-12">
				    				<label>Active to</label>
									<input ref="active_to" type="text" class="form-control calendar-form" name="active_to" v-model="item.active_to" readonly>
				    			</div>
				    			<div class="form-group col-sm-12">
				    				<label>Suspended <input type="checkbox" :true-value="1" :false-value="0" name="suspended" v-model="item.suspended"></label>
				    			</div>
				    			<div class="form-group col-sm-12">
				    				<label>Monetory <input type="checkbox" :true-value="1" :false-value="0" name="monetary" v-model="item.monetary"></label>
				    			</div>				    			
				    			<div class="form-group col-sm-12">
				    				<label>Close</label>
				    	            <select name="close" v-model="item.close" class="form-control">
				    	            	<option value="Debit">Debit</option>
				    	            	<option value="Credit">Credit</option>
				    	            </select>
				    			</div>
<!-- 				    			<div class="form-group col-sm-12">
				    				<label>Allocation <input type="checkbox" :true-value="1" :false-value="0" name="allocation" v-model="item.allocation"></label>
				    				<label>Does allocation term exist <input type="checkbox" :true-value="1" :false-value="0" name="allocation_term_exist"></label>
				    			</div>
				    			<div class="form-group col-sm-12">
				    				<label>Mandatory reference <input type="checkbox" :true-value="1" :false-value="0" name="mandatory_reference"></label>
				    			</div>
 -->

				    			<div class="form-group col-sm-12 mt-5">
				    				<label>Consolidation</label>
				    			</div>
				    			<div class="form-group col-sm-12">
				    				<label>Consolidation account</label>
				    	            <input name="default_consolidation_account" v-model="item.default_consolidation_account" class="form-control">
				    			</div>
				    		</div>
				    	</div>
				    </div>
				</div>

				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>Setup</b></h3>

			            <div class="card-tools">
			              	<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
				            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
				        </div>
				    </div>
				    <div class="card-body">
				    	<div class="row">
							<div class="form-group col-sm-12">
								<label>Sales tax</label>
							</div>
							<div class="form-group col-sm-12">
								<label>Posting type</label>
								<select class="form-control" name="posting_type" v-model="item.posting_type">
									<option value="Profit and loss">Profit and loss</option>
									<option value="Revenue">Revenue</option>
								</select>
							</div>
							<div class="form-group col-sm-12">
								<label>Validate posting</label>
								<select class="form-control" name="validate_posting" v-model="item.validate_posting">
									<option value="Profit and loss">Profit and loss</option>
									<option value="Revenue">Revenue</option>
								</select>
							</div>					    							    		
							<div class="form-group col-sm-12">
								<label>Sales tax group</label>
								<select class="form-control" name="sales_tax_group" v-model="item.sales_tax_group">
									<option value="Profit and loss">Profit and loss</option>
									<option value="Revenue">Revenue</option>
								</select>
							</div>
							<div class="form-group col-sm-12">
								<label>Item sales tax group</label>
								<select class="form-control" name="item_sales_tax_group" v-model="item.item_sales_tax_group">
									<option value="Profit and loss">Profit and loss</option>
									<option value="Revenue">Revenue</option>
								</select>
							</div>
							<div class="form-group col-sm-12">
								<label>Sales tax direction</label>
								<select class="form-control" name="sales_tax_direction" v-model="item.sales_tax_direction">
									<option value="Profit and loss">Profit and loss</option>
									<option value="Revenue">Revenue</option>
								</select>
							</div>

							<div class="form-group col-sm-12">
								<label>Exempt <input type="checkbox" :true-value="1" :false-value="0" name="exempt" v-model="item.exempt"></label>
							</div>
							<div class="form-group col-sm-12">
								<label>Sales tax code</label>
								<select class="form-control" name="sales_tax_code" v-model="item.sales_tax_code">
									<option value="Profit and loss">Profit and loss</option>
									<option value="Revenue">Revenue</option>
								</select>
							</div>
							<div class="form-group col-sm-12">
								<label>Validate sales tax</label>
								<select class="form-control" name="validate_sales_tax" v-model="item.validate_sales_tax">
									<option value="Profit and loss">Profit and loss</option>
									<option value="Revenue">Revenue</option>	
								</select>
							</div>
				    	</div>
				    </div>
				</div>

				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>Financial statement</b></h3>

			            <div class="card-tools">
			              	<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
				            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
				        </div>
				    </div>
				    <div class="card-body">
				    	<div class="row">
			    			<div class="form-group col-sm-12">
			    				<label>Special report</label>
			    			</div>
			    			<div class="form-group col-sm-6">
			    				<label>Invert sign <input type="checkbox" :true-value="1" :false-value="0" name="invert_sign" v-model="item.invert_sign"></label>
			    			</div>
			    			<div class="form-group col-sm-6 text-nowrap">
			    				<label>Column</label>
			    				<input type="number" name="column" v-model="item.column" class="form-control w-25 d-inline">
			    			</div>
			    			<div class="form-group col-sm-6">
			    				<label>Line above <input type="checkbox" :true-value="1" :false-value="0" name="line_above" v-model="item.line_above"></label>
			    			</div>
			    			<div class="form-group col-sm-6">
			    				<label>Line below <input type="checkbox" :true-value="1" :false-value="0" name="line_below" v-model="item.line_below"></label>
			    			</div>
			    			<div class="form-group col-sm-6">
			    				<label>Italics <input type="checkbox" :true-value="1" :false-value="0" name="italics" v-model="item.italics"></label>
			    			</div>
			    			<div class="form-group col-sm-6">
			    				<label>Underline text <input type="checkbox" :true-value="1" :false-value="0" name="underline_text" v-model="item.underline_text"></label>
			    			</div>			    			
			    			<div class="form-group col-sm-6">
			    				<label>Underline amount <input type="checkbox" :true-value="1" :false-value="0" name="underline_amount" v-model="item.underline_amount"></label>
			    			</div>
				    	</div>
				    </div>
				</div>

				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>Audit Trail</b></h3>

			            <div class="card-tools">
			              	<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
				            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
				        </div>
				    </div>
				    <div class="card-body">
				    	<div class="row">
					    		<div class="form-group col-sm-12">
	    		    			<label>Created By</label>
									<input readonly v-model="created_by" type="text" class="form-control">
							</div>
					    		<div class="form-group col-sm-12">
	    		    			<label>Created On</label>
								<input readonly v-model="item.formatted_created_at" type="text" class="form-control">
							</div>
    		    		
					    		<div class="form-group col-sm-12">
	    		    			<label>Updated By</label>
								<input readonly v-model="updated_by" type="text" class="form-control">
							</div>    		    	
					    		<div class="form-group col-sm-12">
	    		    			<label>Updated on</label>
	    		                <input readonly v-model="item.formatted_updated_at" type="text" class="form-control">
	    		            </div>				    		
				    	</div>
				    </div>
				</div>
				<template v-slot:footer>
					<action-button type="submit" :disabled="loading" class="btn btn-sm btn-primary">Save Changes</action-button>
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
	import { ModelListSelect } from 'vue-search-select'

	import Datepicker from 'vuejs-datepicker';
	import flatpickr from 'flatpickr';
	import Vselect from 'vue-select';
	import 'flatpickr/dist/flatpickr.css';

	export default {
		props: {
			coaId: Object,
			submitUrl: String,
		},

		components: {
			ModelListSelect,			
			Card,
			'v-select': Vselect,
			'text-editor': TextEditor,
			'form-request': FormRequest,
		    'datepicker': Datepicker,
			'action-button': ActionButton,
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.main_account_categories = data.main_account_categories ? data.main_account_categories.filter(item => item.client_id == this.coaId.client_id) : this.main_account_categories;
				this.clients = data.clients ? data.clients : this.clients;
			},
		},

		data() {
			return {
				created_by : null,
				updated_by : null,				
				item: [],	
				main_account_categories: [],
				clients : [],		
				item: {
					main_account_id: null,
				}
			}
		},

		mounted() {
            let vm = this;

			this.item.client_id = this.coaId.client_id; 

			if(!this.item.main_account_id) {
				var date = new Date();
				var time = Math.round(date.getTime() / 1000);	
				this.item.main_account_id = date.getDate().toString() + (date.getMonth() + 1).toString() + date.getFullYear().toString() +'-'+ time.toString();
				this.item.main_account_id += "-" + Math.random().toString(36).substring(2, 6);
			}

			flatpickr(this.$refs.active_from)
			flatpickr(this.$refs.active_to)
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
