<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="submitParams">

			<card>
				<template v-slot:header>
					Collection Information
					<div class="float-right">
						<action-button type="submit" :disabled="loading" class="btn btn-primary btn-sm">Save Changes</action-button>
						<action-button
	                        v-if="item.postUrl"
							small
	                        color="btn-success"
	                        :action-url="item.postUrl"
	                        confirm-dialog
	                        title="Post Item"
	                        :message="`Are you sure you want to post ${item.collection_id}?`"
	                        @load="load"
	                        @success="fetch"
	                        :disabled="item.posted_checkbox || loading"
                        >Post</action-button>
					</div>
				</template>
				<div class="card">
					<div class="card-header p-2">	
						<div class="row">
							<div class="col-md-9">
								<ul class="nav nav-pills">
									<li class="nav-item"><a class="nav-link active" href="#bank_account" data-toggle="tab">Collection</a></li>
									<li class="nav-item"><a class="nav-link" href="#financial" data-toggle="tab">Financial Dimensions</a></li>
								</ul>
							</div>
						</div>
				    </div>

				     <div class="card-body">
				        <div class="tab-content">
				        	<div class="tab-pane show active" id="bank_account">
				        		<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<h4><i class="fas fa-tags"></i> General Info</h4><hr>
										</div>

										<div class="form-group">
											<label>Client <b class="text-danger">*</b></label>
											<v-select v-model="item.client_id" :reduce="item => item.id" label="name" placeholder="Select Client" :options="clients"></v-select>
										</div>

										<div class="form-group">
			        		    			<label for="interest_note">Interest Note <b class="text-danger">*</b></label>
											<input id="interest_note" name="interest_note" type="text" class="form-control" v-model="item.interest_note">
										</div>

										<div class="form-group">
											<label>Interest Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="interest_date" type="text" class="form-control calendar-form" name="interest_date" v-model="item.interest_date">
											</div>
										</div>

										<div class="form-group">
											<label>Interest Date Updated <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="interest_updated_date" type="text" class="form-control calendar-form" name="interest_updated_date" v-model="item.interest_updated_date">
											</div>
										</div>

										<div class="form-group">
											<label>Start Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="start_date" type="text" class="form-control calendar-form" name="start_date" v-model="item.start_date">
											</div>
										</div>

										<div class="form-group">
											<label>End Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="end_date" type="text" class="form-control calendar-form" name="end_date" v-model="item.end_date">
											</div>
										</div>

										<div class="form-group">
			        		    			<label for="days">Days <b class="text-danger">*</b></label>
											<input id="days" name="days" type="text" class="form-control" v-model="item.days">
										</div>

										<div class="form-group">
			        		    			<label for="description">Description <b class="text-danger">*</b></label>
											<input id="description" name="description" type="text" class="form-control" v-model="item.description">
										</div>

										<div class="form-group">
			        		    			<label for="interest_note_voucher">Interest Note Voucher <b class="text-danger">*</b></label>
											<input id="interest_note_voucher" name="interest_note_voucher" type="text" class="form-control" v-model="item.interest_note_voucher">
										</div>

									</div>

									<div class="col-md-3">
										<div class="form-group">
											<h4><i class="fas fa-tags"></i> Fees</h4><hr>
										</div>

										<div class="form-group">
			        		    			<label for="fee_note">Fee Note <b class="text-danger">*</b></label>
											<input id="fee_note" name="fee_note" type="text" class="form-control" v-model="item.fee_note">
										</div>

										<div class="form-group">
			        		    			<label for="fee_write_off_amount">Fee Write Off Amount <b class="text-danger">*</b></label>
											<input id="fee_write_off_amount" name="fee_write_off_amount" type="text" class="form-control" v-model="item.fee_write_off_amount">
										</div>

										<div class="form-group">
											<label>Fee Adjustment Status <b class="text-danger">*</b></label>
											<v-select v-model="item.fee_adjustment_status" placeholder="Select A Status" :options="fee_adjustment_statuses"></v-select>
										</div>

										<div class="form-group">
			        		    			<label for="total">Total <b class="text-danger">*</b></label>
											<input id="total" name="total" type="text" class="form-control" v-model="item.total">
										</div>

										<div class="form-group">
			        		    			<label for="sales_tax_amount">Sales Tax Amount <b class="text-danger">*</b></label>
											<input id="sales_tax_amount" name="sales_tax_amount" type="text" class="form-control" v-model="item.sales_tax_amount">
										</div>

										<div class="form-group">
											<hr><h4><i class="fas fa-tags"></i> invoice</h4><hr>
										</div>

										<div class="form-group">
											<label>Invoice Number <b class="text-danger">*</b></label>
											<v-select v-model="item.invoice_number" :reduce="item => item.customer_invoice_number" label="customer_invoice_number" placeholder="Select Invoice" :options="invoices"></v-select>
										</div>

										<div class="form-group">
											<label>Invoice Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="invoice_date" type="text" class="form-control calendar-form" name="invoice_date" v-model="item.invoice_date">
											</div>
										</div>

										<div class="form-group">
											<label>Invoice Due Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="invoice_due_date" type="text" class="form-control calendar-form" name="invoice_due_date" v-model="item.invoice_due_date">
											</div>
										</div>

										<div class="form-group">
			        		    			<label for="original_amount">Original Amount <b class="text-danger">*</b></label>
											<input id="original_amount" name="original_amount" type="text" class="form-control" v-model="item.original_amount">
										</div>

										<div class="form-group">
			        		    			<label for="amount_of_interest">Amount of Interest <b class="text-danger">*</b></label>
											<input id="amount_of_interest" name="amount_of_interest" type="text" class="form-control" v-model="item.amount_of_interest">
										</div>

										<div class="form-group">
                                            <label>Interest</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            <input type="checkbox" class="custom-control-input" id="interest" name="interest" v-model="item.interest">
                                                <label class="custom-control-label" for="interest">
                                                    <span class="badge" :class="item.interest ? 'badge-success' : 'badge-danger'">
                                                        {{ item.interest ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
			        		    			<label for="interest_on_transaction_voucher">Interest On Transaction Voucher <b class="text-danger">*</b></label>
											<input id="interest_on_transaction_voucher" name="interest_on_transaction_voucher" type="text" class="form-control" v-model="item.interest_on_transaction_voucher">
										</div>

										<div class="form-group">
			        		    			<label for="voucher">Voucher <b class="text-danger">*</b></label>
											<input id="voucher" name="voucher" type="text" class="form-control" v-model="item.voucher">
										</div>

										<div class="form-group">
			        		    			<label for="written_off">Written Off <b class="text-danger">*</b></label>
											<input id="written_off" name="written_off" type="text" class="form-control" v-model="item.written_off">
										</div>

									</div>

									<div class="col-md-3">
										<div class="form-group">
											<h4><i class="fas fa-tags"></i> Status</h4><hr>
										</div>

										<div class="form-group">
											<label>Interest Note Status <b class="text-danger">*</b></label>
											<v-select v-model="item.interest_note_status" placeholder="Select A Status" :options="interest_note_statuses"></v-select>
										</div>

										<div class="form-group">
											<label>Adjustment Status <b class="text-danger">*</b></label>
											<v-select v-model="item.adjustment_status" placeholder="Select A Status" :options="adjustment_statuses"></v-select>
										</div>

										<div class="form-group">
											<label>Canceled</label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="canceled" type="text" class="form-control calendar-form" name="canceled" v-model="item.canceled">
											</div>
										</div>

										<div class="form-group">
                                            <label>Block</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            <input type="checkbox" class="custom-control-input" id="block" name="block" v-model="item.block">
                                                <label class="custom-control-label" for="block">
                                                    <span class="badge" :class="item.block ? 'badge-success' : 'badge-danger'">
                                                        {{ item.block ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Posted Checkbox</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            <input type="checkbox" class="custom-control-input" id="posted_checkbox" name="posted_checkbox" :checked="item.posted_checkbox" disabled>
                                                <label class="custom-control-label" for="posted_checkbox">
                                                    <span class="badge" :class="item.posted_checkbox ? 'badge-success' : 'badge-danger'">
                                                        {{ item.posted_checkbox ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
			        		    			<label for="posted_date">Posted Date</label>
											<input id="posted_date" name="posted_date" type="text" class="form-control" :value="item.posted_date" disabled>
										</div>

										<div class="form-group">
			        		    			<label for="posted_by">Posted By</label>
											<input id="posted_by" name="posted_by" type="text" class="form-control" :value="item.posted_by" disabled>
										</div>

										<div class="form-group">
											<label>Posting Profile From <b class="text-danger">*</b></label>
											<v-select v-model="item.posting_profile_from" placeholder="Select Posting Profile From" :options="posting_profile_from"></v-select>
										</div>

										<div class="form-group">
											<label>Posting Profile <b class="text-danger">*</b></label>
											<v-select 
												v-model="item.customer_posting_profile_id" 
												:reduce="item => item.id" 
												label="posting_profile" 
												placeholder="Select Posting Profile" 
												:options="posting_profiles"
												:disabled="item.posting_profile_from != 'Select'"
											></v-select>
										</div>

									</div>

									<div class="col-md-3">
										<div class="form-group">
											<h4><i class="fas fa-tags"></i> Customer</h4><hr>
										</div>

										<div class="form-group">
											<label>Customer Account <b class="text-danger">*</b></label>
											<v-select v-model="item.customer_account" :reduce="item => item.customer_account" label="fullname" placeholder="Select Customer" :options="customers"></v-select>
										</div>

										<div class="form-group">
			        		    			<label for="location_id">Location ID</label>
											<input id="location_id" name="location_id" type="text" class="form-control" v-model="item.location_id">
										</div>

										<div class="form-group">
			        		    			<label for="name_or_description">Name or description</label>
											<input id="name_or_description" name="name_or_description" type="text" class="form-control" v-model="item.name_or_description">
										</div>

										<div class="form-group">
			        		    			<label for="street">Street</label>
											<input id="street" name="street" type="text" class="form-control" v-model="item.street">
										</div>

										<div class="form-group">
			        		    			<label for="zip_post_code">Zip Post Code</label>
											<input id="zip_post_code" name="zip_post_code" type="text" class="form-control" v-model="item.zip_post_code">
										</div>

										<div class="form-group">
			        		    			<label for="city">City</label>
											<input id="city" name="city" type="text" class="form-control" v-model="item.city">
										</div>

										<div class="form-group">
			        		    			<label for="county">County</label>
											<input id="county" name="county" type="text" class="form-control" v-model="item.county">
										</div>

										<div class="form-group">
			        		    			<label for="state">State</label>
											<input id="state" name="state" type="text" class="form-control" v-model="item.state">
										</div>

										<div class="form-group">
			        		    			<label for="country_region">Country/region</label>
											<input id="country_region" name="country_region" type="text" class="form-control" v-model="item.country_region">
										</div>

										<div class="form-group">
			        		    			<label for="address">Address</label>
											<input id="address" name="address" type="text" class="form-control" v-model="item.address">
										</div>

									</div>
			        		    </div>
				        	</div>
				        	<div class="tab-pane" id="financial">
				        		<div class="row">
				        			<div class="form-group col-sm-6">
										<h4><i class="fas fa-hand-holding-usd"></i> Financial Dimension</h4><hr>
										<div class="form-group">
											<label>Cost Center <b class="text-danger">*</b></label>
											<v-select v-model="item.cost_center" :options="cost_centers" label="dimension_name" placeholder="Select Cost Center" :reduce="item => item.id" class="mb-2"></v-select> 
										</div>
										<div class="form-group">
											<label>Department <b class="text-danger">*</b></label>
											<v-select v-model="item.department" :options="departments" label="dimension_name" placeholder="Select Department" :reduce="item => item.id" class="mb-2"></v-select>
										</div>
										<div class="form-group">
											<label>Expense Purpose <b class="text-danger">*</b></label>
											<v-select v-model="item.expense_purpose" :options="expense_purposes" label="dimension_name" placeholder="Select Expense Purpose" :reduce="item => item.id" class="mb-2"></v-select>
										</div>
		        		    			<label>Posting Profile</label> 
		        		    			<v-select v-model="item.posting_profile" :reduce="item => item.id" label="posting_profile" :options="posting_profiles"></v-select>
		        		                <input name="posting_profile" v-model="item.posting_profile" type="hidden" class="form-control mb-2">
		        		    			
		        		    			<label>Document</label>
		        		    			<input type="text" class="form-control mb-2" name="document" v-model="item.document">
		        		    			<label>Document Status</label>
		        		    			<input type="text" class="form-control mb-2" name="document_status" v-model="item.document_status">

		        		    			<label>Accouting Distribution</label>
		        		                <input name="accouting_distribution" v-model="item.accouting_distribution" type="text" class="form-control mb-2">
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
				customers: [],
				fee_adjustment_statuses: [
					'Waived',
					'Reinstated',
					'None',
				],
				interest_note_statuses: [
					'Created',
					'Posted',
					'Canceled',
				],
				adjustment_statuses: [
					'Waived',
					'Reinstated',
					'Reveresed',
					'None',
				],
				posting_profile_from: [
					'Methods of Payment Customer',
					'Select',
					'Transaction',
				],
				posting_profiles: [],
				invoices: [],
				cost_centers: [],
				departments: [],
				expense_purposes: [],
			}
		},

		mounted() {
			flatpickr(this.$refs.interest_date);
			flatpickr(this.$refs.interest_updated_date);
			flatpickr(this.$refs.start_date);
			flatpickr(this.$refs.end_date);
			flatpickr(this.$refs.invoice_date);
			flatpickr(this.$refs.invoice_due_date);
			flatpickr(this.$refs.canceled);
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.customers = data.customers ? data.customers : this.customers;
				this.posting_profiles = data.posting_profiles ? data.posting_profiles : this.posting_profiles;
				this.invoices = data.invoices ? data.invoices : this.invoices;
				this.cost_centers = data.cost_centers ? data.cost_centers : this.cost_centers;
				this.departments = data.departments ? data.departments : this.departments;
				this.expense_purposes = data.expense_purposes ? data.expense_purposes : this.expense_purposes;
			},
		},

		watch: {
			// 'item.customer_account'(val) {
			// 	_.each(this.customers, (customer) => {
			// 		if(customer.customer_account == val) {
			// 			this.item.invoice_account = customer.customer_account;
			// 			this.item.customer_name = customer.fullname;
			// 			this.item.customer_address = `${customer.shipping_street}, ${customer.shipping_city}, ${customer.shipping_province}, ${customer.shipping_postal_code}, ${customer.shipping_country}`;
			// 			this.item.customer_contact_id = customer.fullname;
			// 		} else {
			// 			this.item.invoice_account = null;
			// 			this.item.customer_name = null;
			// 			this.item.customer_address = null;
			// 			this.item.customer_contact_id = null;
			// 		}
			// 	})
			// },
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