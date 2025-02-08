<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="submitSuccess" confirm-dialog sync-on-success :params="submitParams">
				<div class="row mb-2">
					<div class="col-12 text-right">
						<action-button type="submit" :disabled="loading" class="btn btn-primary">Save Changes</action-button>
					</div>
				</div>
				<div class="card">
					<div class="card-header p-2">
						<div class="row">
							<div class="col-md-9">
								<ul class="nav nav-pills">
									<li class="nav-item"><a class="nav-link active" @click="currentTab = 'payment_schedule_line'" href="#payment_schedule_line" data-toggle="tab">Payment Schedule Line</a></li>
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
				        	<div v-show="currentTab == 'payment_schedule_line'">
				        		<div class="row">
				        			<div class="col-12">
				        				<div class="form-group">
											<h4><i class="fas fa-tags"></i> General Information</h4>
										</div>
				        			</div>

									<div class="col-md-6">
										<div class="form-group">
											<hr>
										</div>

										<div class="form-group">
                                            <label for="schedule_line_id">Payment Schedule Line ID</label>
                                            <input type="text" class="form-control" :value="item.schedule_line_id" id="schedule_line_id" readonly>
                                        </div>

										<div class="form-group">
                                            <label for="payment_schedule_id">Payment Schedule ID</label>
                                            <input type="text" class="form-control" :value="parent.payment_schedule_id" id="payment_schedule_id" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="due_date">Due Date <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input ref="due_date" type="text" class="form-control calendar-form" id="due_date" name="due_date" v-model="item.due_date">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="duration">Duration <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" v-model="item.duration" id="duration">
                                        </div>

									</div>

									<div class="col-md-6">
										<div class="form-group">
											<hr>
										</div>

										<div class="form-group">
                                            <label for="principal_amount">Principal Amount <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" v-model="item.principal_amount" id="principal_amount">
                                        </div>

                                        <div class="form-group">
                                            <label for="interest">Interest <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" v-model="item.interest" id="interest">
                                        </div>

                                        <div class="form-group">
                                            <label for="payment">Payment <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" v-model="item.payment" id="payment">
                                        </div>

                                        <div class="form-group">
                                            <label for="balance">Balance <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" v-model="item.balance" id="balance">
                                        </div>

                                        <div class="form-group">
                                            <label>Line Status <b class="text-danger">*</b></label>
                                            <v-select 
                                                v-model="item.line_status" 
                                                :options="line_statuses"
                                            ></v-select>
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
			<!-- </card> -->


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
				currentTab: 'payment_schedule_line',
				item: {},
				line_statuses: [
					'Applied',
					'Printed',
					'Paid',
					'Posted',
				],
			}
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : {};
			},

			mountInputs() {
                flatpickr(this.$refs.due_date);
            },

            submitSuccess() {
            	this.fetch();
            	this.$emit('submit-success');
            },
		},

		computed: {
			submitParams() {
				let item = this.item;
				item.payment_schedule_id = this.parent.payment_schedule_id;
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