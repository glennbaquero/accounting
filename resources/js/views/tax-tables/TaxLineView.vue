<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="submitSuccess" confirm-dialog sync-on-success :params="submitParams">

			<card>
				<template v-slot:header>
					Tax Line
					<div class="float-right">
						<action-button type="submit" :disabled="loading" class="btn btn-primary btn-sm">Save Changes</action-button>
					</div>
				</template>
				<div class="card">
					<div class="card-header p-2">	
						<div class="row">
							<div class="col-md-9">
								<ul class="nav nav-pills">
									<li class="nav-item"><a class="nav-link active" @click="currentTab = 'tax_line'" href="#tax_line" data-toggle="tab">Tax Line</a></li>
									<li class="nav-item"><a class="nav-link"  @click="currentTab = 'audit_br'" href="#audit_br" data-toggle="tab">Audit Trail</a></li>
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
				        	<div v-show="currentTab == 'tax_line'">
				        		<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<h4><i class="fas fa-tags"></i> Header</h4><hr>
										</div>

										<div class="form-group">
			        		    			<label for="tax_name">Tax Name <span class="text-danger">*</span></label>
											<input id="tax_name" name="tax_name" type="text" class="form-control" v-model="item.tax_name">
										</div>

										<!-- <div class="form-group">
			        		    			<label for="tax_posting_id">Tax Posting ID <span class="text-danger">*</span></label>
											<input id="tax_posting_id" name="tax_posting_id" type="text" class="form-control" v-model="item.tax_posting_id">
										</div>
 -->
										<div class="form-group">
			        		    			<label for="tax_posting">Tax Posting <span class="text-danger">*</span></label>
											<input id="tax_posting" name="tax_posting" type="text" class="form-control" v-model="item.tax_posting">
										</div>

										<div class="form-group">
			        		    			<label for="description">Description <span class="text-danger">*</span></label>
											<input id="description" name="description" type="text" class="form-control" v-model="item.description">
										</div>

										<div class="form-group">
											<label>Level <b class="text-danger">*</b></label>
											<v-select 
												v-model="item.level"
												placeholder="Select Level"
												:options="levels"
											></v-select>
										</div>

										<div class="form-group">
											<label>Applied To <b class="text-danger">*</b></label>
											<v-select 
												v-model="item.applied_to"
												placeholder="Select Applied To"
												:options="applies"
											></v-select>
										</div>

										<div class="form-group">
			        		    			<label for="tax_percent">Tax Percent <span class="text-danger">*</span></label>
											<input id="tax_percent" name="tax_percent" type="text" class="form-control" v-model="item.tax_percent">
										</div>

										<div class="form-group">
                                            <label>PEZA</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            	<input type="checkbox" class="custom-control-input" id="peza_checkbox" name="peza_checkbox" v-model="item.peza_checkbox">
                                                <label class="custom-control-label" for="peza_checkbox">
                                                    <span class="badge" :class="item.peza_checkbox ? 'badge-success' : 'badge-danger'">
                                                        {{ item.peza_checkbox ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>VAT Exempt Number</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            	<input type="checkbox" class="custom-control-input" id="vat_exempt_number" name="vat_exempt_number" v-model="item.vat_exempt_number">
                                                <label class="custom-control-label" for="vat_exempt_number">
                                                    <span class="badge" :class="item.vat_exempt_number ? 'badge-success' : 'badge-danger'">
                                                        {{ item.vat_exempt_number ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
			        		    			<label for="major_industry_clasification">Major Industry Clasification <span class="text-danger">*</span></label>
											<input id="major_industry_clasification" name="major_industry_clasification" type="text" class="form-control" v-model="item.major_industry_clasification">
										</div>

										<div class="form-group">
			        		    			<label for="industry_clasification_group">Industry Clasification Group <span class="text-danger">*</span></label>
											<input id="industry_clasification_group" name="industry_clasification_group" type="text" class="form-control" v-model="item.industry_clasification_group">
										</div>

										<div class="form-group">
			        		    			<label for="psic_sections">PSIC Sections <span class="text-danger">*</span></label>
											<input id="psic_sections" name="psic_sections" type="text" class="form-control" v-model="item.psic_sections">
										</div>

										<div class="form-group">
			        		    			<label for="psic_divisions">PSIC Divisions <span class="text-danger">*</span></label>
											<input id="psic_divisions" name="psic_divisions" type="text" class="form-control" v-model="item.psic_divisions">
										</div>

										<div class="form-group">
			        		    			<label for="psic_groups">PSIC Groups <span class="text-danger">*</span></label>
											<input id="psic_groups" name="psic_groups" type="text" class="form-control" v-model="item.psic_groups">
										</div>

										<div class="form-group">
			        		    			<label for="psic_class">PSIC Class <span class="text-danger">*</span></label>
											<input id="psic_class" name="psic_class" type="text" class="form-control" v-model="item.psic_class">
										</div>

										<div class="form-group">
			        		    			<label for="psic_subclass">PSIC SubClass <span class="text-danger">*</span></label>
											<input id="psic_subclass" name="psic_subclass" type="text" class="form-control" v-model="item.psic_subclass">
										</div>

									</div>

									<div class="col-md-4">
										<div class="form-group">
											<h4><i class="fas fa-tags"></i> Procurement</h4><hr>
										</div>

										<div class="form-group">
											<label>Procurement Posting</label>
											<v-select v-model="item.procurement_posting" :reduce="item => item.id" label="procurement" :options="procurements"></v-select>
										</div>

										<div class="form-group">
											<label>Product</label>
											<v-select v-model="item.product_id" :reduce="item => item.id" label="name" :options="products" placeholder="Select a Product"></v-select>
										</div>

										<div class="form-group">
											<label>Variant</label>
											<v-select v-model="item.variant_id" :reduce="item => item.id" label="name" :options="variants" placeholder="Select a Variant"></v-select>
										</div>

										<div class="form-group">
											<label>Service</label>
											<v-select v-model="item.service_id" :reduce="item => item.id" label="name" :options="services" placeholder="Select a Service"></v-select>
										</div>

										<div class="form-group">
											<label>Service Task</label>
											<v-select v-model="item.service_task_id" :reduce="item => item.id" label="service" :options="service_tasks" placeholder="Select a Service"></v-select>
										</div>

										<div class="form-group">
											<label>Delivery Type</label>
											<v-select 
												v-model="item.delivery_type"
												placeholder="Select Delivery Type"
												:options="delivery_types"
											></v-select>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<h4><i class="fas fa-tags"></i> Main Account</h4><hr>
										</div>

										<div class="form-group">
							                <label>Tax Account Code Number <b class="text-danger">*</b></label>
							                <input type="text" class="form-control" name="tax_account_code_number" v-model="item.tax_account_code_number">
							            </div>

										<div class="form-group">
											<label>Tax Account <b class="text-danger">*</b></label>
											<v-select v-model="item.tax_account" :reduce="item => item.main_account_id" label="main_account_name" :options="main_accounts" placeholder="Select a Tax Account"></v-select>
										</div>
									</div>

			        		    </div>
				        	</div>

				        	<div v-show="currentTab == 'audit_br'">
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

		mounted() {
			this.mountInputs();
		},

		data() {
			return {
				currentTab: 'tax_line',
				item: {
					tax_posting: this.parent.tax_posting,
					tax_percent: this.parent.tax_percent,
					peza_checkbox: this.parent.peza_checkbox,
					vat_exempt_number: this.parent.vat_exempt_number_checkbox,
					tax_account: this.parent.tax_account,
					tax_account_code_number: this.parent.tax_account_code_number,
					tax_posting_id: this.parent.id,
				},
				products: [],
				services: [],
				service_tasks: [],
				main_accounts: [],
				variants: [],
				levels: [
					'Main',
					'Line',
				],
				applies: [
					'All',
					'Customer',
					'Vendor',
					'Product',
					'Service',
				],
				delivery_types: [
					'Air',
					'Land',
					'Sea',
				],
				procurements: [],
			}
		},

		watch: {
			'item.product_id'(value) {
				let result = this.products.find((product) => {
					return product.id == value;
				});

				this.variants = result ? result.variants : [];
			},
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.products = data.products ? data.products : [];
				this.services = data.services ? data.services : [];
				this.service_tasks = data.service_tasks ? data.service_tasks : [];
				this.main_accounts = data.main_accounts ? data.main_accounts : [];
				this.procurements = data.procurements ? data.procurements : [];
			},

			mountInputs() {

            },

            submitSuccess() {
            	this.fetch();
            	this.$emit('submit-success');
            },
		},

		computed: {
			submitParams() {
				let item = this.item;
				item.tax_id = this.parent.id;
				return item;
			},

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

		props: {
            clients: {
                type: Array,
                default: () => [],
            },
            parent: {
                type: Object,
                default: () => {},
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