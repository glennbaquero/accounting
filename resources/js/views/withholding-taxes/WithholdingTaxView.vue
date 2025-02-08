<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<template v-slot:header>
					Header Information
					<div class="float-right">
						<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
					</div>
				</template>
				<div class="card">
					<div class="card-header p-2">
						<div class="row">
							<div class="col-md-9">
							    <ul class="nav nav-pills">
							        <li class="nav-item"><a class="nav-link active" href="#details" data-toggle="tab">Journal Details</a></li>
							        <li class="nav-item"><a class="nav-link" href="#financial" data-toggle="tab">Financial Dimensions</a></li>
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
					    	<div class="tab-pane show active" id="details">
					    		<div class="row">
					    			<div class="col-md-4">
					    				<div class="row">
				        		    		<div class="col-sm-12">
												<h4 class="mb-2"><i class="fas fa-tags"></i> Payment Schedule</h4><hr>
											</div>

											<div class="col-sm-12 mb-2">
								                <label for="withholding_tax_posting">Withholding Tax Posting <b class="text-danger">*</b></label>
								                <input type="text" class="form-control" id="withholding_tax_posting" name="withholding_tax_posting" v-model="item.withholding_tax_posting">
								            </div>

								            <div class="col-sm-12 mb-2">
								                <label for="withholding_tax_posting_name">Withholding Tax Posting Name <b class="text-danger">*</b></label>
								                <input type="text" class="form-control" id="withholding_tax_posting_name" name="withholding_tax_posting_name" v-model="item.withholding_tax_posting_name">
								            </div>

								            <div class="col-sm-12 mb-2">
								                <label for="description">Description <b class="text-danger">*</b></label>
								                <input type="text" class="form-control" id="description" name="description" v-model="item.description">
								            </div>

								            <div class="col-sm-12 mb-2">					
												<label>Effective Date <b class="text-danger">*</b></label>
												<div class="input-group mb-2">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
													</div>
													<input ref="effective_date" type="text" class="form-control calendar-form" name="effective_date" v-model="item.effective_date">
												</div>
											</div>

											<div class="col-sm-12 mb-2">					
												<label>Expiration Date <b class="text-danger">*</b></label>
												<div class="input-group mb-2">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
													</div>
													<input ref="expiration_date" type="text" class="form-control calendar-form" name="expiration_date" v-model="item.expiration_date">
												</div>
											</div>

											<div class="col-sm-12 mb-2">
								                <label for="withholding_tax_percent">Withholding Tax Percent <b class="text-danger">*</b></label>
								                <input type="text" class="form-control" id="withholding_tax_percent" name="withholding_tax_percent" v-model="item.withholding_tax_percent">
								            </div>

								            <div class="col-sm-12 mb-2">
	                                            <label>Withholding Tax Exemptions</label>
	                                            <div class="custom-control custom-switch mb-3 mt-2">
	                                            	<input type="checkbox" class="custom-control-input" id="withholding_tax_exemptions_checkbox" name="withholding_tax_exemptions_checkbox" v-model="item.withholding_tax_exemptions_checkbox">
	                                                <label class="custom-control-label" for="withholding_tax_exemptions_checkbox">
	                                                    <span class="badge" :class="item.withholding_tax_exemptions_checkbox ? 'badge-success' : 'badge-danger'">
	                                                        {{ item.withholding_tax_exemptions_checkbox ? 'Yes' : 'No'  }}
	                                                    </span>
	                                                </label>
	                                            </div>
	                                        </div>
					    				</div>
					    			</div>
					    			<div class="col-md-4">
					    				<div class="row">
				        		    		<div class="col-sm-12">
												<h4 class="mb-2"><i class="fas fa-tags"></i> Main Account</h4><hr>
											</div>

											<div class="col-sm-12">
												<label>Withholding Tax Debit Account </label>
												<v-select id="withholding_tax_debit_account" v-model="item.withholding_tax_debit_account" :reduce="item => item.main_account_id" label="main_account_name" placeholder="Select Main Account" :options="main_accounts">
													<template #option="{ main_account_type, main_account_category, main_account_code, main_account_name, balance_control }">
														<b>Type</b> : {{ main_account_type }} - 
														<b>Category</b> : {{ main_account_category }} - 
														<b>Code</b> : {{ main_account_code }} - 
														<b>Name</b> : {{ main_account_name }}
														<b>Balance Control</b> : {{ balance_control }}
													</template>
												</v-select>
												<input type="text" class="form-control" hidden name="withholding_tax_debit_account" v-model="item.withholding_tax_debit_account">
				        		    		</div>

				        		    		<div class="col-sm-12 mb-2">
								                <label for="withholding_tax_debit_account_code_number">Withholding Tax Debit Account Code Number </label>
								                <input type="text" class="form-control" id="withholding_tax_debit_account_code_number" name="withholding_tax_debit_account_code_number" v-model="item.withholding_tax_debit_account_code_number">
								            </div>

								            <div class="col-sm-12">
												<label>Withholding Tax Credit Account </label>
												<v-select id="withholding_tax_credit_account" v-model="item.withholding_tax_credit_account" :reduce="item => item.main_account_id" label="main_account_name" placeholder="Select Main Account" :options="main_accounts">
													<template #option="{ main_account_type, main_account_category, main_account_code, main_account_name, balance_control }">
														<b>Type</b> : {{ main_account_type }} - 
														<b>Category</b> : {{ main_account_category }} - 
														<b>Code</b> : {{ main_account_code }} - 
														<b>Name</b> : {{ main_account_name }}
														<b>Balance Control</b> : {{ balance_control }}
													</template>
												</v-select>
												<input type="text" class="form-control" hidden name="withholding_tax_credit_account" v-model="item.withholding_tax_credit_account">
				        		    		</div>

				        		    		<div class="col-sm-12 mb-2">
								                <label for="withholding_tax_credit_account_code_number">Withholding Tax Credit Account Code Number </label>
								                <input type="text" class="form-control" id="withholding_tax_credit_account_code_number" name="withholding_tax_credit_account_code_number" v-model="item.withholding_tax_credit_account_code_number">
								            </div>

					    				</div>
					    				
					    				
					    			</div>
					    			<div class="col-md-4">
					    				<div class="row">
		    					     		<div class="col-sm-12">
												<h4 class="mb-2"><i class="fas fa-tags"></i> Offset Account</h4><hr>
											</div>

											<div class="col-sm-12">
												<label>Withholding Tax Debit Offset Account </label>
												<v-select id="withholding_tax_debit_offset_account" v-model="item.withholding_tax_debit_offset_account" :reduce="item => item.main_account_id" label="main_account_name" placeholder="Select Main Offset Account" :options="main_accounts">
													<template #option="{ main_account_type, main_account_category, main_account_code, main_account_name, balance_control }">
														<b>Type</b> : {{ main_account_type }} - 
														<b>Category</b> : {{ main_account_category }} - 
														<b>Code</b> : {{ main_account_code }} - 
														<b>Name</b> : {{ main_account_name }}
														<b>Balance Control</b> : {{ balance_control }}
													</template>
												</v-select>
												<input type="text" class="form-control" hidden name="withholding_tax_debit_offset_account" v-model="item.withholding_tax_debit_offset_account">
				        		    		</div>

				        		    		<div class="col-sm-12 mb-2">
								                <label for="withholding_tax_debit_offset_account_code_number">Withholding Tax Debit Offset offset_Account Code Number </label>
								                <input type="text" class="form-control" id="withholding_tax_debit_offset_account_code_number" name="withholding_tax_debit_offset_account_code_number" v-model="item.withholding_tax_debit_offset_account_code_number">
								            </div>

								            <div class="col-sm-12">
												<label>Withholding Tax Credit Offset Account </label>
												<v-select id="withholding_tax_credit_offset_account" v-model="item.withholding_tax_credit_offset_account" :reduce="item => item.main_account_id" label="main_account_name" placeholder="Select Main Offset Account" :options="main_accounts">
													<template #option="{ main_account_type, main_account_category, main_account_code, main_account_name, balance_control }">
														<b>Type</b> : {{ main_account_type }} - 
														<b>Category</b> : {{ main_account_category }} - 
														<b>Code</b> : {{ main_account_code }} - 
														<b>Name</b> : {{ main_account_name }}
														<b>Balance Control</b> : {{ balance_control }}
													</template>
												</v-select>
												<input type="text" class="form-control" hidden name="withholding_tax_credit_offset_account" v-model="item.withholding_tax_credit_offset_account">
				        		    		</div>

				        		    		<div class="col-sm-12 mb-2">
								                <label for="withholding_tax_credit_offset_account_code_number">Withholding Tax Credit Offset offset_Account Code Number </label>
								                <input type="text" class="form-control" id="withholding_tax_credit_offset_account_code_number" name="withholding_tax_credit_offset_account_code_number" v-model="item.withholding_tax_credit_offset_account_code_number">
								            </div>

					    				</div>
					    			</div>
					    		</div>
					    	</div>
					    	<div class="tab-pane" id="financial">
					    		<div class="row">
					    			<div class="col-md-6">
					    				<div class="row">
						    				<div class="col-sm-12">
					            				<h4 class="mb-2"><i class="fas fa-tags"></i> Audit</h4><hr>
					            			</div>
						    				<div class="col-sm-12 mb-3">
						    				    <label>Created by</label>
						    				    <input type="text" class="form-control" :value="item.created_by" disabled>
						    				</div>
						    				<div class="col-sm-12 mb-3">
						    				    <label>Created on</label>
						    				    <input type="text" class="form-control" :value="item.created_at" disabled>
						    				</div>
						    				<div class="col-sm-12 mb-3">
						    				    <label>Updated by</label>
						    				    <input type="text" class="form-control" :value="item.updated_by" disabled>
						    				</div>
						    				<div class="col-sm-12 mb-3">
						    				    <label>Updated on</label>
						    				    <input type="text" class="form-control" :value="item.updated_at" disabled>
						    				</div>
						    			</div>
					    			</div>
					    		</div>
					    	</div>
					    	
					    </div>
					</div>
				</div>

			</card>
		</form-request>
	</div>
</template>
<script type="text/javascript">

	import CrudMixin from 'Mixins/crud.js';
	import { ModelListSelect } from 'vue-search-select';
	import Vselect from 'vue-select';

	import flatpickr from 'flatpickr';
	import 'flatpickr/dist/flatpickr.css';

	export default {
		props: {
			clients: Array,
		},

		mixins: [ CrudMixin ],

		components: {
			ModelListSelect,
			'v-select' : Vselect
		},

		mounted() {
			flatpickr(this.$refs.effective_date);
			flatpickr(this.$refs.expiration_date);
		},

		watch: {
			'item.withholding_tax_debit_account'(value) {
				let account = this.main_accounts.find((main) => {
					return value == main.main_account_id;
				});

				if(account) {
					this.item.withholding_tax_debit_account_code_number = account.main_account_code_number;
				}
			},

			'item.withholding_tax_credit_account'(value) {
				let account = this.main_accounts.find((main) => {
					return value == main.main_account_id;
				});

				if(account) {
					this.item.withholding_tax_credit_account_code_number = account.main_account_code_number;
				}
			},

			'item.withholding_tax_debit_offset_account'(value) {
				let account = this.main_accounts.find((main) => {
					return value == main.main_account_id;
				});

				if(account) {
					this.item.withholding_tax_debit_offset_account_code_number = account.main_account_code_number;
				}
			},

			'item.withholding_tax_credit_offset_account'(value) {
				let account = this.main_accounts.find((main) => {
					return value == main.main_account_id;
				});

				if(account) {
					this.item.withholding_tax_credit_offset_account_code_number = account.main_account_code_number;
				}
			},

		},

		data() {
			return {
				item: {},
				main_accounts: [],
			}
		},

		methods: {
			
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.main_accounts = data.main_accounts ? data.main_accounts : this.main_accounts;
			},

		},
	}
</script>