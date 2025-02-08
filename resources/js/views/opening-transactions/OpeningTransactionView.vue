<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<div class="card">
				<div class="card-header">
					<ul class="nav nav-pills">
						<li class="nav-item"><a class="nav-link active" href="#overview" data-toggle="tab">Overview</a></li>
						<li class="nav-item"><a class="nav-link" href="#details-record" data-toggle="tab">Details</a></li>
					</ul>
				</div>
				<div class="card-body">
					<div class="tab-content">
						<div class="tab-pane show active" id="overview">
							<div class="row">
								<div class="form-group col-sm-3">
									<label>Client</label>
									<v-select v-model="item.client_id" :reduce="item => item.id" :options="clients" label="name" placeholder="Select Client"></v-select>
									<input name="client_id" hidden v-model="item.client_id">
								</div>
								<div class="form-group col-sm-3">
									<label>Main Account</label>
									<v-select v-model="item.main_account_id" :reduce="item => item.id" label="main_account_name" placeholder="Select Main Account" :options="filtered_main_accounts">
										<template #option="{ main_account_type, main_account_category, main_account_code, main_account_name, balance_control }">
											<b>Type</b> : {{ main_account_type }} - 
											<b>Category</b> : {{ main_account_category }} - 
											<b>Code</b> : {{ main_account_code }} - 
											<b>Name</b> : {{ main_account_name }}
											<b>Balance Control</b> : {{ balance_control }}
										</template>
									</v-select>
									<input name="main_account_id" hidden v-model="item.main_account_id">
								</div>
								<div class="form-group col-sm-6">
									<label>General Ledger</label>
									<v-select disabled v-model="item.general_ledger_id" :reduce="item => item.id" :options="filtered_general_ledgers" label="name" placeholder="Select General Ledger"></v-select>
									<input name="general_ledger_id" hidden v-model="item.general_ledger_id">
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-3">
									<label>Ledger <b class="text-danger">*</b></label>
									<v-select v-model="item.ledger_id" :reduce="item => item.id" :options="filtered_ledgers" label="ledger_name" :resetOnOptionsChange="loaded" placeholder="Select Ledger"></v-select>
									<input name="ledger_id" hidden v-model="item.ledger_id"> 
								</div>
								<div class="form-group col-sm-3">
									<label>Ledger Calendar</label>
									<v-select disabled v-model="item.ledger_calendar_id" :reduce="item => item.id" :options="filtered_ledger_calendars" label="ledger_calendar_name" :resetOnOptionsChange="loaded" placeholder="Select Ledger Calendar"></v-select>
									<input name="ledger_calendar_id" hidden v-model="item.ledger_calendar_id"> 
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
									<label>Chart of Account</label>
									<input readonly class="form-control" :value="coa">
								</div>
								<div class="form-group col-sm-3">
									<label>Debit</label>
									<input readonly class="form-control" :value="balance">
								</div>
								<div class="form-group col-sm-3">
									<label>Debit</label>
									<input readonly class="form-control" :value="debit">
								</div>
								<div class="form-group col-sm-3">
									<label>Credit</label>
									<input readonly class="form-control" :value="credit">
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
										<label>Description</label>
									<text-editor name="description" v-model="item.description"></text-editor>
								</div>
							</div>
						</div>
						<div class="tab-pane show" id="details-record">.
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
									<label>Approve</label>
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
									<label>Review</label>
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
									<label>Reverse</label>
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
									<label>Adjusted Date</label>
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
										<span class="badge" :class="item.adjusted_checkbox ? 'badge-success' : 'badge-success'">
											{{ item.created_at ? 'Yes' : 'Yes'  }}
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
					</div>
				</div>
				<div class="card-footer text-right">
					<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
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
	
	import flatpickr from 'flatpickr';
	import 'flatpickr/dist/flatpickr.css';

	export default {

		props: {
			fetchUrlJournalLine: String,
			fetchUrlVendorLine: String,
			fetchUrlCustomerLine: String,
			fetchUrlGeneralLedgerSummary: String,
			fetchUrlTrialBalance: String,
		},

		mixins: [ CrudMixin, SetupMixin ],

		components: {
			'form-request': FormRequest,
		    'datepicker': Datepicker,
			'action-button': ActionButton,
			'text-editor': TextEditor,
			'v-select' : Vselect,
		},

		data() {
			return {

				loaded : false,
				coa : null,

				item : {},
				items : [],
				clients : [],
				
				main_accounts : [],
				filtered_main_accounts : [],

				general_ledgers : [],
				filtered_general_ledgers : [],

				ledgers : [],
				filtered_ledgers : [],

				ledger_calendars: [],
				filtered_ledger_calendars: [],
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
				this.filtered_general_ledgers = this.general_ledgers.filter(item => item.client_id == value);
			},

			'item.ledger_id'(value) {
				let ledger = this.ledgers.filter(item => item.id == value)[0];
				if(ledger) {
					this.coa  = ledger.chart_of_account.coa_name;
					this.item.general_ledger_id = ledger.general_ledger.id;
					this.item.ledger_calendar_id = ledger.ledger_calendar.id;
				}else {
					this.coa = null;
					this.item.general_ledger_id = null;
				}
			},
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.clients =  data.clients ? data.clients : this.clients;
				this.main_accounts =  data.main_accounts ? data.main_accounts : this.main_accounts;
				this.ledgers =  data.ledgers ? data.ledgers : this.ledgers;
				this.ledger_calendars =  data.ledger_calendars ? data.ledger_calendars : this.ledger_calendars;
				this.general_ledgers =  data.general_ledgers ? data.general_ledgers : this.general_ledgers;

				setTimeout(()=>{
					this.loaded = true;
				}, 1000);
			},

			initFlatPickers() {
				flatpickr(this.$refs.period_from);
				flatpickr(this.$refs.period_to);
			},
		},
	}
</script>