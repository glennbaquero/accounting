<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<!-- <template v-slot:header>Customer Information</template> -->

				<!-- <div class="row mb-3">
					<div class="form-group col-md-12">
						<div class="custom-control custom-switch">
							<input
							v-model="item.is_sub_customer"
							name="is_sub_customer" :checked="item.is_sub_customer" type="checkbox" class="custom-control-input" id="is_sub_customer">
							<label class="custom-control-label" for="is_sub_customer">Is sub-customer</label>
						</div>
					</div>
					<template v-if="item.is_sub_customer">
						<div class="form-group col-md-6">
							<label>Parent Customer</label>
							<select class="form-control" name="parent_customer_account" v-model="item.parent_customer_account">
								<option :key="customer" v-for="customer in customer_parent_lists" :value="customer.customer_account">{{ customer.fullname }}</option>
							</select>
						</div>
						<div class="form-group col-md-6">
							<label>Bill with parent</label>
							<select class="form-control" name="bill_parent_customer_account" v-model="item.bill_parent_customer_account">
								<option :key="customer" v-for="customer in customer_bill_parent_lists" :value="customer.customer_account">{{ customer.fullname }}</option>
							</select>
						</div>
					</template>
				</div> -->

				<div class="row">
		    		<div class="form-group col-sm-2">
		    			<label>Title</label>
		                <input name="title" v-model="item.title" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-3">
		    			<label>First name</label>
		                <input name="first_name" v-model="item.first_name" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-3">
		    			<label>Middle name</label>
		                <input name="middle_name" v-model="item.middle_name" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-3">
		    			<label>Last name</label>
		                <input name="last_name" v-model="item.last_name" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-1">
		    			<label>Suffix</label>
		                <input name="suffix" maxlength="5" v-model="item.suffix" type="text" class="form-control">
		    		</div>
				</div>

				<div class="row">
					<div class="form-group col-sm-4">
						<label>Email <small>(Separate multiple emails with comma)</small></label>
			            <input name="email" v-model="item.email" type="text" class="form-control">
					</div>
					<div class="form-group col-sm-4">
						<label>Company</label>
			            <input name="company" v-model="item.company" type="text" class="form-control">
					</div>
					<div class="form-group col-sm-4">
						<label>Display name as</label>
			            <input name="display_name" v-model="item.display_name" type="text" class="form-control">
					</div>
				</div>

				<div class="row">
					<div class="form-group col-sm-4">
						<label>Phone</label>
			            <!-- <input name="phone" type="text" class="form-control"> -->
			            <vue-phone-number-input v-model="item.phone_number_component" required default-country-code="PH" size="sm" @update="updatePhone" ref="phone_number" no-example/>

			            <input type="hidden" name="phone" v-model="item.phone">
			            <input type="hidden" name="phone_calling_code" v-model="item.phone_calling_code">
					</div>
					<div class="form-group col-sm-4">
						<label>Mobile</label>
			            <!-- <input name="mobile" type="text" class="form-control"> -->
			            <vue-phone-number-input v-model="item.mobile_number_component" required default-country-code="PH" size="sm" @update="updateMobile" ref="mobile_number" no-example/>

			            <input type="hidden" name="mobile_number" v-model="item.mobile_number">
			            <input type="hidden" name="mobile_calling_code" v-model="item.mobile_calling_code">
					</div>
					<div class="form-group col-sm-4">
						<label>Fax</label>
			            <input name="fax" v-model="item.fax" type="text" class="form-control">
					</div>
					<div class="form-group col-sm-3">
						<label>Other</label>
			            <input name="other" v-model="item.other" type="text" class="form-control">
					</div>
					<div class="form-group col-sm-3">
						<label>Website</label>
			            <input name="website" v-model="item.website" type="text" class="form-control">
					</div>
            		<div class="form-group col-md-3">
            			<label>Type of Trade</label>
		                <select name="type_of_trade" v-model="item.type_of_trade" class="form-control">
		    				<option value="Import Trade">Import Trade</option>
		    				<option value="Export Trade">Export Trade</option>
		    				<option value="Entrepot Trade">Entrepot Trade</option>
		                </select>
		            </div>
            		<div class="form-group col-md-3 mt-sm-auto">
            			<label><input name="peza_checkbox" v-model="item.peza_checkbox" type="checkbox"> PEZA</label>
		            </div>
				</div>

				<div class="card">
				    <div class="card-header p-2">
				        <ul class="nav nav-pills">
				            <li class="nav-item"><a class="nav-link active" href="#address" data-toggle="tab">Address</a></li>
				            <li class="nav-item"><a class="nav-link" href="#notes" data-toggle="tab">Notes</a></li>
				            <li class="nav-item"><a class="nav-link" href="#tax" data-toggle="tab">Tax Info</a></li>
				            <li class="nav-item"><a class="nav-link" href="#billing" data-toggle="tab">Payment and Billing</a></li>
				            <li class="nav-item"><a class="nav-link" href="#company" data-toggle="tab">Company</a></li>
				            <!-- <li class="nav-item"><a class="nav-link" href="#language" data-toggle="tab">Language</a></li> -->
				            <!-- <li class="nav-item"><a class="nav-link" href="#attachment" data-toggle="tab">Attachments</a></li> -->
				        </ul>
				    </div>
				    <div class="card-body">
				        <div class="tab-content">
				            <div class="tab-pane show active" id="address">
				                <div class="row">
				                	<div class="col-md-6">
										<div class="row">
											<div class="form-group col-sm-12">
												<label>Billing Address</label>
											</div>
											<div class="form-group col-sm-12">
												<input class="form-control" placeholder="Street" name="billing_street"  v-model="item.billing_street" rows="5">
											</div>
											<div class="form-group col-sm-6">
												<input name="billing_city" v-model="item.billing_city" type="text" class="form-control" placeholder="City/Town">
											</div>
											<div class="form-group col-sm-6">
												<input name="billing_province" v-model="item.billing_province" type="text" class="form-control" placeholder="State/Province">
											</div>
											<div class="form-group col-sm-6">
												<input name="billing_postal_code" v-model="item.billing_postal_code" type="text" class="form-control" placeholder="Postal code">
											</div>
											<div class="form-group col-sm-6">
												<input name="billing_country" v-model="item.billing_country" type="text" class="form-control" placeholder="Country">
											</div>
										</div>
				                	</div>
				                	<div class="col-md-6 row">
										<div class="row">
											<div class="form-group col-sm-12">
												<label>Shipping Address</label>
												<label class="float-right">Same as billing address</label>
												<input class="float-right mr-2 mt-1" type="checkbox" v-model="item.same_as_billing_address">
											</div>
											<div class="form-group col-sm-12">
												<input class="form-control" placeholder="Street" name="shipping_street" v-model="item.shipping_street">
											</div>
											<div class="form-group col-sm-6">
												<input name="shipping_city" v-model="item.shipping_city" type="text" class="form-control" placeholder="City/Town">
											</div>
											<div class="form-group col-sm-6">
												<input name="shipping_province" v-model="item.shipping_province" type="text" class="form-control" placeholder="State/Province">
											</div>
											<div class="form-group col-sm-6">
												<input name="shipping_postal_code" v-model="item.shipping_postal_code" type="text" class="form-control" placeholder="Postal code">
											</div>
											<div class="form-group col-sm-6">
												<input name="shipping_country" v-model="item.shipping_country" type="text" class="form-control" placeholder="Country">
											</div>
				                		</div>
									</div>
								</div>
				            </div>
				            <div class="tab-pane" id="notes">
				                <text-editor
				                v-model="item.notes"
				                class="col-sm-12"
				                label="Notes"
				                name="notes"
				                row="5"
				                ></text-editor>
				            </div>
				            <div class="tab-pane" id="tax">
				            	<div class="row">
	                				<div class="form-group col-sm-6">
	                					<label>Tax Register Number</label>
							            <input name="tax_exempt_number" type="text" class="form-control">
	                				</div>
	                				<div class="form-group col-sm-6">
	                					<label>VAT Exempt Number</label>
							            <input name="vat_exempt_number" type="text" class="form-control">
	                				</div>
				            	</div>
				            </div>
				            <div class="tab-pane" id="billing">
				            	<div class="row">
				            		<div class="col-md-12">
				            			<label>Payment</label>
				            		</div>
				            		<div class="col-md-6">
				            			Terms of payment
						                <select name="terms_of_payment" v-model="item.terms_of_payment" class="form-control">
						    				<option v-for="payment in terms_of_payments" :value="payment.terms_of_payment">{{ payment.terms_of_payment }}</option>
						                </select>
				            			Method of payment
						                <select name="method_of_payment" v-model="item.method_of_payment" class="form-control">
						    				<option v-for="method in payment_methods" :value="method.id">{{ method.name }}</option>
						                </select>
				            			Payment type
				            			<input type="text" name="payment_type" class="form-control" readonly>
				            			Payment specification
						                <select name="payment_specification" v-model="item.payment_specification" class="form-control">
						    				<option v-for="method in payment_methods" :value="method.id">{{ method.name }}</option>
						                </select>
				            			Payment schedule
						                <select name="payment_schedule" v-model="item.payment_schedule" class="form-control">
						    				<option v-for="method in payment_methods" :value="method.id">{{ method.name }}</option>
						                </select>
				            		</div>
				            		<div class="col-md-6">
				            			Bank account
						                <select name="bank_account" v-model="item.bank_account" class="form-control">
						    				<option v-for="method in payment_methods" :value="method.id">{{ method.name }}</option>
						                </select>
						                Payment ID
				            			<input type="text" name="payment_id" class="form-control">
				            			Payment day
						                <select name="payment_day" v-model="item.payment_day" class="form-control">
						    				<option v-for="method in payment_methods" :value="method.id">{{ method.name }}</option>
						                </select>
						                Bank account number
				            			<input type="text" name="bank_account_number" class="form-control" readonly>
				            			Use cash discount
						                <select name="use_cash_discount" v-model="item.use_cash_discount" class="form-control">
						    				<option v-for="method in payment_methods" :value="method.id">{{ method.name }}</option>
						                </select>
				            		</div>
				            	</div>
				            </div>
				            <div class="tab-pane" id="company">
				            	<div class="row">
				            		
				            		<div class="col-md-6">
				            			<label>Major Industry Classification</label>
				            			<input class="form-control" placeholder="Major Industry Classification" name="major_industry_classification" v-model="item.major_industry_classification">
						            </div>
				            		<div class="col-md-6">
				            			<label>Industry Classification Group</label>
				            			<input class="form-control" placeholder="Industry Classification Group" name="industry_classification_group" v-model="item.industry_classification_group">
						            </div>
				            		<div class="col-md-6">
				            			<label>PSIC Sections</label>
				            			<input class="form-control" placeholder="PSIC Sections" name="psic_sections" v-model="item.psic_sections">
						            </div>
				            		<div class="col-md-6">
				            			<label>PSIC Divisions</label>
				            			<input class="form-control" placeholder="PSIC Divisions" name="psic_divisions" v-model="item.psic_divisions">
						            </div>
				            		<div class="col-md-6">
				            			<label>PSIC Groups</label>
				            			<input class="form-control" placeholder="PSIC Groups" name="psic_groups" v-model="item.psic_groups">
						            </div>
				            		<div class="col-md-6">
				            			<label>PSIC Class</label>
				            			<input class="form-control" placeholder="PSIC Class" name="psic_class" v-model="item.psic_class">
						            </div>
				            		<div class="col-md-6">
				            			<label>PSIC SubClass</label>
				            			<input class="form-control" placeholder="PSIC SubClass" name="psic_subclass" v-model="item.psic_subclass">
						            </div>
				            	</div>
				            </div>
				            <div class="tab-pane" id="attachment">

				            </div>
				        </div>
				    </div>

				</div>
				<template v-slot:footer>
					<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
				</template>
			</card>
			
		</form-request>
	</div>
</template>

<script>
	import CrudMixin from 'Mixins/crud.js';
	import Card from 'Components/containers/Card.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';

	export default {
		components: {
			Card,
			'text-editor': TextEditor,
			'form-request': FormRequest,
			'action-button': ActionButton,
		},

		data() {
			return {
				item: {},
				customer_parent_lists: [],
				customer_bill_parent_lists: [],
				payment_methods: [],
				terms_of_payments: [],
			}
		},

		watch: {
			'item.same_as_billing_address'(val) {
				if(val) {
					this.item.shipping_city = this.item.billing_city;
					this.item.shipping_street = this.item.billing_street;
					this.item.shipping_province = this.item.billing_province;
					this.item.shipping_country = this.item.billing_country;
					this.item.shipping_postal_code = this.item.billing_postal_code;
				}
			},

			'item.billing_city'(val) {
				if(this.item.same_as_billing_address) {
					this.item.shipping_city = val;
				}
			},

			'item.billing_street'(val) {
				if(this.item.same_as_billing_address) {
					this.item.shipping_street = val;
				}
			},

			'item.billing_province'(val) {
				if(this.item.same_as_billing_address) {
					this.item.shipping_province = val;
				}
			},

			'item.billing_country'(val) {
				if(this.item.same_as_billing_address) {
					this.item.shipping_country = val;
				}
			},

			'item.billing_postal_code'(val) {
				if(this.item.same_as_billing_address) {
					this.item.shipping_postal_code = val;
				}
			},
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.customer_parent_lists = data.customer_parent_lists ? data.customer_parent_lists : this.customer_parent_lists;
				this.customer_bill_parent_lists = data.customer_bill_parent_lists ? data.customer_bill_parent_lists : this.customer_bill_parent_lists;
				this.payment_methods = data.payment_methods ? data.payment_methods : this.payment_methods;
				this.terms_of_payments = data.terms_of_payments ? data.terms_of_payments : this.terms_of_payments;
			},

			updateMobile(e) {
			    this.item.calling_code = this.$refs['mobile_number'].results.countryCallingCode;
			    this.item.mobile_number = e.countryCallingCode+''+e.nationalNumber;
			},
			updatePhone(e) {
			    this.item.calling_code = this.$refs['phone_number'].results.countryCallingCode;
			    this.item.phone = e.countryCallingCode+''+e.nationalNumber;
			},
		},

		mixins: [ CrudMixin ],
	}
</script>