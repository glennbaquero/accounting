<template>
	<div>
		<card>
			<template v-slot:header>
				Bank Reconciliation Details
				<div class="float-right">
					<button class="btn btn-primary btn-sm" :disabled="loading" @click="fetch">Refresh</button>
				</div>
			</template>
			<template v-slot:default>
				<div class="row">
					<div class="col-12 col-md-4">
						
						<div class="form-group">
							<h4><i class="fas fa-balance-scale-right"></i> To Reconcile</h4><hr>
						</div>

						
						<div class="table-responsive-sm">
							<table class="table table-borderless">
								<thead>
									<tr>
										<td><h5>Bank Statement</h5></td>
										<td>Total Count</td>
										<td>Total Amount</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Deposit</td>
										<td>{{ reconcile_bank_statement.deposit_count }}</td>
										<td>{{ reconcile_bank_statement.deposit_amount }}</td>
									</tr>
									<tr>
										<td>Witddrawal</td>
										<td>{{ reconcile_bank_statement.witddrawal_count }}</td>
										<td>{{ reconcile_bank_statement.witddrawal_amount }}</td>
									</tr>
									<tr>
										<td>Statement Check</td>
										<td>{{ reconcile_bank_statement.statement_check_count }}</td>
										<td>{{ reconcile_bank_statement.statement_check_amount }}</td>
									</tr>
									<tr>
										<td>Bank Posting</td>
										<td>{{ reconcile_bank_statement.bank_posting_count }}</td>
										<td>{{ reconcile_bank_statement.bank_posting_amount }}</td>
									</tr>
								</tbody>
							</table>
						</div>

						<div class="table-responsive-sm">
							<table class="table table-borderless">
								<thead>
									<tr>
										<td><h5>Cash Register</h5></td>
										<td>Total Count</td>
										<td>Total Amount</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Customer Payment</td>
										<td>{{ reconcile_cash_register.customer_payment_count }}</td>
										<td>{{ reconcile_cash_register.customer_payment_amount }}</td>
									</tr>
									<tr>
										<td>Vendor Payment</td>
										<td>{{ reconcile_cash_register.vendor_payment_count }}</td>
										<td>{{ reconcile_cash_register.vendor_payment_amount }}</td>
									</tr>
									<tr>
										<td>Deposit Slip</td>
										<td>{{ reconcile_cash_register.deposit_slip_count }}</td>
										<td>{{ reconcile_cash_register.deposit_slip_amount }}</td>
									</tr>
									<tr>
										<td>Check</td>
										<td>{{ reconcile_cash_register.check_count }}</td>
										<td>{{ reconcile_cash_register.check_amount }}</td>
									</tr>
								</tbody>
							</table>
						</div>

					</div>

					<div class="col-12 col-md-4">
						
						<div class="form-group">
							<h4><i class="fas fa-equals"></i> Matched</h4><hr>
							<p>Total Matched Cash Register and Bank Statement {{ matched_bank_statement.total_matched_count }}</p>
						</div>

						<div class="table-responsive-sm">
							<table class="table table-borderless">
								<thead>
									<tr>
										<td><h5>Bank Statement</h5></td>
										<td>Total Count</td>
										<td>Total Amount</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Deposit</td>
										<td>{{ matched_bank_statement.deposit_matched_count }}</td>
										<td>{{ matched_bank_statement.deposit_matched_amount }}</td>
									</tr>
									<tr>
										<td>Withdrawal</td>
										<td>{{ matched_bank_statement.withdrawal_matched_count }}</td>
										<td>{{ matched_bank_statement.withdrawal_matched_amount }}</td>
									</tr>
									<tr>
										<td>Statement Check</td>
										<td>{{ matched_bank_statement.statement_check_matched_count }}</td>
										<td>{{ matched_bank_statement.statement_check_matched_amount }}</td>
									</tr>
									<tr>
										<td>Bank Posting</td>
										<td>{{ matched_bank_statement.bank_posting_matched_count }}</td>
										<td>{{ matched_bank_statement.bank_posting_matched_amount }}</td>
									</tr>
								</tbody>
							</table>
						</div>

						<div class="form-group">
							<p>Total Amount Cash Register and Bank Statement {{ matched_cash_register.total_matched_amount }}</p>
						</div>

						<div class="table-responsive-sm">
							<table class="table table-borderless">
								<thead>
									<tr>
										<td><h5>Cash Register</h5></td>
										<td>Total Count</td>
										<td>Total Amount</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Customer Payment</td>
										<td>{{ matched_cash_register.customer_payment_matched_count }}</td>
										<td>{{ matched_cash_register.customer_payment_matched_amount }}</td>
									</tr>
									<tr>
										<td>Vendor Payment</td>
										<td>{{ matched_cash_register.vendor_payment_matched_count }}</td>
										<td>{{ matched_cash_register.vendor_payment_matched_amount }}</td>
									</tr>
									<tr>
										<td>Deposit Slip</td>
										<td>{{ matched_cash_register.deposit_slip_matched_count }}</td>
										<td>{{ matched_cash_register.deposit_slip_matched_amount }}</td>
									</tr>
									<tr>
										<td>Check</td>
										<td>{{ matched_cash_register.check_matched_count }}</td>
										<td>{{ matched_cash_register.check_matched_amount }}</td>
									</tr>
								</tbody>
							</table>
						</div>

					</div>

					<div class="col-12 col-md-4">
						
						<div class="form-group">
							<h4><i class="fas fa-sliders-h"></i> Adjustment</h4><hr>
						</div>

						<div class="table-responsive-sm">
							<table class="table table-borderless">
								<thead>
									<tr>
										<td><h5>Bank Statement</h5></td>
										<td>Total Count</td>
										<td>Total Amount</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Statement</td>
										<td>{{ adjustment_bank_statement.statement_adjustment_count }}</td>
										<td>{{ adjustment_bank_statement.statement_adjustment_amount }}</td>
									</tr>
									<tr>
										<td>Statement Check</td>
										<td>{{ adjustment_bank_statement.statement_adjustment_check_count }}</td>
										<td>{{ adjustment_bank_statement.statement_adjustment_check_amount }}</td>
									</tr>
									<tr>
										<td>Bank Posting</td>
										<td>{{ adjustment_bank_statement.bank_posting_adjustment_count }}</td>
										<td>{{ adjustment_bank_statement.bank_posting_adjustment_amount }}</td>
									</tr>
								</tbody>
							</table>
						</div>

						<div class="table-responsive-sm">
							<table class="table table-borderless">
								<thead>
									<tr>
										<td><h5>Cash Register</h5></td>
										<td>Total Count</td>
										<td>Total Amount</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Cash Register</td>
										<td>{{ adjustment_cash_register.cash_register_adjustment_count }}</td>
										<td>{{ adjustment_cash_register.cash_register_adjustment_amount }}</td>
									</tr>
									<tr>
										<td>Customer Payment</td>
										<td>{{ adjustment_cash_register.customer_payment_adjustment_count }}</td>
										<td>{{ adjustment_cash_register.customer_payment_adjustment_amount }}</td>
									</tr>
									<tr>
										<td>Vendor Payment</td>
										<td>{{ adjustment_cash_register.vendor_payment_adjustment_count }}</td>
										<td>{{ adjustment_cash_register.vendor_payment_adjustment_amount }}</td>
									</tr>
									<tr>
										<td>Deposits In Transit</td>
										<td>{{ adjustment_cash_register.deposit_transit_count }}</td>
										<td>{{ adjustment_cash_register.deposit_transit_amount }}</td>
									</tr>
									<tr>
										<td>Outstanding Checks</td>
										<td>{{ adjustment_cash_register.outstanding_checks_count }}</td>
										<td>{{ adjustment_cash_register.outstanding_checks_amount }}</td>
									</tr>
								</tbody>
							</table>
						</div>

					</div>
				</div>
			</template>
		</card>
		<loader :loading="loading"></loader>
	</div>
</template>

<script>

	import ResponseMixin from 'Mixins/response.js';
	import Loader from 'Components/loaders/Loader.vue';
	import Card from 'Components/containers/Card.vue';
	
	export default {
		mixins: [ ResponseMixin ],

		mounted() {
			this.init();
		},

		data() {
			return {
				loading: false,

				reconcile_bank_statement: {},
				reconcile_cash_register: {},

				matched_bank_statement: {},
				matched_cash_register: {},

				adjustment_bank_statement: {},
				adjustment_cash_register: {},
			}
		},

		methods: {
			init() {
				this.fetch();
			},

			fetch() {
				this.loading = true;
				axios.post(this.fetchUrl, {
					//
				}).then(response => {
					const data = response.data;

					this.reconcile_bank_statement = data.reconcile_bank_statement ? data.reconcile_bank_statement : this.reconcile_bank_statement;
					this.reconcile_cash_register = data.reconcile_cash_register ? data.reconcile_cash_register : this.reconcile_cash_register;

					this.matched_bank_statement = data.matched_bank_statement ? data.matched_bank_statement : this.matched_bank_statement;
					this.matched_cash_register = data.matched_cash_register ? data.matched_cash_register : this.matched_cash_register;

					this.adjustment_bank_statement = data.adjustment_bank_statement ? data.adjustment_bank_statement : this.adjustment_bank_statement;
					this.adjustment_cash_register = data.adjustment_cash_register ? data.adjustment_cash_register : this.adjustment_cash_register;

					this.loading = false;
				}).catch(error => {
					this.loading = false;
					console.log(error.response);
				});
			},
		},

		props: {
			fetchUrl: String,
		},

		components: {
			'loader': Loader,
			'card': Card,
		},
	}

</script>