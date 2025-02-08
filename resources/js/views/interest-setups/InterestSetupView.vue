<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="submitParams">

			<card>
				<template v-slot:header>
					Interest Setup Information
					<div class="float-right">
						<action-button type="submit" :disabled="loading" class="btn btn-primary btn-sm">Save Changes</action-button>
					</div>
				</template>
				<div class="card">
					<div class="card-header p-2">	
						<div class="row">
							<div class="col-md-9">
								<ul class="nav nav-pills">
									<li class="nav-item"><a class="nav-link active" href="#bank_account" data-toggle="tab">Collection</a></li>
									<li class="nav-item"><a class="nav-link" href="#audit" data-toggle="tab">Audit Trail</a></li>
								</ul>
							</div>
						</div>
				    </div>

				     <div class="card-body">
				        <div class="tab-content">
				        	<div class="tab-pane show active" id="bank_account">
				        		<div class="row">
				        			<div class="col-12">
				        				<h4><i class="fas fa-tags"></i> General Information</h4>
				        			</div>
									<div class="col-md-4">
										<div class="form-group">
											<hr>
										</div>

										<div class="form-group">
											<label>Client <b class="text-danger">*</b></label>
											<v-select v-model="item.client_id" :reduce="item => item.id" label="name" placeholder="Select Client" :options="clients"></v-select>
										</div>

										<div class="form-group">
			        		    			<label for="interest_code">Interest Code <b class="text-danger">*</b></label>
											<input id="interest_code" name="interest_code" type="text" class="form-control" v-model="item.interest_code">
										</div>

										<div class="form-group">
			        		    			<label for="interest_name">Interest Name <b class="text-danger">*</b></label>
											<input id="interest_name" name="interest_name" type="text" class="form-control" v-model="item.interest_name">
										</div>

										<div class="form-group">
			        		    			<label for="description">Description <b class="text-danger">*</b></label>
											<input id="description" name="description" type="text" class="form-control" v-model="item.description">
										</div>

										<div class="form-group">
											<label>Interest Type <b class="text-danger">*</b></label>
											<v-select v-model="item.interest_type" placeholder="Select An Interest Type" :options="interest_types"></v-select>
										</div>

										<div class="form-group">
			        		    			<label for="grace_period">Grace Period <b class="text-danger">*</b></label>
											<input id="grace_period" name="grace_period" type="text" class="form-control" v-model="item.grace_period">
										</div>

									</div>

									<div class="col-md-4">
										<div class="form-group">
											<hr>
										</div>

										<div class="form-group">
											<label>Effective Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="effective_date" type="text" class="form-control calendar-form" name="effective_date" v-model="item.effective_date">
											</div>
										</div>

										<div class="form-group">
											<label>Expiration Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="expiration_date" type="text" class="form-control calendar-form" name="expiration_date" v-model="item.expiration_date">
											</div>
										</div>

										<div class="form-group">
											<label>Calculate Interest Every <b class="text-danger">*</b></label>
											<v-select v-model="item.calculate_interest_every" placeholder="Select A Status" :options="calculate_interest_every"></v-select>
										</div>

										<div class="form-group">
											<label>Interest Earning Debit <b class="text-danger">*</b></label>
											<v-select id="interest_earning_debit" v-model="item.interest_earning_debit" :reduce="item => item.main_account_id" label="main_account_name" placeholder="Select Main Account" :options="main_accounts">
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
											<label>Interest Range By <b class="text-danger">*</b></label>
											<v-select v-model="item.interest_range_by" placeholder="Select An Interest Range" :options="interest_ranges"></v-select>
										</div>

									</div>

									<div class="col-md-4">
										<div class="form-group">
											<hr>
										</div>

										<div class="form-group">
			        		    			<label for="interest_amount">Interest Amount <b class="text-danger">*</b></label>
											<input id="interest_amount" name="interest_amount" type="text" class="form-control" v-model="item.interest_amount">
										</div>

										<div class="form-group">
			        		    			<label for="minimum_interest_amount">Minimum Interest Amount <b class="text-danger">*</b></label>
											<input id="minimum_interest_amount" name="minimum_interest_amount" type="text" class="form-control" v-model="item.minimum_interest_amount">
										</div>

										<div class="form-group">
			        		    			<label for="maximum_interest_amount">Maximum Interest Amount <b class="text-danger">*</b></label>
											<input id="maximum_interest_amount" name="maximum_interest_amount" type="text" class="form-control" v-model="item.maximum_interest_amount">
										</div>

										<div class="form-group">
			        		    			<label for="charge_customer_when_interest_exceeds">Charge Customer When Interest Exceeds <b class="text-danger">*</b></label>
											<input id="charge_customer_when_interest_exceeds" name="charge_customer_when_interest_exceeds" type="text" class="form-control" v-model="item.charge_customer_when_interest_exceeds">
										</div>

										<div class="form-group">
			        		    			<label for="fee_amount">Fee Amount <b class="text-danger">*</b></label>
											<input id="fee_amount" name="fee_amount" type="text" class="form-control" v-model="item.fee_amount">
										</div>

										<div class="form-group">
											<label>Fee Account <b class="text-danger">*</b></label>
											<v-select id="fee_account" v-model="item.fee_account" :reduce="item => item.main_account_id" label="main_account_name" placeholder="Select Main Account" :options="main_accounts">
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
                                            <label>Sales Tax <b class="text-danger">*</b></label>
                                            <v-select 
                                                v-model="item.sales_tax" 
                                                :reduce="item => item.tax_account" 
                                                label="tax_account" 
                                                placeholder="Select A Tax" 
                                                :options="taxes"
                                            ></v-select>
                                        </div>

                                        <div class="form-group">
											<label>Interest Payment Credit Account <b class="text-danger">*</b></label>
											<v-select id="interest_payment_credit_account" v-model="item.interest_payment_credit_account" :reduce="item => item.main_account_id" label="main_account_name" placeholder="Select Main Account" :options="main_accounts">
												<template #option="{ main_account_type, main_account_category, main_account_code, main_account_name, balance_control }">
													<b>Type</b> : {{ main_account_type }} - 
													<b>Category</b> : {{ main_account_category }} - 
													<b>Code</b> : {{ main_account_code }} - 
													<b>Name</b> : {{ main_account_name }}
													<b>Balance Control</b> : {{ balance_control }}
												</template>
											</v-select>
			        		    		</div>

										<!-- <div class="form-group">
											<label>Collection Status <b class="text-danger">*</b></label>
											<v-select v-model="item.collection_status" placeholder="Select A Status" :options="collection_statuses"></v-select>
										</div>

										<div class="form-group">
                                            <label for="closed_date">Closed Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="closed_date" type="text" class="form-control" id="closed_date" name="closed_date" v-model="item.closed_date" disabled>
                                            </div>
                                        </div>

										<div class="form-group">
			        		    			<label for="closed_by">Closed By</label>
											<input id="closed_by" name="closed_by" type="text" class="form-control" v-model="item.closed_by" disabled>
										</div>

										<div class="form-group">
                                            <label>Closed</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            <input type="checkbox" class="custom-control-input" id="closed_checkbox" name="closed_checkbox" v-model="item.closed_checkbox" disabled>
                                                <label class="custom-control-label" for="closed_checkbox">
                                                    <span class="badge" :class="item.closed_checkbox ? 'badge-success' : 'badge-danger'">
                                                        {{ item.closed_checkbox ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="posted_date">Posted Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="posted_date" type="text" class="form-control" id="posted_date" name="posted_date" v-model="item.posted_date" disabled>
                                            </div>
                                        </div>

										<div class="form-group">
			        		    			<label for="posted_by">Posted By</label>
											<input id="posted_by" name="posted_by" type="text" class="form-control" v-model="item.posted_by" disabled>
										</div>

										<div class="form-group">
                                            <label>Posted</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            <input type="checkbox" class="custom-control-input" id="posted_checkbox" name="posted_checkbox" v-model="item.posted_checkbox" disabled>
                                                <label class="custom-control-label" for="closed_checkbox">
                                                    <span class="badge" :class="item.posted_checkbox ? 'badge-success' : 'badge-danger'">
                                                        {{ item.posted_checkbox ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
							    			<label>Activity Type <b class="text-danger">*</b></label>
							                <input name="activity_type" v-model="item.activity_type" type="text" class="form-control mb-2">
										</div>

										<div class="form-group">
											<label>Active Start Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="activity_start_date" type="text" class="form-control calendar-form" name="activity_start_date" v-model="item.activity_start_date">
											</div>
										</div>

										<div class="form-group">
											<label>Acitivity Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="activity_date" type="text" class="form-control calendar-form" name="activity_date" v-model="item.activity_date">
											</div>
										</div> -->

									</div>
			        		    </div>
				        	</div>
				        	<div class="tab-pane" id="audit">
				        		<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
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

		data() {
			return {
				item: {},
				main_accounts: [],
				taxes: [],
				calculate_interest_every: [
					'Day',
					'Month',
					'Calendar Day',
				],
				interest_ranges: [
					'None',
					'Amount',
					'Days',
					'Months',
					'Monthly interest %',
				],
				interest_types: [
					'Single Rate',
					'Multiple Rate',
				],
			}
		},

		mounted() {
			flatpickr(this.$refs.effective_date);
			flatpickr(this.$refs.expiration_date);
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.main_accounts = data.main_accounts ? data.main_accounts : this.main_accounts;
				this.taxes = data.taxes ? data.taxes : this.taxes;
			},
		},

		computed: {
			submitParams() {
				let item = this.item;
				return item;
			},
		},

		props: {
            clients: {
                type: Array,
                default: () => [],
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
	}

</script>