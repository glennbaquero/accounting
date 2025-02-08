<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<div class="card">
				<div class="card-header">
					<ul class="nav nav-pills">
						<li class="nav-item"><a class="nav-link active" href="#general-ledger" data-toggle="tab">Operating Transaction</a></li>
						<li class="nav-item"><a class="nav-link" href="#details" data-toggle="tab">Operating Transaction Summary</a></li>
						<li class="nav-item"><a class="nav-link" href="#trial-balance" data-toggle="tab">Trial Balance</a></li>
						<li class="nav-item"><a class="nav-link"  @click="initList('journal-ledger')" href="#journal-ledger" data-toggle="tab">Journal Ledger</a></li>
						<li class="nav-item"><a class="nav-link" href="#vendor-table" data-toggle="tab">Vendor Subsidiary Ledger</a></li>
						<li class="nav-item"><a class="nav-link" href="#customer-table" data-toggle="tab">Customer Subsidiary Ledger</a></li>
						<li class="nav-item"><a class="nav-link" @click="initClosingTransaction" href="#closing-transaction" data-toggle="tab">Closing Transaction</a></li>
					</ul>
				</div>
				<div class="card-body">
					<div class="tab-content">
						<div class="tab-pane show active" id="general-ledger">
							<monthly-table 
							:fetchUrl="fetchUrlGeneralLedgerSummary" 
							:item="item">
							</monthly-table>
						</div>
						<div class="tab-pane show" id="trial-balance">
							<trial-balance 
							:adjusted_trial_balance="adjusted_trial_balance"
							:unadjusted_trial_balance="unadjusted_trial_balance"
							:post_closing_trial_balance="post_closing_trial_balance"
							:approveClosingBalanceUrl="item.approveClosingBalanceUrl"
							>
							</trial-balance>
						</div>
						<div class="tab-pane show" id="details">
							<div class="card">
								<div class="card-header">
									<div class="row">
										<div class="col-md-6">
											<ul class="nav nav-pills">
												<li class="nav-item"><a class="nav-link active" href="#overview" data-toggle="tab">Overview</a></li>
												<li class="nav-item"><a class="nav-link" href="#details-record" data-toggle="tab">Audit Trail</a></li>
											</ul>
										</div>
										<div class="col-md-6 text-right">
											<action-button type="button" 
											:disabled="loading" 
											:action-url="enableClosingTransactionUrl"
											title="Enable Closing Transaction"
											:message="'Are you sure you want to enable closing transaction ?'" 
											class="btn-warning">Enable Closing Transaction
											</action-button>		
											<action-button type="button" 
											:disabled="loading" 
											:action-url="generateClosingTransactionUrl"
											title="Generate Closing Transaction"
											:message="'Are you sure you want to generate closing transaction ?'" 
											class="btn-secondary">Generate Closing Transaction
											</action-button>										
										</div>
									</div>
								</div>
								<div class="card-body">
									<div class="tab-content">
										<div class="tab-pane show active" id="overview">
											<div class="row">
												<div class="form-group col-sm-6">
													<label>Account Summary Title</label>
													<input name="name" class="form-control" v-model="item.name">
												</div>
												<div class="form-group col-sm-6">
													<label>Opening Balance Journal</label>
													<v-select v-model="item.opening_transaction_journal_id" :reduce="item => item.id" :options="filtered_opening_transaction_journals" label="general_ledger_name" placeholder="Select Opening Balance Journal"></v-select>
													<input name="opening_transaction_journal_id" hidden v-model="item.opening_transaction_journal_id">
												</div>
											</div>
											<div class="row justify-content-end">
												<div class="form-group col-sm-3">
													<label>Client</label>
													<v-select disabled v-model="item.client_id" :reduce="item => item.id" :options="clients" label="name" placeholder="Select Client"></v-select>
													<input name="client_id" hidden v-model="item.client_id">
												</div>
												<div class="form-group col-sm-3">
													<label>Period From <b class="text-danger">*</b></label>
													<div class="input-group mb-2">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
														</div>
														<input ref="period_from" name="period_from" v-model="item.period_from" type="text" class="form-control calendar-form">
													</div>
												</div>
												<div class="form-group col-sm-3">
													<label>Period To <b class="text-danger">*</b></label>
													<div class="input-group mb-2">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
														</div>
														<input ref="period_to" name="period_to" v-model="item.period_to" type="text" class="form-control calendar-form">
													</div>
												</div>

												<div class="form-group col-sm-3">									
													<label>Use Retain Earnings as Opening</label>
													<div class="custom-control custom-switch mb-3 mt-2">
													<input type="checkbox" name="use_retain_earnings_as_opening" class="custom-control-input" id="use_retain_earnings_as_opening" v-model="item.use_retain_earnings_as_opening">
													<label class="custom-control-label" for="use_retain_earnings_as_opening">
														<span class="badge" :class="item.use_retain_earnings_as_opening ? 'badge-success' : 'badge-danger'">
															{{ item.use_retain_earnings_as_opening ? 'Yes' : 'No'  }}
														</span>
													</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-sm-3">
													<label>Ledger <b class="text-danger">*</b></label>
													<v-select v-model="item.ledger_id" :reduce="item => item.id" :options="filtered_ledgers" label="ledger_name" :resetOnOptionsChange="loaded" placeholder="Select Ledger"></v-select>
													<input name="ledger_id" hidden v-model="item.ledger_id"> 
												</div>
												<div class="form-group col-sm-3">
													<label>Ledger Calendar <b class="text-danger">*</b></label>
													<v-select v-model="item.ledger_calendar_id" :reduce="item => item.id" :options="filtered_ledger_calendars" label="ledger_calendar_name" :resetOnOptionsChange="loaded" placeholder="Select Ledger Calendar"></v-select>
													<input name="ledger_calendar_id" hidden v-model="item.ledger_calendar_id"> 
												</div>
												<div class="form-group col-sm-3">
													<label>Chart of Account</label>
													<input readonly class="form-control" :value="coa">
												</div>
												<div class="form-group col-sm-3">
													<label>Main Accounts Count</label>
													<input readonly class="form-control" :value="coa">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-sm-4">
													<label>Total Debit</label>
													<input readonly class="form-control" :value="item.total_debit | currency"> 
												</div>
												<div class="form-group col-sm-4">
													<label>Total Credit</label>
													<input readonly class="form-control" :value="item.total_credit | currency"> 
												</div>
												<div class="form-group col-sm-4">
													<label>Ending Balance</label>
													<input readonly class="form-control" :value="item.ending_balance | currency"> 
												</div>
											</div>
											<div class="row">
												<div class="form-group col-sm-4">
													<label>Total Assets</label>
													<input readonly class="form-control" :value="item.total_assets | currency"> 
												</div>
												<div class="form-group col-sm-4">
													<label>Total Liabilities</label>
													<input readonly class="form-control" :value="item.total_liabilities | currency"> 
												</div>
												<div class="form-group col-sm-4">
													<label>Total Equities</label>
													<input readonly class="form-control" :value="item.total_equities | currency"> 
												</div>
											</div>
											<div class="row">
												<div class="form-group col-sm-6">
													<label>Total Profit & Loss</label>
													<input readonly class="form-control" :value="item.total_profit_and_loss | currency"> 
												</div>
												<div class="form-group col-sm-6">
													<label>Ending Balance - Profit & Loss</label>
													<input readonly class="form-control" :value="item.ending_profit_and_loss | currency"> 
												</div>
											</div>
											<div class="row">
												<div class="form-group col-sm-6">
													<label>Total Income</label>
													<input readonly class="form-control" :value="item.total_income | currency"> 
												</div>
												<div class="form-group col-sm-6">
													<label>Total Expense</label>
													<input readonly class="form-control" :value="item.total_expense | currency"> 
												</div>
											</div>
										</div>
										<div class="tab-pane show" id="details-record">
											<div class="row">
												<div class="form-group col-sm-4">
													<label>Approved By</label>
													<input readonly class="form-control" :value="item.reverse_by_fullname"> 
												</div>

												<div class="form-group col-sm-4">
													<label>Approved Date</label>
													<div class="input-group mb-2">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
														</div>
														<input readonly class="form-control" :value="item.revese_date"> 
													</div>
												</div>

												<div class="form-group col-sm-4 text-center">
													<label>Approved</label>
													<h5>
														<span class="badge" :class="item.reversed_checkbox ? 'badge-success' : 'badge-danger'">
															{{ item.reversed_checkbox ? 'Yes' : 'No'  }}
														</span>
													</h5>	
												</div>
											</div>
											<div class="row">
												<div class="form-group col-sm-4">
													<label>Reviewed By</label>
													<input readonly class="form-control" :value="item.reverse_by_fullname"> 
												</div>

												<div class="form-group col-sm-4">
													<label>Reviewed Date</label>
													<div class="input-group mb-2">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
														</div>
														<input readonly class="form-control" :value="item.revese_date"> 
													</div>
												</div>

												<div class="form-group col-sm-4 text-center">
													<label>Reviewed</label>
													<h5>
														<span class="badge" :class="item.reversed_checkbox ? 'badge-success' : 'badge-danger'">
															{{ item.reversed_checkbox ? 'Yes' : 'No'  }}
														</span>
													</h5>	
												</div>
											</div>
											<div class="row">
												<div class="form-group col-sm-4">
													<label>Reverse By</label>
													<input readonly class="form-control" :value="item.reverse_by_fullname"> 
												</div>

												<div class="form-group col-sm-4">
													<label>Reverse Date</label>
													<div class="input-group mb-2">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
														</div>
														<input readonly class="form-control" :value="item.revese_date"> 
													</div>
												</div>

												<div class="form-group col-sm-4 text-center">
													<label>Reversed</label>
													<h5>
														<span class="badge" :class="item.reversed_checkbox ? 'badge-success' : 'badge-danger'">
															{{ item.reversed_checkbox ? 'Yes' : 'No'  }}
														</span>
													</h5>	
												</div>
											</div>
											<div class="row">
												<div class="form-group col-sm-4">
													<label>Posted By</label>
													<input readonly class="form-control" :value="item.posted_by_fullname"> 
												</div>

												<div class="form-group col-sm-4">
													<label>Posting Date</label>
													<div class="input-group mb-2">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
														</div>
														<input readonly class="form-control" :value="item.posted_on"> 
													</div>
												</div>

												<div class="form-group col-sm-4 text-center">
													<label>Posted</label>
													<h5>
														<span class="badge" :class="item.posted_checkbox ? 'badge-success' : 'badge-danger'">
															{{ item.posted_checkbox ? 'Yes' : 'No'  }}
														</span>
													</h5>	
												</div>
											</div>
											<div class="row">
												<div class="form-group col-sm-4">
													<label>Adjusted By</label>
													<input readonly class="form-control" :value="item.adjusted_by_fullname"> 
												</div>

												<div class="form-group col-sm-4">
													<label>Reverse Date</label>
													<div class="input-group mb-2">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
														</div>
														<input readonly class="form-control" :value="item.adjusted_date"> 
													</div>
												</div>

												<div class="form-group col-sm-4 text-center">
													<label>Adjusted</label>
													<h5>
														<span class="badge" :class="item.adjusted_checkbox ? 'badge-success' : 'badge-danger'">
															{{ item.adjusted_checkbox ? 'Yes' : 'No'  }}
														</span>
													</h5>	
												</div>
											</div>
											<div class="row">
												<div class="form-group col-sm-3">
													<label>Created By</label>
													<input readonly class="form-control" :value="item.created_by_fullname"> 
												</div>

												<div class="form-group col-sm-3">
													<label>Created On</label>
													<div class="input-group mb-2">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
														</div>
														<input readonly class="form-control" :value="item.created_at"> 
													</div>
												</div>

												<div class="form-group col-sm-3">
													<label>Updated By</label>
													<input readonly class="form-control" :value="item.updated_by_fullname"> 
												</div>

												<div class="form-group col-sm-3">
													<label>Updated On</label>
													<div class="input-group mb-2">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
														</div>
														<input readonly class="form-control" :value="item.updated_at"> 
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-md-12">
														<label>Description</label>
													<text-editor name="description" v-model="item.description"></text-editor>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card-footer text-right">
									<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="journal-ledger">
							<div class="row">
								<div class="form-group col-sm-12">
									<journal-line-table ref="journal-ledger" :main_accounts="main_accounts" :fetch-url='fetchUrlJournalLine'></journal-line-table>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="vendor-table">
							<subsidiary-line-table
								ref="journal-line-table"
								:fetch-url="fetchUrlVendorLine"
							></subsidiary-line-table>
						</div>
						<div class="tab-pane" id="customer-table">
							<subsidiary-line-table
								ref="journal-line-table"
								:fetch-url="fetchUrlCustomerLine"
							></subsidiary-line-table>
						</div>
						<div class="tab-pane show" id="closing-transaction">
							<template v-if="item.closing_transaction">
								<template v-if="item.should_validate_closing_transaction && !authenticated">
									<div class="jumbotron">
										<div class="lead">
											<password-form
											@authenticated="authenticated = true"
											:submit-url="item.closingAuthenticationUrl"
											></password-form>
										</div>
									</div>
								</template>
								<template v-if="authenticated">
									<closing-transaction-view
									ref="closing-transaction-form"
									:set-password-url="setPasswordUrl"
									:submit-url="item.closing_transaction_update_url"
									:fetch-url="item.closing_transaction_fetch_url"
									></closing-transaction-view>
								</template>

							</template>
							<template v-else>
								<div class="jumbotron text-center">
									<p class="lead">
										<action-button type="button" 
										:disabled="loading" 
										:action-url="generateClosingTransactionUrl"
										@success="fetch()"
										title="Generate Closing Transaction"
										:message="'Are you sure you want to generate closing transaction ?'" 
										class="btn-secondary">Generate Closing Transaction
										</action-button>	
									</p>
								</div>
							</template>
						</div>
					</div>
				</div>
			</div>

			<loader 
            :loading="loading">
            </loader>
		</form-request>
	</div>
</template>

<script>
	import CrudMixin from 'Mixins/crud.js';
	import SetupMixin from 'Mixins/setup.js';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import Vselect from 'vue-select';

	import Datepicker from 'vuejs-datepicker';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import TextEditor from 'Components/inputs/TextEditor.vue';

	import SubsidiaryLedgerTable from './SubsidiaryLedgerTable.vue';
	import JournalLineTable from './JournalLineTable.vue';
	import TrialBalance from './TrialBalance.vue';
	import MonthlyTable from './MonthyTable.vue';

	import PasswordForm from './Password.vue';
	
	import flatpickr from 'flatpickr';
	import 'flatpickr/dist/flatpickr.css';

	export default {

		props: {
			fetchUrlJournalLine: String,
			fetchUrlVendorLine: String,
			fetchUrlCustomerLine: String,
			fetchUrlGeneralLedgerSummary: String,
			fetchUrlAdjustedTrialBalance: String,
			fetchUrlUnadjustedTrialBalance: String,
			fetchUrlPostClosingTrialBalance : String,
			generateClosingTransactionUrl: String,
			enableClosingTransactionUrl: String,
			setPasswordUrl : String,
		},

		mixins: [ CrudMixin, SetupMixin ],

		components: {
			'form-request': FormRequest,
		    'datepicker': Datepicker,
			'action-button': ActionButton,
			'text-editor': TextEditor,
			'subsidiary-line-table' : SubsidiaryLedgerTable,
			'trial-balance' : TrialBalance,
			'v-select' : Vselect,
			'monthly-table' : MonthlyTable,
			'journal-line-table' : JournalLineTable,
			'password-form' : PasswordForm,
		},

		data() {
			return {

				loaded : false,
				coa : null,
				authenticated : false,

				item : {},
				items : [],
				clients : [],
				
				main_accounts : [],
				filtered_main_accounts : [],

				ledgers : [],
				filtered_ledgers : [],

				ledger_calendars: [],
				filtered_ledger_calendars: [],

				opening_transaction_journals : [],
				filtered_opening_transaction_journals : [],

				adjusted_trial_balance : [],
				unadjusted_trial_balance : [],
				post_closing_trial_balance : [],

				monthly_data : [],
			}
		},

		mounted() {
			this.initFlatPickers();
		},

		watch : {
			'item.client_id'(value) {
				this.filtered_main_accounts = this.main_accounts.filter(item => item.client_id == value);
				this.filtered_ledgers = this.ledgers.filter(item => item.client_id == value);
				this.filtered_ledger_calendars = this.ledger_calendars.filter(item => item.client_id == value);
				this.filtered_opening_transaction_journals = this.opening_transaction_journals.filter(item => item.client_id == value);
			},
			

			'item.ledger_id'(value) {
				let ledger = this.ledgers.filter(item => item.id == value)[0];
				if(ledger) {
					this.coa  = ledger.chart_of_account.coa_name;
				}else {
					this.coa = null;
				}
			},

			'item.id'(id) {
				if(id) {
					axios.post(this.fetchUrlAdjustedTrialBalance, { id : id }).then((response)=>{
						this.adjusted_trial_balance = response.data;
					}).catch((error)=>{
						console.log(error);
					});

					axios.post(this.fetchUrlUnadjustedTrialBalance, { id : id }).then((response)=>{
						this.unadjusted_trial_balance = response.data;
					}).catch((error)=>{
						console.log(error);
					});

					
					axios.post(this.fetchUrlPostClosingTrialBalance, { id : id }).then((response)=>{
						this.post_closing_trial_balance = response.data;
					}).catch((error)=>{
						console.log(error);
					});
				}
			}
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.clients =  data.clients ? data.clients : this.clients;
				this.main_accounts =  data.main_accounts ? data.main_accounts : this.main_accounts;
				this.ledgers =  data.ledgers ? data.ledgers : this.ledgers;
				this.ledger_calendars =  data.ledger_calendars ? data.ledger_calendars : this.ledger_calendars;
				this.opening_transaction_journals =  data.opening_transaction_journals ? data.opening_transaction_journals : this.opening_transaction_journals;

				this.items = this.$refs['journal-line-table'].$refs['data-table'].items;

				if(!this.item.should_validate_closing_transaction) {
					this.authenticated = true;
				}
				
				setTimeout(()=>{
					this.loaded = true;
				}, 1000);
			},

			initFlatPickers() {
				flatpickr(this.$refs.period_from);
				flatpickr(this.$refs.period_to);
			},

			initClosingTransaction() {

				if(this.item.closing_transaction) {

					if(this.authenticated) {
						this.$refs['closing-transaction-form'].fetch();
					}

				}
				
			}
		},
	}
</script>