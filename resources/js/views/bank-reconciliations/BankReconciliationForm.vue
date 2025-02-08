<template>
	<div>
		<div class="row">
			<div class="col-12">
				<div class="row mb-3">
					<div class="col-4">
						<!-- <v-select 
							:reduce="item => item.bank_account" 
							label="bank_name" 
							placeholder="Select Client Bank" 
							:options="clientBanks"
							v-model="clientBank"
							:disabled="propClientBank"
						></v-select> -->
							<!-- :reduce="item => item.client_bank_account_number"  -->
						<v-select 
							label="bank_account_transaction_number" 
							placeholder="Select A Statement" 
							:options="bankStatements"
							:disabled="propClientBank"
							v-model="bankStatement"
						>
							<!-- v-model="clientBank" -->
							<template #option="{ client_bank_account, bank_statement_from_date, bank_statement_to_date }">
								 {{ client_bank_account.bank_name }} 
								 {{ client_bank_account.bank_account_type }}
								 {{ dateString(bank_statement_from_date, bank_statement_to_date) }}
							</template>
						</v-select>
					</div>

					<div class="col-8 text-right">
						<!-- <button class="btn btn-success" @click="generateMatch">Generate Match</button> -->
						<button class="btn btn-success" data-toggle="modal" data-target="#matchFilter">Generate Match</button>
						<button class="btn btn-primary" @click="generateAdjustments">Generate Adjustments</button>
						<button class="btn btn-warning" @click="generateReconciliation">Generate Reconciliation</button>
					</div>
				</div>
			</div>

			<div class="col-12 col-md-6">
				<div class="row">
					<div class="col-6">
						<h4>Bank Statement</h4>
					</div>
					<div class="col-6 text-right">
						<button class="btn btn-danger" @click="generateStatement">Select Statement</button>
					</div>
				</div>
				<bank-statement-line-match-table
					:fetch-url="statementLineUrl"
					:client-bank="clientBank"
					ref="table-1"
					@submit-success="fetchAll"
				></bank-statement-line-match-table>
			</div>

			<div class="col-12 col-md-6">
				<div class="row">
					<div class="col-6">
						<h4>Cash Register</h4>
					</div>
					<div class="col-6 text-right">
						<button class="btn btn-danger" @click="generateCashRegister">Select Cash Register</button>
					</div>
				</div>
				<cash-register-match-table
					:fetch-url="cashRegisterUrl"
					:client-bank="clientBank"
					ref="table-2"
					@submit-success="fetchAll"
				></cash-register-match-table>
			</div>

		</div>

		<div class="row">

			<div class="col-12 col-md-6">
				<div class="row">
					<div class="col-6">
						<h4>Matched Bank Statement</h4>
					</div>
				</div>
				<bank-statement-line-match-table
					:fetch-url="statementLineUrl"
					:client-bank="clientBank"
					filter-matched
					ref="table-3"
					@submit-success="fetchAll"
				></bank-statement-line-match-table>
			</div>

			<div class="col-12 col-md-6">
				<div class="row">
					<div class="col-6">
						<h4>Matched Cash Register</h4>
					</div>
				</div>
				<cash-register-match-table
					:fetch-url="cashRegisterUrl"
					:client-bank="clientBank"
					filter-matched
					ref="table-4"
					@submit-success="fetchAll"
				></cash-register-match-table>
			</div>
			
		</div>

		<div class="row">

			<div class="col-12 col-md-6">
				<div class="row">
					<div class="col-6">
						<h4>Adjusted Cash Register</h4>
					</div>
				</div>
				<cash-register-adjustment-table
					:fetch-url="cashAdjustmentUrl"
					:client-bank="clientBank"
					ref="table-5"
					@submit-success="fetchAll"
				></cash-register-adjustment-table>
			</div>

			<div class="col-12 col-md-6">
				<div class="row">
					<div class="col-6">
						<h4>Adjusted Bank Statement</h4>
					</div>
				</div>
				<bank-statement-line-adjustment-table
					:fetch-url="statementLineAdjustmentUrl"
					:client-bank="clientBank"
					ref="table-6"
					@submit-success="fetchAll"
				></bank-statement-line-adjustment-table>
			</div>

		</div>

		<div class="modal fade" id="matchFilter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">Generate Match</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-6">
								<div class="form-group">
									<h4>Bank Statement</h4>
								</div>

								<div class="form-group">
                                    <label for="withdrawal">Withdrawal</label>
                                    <div class="custom-control custom-switch mb-3 mt-2">
                                    	<input type="checkbox" class="custom-control-input" id="withdrawal" name="withdrawal" v-model="filter.withdrawal">
                                        <label class="custom-control-label" for="withdrawal">
                                            <span class="badge" :class="filter.withdrawal ? 'badge-success' : 'badge-danger'">
                                                {{ filter.withdrawal ? 'Yes' : 'No'  }}
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="deposit">Deposit</label>
                                    <div class="custom-control custom-switch mb-3 mt-2">
                                    	<input type="checkbox" class="custom-control-input" id="deposit" name="deposit" v-model="filter.deposit">
                                        <label class="custom-control-label" for="deposit">
                                            <span class="badge" :class="filter.deposit ? 'badge-success' : 'badge-danger'">
                                                {{ filter.deposit ? 'Yes' : 'No'  }}
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <div class="custom-control custom-switch mb-3 mt-2">
                                    	<input type="checkbox" class="custom-control-input" id="description" name="description" v-model="filter.description">
                                        <label class="custom-control-label" for="description">
                                            <span class="badge" :class="filter.description ? 'badge-success' : 'badge-danger'">
                                                {{ filter.description ? 'Yes' : 'No'  }}
                                            </span>
                                        </label>
                                    </div>
                                </div>

							</div>

							<div class="col-6">
								<div class="form-group">
									<h4>Cash Register</h4>
								</div>

								<div class="form-group">
                                    <label for="withdrawal">Debit</label>
                                    <div class="custom-control custom-switch mb-3 mt-2">
                                    	<input type="checkbox" class="custom-control-input" id="withdrawal" name="withdrawal" v-model="filter.withdrawal">
                                        <label class="custom-control-label" for="withdrawal">
                                            <span class="badge" :class="filter.withdrawal ? 'badge-success' : 'badge-danger'">
                                                {{ filter.withdrawal ? 'Yes' : 'No'  }}
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="deposit">Credit</label>
                                    <div class="custom-control custom-switch mb-3 mt-2">
                                    	<input type="checkbox" class="custom-control-input" id="deposit" name="deposit" v-model="filter.deposit">
                                        <label class="custom-control-label" for="deposit">
                                            <span class="badge" :class="filter.deposit ? 'badge-success' : 'badge-danger'">
                                                {{ filter.deposit ? 'Yes' : 'No'  }}
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="payment_reference">Payment Reference</label>
                                    <div class="custom-control custom-switch mb-3 mt-2">
                                    	<input type="checkbox" class="custom-control-input" id="payment_reference" name="payment_reference" v-model="filter.payment_reference">
                                        <label class="custom-control-label" for="payment_reference">
                                            <span class="badge" :class="filter.payment_reference ? 'badge-success' : 'badge-danger'">
                                                {{ filter.payment_reference ? 'Yes' : 'No'  }}
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="check_number">Check Number</label>
                                    <div class="custom-control custom-switch mb-3 mt-2">
                                    	<input type="checkbox" class="custom-control-input" id="check_number" name="check_number" v-model="filter.check_number">
                                        <label class="custom-control-label" for="check_number">
                                            <span class="badge" :class="filter.check_number ? 'badge-success' : 'badge-danger'">
                                                {{ filter.check_number ? 'Yes' : 'No'  }}
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="deposit_slip_number">Deposit Slip Number</label>
                                    <div class="custom-control custom-switch mb-3 mt-2">
                                    	<input type="checkbox" class="custom-control-input" id="deposit_slip_number" name="deposit_slip_number" v-model="filter.deposit_slip_number">
                                        <label class="custom-control-label" for="deposit_slip_number">
                                            <span class="badge" :class="filter.deposit_slip_number ? 'badge-success' : 'badge-danger'">
                                                {{ filter.deposit_slip_number ? 'Yes' : 'No'  }}
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Match Description To Column: </label>
                                    <select class="form-control mb-3 mt-2" id="description" placeholder="Select Column" :disabled="!filter.description">
                                    	<option v-for="option in options">{{ option }}</option>
                                    </select>
                                </div>
								
							</div>

						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" @click="generateMatch">Submit</button>
					</div>
				</div>
			</div>
		</div>

		<loader :loading="loading"></loader>

	</div>
</template>

<script>
	
	import BankStatementLineAdjustmentTable from './BankStatementLineAdjustmentTable.vue';
	import CashRegisterAdjustmentTable from './CashRegisterAdjustmentTable.vue';
	import BankStatementLineMatchTable from './BankStatementLineMatchTable.vue';
	import CashRegisterMatchTable from './CashRegisterMatchTable.vue';
	import Loader from 'Components/loaders/Loader.vue';
	import ResponseMixin from 'Mixins/response.js';
	import Vselect from 'vue-select';
	import Swal from 'sweetalert2';

	export default {
		components: {
			'bank-statement-line-adjustment-table': BankStatementLineAdjustmentTable,
			'cash-register-adjustment-table': CashRegisterAdjustmentTable,
			'bank-statement-line-match-table': BankStatementLineMatchTable,
			'cash-register-match-table': CashRegisterMatchTable,
			'v-select': Vselect,
			'loader': Loader,
		},

		data() {
			return {
				loading: false,
				clientBank: this.propClientBank ? this.propClientBank : null,
				bankStatement: {},
				filter: {
					withdrawal: false,
					deposit: false,
					description: false,
					payment_reference: true,
					check_number: false,
					deposit_slip_number: false,
					option: 'description',
				},
				options: [
					'description',
					'line_number',
					'payment_reference',
					'bank_transaction_code',
					'bank_reason',
					'withdrawal_debit_amount',
					'deposit_credit_amount',
					'cost_center',
					'department',
					'vendor_payment_journal_voucher',
					'vendor_payment_id',
					'vendor_account',
					'vendor_name',
					'method_of_payment_vendor',
					'customer_payment_journal_voucher',
					'customer_payment_id',
					'customer_account',
					'customer_name',
					'method_of_payment_customer',
					'deposit_id',
					'check_id',
					'settlement_type',
					'subledger_journal',
					'ledger_account',
				],
			}
		},

		props: {
			generateReconciliationUrl: String,
			generateCashRegisterUrl: String,
			generateMatchUrl: String,
			generateTransactionsUrl: String,
			generateAdjustmentsUrl: String,
			statementLineUrl: String,
			cashRegisterUrl: String,
			statementLineAdjustmentUrl: String,
			propClientBank: String,
			cashAdjustmentUrl: String,
			clientBanks: {
				type: Array,
				default: () => [],
			},
			bankStatements: {
				type: Array,
				default: () => [],
			},
		},

		watch: {
			bankStatement(value) {
				this.clientBank = value.client_bank_account_number;
			},
		},

		methods: {
			generateReconciliation() {
				if(!this.clientBank) {
					return this.validationError('generate reconciliation');
				}

				this.loading = true;
				axios.post(this.generateReconciliationUrl, {
					
					client_bank: this.clientBank,
					bank_statement_id: this.bankStatement.bank_statement_id,

				}).then(response => {
					const data = response.data;

					if(data.redirect) {
						window.location.href = data.redirect;
					}
					
					this.loading = false;
					this.fetchAll();
				}).catch(error => {
					this.loading = false;
					this.parseError(error);
				});
				
			},

			validationError(message = 'generate cash registers') {
				return Swal.fire({
					title: 'Ooops',
					icon: 'error',
					text: 'Kindly select a bank account first to ' + message,
				});
			},

			generateStatement() {
				this.$refs['table-1'].fetch();
			},

			fetchAll() {
				this.$refs['table-1'].fetch();
				this.$refs['table-2'].fetch();
				this.$refs['table-3'].fetch();
				this.$refs['table-4'].fetch();
				this.$refs['table-5'].fetch();
				this.$refs['table-6'].fetch();
			},

			generateCashRegister() {
				if(!this.clientBank) {
					return this.validationError();
				}

				this.loading = true;
				axios.post(this.generateCashRegisterUrl, {
					
					client_bank: this.clientBank,

				}).then(response => {
					this.loading = false;
				}).catch(error => {
					console.log(error.response);
					this.loading = false;
				});

			},

			generateMatch() {
				if(!this.clientBank) {
					return this.validationError('generate match');
				}

				this.loading = true;
				axios.post(this.generateMatchUrl, {

					client_bank: this.clientBank, ... this.filter
				
				}).then(response => {
					this.loading = false;
					this.parseSuccess(response);
					this.fetchAll();
				}).catch(error => {
					this.parseError(error);
					this.loading = false;
				});
			},

			generateAdjustments() {
				if(!this.clientBank) {
					return this.validationError('generate adjustments');
				}

				this.loading = true;
				axios.post(this.generateAdjustmentsUrl, {

					client_bank: this.clientBank,
				
				}).then(response => {
					this.loading = false;
					this.parseSuccess(response);
					this.fetchAll();
				}).catch(error => {
					this.parseError(error);
					this.loading = false;
				});
			},

			dateString(bank_statement_from_date, bank_statement_to_date) {
				if(bank_statement_from_date && bank_statement_to_date) {
					let from = moment(bank_statement_from_date).format('MM/DD/Y');
					let to = moment(bank_statement_to_date).format('MM/DD/Y');
					return `${from} - ${to}`;
				}


			},

		},

		mixins: [ ResponseMixin, ],
	}

</script>