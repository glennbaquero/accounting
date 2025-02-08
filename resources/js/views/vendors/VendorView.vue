<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<!-- <template v-slot:header>Vendor Information</template> -->

				<div class="row">
					<div class="form-group col-sm-3">
		    			<label>Company Name <b class="text-danger">*</b></label>
		                <input name="company_name" v-model="item.company_name" type="text" class="form-control">
		    		</div>
				</div>

				<div class="row">
		    		<div class="form-group col-sm-2">
		    			<label>Title</label>
		                <input @input="getDisplayName" name="title" v-model="item.title" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-3">
		    			<label>First Name <b class="text-danger">*</b></label>
		                <input @input="getDisplayName" name="first_name" v-model="item.first_name" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-3">
		    			<label>Middle Name</label>
		                <input name="middle_name" v-model="item.middle_name" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-3">
		    			<label>Last Name <b class="text-danger">*</b></label>
		                <input @input="getDisplayName" name="last_name" v-model="item.last_name" type="text" class="form-control">
		    		</div>
		    		<div class="form-group col-sm-1">
		    			<label>Suffix</label>
		                <input @input="getDisplayName" name="suffix" v-model="item.suffix" type="text" class="form-control">
		    		</div>
				</div>

				<div class="row">
					<div class="form-group col-sm-4">
						<label>Display Name</label>
			            <input readonly name="display_name" v-model="item.display_name" type="text" class="form-control">
					</div>
					<div class="form-group col-sm-4">
						<label>Email <small>(Separate multiple emails with comma)</small></label>
			            <input name="email" v-model="item.email" type="text" class="form-control">
					</div>
					<div class="form-group col-sm-4">
	    				<label>Client <b class="text-danger">*</b></label>
						<v-select v-model="item.client_id" :reduce="item => item.id" name="client_id" label="name" placeholder="Select Client" :options="clients"></v-select>
						<input type="hidden" name="client_id" v-model="item.client_id"> 			
					</div>
				</div>

				<div class="row">
					<div class="form-group col-sm-4">
						<label>Phone</label>
			            <vue-phone-number-input v-model="item.phone_number_component" default-country-code="PH" size="sm" @update="updatePhone" ref="phone_number" no-example/>
			            <input type="hidden" name="phone" v-model="item.phone">
			            <input type="hidden" name="phone_calling_code" v-model="item.phone_calling_code">
					</div>
					<div class="form-group col-sm-4">
						<label>Mobile</label>
			            <vue-phone-number-input v-model="item.mobile_number_component" default-country-code="PH" size="sm" @update="updateMobile" ref="mobile_number" no-example/>

			            <input type="hidden" name="mobile_number" v-model="item.mobile_number">
			            <input type="hidden" name="mobile_calling_code" v-model="item.mobile_calling_code">
					</div>
	
					<div class="form-group col-sm-4">
						<label>Fax</label>
			            <input type="input" class="form-control" name="fax" v-model="item.fax">
					</div>

					<div class="form-group col-sm-6">
						<label>Address</label>
			            <input name="address" v-model="item.address" type="text" class="form-control">
					</div>

					<div class="form-group col-sm-6">
						<label>Others</label>
			            <input name="other" v-model="item.other" type="text" class="form-control">
					</div>
					<div class="form-group col-sm-4">
						<label>Website</label>
			            <input name="website" v-model="item.website" type="text" class="form-control">
					</div>
					<div class="form-group col-md-4">
						<label>Type of Trade</label>
						<select name="type_of_trade" v-model="item.type_of_trade" class="form-control">
							<option value="Import Trade">Import Trade</option>
							<option value="Export Trade">Export Trade</option>
							<option value="Entrepot Trade">Entrepot Trade</option>
						</select>
					</div>
					<div class="form-group col-md-4 mt-sm-auto">
						<label><input name="peza_checkbox" v-model="item.peza_checkbox" type="checkbox"> PEZA</label>
					</div>
				</div>

				<div class="card">
				    <div class="card-header p-2">
				        <ul class="nav nav-pills">
				            <li class="nav-item"><a class="nav-link active" href="#notes" data-toggle="tab">Notes</a></li>
				            <li class="nav-item"><a class="nav-link" href="#tax" data-toggle="tab">Tax Info</a></li>
				            <li class="nav-item"><a class="nav-link" href="#billing" data-toggle="tab">Payment and Billing</a></li>
				            <li class="nav-item"><a class="nav-link" href="#company" data-toggle="tab">Company</a></li>
				        </ul>
				    </div>
				    <div class="card-body">
				        <div class="tab-content">
				            <div class="tab-pane show active" id="notes">
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
				            	</div>
				            </div>
				            <div class="tab-pane" id="billing">
				            	<div class="row">
				            		<div class="col-md-6">
				            			<h5><label>Payment</label></h5>
				            		</div>
									<div class="col-md-6">
				            			<h5><label>Billing</label></h5>
				            		</div>
				            		<div class="col-md-6">
										<div class="form-group">
											<label>Terms of payment</label>
											<v-select v-model="item.terms_of_payment" :reduce="item => item.id"  label="terms_of_payment" placeholder="Select Terms of Payment" :options="terms_of_payments"></v-select>
											<input type="hidden" name="terms_of_payment" v-model="item.terms_of_payment"> 			
										</div>

										<div class="form-group">
											<label>Method of payment</label>
											<v-select v-model="item.method_of_payment" :reduce="item => item.id" label="name" placeholder="Select Method of Payment" :options="payment_methods"></v-select>
											<input type="hidden" name="method_of_payment" v-model="item.method_of_payment"> 	
										</div>

										<div class="form-group">
											<label>Payment type</label>
											<input type="text" name="payment_type" class="form-control" v-model="item.payment_type">
										</div>

										<div class="form-group">
											<label>Payment specification</label>
											<input type="text" name="payment_specification" class="form-control" v-model="item.payment_specification">
										</div>
				            		</div>
				            		<div class="col-md-6">
										<div class="form-group">
											<label>Bank Account</label>
											<input type="text" name="bank_account" v-model="item.bank_account" class="form-control">
										</div>
										<div class="form-group">
											<label>Bank Account Number</label>
											<input type="text" name="bank_account_number" class="form-control">
										</div>
										<div class="form-group">
											<label>Payment ID</label>
											<input type="text" name="payment_id" class="form-control">
										</div>
										<div class="form-group">
											<label>Payment Day</label>
											<v-select v-model="item.payment_day_id" :reduce="item => item.id" label="payment_day" placeholder="Select Payment Day" :options="payment_days"></v-select>
											<input type="hidden" name="payment_day_id" v-model="item.payment_day_id"> 
										</div>
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
	import "vue-select/dist/vue-select.css";
	import Vselect from "vue-select";

	export default {
		components: {
			Card,
			'v-select' : Vselect,
			'text-editor': TextEditor,
			'form-request': FormRequest,
			'action-button': ActionButton,
			'v-select' : Vselect
		},

		data() {
			return {
				item: {},
				payment_methods: [],
				payment_days: [],
				terms_of_payments: [],
				clients: [],
			}
		},

		watch : {
			'clients'(val) {
				// set the first client as default value
				if(!this.item.id) {
					this.item.client_id = val[0] ? val[0].id : null;
				}
			}
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.clients = data.clients ? data.clients : this.clients;
				this.payment_methods = data.payment_methods ? data.payment_methods : this.payment_methods;
				this.terms_of_payments = data.terms_of_payments ? data.terms_of_payments : this.terms_of_payments;
				this.payment_days = data.payment_days ? data.payment_days : this.payment_days;
			},
			updateMobile(e) {
				if(e.countryCallingCode && e.nationalNumber) {
					this.item.mobile_calling_code = this.$refs['mobile_number'].results.countryCallingCode;
					this.item.mobile_number = e.countryCallingCode+''+e.nationalNumber;
				}else {
					this.item.mobile_calling_code = null;
					this.item.mobile_number = null;
				}
			},
			updatePhone(e) {
				if(e.countryCallingCode && e.nationalNumber) {
					this.item.phone_calling_code = this.$refs['phone_number'].results.countryCallingCode;
					this.item.phone = e.countryCallingCode+''+e.nationalNumber;
				}else {
					this.item.phone_calling_code = null;
					this.item.phone = null;
				}
			},
			
			// automatically concut display name @input
			getDisplayName() {
				if(this.item.first_name && this.item.last_name) {
					this.item.display_name = this.item.first_name  + ' ' + this.item.last_name;
					if(this.item.title) {
						this.item.display_name = this.item.title +' '+ this.item.display_name;
					}
					if(this.item.suffix) {
						this.item.display_name += ' ' + this.item.suffix;
					}
				}
				
			}
		},

		mixins: [ CrudMixin ],
	}
</script>