<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="submitParams">

			<card>
				<template v-slot:header>
					Bank Posting Information
					<div class="float-right">
						<button type="button" :disabled="loading" class="btn btn-secondary btn-sm">Approved</button>
						<action-button type="submit" :disabled="loading" class="btn btn-primary btn-sm">Save Changes</action-button>
					</div>
				</template>
				<div class="card">
					<div class="card-header p-2">	
						<div class="row">
							<div class="col-md-9">
								<ul class="nav nav-pills">
									<li class="nav-item"><a class="nav-link active" href="#bank_account" data-toggle="tab">Bank Posting</a></li>
									<li class="nav-item"><a class="nav-link" href="#audit" data-toggle="tab">Audit Trail</a></li>
								</ul>
							</div>
						</div>
				    </div>

				     <div class="card-body">
				        <div class="tab-content">
				        	<div class="tab-pane show active" id="bank_account">
				        		<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<h4><i class="fas fa-user"></i> Header</h4><hr>
										</div>

										<div class="form-group">
											<label>Client <b class="text-danger">*</b></label>
											<v-select v-model="item.client_id" :reduce="item => item.id" label="name" placeholder="Select Client" :options="clients"></v-select>
										</div>

										<div class="form-group">
			        		    			<label for="bank_reconciliation_id">Bank Reconciliation ID</label>
											<input id="bank_reconciliation_id" name="bank_reconciliation_id" type="text" class="form-control" :value="item.bank_reconciliation_id" disabled>
										</div>

										<div class="form-group">
			        		    			<label for="name">Name <span class="text-danger">*</span></label>
											<input id="name" name="name" type="text" class="form-control" v-model="item.name">
										</div>

										<div class="form-group">
			        		    			<label for="description">Description <span class="text-danger">*</span></label>
											<input id="description" name="description" type="text" class="form-control" v-model="item.description">
										</div>

										<div class="form-group">
                                            <label for="reconciled_date">Reconciled Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="reconciled_date" type="text" class="form-control" id="reconciled_date" name="reconciled_date" :value="item.reconciled_date" disabled>
                                            </div>
                                        </div>

										<div class="form-group">
			        		    			<label for="reconciled_by">Reconciled By</label>
											<input id="reconciled_by" name="reconciled_by" type="text" class="form-control" :value="item.reconciled_by" disabled>
										</div>

										<div class="form-group">
                                            <label>Reconciled</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            <input type="checkbox" class="custom-control-input" id="reconciled_checkbox" name="reconciled_checkbox" :value="item.reconciled_checkbox" disabled>
                                                <label class="custom-control-label" for="reconciled_checkbox">
                                                    <span class="badge" :class="item.reconciled_checkbox ? 'badge-success' : 'badge-danger'">
                                                        {{ item.reconciled_checkbox ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="posted_date">Posted Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="posted_date" type="text" class="form-control" id="posted_date" name="posted_date" :value="item.posted_date" disabled>
                                            </div>
                                        </div>

										<div class="form-group">
			        		    			<label for="posted_by">Posted By</label>
											<input id="posted_by" name="posted_by" type="text" class="form-control" :value="item.posted_by" disabled>
										</div>

										<div class="form-group">
                                            <label>Posted</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            <input type="checkbox" class="custom-control-input" id="posted_checkbox" name="posted_checkbox" :value="item.posted_checkbox" disabled>
                                                <label class="custom-control-label" for="posted_checkbox">
                                                    <span class="badge" :class="item.posted_checkbox ? 'badge-success' : 'badge-danger'">
                                                        {{ item.posted_checkbox ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="approved_date">Approved Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="approved_date" type="text" class="form-control" id="approved_date" name="approved_date" :value="item.approved_date" disabled>
                                            </div>
                                        </div>

										<div class="form-group">
			        		    			<label for="approved_by">Approved By</label>
											<input id="approved_by" name="approved_by" type="text" class="form-control" :value="item.approved_by" disabled>
										</div>

										<div class="form-group">
                                            <label>Approved</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            <input type="checkbox" class="custom-control-input" id="approved_checkbox" name="approved_checkbox" :value="item.approved_checkbox" disabled>
                                                <label class="custom-control-label" for="approved_checkbox">
                                                    <span class="badge" :class="item.approved_checkbox ? 'badge-success' : 'badge-danger'">
                                                        {{ item.approved_checkbox ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
			        		    			<label for="ending_balance">Ending Balance <span class="text-danger">*</span></label>
											<input id="ending_balance" name="ending_balance" type="text" class="form-control" v-model="item.ending_balance">
										</div>

										<div class="form-group">
			        		    			<label for="reconciled_transactions">Reconciled Transactions <span class="text-danger">*</span></label>
											<input id="reconciled_transactions" name="reconciled_transactions" type="text" class="form-control" v-model="item.reconciled_transactions">
										</div>

										<div class="form-group">
			        		    			<label for="unreconciled_transactions">Unreconciled Transactions <span class="text-danger">*</span></label>
											<input id="unreconciled_transactions" name="unreconciled_transactions" type="text" class="form-control" v-model="item.unreconciled_transactions">
										</div>

									</div>

									<div class="col-md-4">
										<div class="form-group">
											<h4><i class="fas fa-file-invoice-dollar"></i> Bank Statement</h4><hr>
										</div>

										<div class="form-group">
											<label>Client Bank Account <b class="text-danger">*</b></label>
											<v-select v-model="item.client_bank_account" :reduce="item => item.bank_account" label="bank_name" placeholder="Select Client" :options="client_banks"></v-select>
										</div>

										<div class="form-group">
			        		    			<label for="bank_account_number">Bank Account Number <span class="text-danger">*</span></label>
											<input id="bank_account_number" name="bank_account_number" type="text" class="form-control" v-model="item.bank_account_number">
										</div>

										<div class="form-group">
			        		    			<label for="bank_account_type">Bank Account Type <span class="text-danger">*</span></label>
											<input id="bank_account_type" name="bank_account_type" type="text" class="form-control" v-model="item.bank_account_type">
										</div>

										<div class="form-group">
											<label>Bank Statement ID <b class="text-danger">*</b></label>
											<v-select v-model="item.bank_statement_id" :reduce="item => item.id" label="bank_statement_id" placeholder="Select Bank Statement" :options="bank_statements"></v-select>
										</div>

										<div class="form-group">
                                            <label for="statement_as_of_date">Statement As Of Date <b class="text-danger">*</b></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="statement_as_of_date" type="text" class="form-control calendar-form" id="statement_as_of_date" v-model="item.statement_as_of_date">
                                            </div>
                                        </div>

                                        <div class="form-group">
			        		    			<label for="statement_ending_balance">Statement Ending Balance <span class="text-danger">*</span></label>
											<input id="statement_ending_balance" name="statement_ending_balance" type="text" class="form-control" v-model="item.statement_ending_balance">
										</div>

										<div class="form-group">
			        		    			<label for="statement_total_amount">Statement Total Amount <span class="text-danger">*</span></label>
											<input id="statement_total_amount" name="statement_total_amount" type="text" class="form-control" v-model="item.statement_total_amount">
										</div>

										<div class="form-group">
			        		    			<label for="statement_open_amount">Statement Open Amount <span class="text-danger">*</span></label>
											<input id="statement_open_amount" name="statement_open_amount" type="text" class="form-control" v-model="item.statement_open_amount">
										</div>

										<div class="form-group">
			        		    			<label for="balance_per_bank_statement">Balance Per Bank Statement <span class="text-danger">*</span></label>
											<input id="balance_per_bank_statement" name="balance_per_bank_statement" type="text" class="form-control" v-model="item.balance_per_bank_statement">
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<h4><i class="fas fa-dollar-sign"></i> Cash Register</h4><hr>
										</div>

										<div class="form-group">
											<label>Cash Register ID <b class="text-danger">*</b></label>
											<v-select v-model="item.cash_register_id" :reduce="item => item.id" label="cashflow_transaction_id" placeholder="Select Cash Register" :options="cash_registers"></v-select>
										</div>

										<div class="form-group">
                                            <label for="cash_register_as_of_date">Cash Register As Of Date <b class="text-danger">*</b></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="cash_register_as_of_date" type="text" class="form-control calendar-form" id="cash_register_as_of_date" v-model="item.cash_register_as_of_date">
                                            </div>
                                        </div>

                                        <div class="form-group">
			        		    			<label for="cash_register_ending_balance">Cash Register Ending Balance <span class="text-danger">*</span></label>
											<input id="cash_register_ending_balance" name="cash_register_ending_balance" type="text" class="form-control" v-model="item.cash_register_ending_balance">
										</div>

										<div class="form-group">
			        		    			<label for="cash_register_total_amount">Cash Register Total Amount <span class="text-danger">*</span></label>
											<input id="cash_register_total_amount" name="cash_register_total_amount" type="text" class="form-control" v-model="item.cash_register_total_amount">
										</div>

										<div class="form-group">
			        		    			<label for="cash_register_open_amount">Cash Register Open Amount <span class="text-danger">*</span></label>
											<input id="cash_register_open_amount" name="cash_register_open_amount" type="text" class="form-control" v-model="item.cash_register_open_amount">
										</div>

										<div class="form-group">
			        		    			<label for="balance_per_cash_register">Balance Per Cash Register <span class="text-danger">*</span></label>
											<input id="balance_per_cash_register" name="balance_per_cash_register" type="text" class="form-control" v-model="item.balance_per_cash_register">
										</div>

										<div class="form-group">
			        		    			<label for="cash_register_description">Description <span class="text-danger">*</span></label>
											<input id="cash_register_description" name="cash_register_description" type="text" class="form-control" v-model="item.cash_register_description">
										</div>

									</div>

			        		    </div>
				        	</div>

				        	<div class="tab-pane" id="audit">
				        		<div class="row">
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

		mounted() {
			this.mountInputs();
		},

		watch: {
			'item.client_bank_account'(bank_account) {
				let item = this.client_banks.find((bank) => {
					return bank.bank_account == bank_account;
				});

				if(item) {
					this.item.bank_account_number = item.bank_account_number;
					this.item.bank_account_type = item.bank_account_type;
				}
			},
		},

		data() {
			return {
				client_banks: [],
				cash_registers: [],
				bank_statements: [],
				item: {},
			}
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.client_banks = data.client_banks ? data.client_banks : this.client_banks;
				this.cash_registers = data.cash_registers ? data.cash_registers : this.cash_registers;
				this.bank_statements = data.bank_statements ? data.bank_statements : this.bank_statements;
			},

			mountInputs() {
                flatpickr(this.$refs.statement_as_of_date, {enableTime: true});
                flatpickr(this.$refs.cash_register_as_of_date, {enableTime: true});
            },

            approved() {
            	axios.post(this.approvedUrl)
            		.then(response => {

            		}).catch(errors => {

            		})
            }
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

            approvedUrl: String,
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