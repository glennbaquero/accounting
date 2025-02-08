<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="submitSuccess" confirm-dialog sync-on-success :params="submitParams">

			<card>
				<template v-slot:header>
					Payment Cancellation Journal Voucher
					<div class="float-right">
						<action-button type="submit" :disabled="loading" class="btn btn-primary btn-sm">Save Changes</action-button>
					</div>
				</template>
				<div class="card">
					<div class="card-header p-2">	
						<div class="row">
							<div class="col-md-9">
								<ul class="nav nav-pills">
									<li class="nav-item"><a class="nav-link active" @click="currentTab = 'bank_reconciliation_line'" href="#bank_reconciliation_line" data-toggle="tab">Payment Cancellation Journal Voucher</a></li>
									<li class="nav-item"><a class="nav-link"  @click="currentTab = 'audit_br'" href="#audit_br" data-toggle="tab">Audit Trail</a></li>
								</ul>
							</div>
							<div class="col-md-3">
								<div class="row">
									<div class="col-md-2 mt-2">
										<label>Client</label>
									</div>
									<div class="col-md-10">
										<v-select v-model="item.client_id" :reduce="item => item.id" label="name" :options="clients" placeholder="Select Client"></v-select>
										<input name="client_id" hidden v-model="item.client_id"> 
									</div>
								</div>
							</div>
						</div>
				    </div>

				     <div class="card-body">
				        <div class="tab-content">
				        	<div v-show="currentTab == 'bank_reconciliation_line'">
				        		<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<h4><i class="fas fa-money-check-alt"></i> Customer Payment</h4><hr>
										</div>

										<div class="form-group">
											<label>Customer Payment <b class="text-danger">*</b></label>
											<v-select 
												v-model="item.customer_payment_id"
												:reduce="item => item.customer_payment_number"
												label="customer_payment_number"
												placeholder="Select a Customer Payment"
												:options="customer_payments"
											></v-select>
										</div>

										<div class="form-group">
                                            <label for="customer_payment_issued_date">Customer Payment Issued Date <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="customer_payment_issued_date" type="text" class="form-control calendar-form" id="customer_payment_issued_date" name="customer_payment_issued_date" v-model="item.customer_payment_issued_date">
                                            </div>
                                        </div>

                                        <div class="form-group">
			        		    			<label for="customer_name">Customer Name <span class="text-danger">*</span></label>
											<input id="customer_name" name="customer_name" type="text" class="form-control" v-model="item.customer_name">
										</div>

                                        <div class="form-group">
                                            <label>Customer Payment Method <b class="text-danger">*</b></label>
                                            <v-select 
                                                v-model="item.customer_payment_method"
                                                :reduce="item => item.method_of_payment_id"
                                                label="method_of_payment"
                                                placeholder="Select a payment method"
                                                :options="customer_payment_methods"
                                            ></v-select>
                                        </div>


										<div class="form-group">
											<h4><i class="fas fa-money-check-alt"></i> Vendor Payment</h4><hr>
										</div>

										<div class="form-group">
											<label>Vendor Payment <b class="text-danger">*</b></label>
											<v-select 
												v-model="item.vendor_payment_id" 
												:reduce="item => item.vendor_payment_number" 
												label="vendor_payment_number" 
												placeholder="Select a Vendor Payment" 
												:options="vendor_payments"
											></v-select>
										</div>

										<div class="form-group">
                                            <label for="vendor_payment_issued_date">Vendor Payment Issued Date <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="vendor_payment_issued_date" type="text" class="form-control calendar-form" id="vendor_payment_issued_date" name="vendor_payment_issued_date" v-model="item.vendor_payment_issued_date">
                                            </div>
                                        </div>

                                        <div class="form-group">
			        		    			<label for="vendor_name">Vendor Name <span class="text-danger">*</span></label>
											<input id="vendor_name" name="vendor_name" type="text" class="form-control" v-model="item.vendor_name">
										</div>

                                        <div class="form-group">
                                            <label>Vendor Payment Method <b class="text-danger">*</b></label>
                                            <v-select 
                                                v-model="item.vendor_payment_method" 
                                                :reduce="item => item.method_of_payment_id" 
                                                label="method_of_payment" 
                                                placeholder="Select a payment method" 
                                                :options="vendor_payment_methods"
                                            ></v-select>
                                        </div>

									</div>

									<div class="col-md-4">
										<div class="form-group">
											<h4><i class="fas fa-university"></i> Bank Account Segment</h4><hr>
										</div>

										<div class="form-group">
                                            <label>Bank Account Number <b class="text-danger">*</b></label>
                                            <v-select 
                                                v-model="item.client_bank_account_number" 
                                                :reduce="item => item.bank_account" 
                                                label="bank_account" 
                                                placeholder="Select Bank" 
                                                :options="client_banks"
                                            >
                                                <template #option="{ bank_account, bank_account_number, account_holder, bank_account_type, bank_name }">
                                                    <b>Bank</b> : {{ bank_account }} - 
                                                    <b>Bank Name</b> : {{ bank_name }} - 
                                                    <b>Account Holder</b> : {{ account_holder }} - 
                                                    <b>Account Number</b> : {{ bank_account_number }} - 
                                                    <b>Account Type</b> : {{ bank_account_type }}
                                                </template>
                                            </v-select>
                                        </div>

                                        <div class="form-group">
                                            <label for="client_bank_name">Bank Name</label>
                                            <input type="text" class="form-control" :value="client_bank_account.bank_name" id="client_bank_name" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="client_bank_branch">Bank Branch</label>
                                            <input type="text" class="form-control" :value="client_bank_account.bank_branch" id="client_bank_branch" readonly>
                                        </div>

                                        <div class="form-group">
											<label>Check <b class="text-danger">*</b></label>
											<v-select 
												v-model="item.check_id" 
												:reduce="item => item.check_id" 
												label="check_id" 
												placeholder="Select a Check" 
												:options="checks"
											></v-select>
										</div>
										<div class="form-group">
			        		    			<label for="check_number">Check Number <span class="text-danger">*</span></label>
											<input id="check_number" name="check_number" type="text" class="form-control" v-model="item.check_number">
										</div>

										<div class="form-group">
			        		    			<label for="check_amount">Check Amount <span class="text-danger">*</span></label>
											<input id="check_amount" name="check_amount" type="text" class="form-control" v-model="item.check_amount">
										</div>

										<div class="form-group">
											<label>Deposit <b class="text-danger">*</b></label>
											<v-select 
												v-model="item.deposit_id" 
												:reduce="item => item.deposit_slip_id" 
												label="deposit_slip_id" 
												placeholder="Select a Deposit Slip" 
												:options="deposits"
											></v-select>
										</div>

										<div class="form-group">
                                            <label for="payment_reference">Payment Refernce <span class="text-danger">*</span></label>
                                            <input id="payment_reference" name="payment_reference" type="text" class="form-control" v-model="item.payment_reference">
                                        </div>

                                        <div class="form-group">
											<label>Bank Account Transaction <b class="text-danger">*</b></label>
											<v-select 
												v-model="item.bank_account_transaction_id" 
												:reduce="item => item.bank_account_transaction_number" 
												label="bank_account_transaction_number" 
												placeholder="Select a Bank Transaction" 
												:options="bank_transactions"
											></v-select>
										</div>

										<div class="form-group">
                                            <label for="reconcile_date">Reconcile Date <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="reconcile_date" type="text" class="form-control calendar-form" id="reconcile_date" name="reconcile_date" v-model="item.reconcile_date">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Matched</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            	<input type="checkbox" class="custom-control-input" id="matched_checkbox" name="matched_checkbox" v-model="item.matched_checkbox">
                                                <label class="custom-control-label" for="matched_checkbox">
                                                    <span class="badge" :class="item.matched_checkbox ? 'badge-success' : 'badge-danger'">
                                                        {{ item.matched_checkbox ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Bank Posting <b class="text-danger">*</b></label>
                                            <v-select 
                                                v-model="item.bank_posting" 
                                                :reduce="item => item.id" 
                                                label="bank_transaction_posting" 
                                                placeholder="Select a bank posting" 
                                                :options="bank_postings"
                                            ></v-select>
                                        </div>

                                        <div class="form-group">
                                            <label>Bank Reason <b class="text-danger">*</b></label>
                                            <v-select 
                                                v-model="item.bank_reason" 
                                                :reduce="item => item.reason_code" 
                                                label="default_comment" 
                                                placeholder="Select a reason" 
                                                :options="bank_reasons"
                                            ></v-select>
                                        </div>

									</div>

									<div class="col-md-4">
										<div class="form-group">
											<h4><i class="fas fa-history"></i> Reversal & Cancellation</h4><hr>
										</div>

										<div class="form-group">
											<label>Payment Reversal <b class="text-danger">*</b></label>
											<v-select 
												v-model="item.reversal_id" 
												:reduce="item => item.payment_reversal_id" 
												label="payment_reversal_id" 
												placeholder="Select a Payment Reversal" 
												:options="payment_reversals"
											></v-select>
										</div>

										<div class="form-group">
                                            <label for="reversed_date">Reversed Date <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="reversed_date" type="text" class="form-control calendar-form" id="reversed_date" name="reversed_date" v-model="item.reversed_date">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="cancelled_date">Cancelled Date <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="cancelled_date" type="text" class="form-control calendar-form" id="cancelled_date" name="cancelled_date" v-model="item.cancelled_date">
                                            </div>
                                        </div>


										<div class="form-group">
											<hr><h4><i class="fas fa-equals"></i> Bank Reconciliation</h4><hr>
										</div>

										<div class="form-group">
											<label>Bank Reconciliation <b class="text-danger">*</b></label>
											<v-select 
												v-model="item.bank_reconciliation_id" 
												:reduce="item => item.bank_reconciliation_id" 
												label="bank_reconciliation_id" 
												placeholder="Select a Bank Reconciliation" 
												:options="bank_reconciliations"
											></v-select>
										</div>

										<div class="form-group">
											<label>Bank Statement Adjustment <b class="text-danger">*</b></label>
											<v-select 
												v-model="item.statement_adjustment_id" 
												:reduce="item => item.bank_statement_adjustment_id" 
												label="bank_statement_adjustment_id" 
												placeholder="Select a Statement Adjustment" 
												:options="bank_statement_adjustments"
											></v-select>
										</div>

										<div class="form-group">
											<label>Cash Register Adjustment <b class="text-danger">*</b></label>
											<v-select
												v-model="item.cash_register_adjustment_id"
												:reduce="item => item.cashflow_adjustment_id"
												label="cashflow_adjustment_id"
												placeholder="Select a Cash Register Adjustment"
												:options="cash_register_adjustments"
											></v-select>
										</div>

										<div class="form-group">
											<label>Bank Statement <b class="text-danger">*</b></label>
											<v-select 
												v-model="item.bank_statement_id" 
												:reduce="item => item.bank_statement_id" 
												label="bank_statement" 
												placeholder="Select a statement" 
												:options="bank_statements"
											></v-select>
										</div>

									</div>

			        		    </div>
				        	</div>

				        	<div v-show="currentTab == 'audit_br'">
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
				currentTab: 'bank_reconciliation_line',
				item: {},
				client_banks: [],
				bank_statement_adjustments: [],
				cash_register_adjustments: [],
				bank_statements: [],
				bank_reconciliations: [],
				vendor_payments: [],
				customer_payments: [],
				checks: [],
				deposits: [],
                vendor_payment_methods: [],
                customer_payment_methods: [],
                bank_postings: [],
                bank_reasons: [],
                bank_transactions: [],
                bank_statements: [],
                payment_reversals: [],
				statuses: [
                    'Open',
                    'On Hold',
                    'Void',
                    'Paid',
                    'Posted',
                    'Cancelled',
                    'Pending Cancellation',
                ],
				
			}
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : {};
				this.client_banks = data.client_banks ? data.client_banks : this.client_banks;
				this.bank_statement_adjustments = data.bank_statement_adjustments ? data.bank_statement_adjustments : this.bank_statement_adjustments;
				this.cash_register_adjustments = data.cash_register_adjustments ? data.cash_register_adjustments : this.cash_register_adjustments;
				this.bank_reconciliations = data.bank_reconciliations ? data.bank_reconciliations : this.bank_reconciliations;
				this.checks = data.checks ? data.checks : this.checks;
				this.deposits = data.deposits ? data.deposits : this.deposits;
				this.vendor_payments = data.vendor_payments ? data.vendor_payments : this.vendor_payments;
				this.customer_payments = data.customer_payments ? data.customer_payments : this.customer_payments;
                this.vendor_payment_methods = data.vendor_payment_methods ? data.vendor_payment_methods : this.vendor_payment_methods;
                this.customer_payment_methods = data.customer_payment_methods ? data.customer_payment_methods : this.customer_payment_methods;
                this.bank_postings = data.bank_postings ? data.bank_postings : this.bank_postings;
                this.bank_reasons = data.bank_reasons ? data.bank_reasons : this.bank_reasons;
                this.bank_transactions = data.bank_transactions ? data.bank_transactions : this.bank_transactions;
                this.bank_statements = data.bank_statements ? data.bank_statements : this.bank_statements;
                this.payment_reversals = data.payment_reversals ? data.payment_reversals : this.payment_reversals;
			},

			mountInputs() {
                flatpickr(this.$refs.customer_payment_issued_date, {enableTime: true});
                flatpickr(this.$refs.vendor_payment_issued_date, {enableTime: true});
                flatpickr(this.$refs.reconcile_date, {enableTime: true});
                flatpickr(this.$refs.reversed_date, {enableTime: true});
                flatpickr(this.$refs.cancelled_date, {enableTime: true});
            },

            submitSuccess() {
            	this.fetch();
            	this.$emit('submit-success');
            },
		},

		computed: {
			submitParams() {
				let item = this.item;
				return item;
			},

			client_bank_account() {
                let item = this.client_banks.find((data) => {
                    return data.bank_account == this.item.client_bank_account_number;
                });

                return item ? item : {};
            },
		},

		props: {
            clients: {
                type: Array,
                default: () => [],
            },
            parent: {
                type: Object,
                default: () => {},
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