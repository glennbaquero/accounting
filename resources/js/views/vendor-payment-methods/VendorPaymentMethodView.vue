<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="item">

			<card>
				<template v-slot:header>
					Methods of Payment - Vendor Information
					<div class="float-right">
						<action-button type="submit" :disabled="loading" class="btn btn-primary btn-sm">Save Changes</action-button>
					</div>
				</template>
				<div class="card">
					<div class="card-header p-2">	
						<div class="row">
							<div class="col-md-9">
								<ul class="nav nav-pills">
									<li class="nav-item"><a class="nav-link active" href="#methods" data-toggle="tab">Methods of Payment - Vendor</a></li>
								</ul>
							</div>
						</div>
				    </div>

				     <div class="card-body">
				        <div class="tab-content">
				        	<div class="tab-pane show active" id="methods">
				        		<div class="row">
				        			<div class="col-md-6">
		        		    			<h4 class="mb-2"><i class="fas fa-info-circle"></i> General Info</h4><hr>
			        		    		<div class="form-group">
											<div class="form-group mb-2">
				        		    			<label for="method_of_payment_id">Method of Payment ID <b class="text-danger">*</b></label>
												<input id="method_of_payment_id" name="method_of_payment_id" type="text" class="form-control" v-model="item.method_of_payment_id">
											</div>
											
											<label>Client <b class="text-danger">*</b></label>
											<v-select ref="client_select" class="mb-2" v-model="item.client_id" :reduce="item => item.id" label="name" placeholder="Select Client" :options="clients"></v-select>

				        		    		<div class="form-group mb-2">
				        		    			<label for="account_type">Account Type <b class="text-danger">*</b></label>
												<v-select 
                                                    v-model="item.account_type"  
                                                    placeholder="Select an Account Type" 
                                                    :options="account_types"
                                                ></v-select>
											</div>

											<div class="form-group mb-2">
				        		    			<label for="method_of_payment">Method of Payment <b class="text-danger">*</b></label>
												<input id="method_of_payment" name="method_of_payment" type="text" class="form-control" v-model="item.method_of_payment">
											</div>

											<div class="form-group mb-2">
				        		    			<label for="description">Description <b class="text-danger">*</b></label>
												<input id="description" name="description" type="text" class="form-control" v-model="item.description">
											</div>

											<div class="form-group mb-2">
				        		    			<label for="document">Document <b class="text-danger">*</b></label>
												<!-- <input id="document" name="document" type="text" class="form-control" v-model="item.document"> -->
												<v-select 
                                                    v-model="item.document"  
                                                    :options="documents"
                                                ></v-select>
											</div>

											<div class="form-group mb-2">
				        		    			<label for="payment_status">Payment Status <b class="text-danger">*</b></label>
												<v-select 
                                                    v-model="item.payment_status"  
                                                    :options="payment_statuses"
                                                    name="payment_status"
                                                    id="payment_status"
                                                ></v-select>
											</div>

											<div class="form-group">
                                                <label>Postdated Check Status <b v-if="required" class="text-danger">*</b></label>
                                                <v-select 
                                                    v-model="item.postdated_check_status"  
                                                    placeholder="Select a Postdated Status" 
                                                    :options="statuses"
                                                ></v-select>
                                            </div>

											<div class="form-group">
												<label for="postdated_check_clearing_posting">Postdated Check Clearing Posting <b v-if="required" class="text-danger">*</b></label>
												<v-select id="postdated_check_clearing_posting" v-model="item.postdated_check_clearing_posting" :reduce="item => item.main_account_id" label="main_account_name" placeholder="Select Main Account" :options="filteredMainAccounts">
													<template #option="{ main_account_type, main_account_category, main_account_code, main_account_name, balance_control }">
														<b>Type</b> : {{ main_account_type }} - 
														<b>Category</b> : {{ main_account_category }} - 
														<b>Code</b> : {{ main_account_code }} - 
														<b>Name</b> : {{ main_account_name }}
														<b>Balance Control</b> : {{ balance_control }}
													</template>
												</v-select>
				        		    		</div>

											<div class="form-group">
                                                <label>Bank Posting Profile <b class="text-danger">*</b></label>
                                                <v-select 
                                                    v-model="item.bank_posting_profile"  
                                                    placeholder="Select a posting profile"
                                                    label="bank_transaction_posting" 
                                                    :options="bank_postings"
                                                    :reduce="item => item.id"
                                                ></v-select>
                                            </div>

											<div class="form-group mb-2">
				        		    			<label for="journal_name">Journal Name <b class="text-danger">*</b></label>
												<!-- <input id="journal_name" name="journal_name" type="text" class="form-control" v-model="item.journal_name"> -->
												<v-select 
                                                    v-model="item.journal_name"  
                                                    :options="journal_names"
                                                ></v-select>
											</div>

											<div class="form-group mb-2" hidden>
				        		    			<label for="payment_account">Payment Account <b class="text-danger">*</b></label>
												<input id="payment_account" name="payment_account" type="text" class="form-control" v-model="item.payment_account">
											</div>

			        		    		</div>

			        		    	</div>

									<div class="form-group col-sm-6">
										<h4><i class="fas fa-user-tie"></i> Audit</h4><hr>
		        		    			<label>Created By</label>
 										<input readonly v-model="item.created_by" type="text" class="form-control mb-2">
		        		    	
		        		    			<label>Created On</label>
										<input readonly v-model="item.created_date" type="text" class="form-control mb-2">
		        		    		
		        		    			<label>Updated By</label>
										<input readonly v-model="item.updated_by" type="text" class="form-control mb-2">
		        		    	
		        		    			<label>Updated on</label>
		        		                <input readonly v-model="item.updated_date" type="text" class="form-control">

		        		                <hr><h4><i class="fas fa-file-invoice"></i> Main Account</h4><hr>

		        		                <div class="form-group">
											<label for="payment_account">Payment Account <b class="text-danger">*</b></label>
											<v-select id="payment_account" v-model="item.payment_account" :reduce="item => item.main_account_id" label="main_account_name" placeholder="Select Main Account" :options="filteredMainAccounts">
												<template #option="{ main_account_type, main_account_category, main_account_code, main_account_name, balance_control }">
													<b>Type</b> : {{ main_account_type }} - 
													<b>Category</b> : {{ main_account_category }} - 
													<b>Code</b> : {{ main_account_code }} - 
													<b>Name</b> : {{ main_account_name }}
													<b>Balance Control</b> : {{ balance_control }}
												</template>
											</v-select>
			        		    		</div>

			        		    		<div class="form-group mb-2">
			        		    			<label for="payment_account_code">Payment Account Code</label>
											<input id="payment_account_code" name="payment_account_code" type="text" class="form-control" disabled :value="mainAccount.main_account_code">
										</div>

										<div class="form-group">
											<label for="postdated_check_account">Postdated Check Account <b v-if="required" class="text-danger">*</b></label>
											<v-select id="postdated_check_account" v-model="item.postdated_check_account" :reduce="item => item.main_account_id" label="main_account_name" placeholder="Select Main Account" :options="filteredMainAccounts">
												<template #option="{ main_account_type, main_account_category, main_account_code, main_account_name, balance_control }">
													<b>Type</b> : {{ main_account_type }} - 
													<b>Category</b> : {{ main_account_category }} - 
													<b>Code</b> : {{ main_account_code }} - 
													<b>Name</b> : {{ main_account_name }}
													<b>Balance Control</b> : {{ balance_control }}
												</template>
											</v-select>
			        		    		</div>

			        		    		<div class="form-group mb-2">
			        		    			<label for="postdated_check_account_code">Postdated Check Account Code</label>
											<input id="postdated_check_account_code" name="postdated_check_account_code" type="text" class="form-control" disabled :value="postdatedCheckAccount.main_account_code">
										</div>

										<div class="form-group">
											<label for="not_sufficient_fund_account">Not Sufficient Fund Account <b v-if="required" class="text-danger">*</b></label>
											<v-select id="not_sufficient_fund_account" v-model="item.not_sufficient_fund_account" :reduce="item => item.main_account_id" label="main_account_name" placeholder="Select Main Account" :options="filteredMainAccounts">
												<template #option="{ main_account_type, main_account_category, main_account_code, main_account_name, balance_control }">
													<b>Type</b> : {{ main_account_type }} - 
													<b>Category</b> : {{ main_account_category }} - 
													<b>Code</b> : {{ main_account_code }} - 
													<b>Name</b> : {{ main_account_name }}
													<b>Balance Control</b> : {{ balance_control }}
												</template>
											</v-select>
			        		    		</div>

			        		    		<div class="form-group mb-2">
			        		    			<label for="not_sufficient_fund_account_code">Not Sufficient Fund Code</label>
											<input id="not_sufficient_fund_account_code" name="not_sufficient_fund_account_code" type="text" class="form-control" disabled :value="notSufficientFundAccount.main_account_code">
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
		
		props : {
			code : String,
		},

		mounted() {
			this.item.method_of_payment_id = this.item.id ? this.item.methopcustd_of_payment_id : this.code;
		},

		computed: {
			mainAccount() {
				let item = this.main_accounts.find((account) => {
					return account.main_account_id == this.item.payment_account;
				});

				return item ? item : {};
			},

			postdatedCheckAccount() {
				let item = this.main_accounts.find((account) => {
					return account.main_account_id == this.item.postdated_check_account;
				});

				return item ? item : {};
			},

			notSufficientFundAccount() {
				let item = this.main_accounts.find((account) => {
					return account.main_account_id == this.item.not_sufficient_fund_account;
				});

				return item ? item : {};
			},

			required() {
				return this.item.document == 'Check';
			},
		},

		methods: {
			fetchSuccess(data) {
				this.bank_postings = data.bank_postings ? data.bank_postings : this.bank_postings;
				this.main_accounts = data.main_accounts ? data.main_accounts : this.main_accounts;
				this.clients = data.clients ? data.clients : this.clients;
				this.item = data.item ? data.item : this.item;
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

		watch: {
			'item.client_id'(value) {
				this.filteredMainAccounts = this.main_accounts.filter((data) => {
					return	data.client_id == value;
				});
			},
		},

		data() {
			return {
				item: {
					account_type: 'Bank',
				},
				main_accounts: [],
				clients : [],
				filteredMainAccounts: [],
				bank_postings: [],
				statuses: [
                    'Open',
                    'On Hold',
                    'Void',
                    'Paid',
                    'Posted',
                    'Cancelled',
                    'Pending Cancellation',
                ],
                payment_statuses: [
                	'None',
                	'Sent',
                	'Received',
                	'Approved',
                	'Rejected',
                ],
                account_types: [
                	'Ledger',
                	'Customer',
                	'Vendor',
                	'Project',
                	'Fixed Asset',
                	'Bank',
                ],
                documents: [
                	'Purchase Order',
					'Vendor Invoice',
					'Vendor Payment',
					'Purchase Order Returns',
					'Promissory Note',
					'Vendor Bank Remittance',
					'Vendor Summary Account',
					'Vendor Aging -Write Off',
					'Sales Order',
					'Sales Order To Purchase Order',
					'Customer Invoice',
					'Customer Payment',
					'Sales Order Returns',
					'Bills of Exchange',
					'Customer Bank Remittance',
					'Customer Summary Account',
					'Customer Aging -Write Off',
					'Inventory On Hand',
					'Deposit',
					'Check',
					'Collections',
					'Interest Note',
					'Bank Reconciliation',
					'Refund',
					'Reimburse',
					'Payment Reversal',
					'Payment Cancellation',
                ],
                journal_names: [
                	'Invoice Approval Journal',
					'Vendor Payment Journal',
					'Purchase Order Return Journal',
					'Promissory Note Journal',
					'Customer Invoice Journal',
					'Customer Payment Journal',
					'Sales Order Return Journal',
					'Bills of Exchange Journal',
					'Inventory Journal',
					'Bank Reconciliation Journal',
					'Reversal & Cancellation Journal',
					'General Journal',
                ],
			}
		}
	}

</script>