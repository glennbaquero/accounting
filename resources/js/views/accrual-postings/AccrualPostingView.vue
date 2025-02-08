<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<div class="card">
				<div class="card-header">				
					<div class="row">
						<div class="col-md-9">
							<ul class="nav nav-pills">
								<li class="nav-item"><a class="nav-link active" href="#accrual-posting-information" data-toggle="tab">Accrual Posting Information</a></li>
								<li class="nav-item"><a class="nav-link" href="#accrual-posting-audit-trail" data-toggle="tab">Audit Trail</a></li>
								<li class="nav-item"><a class="nav-link" @click="initList('accrual-period-table')" href="#accrual-period-table" data-toggle="tab">Accrual Periods</a></li>
							</ul>
						</div>
						<div class="col-md-3 text-right">
							<template v-if="item.id && !item.approved_date && !item.rejected_on">
								<action-button 
								color="btn-success"
								alt-color="btn-danger"
								:show-alt="item.approved_date"
								:action-url="item.approveUrl"
								:alt-action-url="item.rejectUrl"
								confirm-dialog
								:disabled="loading"
								label="Approve"
								alt-label="Reject"
								title="Approve Accrual Posting"
								alt-title="Reject Accrual Posting"
								:message="'Are you sure you want to approve this accrual posting - ' + item.accrual_id + '?'"
								:alt-message="'Are you sure you want to reject this accrual posting - ' + item.accrual_id + '?'"
								@load="load"
								@success="fetch"
								></action-button>
								<action-button 
								color="btn-danger"
								:action-url="item.rejectUrl"
								confirm-dialog
								:disabled="loading"
								label="Reject"
								title="Reject Accrual Posting"
								:message="'Are you sure you want to reject this accrual posting - ' + item.accrual_id + '?'"
								@load="load"
								@success="fetch"
								></action-button>
							</template>
						</div>
					</div>
				</div>	
				<div class="card-body">
					 <div class="tab-content">
						<div class="tab-pane show active" id="accrual-posting-information">
							<div class="row">
								<div class="form-group col-sm-4">
									<template v-if="item.id && !item.approved_date && !item.rejected_on">
										<h5>Status : <span class="badge badge-warning">Pending</span></h5>
									</template>
									<template v-if="item.id && (item.approved_date || item.rejected_on)">
										<h5>Status : 
											<span class="badge" :class="item.approved_date ? 'badge-success' : 'badge-danger'">
											{{ item.approved_date ? 'Approved' : 'Rejected'}}
											</span>
										</h5>
									</template>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-4">
									<label>Client</label>
									<v-select v-model="item.client_id" :reduce="item => item.id" @input="clearLedgerSelection" label="name" placeholder="Select Client" :options="clients"></v-select>
									<input hidden name="client_id" v-model="item.client_id">
								</div>
								<div class="form-group col-sm-4">
									<label>Accrual ID</label>
									<input readonly name="accrual_id" class="form-control" v-model="item.accrual_id">
								</div>
								<div class="form-group col-sm-4">
									<label>Accrual Status</label>
									<v-select v-model="item.accrual_status" :options="accrual_statuses" placeholder="Select Accrual Status"></v-select>
									<input hidden name="accrual_status" class="form-control" v-model="item.accrual_status">
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-4">
									<label>Main Account</label>
									<v-select ref="main-account" v-model="item.main_account_id" :reduce="item => item.id" label="main_account_name" placeholder="Select Main Account" :options="filtered_main_accounts">
										<template #option="{ main_account_type, main_account_category, main_account_code, main_account_name, balance_control }">
											<b>Type</b> : {{ main_account_type }} - 
											<b>Category</b> : {{ main_account_category }} - 
											<b>Code</b> : {{ main_account_code }} - 
											<b>Name</b> : {{ main_account_name }}
											<b>Balance Control</b> : {{ balance_control }}
										</template>
									</v-select>
									<input hidden name="main_account_id" v-model="item.main_account_id">
								</div>
								<div class="form-group col-sm-4">
									<label>Ledger</label>
									<v-select ref='ledger' v-model="item.ledger_id" :reduce="item => item.id" @input="clearMainAccountSelection" label="ledger_name" placeholder="Select Ledger" :options="filtered_ledgers">
										<template #option="{ ledger_code, ledger_name }">
											<b>Code</b> : {{ ledger_code }} -
											<b>Name</b> : {{ ledger_name }}
										</template>
									</v-select>
									<input hidden name="ledger_id" v-model="item.ledger_id">
								</div>
								<div class="form-group col-sm-4">
									<label>Length</label>
									<input name="length" type="number" class="form-control" v-model="item.length">
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-4">
									<label>Calendar Type</label>
									<v-select v-model="item.calendar_type" placeholder="Select Calendar Type" :options="calendar_types"></v-select>
									<input hidden name="calendar_type" v-model="item.calendar_type">
								</div>
								<div class="form-group col-sm-4">
									<label>Period Frequency</label>
									<v-select :disabled="item.calendar_type == 'Fiscal' ? true : false" v-model="item.period_frequency" placeholder="Select Period Frequency" :options="period_frequencies"></v-select>
									<input hidden name="period_frequency" v-model="item.period_frequency">
								</div>
								<div class="form-group col-sm-4">
									<label>Posting Date</label>
									<v-select v-model="item.posting_date" placeholder="Select Period Frequency" :options="posting_dates"></v-select>
									<input hidden name="posting_date" v-model="item.posting_date">	
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-6">
									<label>Period Start</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input :disabled="item.calendar_type == 'Fiscal' ? true : false" ref="period_start" type="text" class="form-control calendar-form" name="period_start" v-model="item.period_start">
										<input hidden name="fiscal_period_start_date" v-model="item.fiscal_period_start_date">
									</div>
								</div>
								<div class="form-group col-sm-6">
									<label>Period End</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input :disabled="item.calendar_type == 'Fiscal' ? true : false" ref="period_end" type="text" class="form-control calendar-form" name="period_end" v-model="item.period_end">
										<input hidden name="fiscal_period_end_date" v-model="item.fiscal_period_end_date">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-6">
									<label>Ledger Posting Debit Account Number</label>
									<v-select ref="ledger-debit" v-model="item.ledger_posting_debit_account_number" :reduce="item => item.id" label="main_account_code" placeholder="Select Ledger Posting Debit Account Number" :options="filtered_main_accounts">
										<template #option="{ main_account_type, main_account_category, main_account_code, main_account_name, balance_control }">
											<b>Type</b> : {{ main_account_type }} - 
											<b>Category</b> : {{ main_account_category }} - 
											<b>Code</b> : {{ main_account_code }} - 
											<b>Name</b> : {{ main_account_name }}
											<b>Balance Control</b> : {{ balance_control }}
										</template>
									</v-select>
									<input hidden name="ledger_posting_debit_account_number" class="form-control" v-model="item.ledger_posting_debit_account_number">
								</div>
								<div class="form-group col-sm-6">
									<label>Ledger Posting Debit</label>
									<input readonly name="ledger_posting_debit" class="form-control" v-model="item.ledger_posting_debit">
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-6">
									<label>Ledger Posting Credit Account Number</label>
									<v-select ref="ledger-credit" v-model="item.ledger_posting_credit_account_number" :reduce="item => item.id" label="main_account_code" placeholder="Select Ledger Posting Debit Account Number" :options="filtered_main_accounts">
										<template #option="{ main_account_type, main_account_category, main_account_code, main_account_name, balance_control }">
											<b>Type</b> : {{ main_account_type }} - 
											<b>Category</b> : {{ main_account_category }} - 
											<b>Code</b> : {{ main_account_code }} - 
											<b>Name</b> : {{ main_account_name }}
											<b>Balance Control</b> : {{ balance_control }}
										</template>
									</v-select>
									<input hidden name="ledger_posting_credit_account_number" class="form-control" v-model="item.ledger_posting_credit_account_number">
								</div>
								<div class="form-group col-sm-6">
									<label>Ledger Posting Credit</label>
									<input readonly name="ledger_posting_credit" class="form-control" v-model="item.ledger_posting_credit">
								</div>
							</div>
							<div class='row'>
								<div class="form-group col-sm-12">
									<label>Description</label>
									<textarea class="form-control" name="description" v-model="item.description"></textarea>
								</div>
							</div>
							<div class="row text-right">
								<div class="col-md-12">
									<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
								</div>
							</div>
						</div>
						<div class="tab-pane show" id="accrual-posting-audit-trail">
							<div class="row">
								<div class="form-group col-sm-4">
									<label>Approved By</label>
									<input readonly class="form-control" :value="item.approved_by_fullname"> 
								</div>

								<div class="form-group col-sm-4">
									<label>Approved Date</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input readonly class="form-control" :value="item.approved_date"> 
									</div>
								</div>

								<div class="form-group col-sm-4 text-center">
									<label>Approve</label>
									<h5>
										<span class="badge" :class="item.approved_date ? 'badge-success' : 'badge-danger'">
											{{ item.approved_date ? 'Yes' : 'No'  }}
										</span>
									</h5>	
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-4">
									<label>Rejected By</label>
									<input readonly class="form-control" :value="item.rejected_by_fullname"> 
								</div>

								<div class="form-group col-sm-4">
									<label>Rejected Date</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input readonly class="form-control" :value="item.rejected_on"> 
									</div>
								</div>

								<div class="form-group col-sm-4 text-center">
									<label>Approve</label>
									<h5>
										<span class="badge" :class="item.rejected_on ? 'badge-success' : 'badge-danger'">
											{{ item.rejected_on ? 'Yes' : 'No'  }}
										</span>
									</h5>	
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-4">
									<label>Prepared By</label>
									<input readonly class="form-control" :value="item.created_by_fullname"> 
								</div>

								<div class="form-group col-sm-4">
									<label>Prepared Date</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input readonly class="form-control" :value="item.created_at"> 
									</div>
								</div>

								<div class="form-group col-sm-4 text-center">
									<label>Prepared</label>
									<h5>
										<span class="badge" :class="true ? 'badge-success' : 'badge-danger'">
											{{ true ? 'Yes' : 'No'  }}
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
						</div>
						<div class="tab-pane show" id="accrual-period-table">
						<accrual-period-table
						ref="accrual-period-table"
						:fetch-url="accrualPeriodFetchUrl">
						</accrual-period-table>
						</div>
					</div>
				</div>
			</div>
		</form-request>
	</div>
</template>

<script>
	import CrudMixin from 'Mixins/crud.js';
	import SetupMixin from 'Mixins/setup.js';
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import Datepicker from 'vuejs-datepicker';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import Vselect from 'vue-select';

	export default {

		mixins: [ CrudMixin, SetupMixin ],

		props : {
			id : String,
			accrualPeriodFetchUrl : String,
		},

		components: {
			'text-editor': TextEditor,
			'form-request': FormRequest,
		    'datepicker': Datepicker,
			'action-button': ActionButton,
			'v-select' : Vselect
		},

		data() {
			return {
				item: {},
				accrual_statuses : ['Active', 'Inactive'],
				calendar_types : ['Calendar', 'Fiscal'],
				period_frequencies : ['Monthly', 'Quarterly'],
				posting_dates : ['Beginning', 'Middle', 'End'],
				main_accounts : [],
				filtered_main_accounts : [],
				ledgers : [],
				filtered_ledgers : [],
				clients : [],
			}
		},

		watch : {
			'item.client_id'(value) {
				this.filtered_ledgers = this.ledgers.filter(item => item.client_id == value);
			},
			'item.ledger_id'(value) {
				let ledger = this.ledgers.filter(item => item.id == value)[0];
				if(ledger) {
					this.filtered_main_accounts = this.main_accounts.filter(item => item.chart_of_account_id == ledger.chart_of_account_id);
					// fill period if calendar type is fiscal else the periods will be manually set
					if(this.item.calendar_type == 'Fiscal') {
						this.item.fiscal_period_start_date = ledger.ledger_calendar.fiscal_calendar.fiscal_year_start_date;
						this.item.fiscal_period_end_date = ledger.ledger_calendar.fiscal_calendar.fiscal_year_end_date;
						this.item.period_start = ledger.ledger_calendar.fiscal_calendar.fiscal_year_start_date;
						this.item.period_end = ledger.ledger_calendar.fiscal_calendar.fiscal_year_end_date;
						this.item.period_frequency = ledger.ledger_calendar.fiscal_calendar.unit;
					}else {
						this.item.fiscal_period_start_date = null;
						this.item.fiscal_period_end_date = null;
						this.item.period_start = null;
						this.item.period_end = null;
					}
				}else {
					this.item.fiscal_period_start_date = null;
					this.item.fiscal_period_end_date = null;
					this.item.period_start = null;
					this.item.period_end = null;
					this.filtered_main_accounts = [];
				}
			},
			'item.calendar_type'(value) {
				let ledger_id = this.item.ledger_id;
				if(ledger_id) {
					let ledger = this.ledgers.filter(item => item.id == this.item.ledger_id)[0];
					if(ledger) {
						// fill period if calendar type is fiscal else the periods will be manually set
						if(value == 'Fiscal') {
							this.item.fiscal_period_start_date = ledger.ledger_calendar.fiscal_calendar.fiscal_year_start_date;
							this.item.fiscal_period_end_date = ledger.ledger_calendar.fiscal_calendar.fiscal_year_end_date;
							this.item.period_start = ledger.ledger_calendar.fiscal_calendar.fiscal_year_start_date;
							this.item.period_end = ledger.ledger_calendar.fiscal_calendar.fiscal_year_end_date;
							this.item.period_frequency = ledger.ledger_calendar.fiscal_calendar.unit;
						}else {
							this.item.fiscal_period_start_date = null;
							this.item.fiscal_period_end_date = null;
							this.item.period_start = null;
							this.item.period_end = null;
						}
					}else {
						this.item.fiscal_period_start_date = null;
						this.item.fiscal_period_end_date = null;
						this.item.period_start = null;
						this.item.period_end = null;
					}
				}
			},
			'item.ledger_posting_debit_account_number'(value) {
				let main_account = this.main_accounts.filter(item => item.id == value)[0]
				if(main_account) {
					this.item.ledger_posting_debit = main_account.main_account_name;
				}else {
					this.item.ledger_posting_debit = '';
				}
			},
			'item.ledger_posting_credit_account_number'(value) {
				let main_account = this.main_accounts.filter(item => item.id == value)[0]
				
				if(main_account) {
					this.item.ledger_posting_credit = main_account.main_account_name;
				}else {
					this.item.ledger_posting_credit = '';
				}
			}
		},

		mounted () {
			if(this.id) {
				this.item.accrual_id = this.id;
			}

			flatpickr(this.$refs.period_start)
			flatpickr(this.$refs.period_end)
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.clients = data.clients ? data.clients : this.clients;
				this.main_accounts = data.main_accounts ? data.main_accounts : this.main_accounts;
				this.ledgers = data.ledgers ? data.ledgers : this.ledgers
			},

			clearLedgerSelection() {
				this.$refs['ledger'].clearSelection();
				this.$refs['ledger-credit'].clearSelection();
				this.$refs['ledger-debit'].clearSelection();
			},

			clearMainAccountSelection() {
				this.$refs['main-account'].clearSelection();
				this.$refs['ledger-credit'].clearSelection();
				this.$refs['ledger-debit'].clearSelection();
			},
		},

		
	}
</script>