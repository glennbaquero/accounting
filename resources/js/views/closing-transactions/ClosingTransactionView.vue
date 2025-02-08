<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-6">
							<ul class="nav nav-pills">
								<li class="nav-item"><a class="nav-link active" href="#closing-overview" data-toggle="tab">Overview</a></li>
								<li class="nav-item"><a class="nav-link" href="#closing-details-record" data-toggle="tab">Audit Trail</a></li>
								<li class="nav-item"><a class="nav-link" href="#closing-archiving" data-toggle="tab">Archiving</a></li>
							</ul>
						</div>
						<div class="col-md-6 text-right">
					
							<action-button type="button" 
							:disabled="loading" 
							:action-url="item.canSetPassword"
							@success="openModal()"
							title="Set Closing Transaction Password"
							:message="'Are you sure you want to set new password for this closing transaction ?'" 
							class="btn-success">Set Password
							</action-button>	


							<action-button type="button" 
							:disabled="loading" 
							:action-url="item.reviewedUrl"
							@success="fetch()"
							title="Generate Closing Transaction"
							:message="'Are you sure you want to mark closing transaction as reviewed ?'" 
							class="btn-warning">Mark as Reviewed
							</action-button>	

							<action-button type="button" 
							:disabled="loading" 
							:action-url="item.approvedUrl"
							@success="fetch()"
							title="Generate Closing Transaction"
							:message="'Are you sure you want to approve closing transaction ?'" 
							class="btn-success">Approved Closing Transaction
							</action-button>	
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="tab-content">
						<div class="tab-pane show active" id="closing-overview">
							<div class="row">
								<div class="form-group col-sm-6">
									<label>General Ledger</label>
									<v-select 
									disabled 
									v-model="item.general_ledger_id" 
									:reduce="item => item.id" 
									:options="filtered_general_ledgers" 
									label="name" 
									placeholder="Select General Ledger">
									</v-select>
									<input name="general_ledger_id" hidden v-model="item.general_ledger_id">
								</div>
								<div class="form-group col-sm-6">
									<label>Opening Transaction</label>
									<input :value="item.opening_transaction" class="form-control" readonly>
								</div>		
							</div>
							<div class="row">
								<div class="form-group col-sm-4">
									<label>Client</label>
									<v-select
									v-model="item.client_id" 
									:reduce="item => item.id" 
									:options="clients" 
									label="name" 
									placeholder="Select Client">
									</v-select>
									<input name="client_id" hidden v-model="item.client_id">
								</div>
								<div class="form-group col-sm-4">
									<label>Ledger</label>
									<v-select 
									v-model="item.ledger_id" 
									:reduce="item => item.id" 
									:options="filtered_ledgers" 
									label="ledger_name" 
									:resetOnOptionsChange="loaded" 
									placeholder="Select Ledger">
									</v-select>
									<input name="ledger_id" hidden v-model="item.ledger_id"> 
								</div>
								<div class="form-group col-sm-4">
									<label>Closing Date</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input ref="due_date" type="text" class="form-control calendar-form" name="closing_date" v-model="item.closing_date" readonly>
									</div>
								</div>

								<div class="form-group col-sm-4">
									<label>Closing Period Status</label>
									<v-select 
									v-model="item.closing_status" 
									:reduce="item => item.value" 
									label ="name" :options="closing_statuses" 
									placeholder="Select Closing Period Status">
									</v-select>
									<input name="closing_status" hidden v-model="item.closing_status">
								</div>

								<div class="form-group col-sm-4">
									<label>Closing Period Start</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input ref="closing_period_start" type="text" class="form-control calendar-form" name="closing_period_start" v-model="item.closing_period_start">
									</div>
								</div>

								<div class="form-group col-sm-4">
									<label>Closing Period End</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input readonly ref="closing_period_end" type="text" class="form-control calendar-form" name="closing_period_end" v-model="item.closing_period_end">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="form-group col-sm-6">
									<label>Income Summary Account from Income</label>
									<input readonly class="form-control" :value="item.income_summary_account_from_income">
								</div>
								<div class="form-group col-sm-6">
									<label>Income Summary Account from Income Amount</label>
									<input readonly class="form-control" :value="item.income_summary_account_from_income_amount">
								</div>
							</div>

							<div class="row">
								<div class="form-group col-sm-6">
									<label>Income Summary Account from Expense</label>
									<input readonly class="form-control" :value="item.income_summary_account_from_expense">
								</div>
								<div class="form-group col-sm-6">
									<label>Income Summary Account from Expense Amount</label>
									<input readonly class="form-control" :value="item.income_summary_account_from_expense_amount">
								</div>
							</div>

							<div class="row">
								<div class="form-group col-sm-6">
									<label>Income Summary Account</label>
									<input readonly class="form-control" :value="item.income_summary_account">
								</div>
								<div class="form-group col-sm-6">
									<label>Income Summary Amount</label>
									<input readonly class="form-control" :value="item.income_summary_amount">
								</div>
							</div>

							<div class="row">
								<div class="form-group col-sm-6">
									<label>Retained Earnings Account</label>
									<input readonly class="form-control" :value="item.retained_earnings_account">
								</div>
								<div class="form-group col-sm-6">
									<label>Retained Earnings Amount</label>
									<input readonly class="form-control" :value="item.retained_earnings_amount">
								</div>
							</div>

							<div class="row">
								<div class="form-group col-sm-6">
									<label>Dividends Account</label>
									<input readonly class="form-control" :value="item.dividends_account">
								</div>
								<div class="form-group col-sm-6">
									<label>Dividends Amount</label>
									<input readonly class="form-control" :value="item.dividends_amount">
								</div>
							</div>
						</div>

						<div class="tab-pane show" id="closing-details-record">

							<div class="row">
								<div class="form-group col-sm-4">
									<label>Password Set By</label>
									<input readonly class="form-control" :value="item.password_set_by_fullname"> 
								</div>

								<div class="form-group col-sm-4">
									<label>Password Set On</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input readonly class="form-control" :value="item.password_set_on"> 
									</div>
								</div>

								<div class="form-group col-sm-4 text-center">
									<label>Password</label>
									<h5>
										<span class="badge" :class="item.approved_by && item.approved_on ? 'badge-success' : 'badge-danger'">
											{{ item.pasword_set_on && item.pasword_set_by ? 'Yes' : 'No'  }}
										</span>
									</h5>	
								</div>
							</div>
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
										<input readonly class="form-control" :value="item.approved_on"> 
									</div>
								</div>

								<div class="form-group col-sm-4 text-center">
									<label>Approved</label>
									<h5>
										<span class="badge" :class="item.approved_by && item.approved_on ? 'badge-success' : 'badge-danger'">
											{{ item.approved_by && item.approved_on ? 'Yes' : 'No'  }}
										</span>
									</h5>	
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-4">
									<label>Reviewed By</label>
									<input readonly class="form-control" :value="item.reviewed_by_fullname"> 
								</div>

								<div class="form-group col-sm-4">
									<label>Reviewed Date</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input readonly class="form-control" :value="item.reviewed_on"> 
									</div>
								</div>

								<div class="form-group col-sm-4 text-center">
									<label>Reviewed</label>
									<h5>
										<span class="badge" :class="item.reviewed_on && item.reviewed_by ? 'badge-success' : 'badge-danger'">
											{{ item.reviewed_on && item.reviewed_by ? 'Yes' : 'No'  }}
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
						<div class="tab-pane show" id="closing-archiving">
							<div class="row">
								<div class="form-group col-sm-4">
									<label>Archived By</label>
									<input readonly class="form-control" :value="item.archive_accounts_payable_by_fullname"> 
								</div>

								<div class="form-group col-sm-4">
									<label>Archiving Date</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input readonly class="form-control" :value="item.archive_payables_on"> 
									</div>
								</div>

								<div class="form-group col-sm-4 text-center">
									<action-button type="button" 
									:disabled="loading" 
									:action-url="item.archive_payables_url"
									@success="fetch()"
									title="Archive Accounts Payable"
									:message="'Are you sure you want to archive accounts payable ?'" 
									class="btn-success mt-4 w-75">Archive Accounts Payable
									</action-button>	
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-4">
									<label>Archived By</label>
									<input readonly class="form-control" :value="item.archive_accounts_receivable_by_fullname"> 
								</div>

								<div class="form-group col-sm-4">
									<label>Archiving Date</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input readonly class="form-control" :value="item.archive_receivable_on"> 
									</div>
								</div>

								<div class="form-group col-sm-4 text-center">
									<action-button type="button" 
									:disabled="loading" 
									:action-url="item.archive_receivables_url"
									@success="fetch()"
									title="Archive Accounts Payables"
									:message="'Are you sure you want to archive accounts payable ?'" 
									class="btn-success mt-4 w-75">Archive Accounts Receivable
									</action-button>	
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-4">
									<label>Archived By</label>
									<input readonly class="form-control" :value="item.archive_accounts_inventories_by_fullname"> 
								</div>

								<div class="form-group col-sm-4">
									<label>Archiving Date</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input readonly class="form-control" :value="item.archive_inventories_on"> 
									</div>
								</div>
								<div class="form-group col-sm-4 text-center">
									<action-button type="button" 
									:disabled="loading" 
									:action-url="item.archive_inventories_url"
									@success="fetch()"
									title="Archive Accounts Inventories"
									:message="'Are you sure you want to archive inventories ?'" 
									class="btn-success mt-4 w-75">Archive Inventories
									</action-button>	
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-4 ">
									<label>Archived By</label>
									<input readonly class="form-control" :value="item.archive_cash_and_bank_by_fullname"> 
								</div>

								<div class="form-group col-sm-4">
									<label>Archiving Date</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input readonly class="form-control" :value="item.archive_cash_and_bank_on"> 
									</div>
								</div>

								<div class="form-group col-sm-4 text-center">
									<action-button type="button" 
									:disabled="loading" 
									:action-url="item.archive_cash_and_bank_url"
									@success="fetch()"
									title="Archive Cash and Bank"
									:message="'Are you sure you want to archive cash and bank ?'" 
									class="btn-success mt-4 w-75">Archive Cash & Bank
									</action-button>	
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-4">
									<label>Archived By</label>
									<input readonly class="form-control" :value="item.archive_general_ledgers_by_fullname"> 
								</div>

								<div class="form-group col-sm-4">
									<label>Archiving Date</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										</div>
										<input readonly class="form-control" :value="item.archive_general_ledger_on"> 
									</div>
								</div>

								<div class="form-group col-sm-4 text-center">
									<action-button type="button" 
									:disabled="loading" 
									:action-url="item.archive_general_ledger_url"
									@success="fetch()"
									title="Archive General Ledger"
									:message="'Are you sure you want to archive general ledger ?'" 
									class="btn-success mt-4 w-75">Archive General Ledger
									</action-button>	
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

		<form-request :submit-url="setPasswordUrl" @load="load" @success="passwordSet" confirm-dialog sync-on-success>
			<div class="modal fade" tabindex="-1" id="closing-transaction-set-password" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Set Password</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<p>We strongly recommends setting a password to protect transactions dated on or before the closing date.</p>

							<div class="row">
								<div class="col-md-12">
									<label>Password</label>
									<div class="input-group mb-3">
										<input hidden name="id"  v-model="item.id">
										<input class="form-control" :type="password_field_type" name="password" v-model="password">
										<div class="input-group-prepend">
											<button @click="switchVisibility('password')" class="btn btn-secondary" type="button">
												<template v-if="password_field_type != 'password'">
													<i class="fas fa-eye"></i>	
												</template>
												<template v-else>
													<i class="fas fa-eye-slash"></i>
												</template>
											</button>
										</div>
									</div>
									<label>Confirm Password</label>
									<div class="input-group mb-3">
										<input class="form-control" :type="confirm_password_field_type" name="password_confirmation" v-model="confirm_password">
										<div class="input-group-prepend">
											<button @click="switchVisibility('confirm_password')" class="btn btn-secondary" type="button">
												<template v-if="confirm_password_field_type != 'password'">
													<i class="fas fa-eye"></i>	
												</template>
												<template v-else>
													<i class="fas fa-eye-slash"></i>
												</template>
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
	import FormRequest from 'Components/forms/FormRequest.vue';
	import Vselect from 'vue-select';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import TextEditor from 'Components/inputs/TextEditor.vue';

	export default {

		props : {
			setPasswordUrl : String,
		},

		mixins: [ CrudMixin, SetupMixin ],

		components: {
			'form-request': FormRequest,
			'action-button': ActionButton,
			'text-editor': TextEditor,
			'v-select' : Vselect,
		},

		mounted() {
			flatpickr(this.$refs.closing_period_start);
			flatpickr(this.$refs.closing_period_end);
		},

		data() {
			return {
				password : null,
				password_field_type : 'password',
				
				confirm_password : null,
				confirm_password_field_type : 'password',

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
				closing_statuses : [],
			}
		},

		watch : {
			'item.client_id'(value) {
				this.filtered_main_accounts = this.main_accounts.filter(item => item.client_id == value);
				this.filtered_ledgers = this.ledgers.filter(item => item.client_id == value);
				this.filtered_ledger_calendars = this.ledger_calendars.filter(item => item.client_id == value);
				this.filtered_general_ledgers = this.general_ledgers.filter(item => item.client_id == value);
			},
			
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.clients =  data.clients ? data.clients : this.clients;
				this.main_accounts =  data.main_accounts ? data.main_accounts : this.main_accounts;
				this.ledgers =  data.ledgers ? data.ledgers : this.ledgers;
				this.general_ledgers =  data.general_ledgers ? data.general_ledgers : this.general_ledgers;
				this.closing_statuses =  data.closing_statuses ? data.closing_statuses : this.closing_statuses;

				setTimeout(()=>{
					this.loaded = true;
				}, 1000);
			},

			switchVisibility(type) {
				
				if(type == 'password') {
					this.password_field_type = this.password_field_type === "password" ? "text" : "password";
				}

				if(type == 'confirm_password') {
					this.confirm_password_field_type = this.confirm_password_field_type === "password" ? "text" : "password";
				}
				
			},

			openModal() {
				
				$('#closing-transaction-set-password').modal('show'); 

			},

			passwordSet() {
				
				$('#closing-transaction-set-password').modal('hide'); 

				swal.fire({
					icon: 'success',
					title: 'Password Set',
					text: 'You successfully set password for this closing transaction',
				})

				this.fetch();

			}
		},
	}
</script>