<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="submitParams">

			<card>
				<template v-slot:header>
					Interest Setup Information
					<div class="float-right">
						<action-button type="submit" :disabled="loading" class="btn btn-primary btn-sm">Save Changes</action-button>
						<button type="button" class="btn btn-success" :disabled="!item.id" @click="generateInterestNote">Generate Interest Note</button>
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
											<label>From Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="from_date" type="text" class="form-control calendar-form" name="from_date" v-model="item.from_date">
											</div>
										</div>

										<div class="form-group">
											<label>To Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="to_date" type="text" class="form-control calendar-form" name="to_date" v-model="item.to_date">
											</div>
										</div>

										<div class="form-group">
			        		    			<label for="round_off">Round Off <b class="text-danger">*</b></label>
											<input id="round_off" name="round_off" type="text" class="form-control" v-model="item.round_off">
										</div>

										<div class="form-group">
                                            <label>Invoice</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            <input type="checkbox" class="custom-control-input" id="invoice" name="invoice" v-model="item.invoice">
                                                <label class="custom-control-label" for="invoice">
                                                    <span class="badge" :class="item.invoice ? 'badge-success' : 'badge-danger'">
                                                        {{ item.invoice ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Credit Note</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            <input type="checkbox" class="custom-control-input" id="credit_note" name="credit_note" v-model="item.credit_note">
                                                <label class="custom-control-label" for="credit_note">
                                                    <span class="badge" :class="item.credit_note ? 'badge-success' : 'badge-danger'">
                                                        {{ item.credit_note ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Payment</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            <input type="checkbox" class="custom-control-input" id="payment" name="payment" v-model="item.payment">
                                                <label class="custom-control-label" for="payment">
                                                    <span class="badge" :class="item.payment ? 'badge-success' : 'badge-danger'">
                                                        {{ item.payment ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
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

									</div>

									<div class="col-md-4">
										<div class="form-group">
											<hr>
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

									</div>

									<div class="col-md-4">
										<div class="form-group">
											<hr>
										</div>

										<div class="form-group">
											<label>Bills of Exchange <b class="text-danger">*</b></label>
											<v-select v-model="item.bills_of_exchange_id" :reduce="item => item.id" label="bills_of_exchange" placeholder="Select Bills Of Exchange" :options="bills_exchanges"></v-select>
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
				posting_profiles: [],
				customers: [],
				bills_exchanges: [],
				customer_banks: [],
				posting_profile_from: [
					'Methods of Payment Customer',
					'Select',
					'Transaction',
				],
			}
		},

		mounted() {
			flatpickr(this.$refs.from_date);
			flatpickr(this.$refs.to_date);
			flatpickr(this.$refs.invoice_date);
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.customers = data.customers ? data.customers : this.customers;
				this.posting_profiles = data.posting_profiles ? data.posting_profiles : this.posting_profiles;
				this.bills_exchanges = data.bills_exchanges ? data.bills_exchanges : this.bills_exchanges;
				this.customer_banks = data.customer_banks ? data.customer_banks : this.customer_banks;
			},

			generateInterestNote() {
				var $this = this;
				swal.fire({
				  title: 'Are you sure?',
				  text: 'Are you sure you want to generate interest note?',
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonText: 'Confirm',
				  cancelButtonText: 'Cancel'
				}).then((result) => {
				  if (result.value) {
				    window.location.href = $this.item.generateInterestNoteUrl;
				  }
				})
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