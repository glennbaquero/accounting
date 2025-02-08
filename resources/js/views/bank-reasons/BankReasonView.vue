<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="submitParams">

			<card>
				<template v-slot:header>
					Bank Reason Information
					<div class="float-right">
						<action-button type="submit" :disabled="loading" class="btn btn-primary btn-sm">Save Changes</action-button>
					</div>
				</template>
				<div class="card">
					<div class="card-header p-2">	
						<div class="row">
							<div class="col-md-9">
								<ul class="nav nav-pills">
									<li class="nav-item"><a class="nav-link active" href="#bank_account" data-toggle="tab">Bank Reason</a></li>
									<li class="nav-item"><a class="nav-link" href="#audit" data-toggle="tab">Audit Trail</a></li>
								</ul>
							</div>
						</div>
				    </div>

				     <div class="card-body">
				        <div class="tab-content">
				        	<div class="tab-pane show active" id="bank_account">
				        		<div class="row">
									<div class="form-group col-md-6">
		        		    			<label for="reason_code">Reason Code</label>
										<input id="reason_code" name="reason_code" type="text" class="form-control" v-model="item.reason_code">
									</div>
									<div class="form-group col-md-6">
		        		    			<label for="default_comment">Default Comment</label>
										<input id="default_comment" name="default_comment" type="text" class="form-control" v-model="item.default_comment">
									</div>

									<div class="col-md-3">
										<div class="form-check">
											<input id="bank" name="bank" type="checkbox" class="form-check-input" v-model="item.bank">
			        		    			<label for="bank">Bank</label>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-check">
											<input id="purpose_code" name="purpose_code" type="checkbox" class="form-check-input" v-model="item.purpose_code">
			        		    			<label for="purpose_code">Purpose Code</label>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-check">
											<input id="cancellation_reason" name="cancellation_reason" type="checkbox" class="form-check-input" v-model="item.cancellation_reason">
			        		    			<label for="cancellation_reason">Cancellation Reason</label>
										</div>
									</div>
			        		    </div>
				        	</div>

				        	<div class="tab-pane" id="audit">
				        		<div class="row">
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

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
			},
		},

		computed: {
			submitParams() {
				let item = this.item;
				return item;
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

		data() {
			return {
				item: {},
			}
		}
	}

</script>