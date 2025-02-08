<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="submitParams">

			<card>
				<template v-slot:header>
					Bank Posting Information
					<div class="float-right">
						<action-button type="submit" :disabled="loading" class="btn btn-primary btn-sm">Save Changes</action-button>
					</div>
				</template>
				<div class="card">
					<div class="card-header p-2">	
						<div class="row">
							<div class="col-md-9">
								<ul class="nav nav-pills">
									<li class="nav-item"><a class="nav-link active" href="#bank_account" data-toggle="tab">Bank Posting</a></li>
									<!-- <li class="nav-item"><a class="nav-link" href="#audit" data-toggle="tab">Audit Trail</a></li> -->
								</ul>
							</div>
						</div>
				    </div>

				     <div class="card-body">
				        <div class="tab-content">
				        	<div class="tab-pane show active" id="bank_account">
				        		<div class="row">
									<div class="col-md-6">
										<div class="form-group">
										<h4><i class="fas fa-user-tie"></i> General Information</h4><hr>
										</div>

										<div class="form-group">
											<label>Client <b class="text-danger">*</b></label>
											<v-select v-model="item.client_id" :reduce="item => item.id" label="name" placeholder="Select Client" :options="clients"></v-select>
										</div>

										<div class="form-group">
			        		    			<label for="bank_transaction_posting">Bank Transaction Posting <span class="text-danger">*</span></label>
											<input id="bank_transaction_posting" name="bank_transaction_posting" type="text" class="form-control" v-model="item.bank_transaction_posting">
										</div>

										<div class="form-group">
			        		    			<label for="description">Description</label>
											<input id="description" name="description" type="text" class="form-control" v-model="item.description">
										</div>

										<div class="form-group">
											<label for="document">Document <span class="text-danger">*</span></label>
											<select class="form-control" v-model="item.document">
												<option value="1">Bank Statement Line</option>
												<option value="2">Cash Register Transaction</option>
											</select>
										</div>

										<template v-if="item.document == '1'">
											<div class="form-group">
												<label>Statement Line Adjustments <b class="text-danger">*</b></label>
												<v-select 
												v-model="item.bank_statement_line_adjustment_id" 
												:reduce="item => item.id" 
												label="bank_statement_adjustment_id" 
												placeholder="Select Statement Line" 
												:options="statement_lines"
												></v-select>
											</div>
										</template>

										<template v-if="item.document == '2'">
											<div class="form-group">
												<label>Cash Register <b class="text-danger">*</b></label>
												<v-select 
												v-model="item.cash_register_adjustment_id" 
												:reduce="item => item.id" 
												label="cashflow_adjustment_id" 
												placeholder="Select Cash Registere" 
												:options="cash_registers"
												></v-select>
											</div>
										</template>

										<div class="form-group">
											<label>Bank Posting <b class="text-danger">*</b></label>
											<v-select 
											v-model="item.bank_posting" 
											:reduce="item => item.main_account_id" 
											label="main_account_name" 
											placeholder="Select Main Account" 
											:options="main_accounts"
											></v-select>
										</div>

										<div class="form-group">
			        		    			<label for="bank_posting_code_number">Bank Posting Code Number <span class="text-danger">*</span></label>
											<input id="bank_posting_code_number" name="bank_posting_code_number" type="text" class="form-control" v-model="item.bank_posting_code_number">
										</div>

									</div>

									<div class="col-sm-6">
										<div class="form-group">
											<h4><i class="fas fa-user-tie"></i> Audit</h4><hr>
			        		    			<label>Created By</label>
	 										<input readonly :value="item.created_by" type="text" class="form-control mb-2">
			        		    	
			        		    			<label>Created On</label>
											<input readonly :value="item.created_date" type="text" class="form-control mb-2">
			        		    		
			        		    			<label>Updated By</label>
											<input readonly :value="item.updated_by" type="text" class="form-control mb-2">
			        		    	
			        		    			<label>Updated on</label>
			        		                <input readonly :value="item.updated_date" type="text" class="form-control">
										</div>
		        		    		</div>
			        		    </div>
				        	</div>

			        	</div>
		        	</div>

				</div>
			</card>


			<loader 
	        :loading="loading">
	        </loader>
		</form-request>
	</div>
</template>

<script>

	import CrudMixin from 'Mixins/crud.js';
	import Card from 'Components/containers/Card.vue';
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import Datepicker from 'vuejs-datepicker';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import { ModelListSelect } from 'vue-search-select';
	import Vselect from 'vue-select';

	import flatpickr from 'flatpickr';
	import 'flatpickr/dist/flatpickr.css';

	import DataTable from 'Components/tables/StaticDataTable.vue';
	
	export default {
		mixins: [ CrudMixin ],

		watch: {
			'item.bank_posting'(main_account_id) {
				let result = this.main_accounts.find((account) => {
					return account.main_account_id == main_account_id;
				});

				if(result) {
					this.item.bank_posting_code_number = result.main_account_code_number;
				}
			},
		},

		data() {
			return {
				statement_lines: [],
				cash_registers: [],
				main_accounts: [],
				item: {},
			}
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.statement_lines = data.statement_lines ? data.statement_lines : this.statement_lines;
				this.cash_registers = data.cash_registers ? data.cash_registers : this.cash_registers;
				this.main_accounts = data.main_accounts ? data.main_accounts : this.main_accounts;
			},
		},

		computed: {
			submitParams() {
				let item = this.item;
				return item;
			},
		},

		props: {
            clients: {
                type: Array,
                default: () => [],
            },
        },

		components: {
			'model-list-select': ModelListSelect,
			'action-button': ActionButton,
			'form-request': FormRequest,
			'text-editor': TextEditor,
			'datepicker': Datepicker,
			'data-table': DataTable,
			'v-select': Vselect,
			'card': Card,
		},
	}

</script>