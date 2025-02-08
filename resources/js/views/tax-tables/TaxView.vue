<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="item">
			<card>
				<template v-slot:header>
					Header Information
					<div class="float-right">
						<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
				<!-- 		<button class="btn btn-warning" :disabled="hasItem">Approval</button>
                        <button class="btn btn-danger" :disabled="hasItem">Validate</button>
                        <button class="btn btn-success" :disabled="hasItem">Post</button> -->
					</div>
				</template>
				<div class="card">
					<div class="card-header p-2">
						<div class="row">
							<div class="col-md-9">
							    <ul class="nav nav-pills">
							        <li class="nav-item"><a class="nav-link active" href="#details" data-toggle="tab">Tax Details</a></li>
							        <!-- <li class="nav-item"><a class="nav-link" href="#financial" data-toggle="tab">Financial Dimensions</a></li> -->
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
												<h4 class="mb-2"><i class="fas fa-tags"></i> Tax Posting</h4><hr>
											</div>

											<div class="col-sm-12 mb-2">
								                <label>Tax Posting <b class="text-danger">*</b></label>
								                <input type="text" class="form-control" name="tax_posting" v-model="item.tax_posting">
								            </div>

								            <div class="col-sm-12 mb-2">
								                <label>Tax Posting Name <b class="text-danger">*</b></label>
								                <input type="text" class="form-control" name="tax_posting_name" v-model="item.tax_posting_name">
								            </div>

								            <div class="col-sm-12 mb-2">
								                <label>Description <b class="text-danger">*</b></label>
								                <input type="text" class="form-control" name="description" v-model="item.description">
								            </div>

								            <div class="col-sm-12 mb-2">
								                <label>Tax Percent <b class="text-danger">*</b></label>
								                <input type="text" class="form-control" name="tax_percent" v-model="item.tax_percent">
								            </div>

								            <div class="col-sm-12 mb-2">
		    					                <label>PEZA</label>
												<div class="custom-control custom-switch mb-3 mt-2">
													<input  type="checkbox" class="custom-control-input"  name="peza_checkbox" id="peza_checkbox" v-model="item.peza_checkbox">
													<label class="custom-control-label" for="peza_checkbox">
														<span class="badge" :class="item.peza_checkbox ? 'badge-success' : 'badge-danger'">
															{{ item.peza_checkbox ? 'Yes' : 'No'  }}
														</span>
													</label>
												</div>
		    					            </div>

		    					            <div class="col-sm-12 mb-2">
		    					                <label>Vat Exempt Number</label>
												<div class="custom-control custom-switch mb-3 mt-2">
													<input  type="checkbox" class="custom-control-input"  name="vat_exempt_number_checkbox" id="vat_exempt_number_checkbox" v-model="item.vat_exempt_number_checkbox">
													<label class="custom-control-label" for="vat_exempt_number_checkbox">
														<span class="badge" :class="item.vat_exempt_number_checkbox ? 'badge-success' : 'badge-danger'">
															{{ item.vat_exempt_number_checkbox ? 'Yes' : 'No'  }}
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

											<div class="col-sm-12 mb-2">
								                <label>Tax Account Code Number <b class="text-danger">*</b></label>
								                <input type="text" class="form-control" name="tax_account_code_number" v-model="item.tax_account_code_number">
								            </div>

											<div class="col-sm-12 mb-2">
												<label>Tax Account <b class="text-danger">*</b></label>
												<v-select v-model="item.tax_account" :reduce="item => item.main_account_id" label="main_account_name" :options="main_accounts" placeholder="Select a Tax Account"></v-select>
											</div>
					    				</div>
					    			</div>
					    			<div class="col-md-4">
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
					    	<!-- <div class="tab-pane" id="financial">
					    		<div class="row">
					    			<div class="col-md-6">
					    				<div class="row">
						    				<div class="col-sm-12">
					            				<h4 class="mb-2"><i class="fas fa-tags"></i> Financial Dimensions</h4><hr>
					            			</div>

								            <div class="col-sm-12 mb-3">
								                <label>Cost Center</label>
												<v-select v-model="item.cost_center" :reduce="item => item.financial_dimension_value_code" :options="cost_centers" label="dimension_name" placeholder="Select Cost Center" ></v-select>
												<input hidden v-model="item.cost_center" name="cost_center">
								            </div>
								            <div class="col-sm-12 mb-3">
								                <label>Department</label>
								                <v-select v-model="item.department" :reduce="item => item.financial_dimension_value_code" :options="departments" label="dimension_name" placeholder="Select Department" ></v-select>
												<input hidden v-model="item.department" name="department">
								            </div>
								            <div class="col-sm-12 mb-3">
								                <label>Expense Purpose</label>
								                <v-select v-model="item.expense_purpose" :reduce="item => item.financial_dimension_value_code" :options="expense_purposes" label="dimension_name" placeholder="Select Expense Purposes" ></v-select>
												<input hidden v-model="item.expense_purpose" name="expense_purpose">
								            </div>
					    				</div>
					    			</div>
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
					    	</div> -->
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

	export default {
		props: {
			clients: Array,
		},

		mixins: [ CrudMixin ],

		components: {
			ModelListSelect,
			'v-select' : Vselect
		},

		data() {
			return {
				item: {},
				main_accounts: [],
			}
		},

		watch: {
			'item.tax_account'(value) {
				let account = this.main_accounts.find((object) => {
					return object.main_account_id == value;
				});

				if(account) {
					this.item.tax_account_code_number = account.main_account_code_number;
				}
			},
		},

		methods: {
			
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.main_accounts = data.main_accounts ? data.main_accounts : this.main_accounts;
			},

		},
	}
</script>