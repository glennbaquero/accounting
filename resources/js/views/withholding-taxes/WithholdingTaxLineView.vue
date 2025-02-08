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
									<li class="nav-item"><a class="nav-link active" @click="currentTab = 'withholding_tax_line'" href="#withholding_tax_line" data-toggle="tab">Withholding Tax Line</a></li>
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
				        	<div v-show="currentTab == 'withholding_tax_line'">
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
                                            <label for="withholding_tax_id">Withholding Tax ID</label>
                                            <input type="text" class="form-control" :value="item.withholding_tax_id" id="withholding_tax_id" readonly>
                                        </div>

										<div class="form-group">
                                            <label for="withholding_tax_posting_id">Withholding Tax Posting ID</label>
                                            <input type="text" class="form-control" :value="parent.id" id="withholding_tax_posting_id" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="withholding_tax_name">Withholding Tax Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" v-model="item.withholding_tax_name" id="withholding_tax_name">
                                        </div>

                                        <div class="form-group">
                                            <label for="withholding_tax_posting">Withholding Tax Posting <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" v-model="item.withholding_tax_posting" id="withholding_tax_posting">
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" v-model="item.description" id="description">
                                        </div>

									</div>

									<div class="col-md-6">
										<div class="form-group">
											<hr>
										</div>

										<div class="form-group">
                                            <label for="minimum_amount">Minimum Amount <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" v-model="item.minimum_amount" id="minimum_amount">
                                        </div>

                                        <div class="form-group">
                                            <label for="maximum_amount">Maximum Amount <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" v-model="item.maximum_amount" id="maximum_amount">
                                        </div>

                                        <div class="form-group">
                                            <label for="tax_percent">Tax Percent <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" v-model="item.tax_percent" id="tax_percent">
                                        </div>

										<div class="form-group">
                                            <label>Withholding Tax Exemptions</label>
                                            <div class="custom-control custom-switch mb-3 mt-2">
                                            	<input type="checkbox" class="custom-control-input" id="withholding_tax_exemptions_checkbox_line" name="withholding_tax_exemptions_checkbox" v-model="item.withholding_tax_exemptions_checkbox">
                                                <label class="custom-control-label" for="withholding_tax_exemptions_checkbox_line">
                                                    <span class="badge" :class="item.withholding_tax_exemptions_checkbox ? 'badge-success' : 'badge-danger'">
                                                        {{ item.withholding_tax_exemptions_checkbox ? 'Yes' : 'No'  }}
                                                    </span>
                                                </label>
                                            </div>
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
			
		},

		data() {
			return {
				currentTab: 'withholding_tax_line',
				item: {},
			}
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : {};
			},

			mountInputs() {
                // flatpickr(this.$refs.due_date);
            },

            submitSuccess() {
            	this.fetch();
            	this.$emit('submit-success');
            },
		},

		computed: {
			submitParams() {
				let item = this.item;
				item.withholding_tax_posting_id = this.parent.id;
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