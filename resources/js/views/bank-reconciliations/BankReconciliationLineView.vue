<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="submitSuccess" confirm-dialog sync-on-success :params="submitParams">

			<card>
				<template v-slot:header>
					Bank Reconciliation Line
					<div class="float-right">
						<action-button type="submit" :disabled="loading" class="btn btn-primary btn-sm">Save Changes</action-button>
					</div>
				</template>
				<div class="card">
					<div class="card-header p-2">	
						<div class="row">
							<div class="col-md-9">
								<ul class="nav nav-pills">
									<li class="nav-item"><a class="nav-link active" @click="currentTab = 'bank_reconciliation_line'" href="#bank_reconciliation_line" data-toggle="tab">Bank Reconciliation Line</a></li>
									<li class="nav-item"><a class="nav-link"  @click="currentTab = 'audit_br'" href="#audit_br" data-toggle="tab">Audit Trail</a></li>
								</ul>
							</div>
						</div>
				    </div>

				     <div class="card-body">
				        <div class="tab-content">
				        	<div v-show="currentTab == 'bank_reconciliation_line'">
				        		<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<h4><i class="fas fa-user"></i> Header</h4><hr>
										</div>

										<div class="form-group">
											<label>Client <b class="text-danger">*</b></label>
											<v-select v-model="item.client_id" :reduce="item => item.id" label="name" placeholder="Select Client" :options="clients"></v-select>
										</div>

										<div class="form-group">
			        		    			<label for="bank_reconciliation_line_id">Bank Reconciliation Line ID</label>
											<input id="bank_reconciliation_line_id" name="bank_reconciliation_line_id" type="text" class="form-control" :value="item.bank_reconciliation_line_id" disabled>
										</div>

										<div class="form-group">
			        		    			<label for="bank_reconciliation_id">Bank Reconciliation ID</label>
											<input id="bank_reconciliation_id" name="bank_reconciliation_id" type="text" class="form-control" :value="item.bank_reconciliation_id" disabled>
										</div>

										<div class="form-group">
                                            <label for="reconciled_date">Reconciled Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="reconciled_date" type="text" class="form-control" id="reconciled_date" name="reconciled_date" :value="parent.reconciled_date" disabled>
                                            </div>
                                        </div>

										<div class="form-group">
			        		    			<label for="reconciled_by">Reconciled By</label>
											<input id="reconciled_by" name="reconciled_by" type="text" class="form-control" :value="parent.reconciled_by" disabled>
										</div>

										<div class="form-group">
                                            <label>Reconciled</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            <input type="checkbox" class="custom-control-input" id="reconciled_checkbox" name="reconciled_checkbox" :value="parent.reconciled_checkbox" disabled>
                                                <label class="custom-control-label" for="reconciled_checkbox">
                                                    <span class="badge" :class="parent.reconciled_checkbox ? 'badge-success' : 'badge-danger'">
                                                        {{ parent.reconciled_checkbox ? 'Yes' : 'No'  }}
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
			        		    			<label for="description">Description</label>
											<input id="description" name="description" type="text" class="form-control" v-model="item.description">
										</div>

									</div>

									<div class="col-md-6">
										<div class="form-group">
											<h4><i class="fas fa-money-check-alt"></i> Transaction</h4><hr>
										</div>

										<div class="form-group">
											<label>Operation Type</label>
											<v-select v-model="item.operation_type" :reduce="item => item.value" label="label" placeholder="Select Operation Type" :options="operation_types"></v-select>
										</div>

										<div class="form-group">
											<label>Source</label>
											<v-select v-model="item.source" placeholder="Select Soruce" :options="sources"></v-select>
										</div>

										<div class="form-group">
											<label>Statement Adjustment ID <b class="text-danger">*</b></label>
											<v-select 
											v-model="item.statement_adjustment_id" 
											:reduce="item => item.bank_statement_adjustment_id" 
											label="bank_statement_adjustment_id" 
											placeholder="Select a Statement" 
											:options="bank_account_statement_adjustments"
											></v-select>
										</div>

										<div class="form-group">
											<label>Cash Register Adjustment ID <b class="text-danger">*</b></label>
											<v-select 
											v-model="item.cash_register_adjustment_id" 
											:reduce="item => item.cashflow_adjustment_id" 
											label="cashflow_adjustment_id" 
											placeholder="Select a Cash Register" 
											:options="cash_register_adjustments"
											></v-select>
										</div>

										<div class="form-group">
											<label>Bank Posting <b class="text-danger">*</b></label>
											<v-select 
											v-model="item.bank_posting_id" 
											:reduce="item => item.id" 
											label="bank_transaction_posting" 
											placeholder="Select a Bank Posting" 
											:options="bank_postings"
											></v-select>
										</div>

										<div class="form-group">
			        		    			<label for="adjustment_name">Adjustment Name <span class="text-danger">*</span></label>
											<input id="adjustment_name" name="adjustment_name" type="text" class="form-control" v-model="item.adjustment_name">
										</div>

										<div class="form-group">
			        		    			<label for="adjustment_amount">Adjustment Amount <span class="text-danger">*</span></label>
											<input id="adjustment_amount" name="adjustment_amount" type="text" class="form-control" v-model="item.adjustment_amount">
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

		watch: {
			'item.client_bank_account'(bank_account) {
				let item = this.client_banks.find((bank) => {
					return bank.bank_account == bank_account;
				});

				if(item) {
					this.item.bank_account_number = item.bank_account_number;
					this.item.bank_account_type = item.bank_account_type;
				}
			},
		},

		data() {
			return {
				currentTab: 'bank_reconciliation_line',
				item: {},
				bank_account_statement_adjustments: [],
				cash_register_adjustments: [],
				client_banks: [],
				bank_postings: [],
				operation_types: [
					{ value: 0, label: 'Less' },
					{ value: 1, label: 'Add' },
				],
				sources: [
					'Bank Statement',
					'Cash Register',
					'Bank Posting',
					'User Entry',
				],
			}
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : {};
				this.bank_postings = data.bank_postings ? data.bank_postings : this.bank_postings;
				this.bank_account_statement_adjustments = data.bank_account_statement_adjustments ? data.bank_account_statement_adjustments : this.bank_account_statement_adjustments;
				this.cash_register_adjustments = data.cash_register_adjustments ? data.cash_register_adjustments : this.cash_register_adjustments;
				this.item.bank_reconciliation_id = this.parent.bank_reconciliation_id;
			},

			mountInputs() {
                // flatpickr(this.$refs.statement_as_of_date, {enableTime: true});
                // flatpickr(this.$refs.cash_register_as_of_date, {enableTime: true});
            },

            submitSuccess() {
            	this.fetch();
            	this.$emit('submit-success');
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