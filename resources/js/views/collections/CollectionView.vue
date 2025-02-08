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
                        <action-button
	                        v-if="item.closeUrl"
							small
	                        color="btn-danger"
	                        :action-url="item.closeUrl"
	                        confirm-dialog
	                        title="Close Item"
	                        :message="`Are you sure you want to close ${item.collection_id}?`"
	                        @load="load"
	                        @success="fetch"
	                        :disabled="item.closed_checkbox || loading"
                        >Close</action-button>
                        <action-button
	                        v-if="item.writeOffUrl"
							small
	                        color="btn-danger"
	                        :action-url="item.writeOffUrl"
	                        confirm-dialog
	                        title="Write Off Item"
	                        :message="`Are you sure you want to close ${item.collection_id}?`"
	                        @load="load"
	                        @success="fetch"
	                        :disabled="item.write_off_date || loading"
                        >Write Off</action-button>
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
									<div class="col-md-3">
										<div class="form-group">
											<h4><i class="fas fa-tags"></i> Header</h4><hr>
										</div>

										<div class="form-group">
											<label>Client <b class="text-danger">*</b></label>
											<v-select v-model="item.client_id" :reduce="item => item.id" label="name" placeholder="Select Client" :options="clients"></v-select>
										</div>

										<div class="form-group">
			        		    			<label for="collection_id">Collection ID</label>
											<input id="collection_id" name="collection_id" type="text" class="form-control" :value="item.collection_id" readonly>
										</div>

										<div class="form-group">
											<label>Collection Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="collection_date" type="text" class="form-control calendar-form" name="collection_date" v-model="item.collection_date">
											</div>
										</div>

										<div class="form-group">
											<label>Sent Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="sent_date" type="text" class="form-control calendar-form" name="sent_date" v-model="item.sent_date">
											</div>
										</div>

										<div class="form-group">
											<label>Due Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="due_date" type="text" class="form-control calendar-form" name="due_date" v-model="item.due_date">
											</div>
										</div>

										<div class="form-group">
			        		    			<label for="amount_to_settle">Amount To Settle <b class="text-danger">*</b></label>
											<input id="amount_to_settle" name="amount_to_settle" type="text" class="form-control" v-model="item.amount_to_settle">
										</div>

										<div class="form-group">
											<label>Client Bank Account <b class="text-danger">*</b></label>
											<v-select v-model="item.client_bank_account" :reduce="item => item.bank_account" label="bank_name" placeholder="Select Client Bank Account" :options="client_banks"></v-select>
										</div>

									</div>

									<div class="col-md-3">
										<div class="form-group">
											<h4><i class="fas fa-tags"></i> Customer</h4><hr>
										</div>


										<div class="form-group">
											<label>Customer Account <b class="text-danger">*</b></label>
											<v-select class="mb-2" v-model="item.customer_account" :reduce="item => item.customer_account" label="company" placeholder="Select Customer" :options="customers"></v-select>
										</div>

										<div class="form-group">
											<label>Invoice Account <b class="text-danger">*</b></label>
											<input class="form-control mb-2" v-model="item.invoice_account">
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
							    			<label>Customer Number <b class="text-danger">*</b></label>
							                <input name="customer_name" v-model="item.customer_name" type="text" class="form-control mb-2">
										</div>

						                <div class="form-group">
							    			<label>Customer Contact ID <b class="text-danger">*</b></label>
							                <input name="customer_contact_id" v-model="item.customer_contact_id" type="text" class="form-control mb-2" >
						                </div>

						                <div class="form-group">
											<label>Customer Address <b class="text-danger">*</b></label>
							                <textarea name="customer_address" v-model="item.customer_address" class="form-control mb-2" rows="3"></textarea>
						                </div>

										<div class="form-group">
											<label>Customer Bank Account <b class="text-danger">*</b></label>
											<v-select v-model="item.customer_bank_account" :reduce="item => item.bank_account" label="bank_name" placeholder="Select Customer Bank Account" :options="customer_banks"></v-select>
										</div>

										<div class="form-group">
							    			<label>Description <b class="text-danger">*</b></label>
							                <input name="description" v-model="item.description" type="text" class="form-control mb-2">
										</div>

										<div class="form-group">
											<label>Bills Of Change ID <b class="text-danger">*</b></label>
											<v-select v-model="item.bills_exchange_id" :reduce="item => item.id" label="bills_of_exchange" placeholder="Select A Bill Of Exchange" :options="bills_exchanges"></v-select>
										</div>

										<div class="form-group">
							    			<label>Bills Of Exchange Status <b class="text-danger">*</b></label>
							                <input name="bills_exchange_status" v-model="item.bills_exchange_status" type="text" class="form-control mb-2">
										</div>

										<div class="form-group">
							    			<label>Voucher <b class="text-danger">*</b></label>
							                <input name="voucher" v-model="item.voucher" type="text" class="form-control mb-2">
										</div>

									</div>

									<div class="col-md-3">
										<div class="form-group">
											<h4><i class="fas fa-tags"></i> Status</h4><hr>
										</div>

										<div class="form-group">
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
										</div>

									</div>
									<div class="col-md-3">
										<div class="form-group">
											<h4><i class="fas fa-tags"></i> Write Off and NSF Payment</h4><hr>
										</div>

										<div class="form-group">
											<label>Write Off Status</label>
											<select class="form-control" v-model="item.write_off_status">
												<option value="Write Off">Write Off</option>
												<option value="Reverse Write Off">Reverse Write Off</option>
											</select>
										</div>

										<div class="form-group">
                                            <label for="write_off_date">Write Off Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="write_off_date" type="text" class="form-control" id="write_off_date" name="write_off_date" v-model="item.write_off_date" >
                                            </div>
                                        </div>
                                        <div class="form-group">
							    			<label>Write Off Issued By</label>
							                <v-select v-model="item.write_off_issued_by" :reduce="item => item.id" label="fullname" placeholder="Select Write Off Issuer" :options="users"></v-select>
										</div>
                                        <div class="form-group">
							    			<label>Write Off Description</label>
							                <input name="write_off_description" v-model="item.write_off_description" type="text" class="form-control">
										</div>
										<div class="form-group">
                                            <label for="reverse_write_off_date">Reverse Write Off Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="reverse_write_off_date" type="text" class="form-control" id="reverse_write_off_date" name="reverse_write_off_date" v-model="item.reverse_write_off_date" >
                                            </div>
                                        </div>

										<div class="form-group">
											<label>NSF Status</label>
											<select class="form-control" v-model="item.nsf_payment_status">
												<option value="NSF Payment">NSF Payment</option>
												<option value="Reverse NSF">Reverse NSF</option>
											</select>
										</div>

										<div class="form-group">
                                            <label for="nsf_payment_date">NSF Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="nsf_payment_date" type="text" class="form-control" id="nsf_payment_date" name="nsf_payment_date" v-model="item.nsf_payment_date" >
                                            </div>
                                        </div>
                                        <div class="form-group">
							    			<label>NSF Issued By</label>
							                <v-select v-model="item.nsf_payment_issued_by" :reduce="item => item.id" label="fullname" placeholder="Select NSF Issuer" :options="users"></v-select>
										</div>
                                        <div class="form-group">
							    			<label>NSF Description</label>
							                <input name="nsf_payment_description" v-model="item.nsf_payment_description" type="text" class="form-control">
										</div>
										<div class="form-group">
                                            <label for="reverse_nsf_payment_date">Reverse NSF Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="reverse_nsf_payment_date" type="text" class="form-control" id="reverse_nsf_payment_date" name="reverse_nsf_payment_date" v-model="item.reverse_nsf_payment_date" >
                                            </div>
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
				users: [],
				bills_exchanges: [],
				collection_statuses: [
					'Not Disputes', 
					'Disputed', 
					'Promise To Pay', 
					'Resolved',
				],
			}
		},

		mounted() {
			flatpickr(this.$refs.collection_date);
			flatpickr(this.$refs.sent_date);
			flatpickr(this.$refs.due_date);
			flatpickr(this.$refs.invoice_date);
			flatpickr(this.$refs.activity_start_date);
			flatpickr(this.$refs.activity_date);

			flatpickr(this.$refs.write_off_date);
			flatpickr(this.$refs.reverse_write_off_date);
			flatpickr(this.$refs.nsf_payment_date);
			flatpickr(this.$refs.reverse_nsf_payment_date);
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.client_banks = data.client_banks ? data.client_banks : this.client_banks;
				this.customer_banks = data.customer_banks ? data.customer_banks : this.customer_banks;
				this.customers = data.customers ? data.customers : this.customers;
				this.bills_exchanges = data.bills_exchanges ? data.bills_exchanges : this.bills_exchanges;
				this.users = data.users ? data.users : this.users;

				if(!_.isEmpty(this.boe)) {
					this.item.bills_exchange_id = this.boe.id;
				}
			},
		},

		watch: {
			'item.customer_account'(val) {
				_.each(this.customers, (customer) => {
					if(customer.customer_account == val) {
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
            boe: Object
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