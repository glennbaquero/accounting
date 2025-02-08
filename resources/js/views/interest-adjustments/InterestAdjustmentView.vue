<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="submitParams">

			<card>
				<template v-slot:header>
					Interest Setup Information
					<div class="float-right">
						<action-button type="submit" :disabled="loading" class="btn btn-primary btn-sm">Save Changes</action-button>
						<action-button
	                        v-if="item.approveUrl"
							small
	                        color="btn-success"
	                        :action-url="item.approveUrl"
	                        confirm-dialog
	                        title="Approve Item"
	                        :message="`Are you sure you want to approve ${item.interest_adjustment_id}?`"
	                        @load="load"
	                        @success="fetch"
	                        :disabled="item.approved_checkbox || loading"
                        >Approve</action-button>
                        <action-button
	                        v-if="item.waiveUrl"
							small
	                        color="btn-danger"
	                        :action-url="item.waiveUrl"
	                        confirm-dialog
	                        title="Waive Item"
	                        :message="`Are you sure you want to waive ${item.interest_adjustment_id}?`"
	                        @load="load"
	                        @success="fetch"
	                        :disabled="item.waived_checkbox || loading"
                        >Waive</action-button>
                        <action-button
	                        v-if="item.reinstateUrl"
							small
	                        color="btn-warning"
	                        :action-url="item.reinstateUrl"
	                        confirm-dialog
	                        title="Reinstate Item"
	                        :message="`Are you sure you want to reinstate ${item.interest_adjustment_id}?`"
	                        @load="load"
	                        @success="fetch"
	                        :disabled="item.reinstated_checkbox || loading"
                        >Reinstate</action-button>
                        <action-button
	                        v-if="item.reserveUrl"
							small
	                        color="btn-info"
	                        :action-url="item.reserveUrl"
	                        confirm-dialog
	                        title="Reserve Item"
	                        :message="`Are you sure you want to reserve ${item.interest_adjustment_id}?`"
	                        @load="load"
	                        @success="fetch"
	                        :disabled="item.reserved_checkbox || loading"
                        >Reserve</action-button>
                        <action-button
	                        v-if="item.postUrl"
							small
	                        color="btn-secondary"
	                        :action-url="item.postUrl"
	                        confirm-dialog
	                        title="Post Item"
	                        :message="`Are you sure you want to post ${item.interest_adjustment_id}?`"
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
									<li class="nav-item"><a class="nav-link active" href="#bank_account" data-toggle="tab">Interest Adjustment</a></li>
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
			        		    			<label for="interest_adjustment_id">Interest Adjustment ID</label>
											<input id="interest_adjustment_id" name="interest_adjustment_id" type="text" class="form-control" :value="item.interest_adjustment_id" disabled>
										</div>

										<div class="form-group">
											<label>Interest Adjustment Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="interest_adjustment_date" type="text" class="form-control calendar-form" name="interest_adjustment_date" v-model="item.interest_adjustment_date">
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
											<label>Customer Account <b class="text-danger">*</b></label>
											<v-select v-model="item.customer_account" label="fullname" :reduce="item => item.customer_account" :options="customers"></v-select>
										</div>

										<div class="form-group">
			        		    			<label for="customer">Customer <b class="text-danger">*</b></label>
											<input id="customer" name="customer" type="text" class="form-control" v-model="item.customer">
										</div>

										<div class="form-group">
											<label>Transaction Date <b class="text-danger">*</b></label>
											<div class="input-group mb-2">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
												</div>
												<input ref="transaction_date" type="text" class="form-control calendar-form" name="transaction_date" v-model="item.transaction_date">
											</div>
										</div>

										<div class="form-group">
											<label>Transaction Type <b class="text-danger">*</b></label>
											<v-select v-model="item.transaction_type" :options="transaction_types"></v-select>
										</div>

										<div class="form-group">
											<label>Interest Note <b class="text-danger">*</b></label>
											<v-select v-model="item.interest_note_id" label="interest_note" :reduce="item => item.id" :options="interest_notes"></v-select>
										</div>

										<div class="form-group">
			        		    			<label for="interest_note_amount">Interest Note Amount <b class="text-danger">*</b></label>
											<input id="interest_note_amount" name="interest_note_amount" type="text" class="form-control" v-model="item.interest_note_amount">
										</div>

										<div class="form-group">
			        		    			<label for="waived_amount">Waived Amount <b class="text-danger">*</b></label>
											<input id="waived_amount" name="waived_amount" type="text" class="form-control" v-model="item.waived_amount">
										</div>

										<div class="form-group">
			        		    			<label for="unpaid_balance">Unpaid Balance <b class="text-danger">*</b></label>
											<input id="unpaid_balance" name="unpaid_balance" type="text" class="form-control" v-model="item.unpaid_balance">
										</div>

										<div class="form-group">
			        		    			<label for="fee_amount">Fee Amount <b class="text-danger">*</b></label>
											<input id="fee_amount" name="fee_amount" type="text" class="form-control" v-model="item.fee_amount">
										</div>

									</div>

									<div class="col-md-4">
										<div class="form-group">
											<hr>
										</div>

										<div class="form-group">
											<label>Interest Adjustment Status <b class="text-danger">*</b></label>
											<v-select v-model="item.interest_adjustment_status" :options="interest_adjustment_statuses"></v-select>
										</div>

										<div class="form-group">
                                            <label for="approved_date">Approved Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="approved_date" type="text" class="form-control" id="approved_date" name="approved_date" v-model="item.approved_date" disabled>
                                            </div>
                                        </div>

										<div class="form-group">
			        		    			<label for="approved_by">Approved By</label>
											<input id="approved_by" name="approved_by" type="text" class="form-control" v-model="item.approved_by" disabled>
										</div>

										<div class="form-group">
                                            <label>Approved</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            <input type="checkbox" class="custom-control-input" id="approved_checkbox" name="approved_checkbox" v-model="item.approved_checkbox" disabled>
                                                <label class="custom-control-label" for="closed_checkbox">
                                                    <span class="badge" :class="item.approved_checkbox ? 'badge-success' : 'badge-danger'">
                                                        {{ item.approved_checkbox ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="waived_date">Waived Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="waived_date" type="text" class="form-control" id="waived_date" name="waived_date" v-model="item.waived_date" disabled>
                                            </div>
                                        </div>

										<div class="form-group">
			        		    			<label for="waived_by">Waived By</label>
											<input id="waived_by" name="waived_by" type="text" class="form-control" v-model="item.waived_by" disabled>
										</div>

										<div class="form-group">
                                            <label>Waived</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            <input type="checkbox" class="custom-control-input" id="waived_checkbox" name="waived_checkbox" v-model="item.waived_checkbox" disabled>
                                                <label class="custom-control-label" for="closed_checkbox">
                                                    <span class="badge" :class="item.waived_checkbox ? 'badge-success' : 'badge-danger'">
                                                        {{ item.waived_checkbox ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="reinstated_date">Reinstated Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="reinstated_date" type="text" class="form-control" id="reinstated_date" name="reinstated_date" v-model="item.reinstated_date" disabled>
                                            </div>
                                        </div>

										<div class="form-group">
			        		    			<label for="reinstated_by">Reinstated By</label>
											<input id="reinstated_by" name="reinstated_by" type="text" class="form-control" v-model="item.reinstated_by" disabled>
										</div>

										<div class="form-group">
                                            <label>Reinstated</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            <input type="checkbox" class="custom-control-input" id="reinstated_checkbox" name="reinstated_checkbox" v-model="item.reinstated_checkbox" disabled>
                                                <label class="custom-control-label" for="closed_checkbox">
                                                    <span class="badge" :class="item.reinstated_checkbox ? 'badge-success' : 'badge-danger'">
                                                        {{ item.reinstated_checkbox ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="reserved_date">Reserved Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="reserved_date" type="text" class="form-control" id="reserved_date" name="reserved_date" v-model="item.reserved_date" disabled>
                                            </div>
                                        </div>

										<div class="form-group">
			        		    			<label for="reserved_by">Reserved By</label>
											<input id="reserved_by" name="reserved_by" type="text" class="form-control" v-model="item.reserved_by" disabled>
										</div>

										<div class="form-group">
                                            <label>Reserved</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            <input type="checkbox" class="custom-control-input" id="reserved_checkbox" name="reserved_checkbox" v-model="item.reserved_checkbox" disabled>
                                                <label class="custom-control-label" for="closed_checkbox">
                                                    <span class="badge" :class="item.reserved_checkbox ? 'badge-success' : 'badge-danger'">
                                                        {{ item.reserved_checkbox ? 'Yes' : 'No'  }}
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

									</div>

									<div class="col-md-4">
										<div class="form-group">
											<hr>
										</div>

										<div class="form-group">
			        		    			<label for="voucher">Voucher <b class="text-danger">*</b></label>
											<input id="voucher" name="voucher" type="text" class="form-control" v-model="item.voucher">
										</div>

										<div class="form-group">
			        		    			<label for="write_off_amount">Write Off Amount <b class="text-danger">*</b></label>
											<input id="write_off_amount" name="write_off_amount" type="text" class="form-control" v-model="item.write_off_amount">
										</div>

										<div class="form-group">
			        		    			<label for="fee_write_off_amount">Fee Write Off Amount <b class="text-danger">*</b></label>
											<input id="fee_write_off_amount" name="fee_write_off_amount" type="text" class="form-control" v-model="item.fee_write_off_amount">
										</div>

									</div>
			        		    </div>
				        	</div>
				        	<div class="tab-pane" id="audit">
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
		        		                <input name="posting_profile" v-model="item.posting_profile" type="hidden" class="form-control mb-2" :disabled="item.approved_by">
		        		    			
		        		    			<label>Document</label>
		        		    			<input type="text" class="form-control mb-2" name="document" v-model="item.document">
		        		    			<label>Document Status</label>
		        		    			<input type="text" class="form-control mb-2" name="document_status" v-model="item.document_status">

		        		    			<label>Accounting Distribution</label>
		        		                <input name="accounting_distribution" v-model="item.accounting_distribution" type="text" class="form-control mb-2" :disabled="item.approved_by">
		        		    		</div>
									<div class="form-group col-sm-6">
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
				interest_notes: [],
				cost_centers: [],
				departments: [],
				expense_purposes: [],
				posting_profiles: [],
				transaction_types: [
					'Vendor Invoice',
					'Vendor Payment',
					'Customer Invoice',
					'Customer Payment',
					'Interest Note',
 				],
 				interest_adjustment_statuses: [
 					'New',
 				 	'Pending',
 				 	'Approved',
 				 	'Cancelled',
 				],
			}
		},

		mounted() {
			flatpickr(this.$refs.interest_adjustment_date);
			flatpickr(this.$refs.start_date);
			flatpickr(this.$refs.end_date);
			flatpickr(this.$refs.transaction_date);
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.customers = data.customers ? data.customers : this.customers;
				this.interest_notes = data.interest_notes ? data.interest_notes : this.interest_notes;
				this.cost_centers = data.cost_centers ? data.cost_centers : this.cost_centers;
				this.departments = data.departments ? data.departments : this.departments;
				this.expense_purposes = data.expense_purposes ? data.expense_purposes : this.expense_purposes;
				this.posting_profiles = data.posting_profiles ? data.posting_profiles : this.posting_profiles;
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