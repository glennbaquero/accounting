<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="submitParams">

			<card>
				<template v-slot:header>
					Bills Of Exchange Information
					<div class="float-right">
						<action-button type="submit" :disabled="loading" class="btn btn-primary btn-sm">Save Changes</action-button>

						<action-button
							v-model="item.approveUrl"
							small
	                        color="btn-success"
	                        :action-url="item.approveUrl"
	                        confirm-dialog
	                        title="Approve Item"
	                        :message="`Are you sure you want to approve ${item.bills_of_exchange}?`"
	                        @load="load"
	                        @success="fetch"
	                        :disabled="item.approved_checkbox || loading"
                        >Approve</action-button>

                        <action-button
							v-model="item.redrawUrl"
							small
	                        color="btn-danger"
	                        :action-url="item.redrawUrl"
	                        confirm-dialog
	                        title="Redraw Item"
	                        :message="`Are you sure you want to redraw ${item.bills_of_exchange}?`"
	                        @load="load"
	                        @success="fetch"
	                        :disabled="loading"
                        >Redraw</action-button>

                        <action-button
							v-model="item.remitUrl"
							small
	                        color="btn-warning"
	                        :action-url="item.remitUrl"
	                        confirm-dialog
	                        title="Remit Item"
	                        :message="`Are you sure you want to remit ${item.bills_of_exchange}?`"
	                        @load="load"
	                        @success="fetch"
	                        :disabled="loading"
                        >Remit</action-button>

                        <action-button
							v-model="item.settleUrl"
							small
	                        color="btn-secondary"
	                        :action-url="item.settleUrl"
	                        confirm-dialog
	                        title="Settle Item"
	                        :message="`Are you sure you want to settle ${item.bills_of_exchange}?`"
	                        @load="load"
	                        @success="fetch"
	                        :disabled="loading"
                        >Settle</action-button>

                        <action-button
							v-model="item.postUrl"
							small
	                        color="btn-info"
	                        :action-url="item.postUrl"
	                        confirm-dialog
	                        title="Post Item"
	                        :message="`Are you sure you want to draw ${item.bills_of_exchange}?`"
	                        @load="load"
	                        @success="fetch"
	                        :disabled="loading"
                        >Draw</action-button>

						<button type="button" class="btn btn-info btn-sm" :disabled="!item.id" @click="redirectTo('Payment Schedule')">Create Payment Schedule</button>
						<button type="button" class="btn btn-info btn-sm" :disabled="!item.id" @click="redirectTo('Interest Note')">Interest Note</button>
						<button type="button" class="btn btn-info btn-sm" :disabled="!item.id" @click="redirectTo('Collection')">Collection</button>
						<button type="button" class="btn btn-info btn-sm" :disabled="!item.id" @click="redirectTo('Remittance')">Remittance</button>

					</div>
				</template>
				<div class="card">
					<div class="card-header p-2">	
						<div class="row">
							<div class="col-md-9">
								<ul class="nav nav-pills">
									<li class="nav-item"><a class="nav-link active" href="#bank_account" data-toggle="tab">Bills Of Exchange</a></li>
									<li class="nav-item"><a class="nav-link" href="#status" data-toggle="tab">Status</a></li>
									<li class="nav-item"><a class="nav-link" href="#audit" data-toggle="tab">Audit Trail</a></li>
									<li class="nav-item"><a class="nav-link" href="#credit" data-toggle="tab">Credit</a></li>
								</ul>
							</div>
						</div>
				    </div>

				     <div class="card-body">
				        <div class="tab-content">
				        	<div class="tab-pane show active" id="bank_account">
				        		<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<h4><i class="fas fa-tags"></i> Header</h4><hr>
										</div>

										<div class="form-group">
											<label>Client <b class="text-danger">*</b></label>
											<v-select v-model="item.client_id" :reduce="item => item.id" label="name" placeholder="Select Client" :options="clients"></v-select>
										</div>

										<div class="form-group">
			        		    			<label for="bills_of_exchange">Bills of Exchange</label>
											<input id="bills_of_exchange" name="bills_of_exchange" type="text" class="form-control" :value="item.bills_of_exchange" readonly>
										</div>

										<div class="form-group">
											<label>Document <b class="text-danger">*</b></label>
											<v-select v-model="item.bank_document_id" :reduce="item => item.id" label="bank_facility_agreement_number" placeholder="Select Document" :options="documents"></v-select>
										</div>

										<div class="form-group">
											<label>Document Type <b class="text-danger">*</b></label>
											<v-select v-model="item.bank_facility_type_id" :reduce="item => item.id" label="bank_facility_type_code" placeholder="Select Document Type" :options="types"></v-select>
										</div>

										<div class="form-group">
											<label>Issue Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="issue_date" type="text" class="form-control calendar-form" name="issue_date" v-model="item.issue_date">
											</div>
										</div>

										<div class="form-group">
											<label>Due From <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="due_from" type="text" class="form-control calendar-form" name="due_from" v-model="item.due_from">
											</div>
										</div>

										<div class="form-group">
											<label>Due To <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="due_to" type="text" class="form-control calendar-form" name="due_to" v-model="item.due_to">
											</div>
										</div>

										<div class="form-group">
			        		    			<label for="principal_amount">Principal Amount <span class="text-danger">*</span></label>
											<input id="principal_amount" name="principal_amount" type="text" class="form-control" v-model="item.principal_amount">
										</div>

										<div class="form-group">
			        		    			<label for="number_of_times_to_settle">Number Of Times To Settle <span class="text-danger">*</span></label>
											<input id="number_of_times_to_settle" name="number_of_times_to_settle" type="text" class="form-control" v-model="item.number_of_times_to_settle">
										</div>

										<div class="form-group">
			        		    			<label for="ammount_to_settle">Ammount To Settle <span class="text-danger">*</span></label>
											<input id="ammount_to_settle" name="ammount_to_settle" type="text" class="form-control" v-model="item.ammount_to_settle">
										</div>

										<div class="form-group">
											<label>Terms Of Payment <b class="text-danger">*</b></label>
											<v-select v-model="item.terms_of_payment" placeholder="Select A Term" :options="terms_of_payments"></v-select>
										</div>

										<div class="form-group">
			        		    			<label for="payment_day">Payment Day <span class="text-danger">*</span></label>
											<input id="payment_day" name="payment_day" type="text" class="form-control" v-model="item.payment_day">
										</div>

										<div class="form-group">
											<label>Bills Of Exchange Stage <b class="text-danger">*</b></label>
											<v-select v-model="item.bills_of_exchange_stage" :options="bills_of_exchange_stages" disabled></v-select>
										</div>

									</div>

									<div class="col-md-4">
										<div class="form-group">
											<h4><i class="fas fa-tags"></i> Interests</h4><hr>
										</div>

										<div class="form-group">
			        		    			<label for="interest_rate">Interest Rate <span class="text-danger">*</span></label>
											<input id="interest_rate" name="interest_rate" type="text" class="form-control" v-model="item.interest_rate">
										</div>

										<div class="form-group">
			        		    			<label for="interest_amount">Interest Ammount <span class="text-danger">*</span></label>
											<input id="interest_amount" name="interest_amount" type="text" class="form-control" v-model="item.interest_amount">
										</div>

										<div class="form-group">
			        		    			<label for="terms_of_interest">Terms Of Interest <span class="text-danger">*</span></label>
											<input id="terms_of_interest" name="terms_of_interest" type="text" class="form-control" v-model="item.terms_of_interest">
										</div>

										<div class="form-group">
			        		    			<label for="voucher">Voucher <span class="text-danger">*</span></label>
											<input id="voucher" name="voucher" type="text" class="form-control" v-model="item.voucher">
										</div>

									</div>
			        		    </div>
				        	</div>
				        	<div class="tab-pane" id="status">
				        		<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<h4><i class="fas fa-tags"></i> Status</h4><hr>
										</div>

										<div class="form-group">
											<label>Bills Of Exchange Status <b class="text-danger">*</b></label>
											<v-select v-model="item.status" placeholder="Select A Term" :options="statuses"></v-select>
										</div>

										<div class="form-group">
                                            <label for="approved_date">Approved Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="approved_date" type="text" class="form-control" id="approved_date" name="approved_date" :value="item.approved_date" disabled>
                                            </div>
                                        </div>

										<div class="form-group">
			        		    			<label for="approved_by">Approved By</label>
											<input id="approved_by" name="approved_by" type="text" class="form-control" :value="item.approved_by" disabled>
										</div>

										<div class="form-group">
                                            <label>Approved</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            <input type="checkbox" class="custom-control-input" id="approved_checkbox" name="approved_checkbox" :value="item.approved_checkbox" disabled>
                                                <label class="custom-control-label" for="approved_checkbox">
                                                    <span class="badge" :class="item.approved_checkbox ? 'badge-success' : 'badge-danger'">
                                                        {{ item.approved_checkbox ? 'Yes' : 'No'  }}
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
                                                <input ref="posted_date" type="text" class="form-control" id="posted_date" name="posted_date" :value="item.posted_date" disabled>
                                            </div>
                                        </div>

										<div class="form-group">
			        		    			<label for="posted_by">Posted By</label>
											<input id="posted_by" name="posted_by" type="text" class="form-control" :value="item.posted_by" disabled>
										</div>

										<div class="form-group">
                                            <label>Posted</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            <input type="checkbox" class="custom-control-input" id="posted_checkbox" name="posted_checkbox" :value="item.posted_checkbox" disabled>
                                                <label class="custom-control-label" for="posted_checkbox">
                                                    <span class="badge" :class="item.posted_checkbox ? 'badge-success' : 'badge-danger'">
                                                        {{ item.posted_checkbox ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>


									</div>

									<div class="col-md-4">
										<div class="form-group">
											<h4><i class="fas fa-tags"></i> Bills Discounting</h4><hr>
										</div>

										<div class="form-group">
										    <label for="discounted_on">Discounted Date</label>
										    <div class="input-group">
										        <div class="input-group-prepend">
										            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										        </div>
										        <input ref="discounted_on" type="text" class="form-control" id="discounted_on" name="discounted_on" v-model="item.discounted_on">
										    </div>
										</div>

										<div class="form-group">
			        		    			<label for="discount_rate">Discount Rate</label>
											<input id="discount_rate" name="discount_rate" type="number" step="any" class="form-control" v-model="item.discount_rate">
										</div>

										<div class="form-group">
			        		    			<label for="discount_period">Discount Period</label>
											<input id="discount_period" name="discount_period" type="number" step="any" class="form-control" v-model="item.discount_period">
										</div>

										<div class="form-group">
			        		    			<label for="discount_amount">Discount Amount</label>
											<input id="discount_amount" name="discount_amount" type="number" step="any" class="form-control" v-model="item.discount_amount">
										</div>



										<div class="form-group">
											<h4><i class="fas fa-tags"></i> Letter Credit Sales</h4><hr>
										</div>

										<div class="form-group">
											<label>Letter Credit Sales Number <b class="text-danger">*</b></label>
											<v-select v-model="item.letter_credit_sales_id" :reduce="item => item.id" label="bank_document_number" placeholder="Select Letter Credit Sales Number" :options="credits"></v-select>
										</div>

										<div class="form-group">
										    <label for="issue_date_credit">Letter Credit Sales Issue Date</label>
										    <div class="input-group">
										        <div class="input-group-prepend">
										            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										        </div>
										        <input ref="issue_date_credit" type="text" class="form-control" id="issue_date_credit" name="issue_date_credit" :value="issue_date_credit" disabled>
										    </div>
										</div>

										<div class="form-group">
											<label>Letter of Guarantee Number <b class="text-danger">*</b></label>
											<v-select v-model="item.letter_of_guarantee_id" :reduce="item => item.id" label="letter_of_guarantee_number" placeholder="Select Letter of Guarantee Number" :options="guarantees"></v-select>
										</div>

										<div class="form-group">
										    <label for="issue_date_guarantee">Letter of Guarantee Issue Date</label>
										    <div class="input-group">
										        <div class="input-group-prepend">
										            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
										        </div>
										        <input ref="issue_date_guarantee" type="text" class="form-control" id="issue_date_guarantee" name="issue_date_guarantee" :value="issue_date_guarantee" disabled>
										    </div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<h4><i class="fas fa-user"></i> Customer</h4><hr>
										</div>
										<div class="form-group">
											<label>Customer Account <b class="text-danger">*</b></label>
											<v-select class="mb-2" v-model="item.customer_id" :reduce="item => item.id" label="fullname" placeholder="Select Customer" :options="customers"></v-select>
										</div>
										<div class="form-group">
											<label>Invoice Account</label>
											<input class="form-control mb-2" readonly v-model="item.invoice_account">
										</div>
										<div class="form-group">
											<label>Customer Number</label>
							                <input name="customer_name" readonly v-model="item.customer_account" type="text" class="form-control mb-2">
										</div>
										<div class="form-group">
											<label>Customer Contact ID</label>
							                <input name="customer_contact_id" v-model="item.customer_contact_id" type="text" class="form-control mb-2" >
										</div>
										<div class="form-group">
											<label>Customer Address</label>
							                <textarea name="customer_address" v-model="item.customer_address" class="form-control mb-2" rows="3"></textarea>
										</div>

										<div class="form-group">
											<h4><i class="fas fa-user"></i> Bank</h4><hr>
										</div>
										
                                        <div class="form-group">
                                            <label for="payment_status">Payment Status <b class="text-danger">*</b></label>
                                            <v-select v-model="item.payment_status" :options="payment_statuses" label="value" placeholder="Select Payment Status" :reduce="item => item.value" class="mb-2"></v-select>
                                        </div>

										<div class="form-group">
											<label>Client Bank Account <b class="text-danger">*</b></label>
											<v-select v-model="item.client_bank_account" :reduce="item => item.bank_account" label="bank_name" placeholder="Select Client Bank Account" :options="client_banks"></v-select>
										</div>

										<div class="form-group">
											<label>Customer Bank Account <b class="text-danger">*</b></label>
											<v-select v-model="item.customer_bank_account" :reduce="item => item.bank_account" label="bank_name" placeholder="Select Customer Bank Account" :options="customer_banks"></v-select>
										</div>
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
				        	<div class="tab-pane" id="credit">
			        			<div class="col-12">
						            <div class="card">
						                <div class="card-header p-2">
						                    <ul class="nav nav-pills">
						                        <li class="nav-item"><a class="nav-link active" href="#payment-schedules" data-toggle="tab">Payment Schedule</a></li>
						                        <li class="nav-item"><a class="nav-link" href="#interest-calculations" data-toggle="tab">Interest Calculation</a></li>
						                        <li class="nav-item"><a class="nav-link" href="#interest-notes" data-toggle="tab">Interest Note</a></li>
						                        <li class="nav-item"><a class="nav-link" href="#interest-setups" data-toggle="tab">Interest Setup</a></li>
						                        <li class="nav-item"><a class="nav-link" href="#interest-adjustments" data-toggle="tab">Interest Adjustments</a></li>
						                        <li class="nav-item"><a class="nav-link" href="#collections" data-toggle="tab">Collections</a></li>
						                        <li class="nav-item"><a class="nav-link" href="#bills-of-exchange-adjustment" data-toggle="tab">Bills Of Exchange Adjustment</a></li>
						                    </ul>
						                </div>
						                <div class="card-body">
						                    <div class="tab-content">
						                        <div class="tab-pane show active" id="payment-schedules">
						                           	<payment-schedule-table
						                           		:clients="clients"
						                           		:fetch-url="paymentScheduleUrl"
						                           	></payment-schedule-table>
						                        </div>
						                        <div class="tab-pane" id="interest-calculations">
						                           	<interest-calculation-table
						                           		:clients="clients"
						                           		:fetch-url="interestCalculationUrl"
						                           	></interest-calculation-table>
						                        </div>
						                        <div class="tab-pane" id="interest-notes">
						                           	<interest-note-table
						                           		:clients="clients"
						                           		:fetch-url="interestNoteUrl"
						                           	></interest-note-table>
						                        </div>
						                        <div class="tab-pane" id="interest-setups">
						                           	<interest-setup-table
						                           		:clients="clients"
						                           		:fetch-url="interestSetupUrl"
						                           	></interest-setup-table>
						                        </div>
						                        <div class="tab-pane" id="interest-adjustments">
						                           	<interest-adjustment-table
						                           		:clients="clients"
						                           		:fetch-url="interestAdjustmentUrl"
						                           	></interest-adjustment-table>
						                        </div>
						                        <div class="tab-pane" id="collections">
						                           	<collection-table
						                           		:clients="clients"
						                           		:fetch-url="collectionUrl"
						                           	></collection-table>
						                        </div>
						                        <div class="tab-pane" id="bills-of-exchange-adjustment">
						                           	<bills-exchange-table
						                           		:clients="clients"
						                           		:fetch-url="boeAdjustmentUrl"
						                           	></bills-exchange-table>
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
				client_banks: [],
				customer_banks: [],
				customers: [],
				credits: [],
				guarantees: [],
				types: [],
				documents: [],
				issue_date_credit: null,
				issue_date_guarantee: null,
				terms_of_payments: [
					'Bi-Weekly',
					'Weekly',	
					'Semi-Monthly',	
					'Monthly',	
					'Quarterly',	
					'Yearly',	
				],
				statuses: [
					'Created',
					'Cancelled',
					'Posted',
					'Blank',
				],
				bills_of_exchange_stages: [
					'Drawn',
					'Redrawn',
					'Remitted',
					'Settled',
				],
                payment_statuses: [
                    { value: 'None' },
                    { value: 'Sent' },
                    { value: 'Received' },
                    { value: 'Approved' },
                    { value: 'Rejected' }
                ],
			}
		},

		mounted() {
			flatpickr(this.$refs.issue_date);
			flatpickr(this.$refs.due_from);
			flatpickr(this.$refs.due_to);
			flatpickr(this.$refs.discounted_on);
			flatpickr(this.$refs.issue_date_credit);
			flatpickr(this.$refs.issue_date_guarantee);
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.client_banks = data.client_banks ? data.client_banks : this.client_banks;
				this.customer_banks = data.customer_banks ? data.customer_banks : this.customer_banks;
				this.customers = data.customers ? data.customers : this.customers;
				this.credits = data.credits ? data.credits : this.credits;
				this.guarantees = data.guarantees ? data.guarantees : this.guarantees;
				this.types = data.types ? data.types : this.types;
				this.documents = data.documents ? data.documents : this.documents;
			},

			redirectTo(module) {
				var $this = this;
				swal.fire({
				  title: 'Are you sure?',
				  text: `Are you sure you want to create ${module}?`,
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonText: 'Confirm',
				  cancelButtonText: 'Cancel'
				}).then((result) => {
				  if (result.value) {
				  	var url = '';
				  	switch(module) {
				  		case 'Payment Schedule':
				  			url = $this.item.createPaymentScheduleUrl;
				  			break;
				  		case 'Interest Note':
				  			url = $this.item.interestNoteUrl;
				  			break;
				  		case 'Collection':
				  			url = $this.item.collectionsUrl;
				  			break;
				  		case 'Remittance':
				  			url = '';
				  			break;
				  	}
				    window.location.href = url;
				  }
				})
			},
		},

		watch: {
			'item.customer_id'(val) {
				_.each(this.customers, (customer) => {
					if(customer.id == val) {
						this.item.invoice_account = customer.customer_account;
						this.item.customer_name = customer.fullname;
						this.item.customer_address = `${customer.shipping_street}, ${customer.shipping_city}, ${customer.shipping_province}, ${customer.shipping_postal_code}, ${customer.shipping_country}`;
						this.item.customer_contact_id = customer.fullname;
					} else {
						this.item.invoice_account = null;
						this.item.customer_name = null;
						this.item.customer_address = null;
						this.item.customer_contact_id = null;
					}
				})
			},

			'item.letter_credit_sales_id'(val) {
				var selected = _.find(this.credits, (credit) => { return credit.id == val });
				this.issue_date_credit = selected.issue_date;
			},

			'item.letter_of_guarantee_id'(val) {
				var selected = _.find(this.guarantees, (guarantee) => { return guarantee.id == val });
				this.issue_date_guarantee = selected.issue_date;
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
            paymentScheduleUrl: String,
			interestSetupUrl: String,
			interestCalculationUrl: String,
			interestNoteUrl: String,
			interestScheduleUrl: String,
			interestAdjustmentUrl: String,

			collectionUrl: String,
			boeAdjustmentUrl: String,
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