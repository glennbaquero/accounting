<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="submitParams">

			<card>
				<template v-slot:header>
					Bank Account Information
					<div class="float-right">
						<action-button type="submit" :disabled="loading" class="btn btn-primary btn-sm">Save Changes</action-button>
					</div>
				</template>
				<div class="card">
					<div class="card-header p-2">	
						<div class="row">
							<div class="col-md-9">
								<ul class="nav nav-pills">
									<li class="nav-item"><a class="nav-link active" href="#bank_account" data-toggle="tab">Bank Account</a></li>
									<li class="nav-item"><a class="nav-link" href="#financial" data-toggle="tab">Financial Dimensions</a></li>
									<li class="nav-item"><a class="nav-link" href="#customer-payments" data-toggle="tab">Customer Payments</a></li>
									<li class="nav-item"><a class="nav-link" href="#checks" data-toggle="tab">Checks</a></li>
									<li class="nav-item"><a class="nav-link" href="#deposits" data-toggle="tab">Deposits</a></li>
								</ul>
							</div>
						</div>
				    </div>

				     <div class="card-body">
				        <div class="tab-content">
				        	<div class="tab-pane show active" id="bank_account">
				        		<div class="row">
				        			<div class="col-md-4">
		        		    			<h4 class="mb-2"><i class="fas fa-user"></i> Customer</h4><hr>
			        		    		<div class="form-group">
											<label>Client <b class="text-danger">*</b></label>
											<v-select ref="client_select" v-model="item.client_id" :reduce="item => item.id" label="name" placeholder="Select Client" :options="clients"></v-select>
			        		    		</div>

										<div class="form-group mb-2">
			        		    			<label>Customer Account <b class="text-danger">*</b></label>
											<input type="text" class="form-control" :value="customer.customer_account" readonly>
										</div>

										<hr>
										<h4 class="mb-2"><i class="fas fa-money-check-alt"></i> Bank Account</h4><hr>

										<div class="form-group mb-2">
			        		    			<label for="bank_account">Bank Account  <b class="text-danger">*</b></label>
											<input id="bank_account" name="bank_account" type="text" class="form-control" :value="item.bank_account" readonly>
										</div>

		        		    			<div class="form-group mb-2">
			        		    			<label for="bank_account_number">Bank Account Number  <b class="text-danger">*</b></label>
											<input id="bank_account_number" name="bank_account_number" type="text" class="form-control" v-model="item.bank_account_number">
										</div>

										<div class="form-group mb-2">
			        		    			<label for="account_holder">Account Holder <b class="text-danger">*</b></label>
											<input id="account_holder" name="account_holder" type="text" class="form-control" v-model="item.account_holder">
										</div>

										<div class="form-group mb-2">
			        		    			<label for="bank_account_type">Bank Account Type  <b class="text-danger">*</b></label>
											<input id="bank_account_type" name="bank_account_type" type="text" class="form-control" v-model="item.bank_account_type">
										</div>

										<hr>
										<h4 class="mb-2"><i class="fas fa-university"></i> Bank</h4><hr>

		        		    			<div class="form-group mb-2">
			        		    			<label for="routing_number">Routing Number</label>
											<input id="routing_number" name="routing_number" type="text" class="form-control" v-model="item.routing_number">
										</div>

										<div class="form-group">
											<label>Bank Name <b class="text-danger">*</b></label>
											<v-select ref="bank_select" v-model="item.bank_name" :reduce="item => item.bank_name" label="bank_name" placeholder="Select Bank" :options="banks"></v-select>
			        		    		</div>

										<div class="form-group mb-2">
			        		    			<label for="bank_branch">Bank Branch <b class="text-danger">*</b></label>
											<input id="bank_branch" name="bank_branch" type="text" class="form-control" v-model="item.bank_branch">
										</div>

										<div class="form-group mb-2">
			        		    			<label for="swift_code">Swift Code</label>
											<input id="swift_code" name="swift_code" type="text" class="form-control" v-model="item.swift_code">
										</div>

										<div class="form-group mb-2">
			        		    			<label for="iban">IBAN</label>
											<input id="iban" name="iban" type="text" class="form-control" v-model="item.iban">
										</div>

			        		    	</div>

			        		    	<div class="col-md-4">
		        		    			<h4 class="mb-2"><i class="far fa-question-circle"></i> Status</h4><hr>

		        		    			<div class="form-group mb-2">
			        		    			<label for="bank_account_status">Bank Account Status</label>
											<input id="bank_account_status" name="bank_account_status" type="text" class="form-control" :value="item.bank_account_status" readonly>
										</div>

										<div class="form-group mb-2">
											<label>Active Date  <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="active_date" type="text" class="form-control calendar-form" name="active_date" v-model="item.active_date">
											</div>
										</div>

										<div class="form-group mb-2">
											<label>Expiration Date  <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="expiration_date" type="text" class="form-control calendar-form" name="expiration_date" v-model="item.expiration_date">
											</div>
										</div>

										<div class="form-group mb-2">
			        		    			<label for="credit_limit">Credit Limit</label>
											<input id="credit_limit" name="credit_limit" type="text" class="form-control" v-model="item.credit_limit">
										</div>

										<hr>
										<h4 class="mb-2"><i class="fas fas fa-tags"></i> Posting</h4><hr>
		        		    			
		        		    			<div class="form-group mb-2">
			        		    			<label for="post_fee_checkbox">Post Fee</label>
											<input id="post_fee_checkbox" name="post_fee_checkbox" type="text" class="form-control" v-model="item.post_fee_checkbox">
										</div>

										<div class="form-group mb-2">
			        		    			<label for="fee_account">Fee Account</label>
											<input id="fee_account" name="fee_account" type="text" class="form-control" v-model="item.fee_account">
										</div>

										<div class="form-group mb-2">
			        		    			<label for="clearing">Clearing</label>
											<input id="clearing" name="clearing" type="text" class="form-control" v-model="item.clearing">
										</div>

			        		    	</div>

			        		    	<div class="col-md-4">
		        		    			<h4 class="mb-2"><i class="fas fa-id-card"></i> Contact</h4><hr>

		        		    			<div class="form-group mb-2">
			        		    			<label for="address">Address</label>
											<input id="address" name="address" type="text" class="form-control" v-model="item.address">
										</div>

										<div class="form-group mb-2">
			        		    			<label for="name_of_person">Name of Person</label>
											<input id="name_of_person" name="name_of_person" type="text" class="form-control" v-model="item.name_of_person">
										</div>

										<div class="form-group mb-2">
			        		    			<label for="telephone">Telephone</label>
											<input id="telephone" name="telephone" type="text" class="form-control" v-model="item.telephone">
										</div>

										<div class="form-group mb-2">
			        		    			<label for="extension">Extension</label>
											<input id="extension" name="extension" type="text" class="form-control" v-model="item.extension">
										</div>

										<div class="form-group mb-2">
			        		    			<label for="pager">Pager</label>
											<input id="pager" name="pager" type="text" class="form-control" v-model="item.pager">
										</div>

										<div class="form-group mb-2">
			        		    			<label for="mobile_phone">Mobile Phone</label>
											<input id="mobile_phone" name="mobile_phone" type="text" class="form-control" v-model="item.mobile_phone">
										</div>

										<div class="form-group mb-2">
			        		    			<label for="fax">Fax</label>
											<input id="fax" name="fax" type="text" class="form-control" v-model="item.fax">
										</div>

										<div class="form-group mb-2">
			        		    			<label for="email">Email</label>
											<input id="email" name="email" type="text" class="form-control" v-model="item.email">
										</div>

										<div class="form-group mb-2">
			        		    			<label for="sms">SMS</label>
											<input id="sms" name="sms" type="text" class="form-control" v-model="item.sms">
										</div>

										<div class="form-group mb-2">
			        		    			<label for="internet_address">Internet Address</label>
											<input id="internet_address" name="internet_address" type="text" class="form-control" v-model="item.internet_address">
										</div>

										<div class="form-group mb-2">
			        		    			<label for="telex_number">Telex Number</label>
											<input id="telex_number" name="telex_number" type="text" class="form-control" v-model="item.telex_number">
										</div>

			        		    	</div>
			        		    </div>
				        	</div>

				        	<div class="tab-pane" id="financial">
				        		<div class="row">
		        		    		<div class="form-group col-sm-6">
										<h4><i class="fas fa-hand-holding-usd"></i> Financial Dimension</h4><hr>
		        		    			<label>Cost Center <b class="text-danger">*</b></label>
						    			<select name="cost_center" v-model="item.cost_center" class="form-control mb-2">
						    				<option :key="cost_center + index" v-for="(cost_center, index) in cost_centers" :value="cost_center.id">{{ cost_center.dimension_name }}</option>
						    			</select>
		        		    	
		        		    			<label>Department <b class="text-danger">*</b></label>
						    			<select name="department" v-model="item.department" class="form-control mb-2">
						    				<option :key="department + index" v-for="(department, index) in departments" :value="department.id">{{ department.dimension_name }}</option>
						    			</select>
		        		    		
		        		    			<label>Expense Purpose <b class="text-danger">*</b></label>
						    			<select name="expense_purpose" v-model="item.expense_purpose" class="form-control mb-2">
						    				<option :key="expense_purpose + index" v-for="(expense_purpose, index) in expense_purposes" :value="expense_purpose.id">{{ expense_purpose.dimension_name }}</option>
						    			</select>
		        		    	
		        		    			<label>Posting Profile</label>
		        		                <input name="posting_profile" v-model="item.posting_profile" type="text" class="form-control mb-2">
		        		    	
		        		    			<label>Accouting Distribution</label>
		        		                <input name="accouting_distribution" v-model="item.accouting_distribution" type="text" class="form-control mb-2">
		        		    		</div>
									<div class="form-group col-sm-6">
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

			        		<div class="tab-pane" id="customer-payments">
			        			<div class="col-xs-12">
						            <div class="card">
						                <div class="card-header p-2">
						                    <ul class="nav nav-pills">
						                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#customer_for_approval_payment" data-toggle="tab">For Approval</a></li>
						                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#customer_approved_payment" data-toggle="tab">Approved</a></li>
						                        <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#customer_posted_payment" data-toggle="tab">Posted</a></li>
						                    </ul>
						                </div>
						                <div class="card-body">
						                    <div class="tab-content">
						                        <div class="tab-pane show active" id="customer_for_approval_payment">
						                            <customer-payment-table 
						                                :clients="clients"
						                                ref="table-1"
						                                :fetch-url="customerPaymentsPending"
						                            ></customer-payment-table>
						                        </div>
						                        <div class="tab-pane" id="customer_approved_payment">
						                            <customer-payment-table 
						                                :clients="clients"
						                                ref="table-2"
						                                :fetch-url="customerPaymentsApproval"
						                                :is-approved="true"
						                            ></customer-payment-table>
						                        </div>
						                        <div class="tab-pane" id="customer_posted_payment">
						                            <customer-payment-table 
						                                :clients="clients"
						                                ref="table-3"
						                                :fetch-url="customerPaymentsPosted"
						                                :is-posted="true"
						                            ></customer-payment-table>
						                        </div>
						                    </div>
						                </div>
						            </div>
						        </div>
				        	</div>

				        	<div class="tab-pane" id="checks">
                                <div class="col-xs-12">
                                    <div class="card">
                                        <div class="card-header p-2">
                                            <ul class="nav nav-pills">
                                                <li class="nav-item"><a @click="initList('table-4')" class="nav-link active" href="#checks-active" data-toggle="tab">Active</a></li>
                                                <li class="nav-item"><a @click="initList('table-5')" class="nav-link" href="#checks-archived" data-toggle="tab">Archived</a></li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content">
                                                <div class="tab-pane show active" id="checks-active">
                                                    <check-table 
                                                        :clients="clients"
                                                        :fetch-url="checksActive"
                                                        ref="table-4"
                                                    ></check-table>
                                                </div>
                                                <div class="tab-pane" id="checks-archived">
                                                    <check-table 
                                                        :clients="clients"
                                                        :fetch-url="checksArchived"
                                                        ref="table-5"
                                                    ></check-table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="deposits">
				        		<div class="col-xs-12">
						            <div class="card">
						                <div class="card-header p-2">
						                    <ul class="nav nav-pills">
						                        <li class="nav-item"><a @click="initList('table-6')" class="nav-link active" href="#deposits-active" data-toggle="tab">Active</a></li>
						                        <li class="nav-item"><a @click="initList('table-7')" class="nav-link" href="#deposits-archived" data-toggle="tab">Archived</a></li>
						                    </ul>
						                </div>
						                <div class="card-body">
						                	<div class="tab-content">
						                		<div class="tab-pane show active" id="deposits-active">
						                			<deposit-table 
						                                :clients="clients"
						                                :fetch-url="depositsActive"
						                                ref="table-6"
						                            ></deposit-table>
						                		</div>
						                		<div class="tab-pane" id="deposits-archived">
						                			<deposit-table 
						                                :clients="clients"
						                                :fetch-url="depositsArchived"
						                                ref="table-7"
						                            ></deposit-table>
						                		</div>
						                	</div>
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
	import SetupMixin from 'Mixins/setup.js';

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
		mixins: [ CrudMixin, SetupMixin ],

		props: {
			clients: {
				default: () => [],
				type: Array,
			},
			customer: {
                default : () => {},
                type : Object,
            },

            customerPaymentsPending: String,
			customerPaymentsApproval: String,
			customerPaymentsPosted: String,

			checksActive: String,
			checksArchived: String,

			depositsActive: String,
			depositsArchived: String,
		},

		mounted() {
			this.mountInputs();
		},

		methods: {
			mountInputs() {
				let options = {
					// dateFormat: 'm/d/Y',
				};

				flatpickr(this.$refs.active_date, options);
				flatpickr(this.$refs.expiration_date, options);
			},

			fetchSuccess(data) {
				this.cost_centers = data.cost_centers ? data.cost_centers : this.cost_centers;
				this.departments = data.departments ? data.departments : this.departments;
				this.expense_purposes = data.expense_purposes ? data.expense_purposes : this.expense_purposes;
				this.banks = data.banks ? data.banks : this.banks;
				this.item = data.item ? data.item : this.item;
			},
		},

		computed: {
			submitParams() {
				let item = this.item;
				item.customer_account = this.customer.customer_account;
				// item.client_id = this.customer.client_id;
				// item.bank_account = this.customer.bank_account;

				return item;
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

		data() {
			return {
				item: {},
				cost_centers: [],
				departments: [],
				expense_purposes: [],
				banks: [],
			}
		}
	}

</script>