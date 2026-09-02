<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<input type="hidden" name="template_lines" :value="serializedLines">

			<card>
				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>Template Information</b></h3>
			        </div>
				    <div class="card-body">
						<div class="row">
				    		<div class="form-group col-sm-6">
			    				<label>Client</label>
								<v-select v-model="item.client_id" :reduce="item => item.id" name="client_id" label="name" placeholder="Select Client" :options="clients"></v-select>
								<input hidden v-model="item.client_id" name="client_id">
			    			</div>
				    		<div class="form-group col-sm-6">
				    			<label>Template Id</label>
				                <input name="template_id" v-model="item.template_id" class="form-control" readonly>
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<label>Template Name</label>
				                <input name="template_name" type="text" v-model="item.template_name" class="form-control">
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<label>Journal Name</label>
				                <input name="journal_name" type="text" v-model="item.journal_name" class="form-control" placeholder="Name given to each generated journal">
				    		</div>

				    		<div class="form-group col-sm-4">
				    			<label>Frequency</label>
				    			<select name="frequency" v-model="item.frequency" class="form-control">
				    				<option value="Daily">Daily</option>
				    				<option value="Weekly">Weekly</option>
				    				<option value="Monthly">Monthly</option>
				    				<option value="Quarterly">Quarterly</option>
				    				<option value="Annually">Annually</option>
				    			</select>
				    		</div>
				    		<div class="form-group col-sm-4">
								<label>Start Date</label>
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
									</div>
				    				<input ref="start_date" type="text" class="form-control calendar-form" name="start_date" v-model="item.start_date" readonly>
								</div>
							</div>
				    		<div class="form-group col-sm-4">
								<label>End Date (optional)</label>
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
									</div>
				    				<input ref="end_date" type="text" class="form-control calendar-form" name="end_date" v-model="item.end_date">
								</div>
							</div>

				    		<div class="form-group col-sm-4">
				    			<label>Occurrences Limit (optional)</label>
				                <input name="occurrences_limit" type="number" v-model.number="item.occurrences_limit" class="form-control">
				    		</div>
				    		<div class="form-group col-sm-4">
				    			<label>Next Run Date</label>
				                <input v-model="item.next_run_date" class="form-control" readonly>
				    		</div>
				    		<div class="form-group col-sm-4">
				    			<label>Status</label>
				                <input v-model="item.status" class="form-control" readonly>
				    		</div>

							<text-editor
								v-model="item.description"
								class="col-sm-12"
								label="Description"
								name="description"
								row="5"
							></text-editor>
						</div>
					</div>
				</div>

				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>Template Lines</b></h3>
				        <div class="card-tools">
				        	<button type="button" class="btn btn-sm btn-primary" @click="addLine"><i class="fa fa-plus"></i> Add Line</button>
				        </div>
			        </div>
				    <div class="card-body table-responsive p-0">
				    	<table class="table table-sm table-bordered">
				    		<thead>
				    			<tr>
				    				<th style="min-width:260px;">Main Account</th>
				    				<th style="min-width:200px;">Description</th>
				    				<th style="min-width:140px;">Debit</th>
				    				<th style="min-width:140px;">Credit</th>
				    				<th></th>
				    			</tr>
				    		</thead>
				    		<tbody>
				    			<tr v-for="(line, index) in template_lines" :key="index">
				    				<td>
				    					<select class="form-control" v-model.number="line.main_account" @change="onMainAccountChange(line)">
				    						<option :value="null">Select account</option>
				    						<option v-for="account in mainaccounts" :key="account.id" :value="account.id">{{ account.main_account_code }} - {{ account.main_account_name }}</option>
				    					</select>
				    				</td>
				    				<td><input type="text" class="form-control" v-model="line.description"></td>
				    				<td><input type="number" step="0.01" class="form-control text-right" v-model.number="line.debit_amount"></td>
				    				<td><input type="number" step="0.01" class="form-control text-right" v-model.number="line.credit_amount"></td>
				    				<td class="text-center">
				    					<button type="button" class="btn btn-sm btn-danger" @click="removeLine(index)"><i class="fa fa-trash"></i></button>
				    				</td>
				    			</tr>
				    			<tr v-if="!template_lines.length">
				    				<td colspan="5" class="text-center text-muted">No lines added yet.</td>
				    			</tr>
				    		</tbody>
				    		<tfoot>
				    			<tr>
				    				<th class="text-right" colspan="2">Total</th>
				    				<th class="text-right">{{ totalDebit.toFixed(2) }}</th>
				    				<th class="text-right">{{ totalCredit.toFixed(2) }}</th>
				    				<th></th>
				    			</tr>
				    		</tfoot>
				    	</table>
				    	<p class="text-muted px-3 pb-2" v-if="totalDebit.toFixed(2) !== totalCredit.toFixed(2)">
				    		<i class="fa fa-exclamation-triangle text-warning"></i> Debit and credit totals do not match — the generated journal will not balance.
				    	</p>
				    </div>
			    </div>

			    <div class="card card-default" v-if="runNowUrl">
			        <div class="card-header">
				        <h3 class="card-title"><b>Automation</b></h3>
				        <div class="card-tools">
				        	<button v-if="item.status === 'Active'" type="button" class="btn btn-sm btn-warning" @click="pauseTemplate"><i class="fa fa-pause"></i> Pause</button>
				        	<button v-if="item.status === 'Paused'" type="button" class="btn btn-sm btn-success" @click="resumeTemplate"><i class="fa fa-play"></i> Resume</button>
				        	<button type="button" class="btn btn-sm btn-primary" @click="runNow"><i class="fa fa-bolt"></i> Run Now</button>
				        </div>
			        </div>
				    <div class="card-body table-responsive p-0">
				    	<table class="table table-sm table-bordered">
				    		<thead>
				    			<tr>
				    				<th>Run Date</th>
				    				<th>General Journal Number</th>
				    			</tr>
				    		</thead>
				    		<tbody>
				    			<tr v-for="run in runs" :key="run.id">
				    				<td>{{ run.run_date }}</td>
				    				<td>{{ run.general_journal_number }}</td>
				    			</tr>
				    			<tr v-if="!runs.length">
				    				<td colspan="2" class="text-center text-muted">No journals generated yet.</td>
				    			</tr>
				    		</tbody>
				    	</table>
				    </div>
			    </div>

				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>Audit Trail</b></h3>
			        </div>
				    <div class="card-body">
				    	<div class="row">
					    		<div class="form-group col-sm-6">
	    		    			<label>Created By</label>
									<input readonly v-model="created_by" type="text" class="form-control">
							</div>
					    		<div class="form-group col-sm-6">
	    		    			<label>Created On</label>
								<input readonly v-model="item.formatted_created_at" type="text" class="form-control">
							</div>

					    		<div class="form-group col-sm-6">
	    		    			<label>Updated By</label>
								<input readonly v-model="updated_by" type="text" class="form-control">
							</div>
					    		<div class="form-group col-sm-6">
	    		    			<label>Updated on</label>
	    		                <input readonly v-model="item.formatted_updated_at" type="text" class="form-control">
	    		            </div>
				    	</div>
				    </div>
			    </div>

				<template v-slot:footer>
					<action-button type="submit" :disabled="loading" class="btn btn-sm btn-primary">Save Changes</action-button>
				</template>
			</card>

		</form-request>
	</div>
</template>

<script>
	import CrudMixin from 'Mixins/crud.js';

	import Card from 'Components/containers/Card.vue';
	import TextEditor from 'Components/inputs/TextEditor.vue';
	import FormRequest from 'Components/forms/FormRequest.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';

	import flatpickr from 'flatpickr';
	import 'flatpickr/dist/flatpickr.css';
	import "vue-select/dist/vue-select.css";
	import Vselect from "vue-select";
	import axios from 'axios';

	export default {
		props: {
			templateId: String,
			submitUrl: String,
			pauseUrl: String,
			resumeUrl: String,
			runNowUrl: String,
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.mainaccounts = data.mainaccounts ? data.mainaccounts : this.mainaccounts;
				this.clients = data.clients ? data.clients : this.clients;

				if (data.item && data.item.template_lines) {
					this.template_lines = data.item.template_lines.map(line => ({ ...line }));
				}

				if (data.item && data.item.runs) {
					this.runs = data.item.runs;
				}
			},

			addLine() {
				this.template_lines.push({
					id: null,
					main_account: null,
					main_account_code: null,
					main_account_name: null,
					description: null,
					debit_amount: 0,
					credit_amount: 0,
				});
			},

			removeLine(index) {
				this.template_lines.splice(index, 1);
			},

			onMainAccountChange(line) {
				let account = this.mainaccounts.find(a => a.id === line.main_account);
				line.main_account_code = account ? account.main_account_code : null;
				line.main_account_name = account ? account.main_account_name : null;
			},

			refreshItem() {
				if (!this.fetchUrl) {
					return;
				}
				axios.post(this.fetchUrl).then(response => {
					this.fetchSuccess(response.data);
				});
			},

			pauseTemplate() {
				axios.post(this.pauseUrl).then(response => {
					this.$notify({ type: 'success', text: response.data.message });
					this.refreshItem();
				});
			},

			resumeTemplate() {
				axios.post(this.resumeUrl).then(response => {
					this.$notify({ type: 'success', text: response.data.message });
					this.refreshItem();
				});
			},

			runNow() {
				axios.post(this.runNowUrl).then(response => {
					this.$notify({ type: 'success', text: response.data.message });
					this.refreshItem();
				}).catch(error => {
					this.$notify({ type: 'error', text: error.response?.data?.message?.[0] || error.response?.data?.message || 'Something went wrong.' });
				});
			},
		},

		data() {
			var item = {};
			if (this.templateId) {
				item['template_id'] = this.templateId;
			}
			return {
				item: item,
				mainaccounts: [],
				clients: [],
				template_lines: [],
				runs: [],
				created_by: null,
				updated_by: null,
			}
		},

		components: {
			Card,
			'text-editor': TextEditor,
			'form-request': FormRequest,
			'action-button': ActionButton,
			'v-select': Vselect
		},

		mounted() {
			flatpickr(this.$refs.start_date);
			flatpickr(this.$refs.end_date, { allowInput: true });
		},

		mixins: [ CrudMixin ],

		computed: {
			totalDebit() {
				return this.template_lines.reduce((total, line) => total + (parseFloat(line.debit_amount) || 0), 0);
			},

			totalCredit() {
				return this.template_lines.reduce((total, line) => total + (parseFloat(line.credit_amount) || 0), 0);
			},

			serializedLines() {
				return JSON.stringify(this.template_lines);
			},
		},

		watch: {
			'item.created_by'(val) {
				this.created_by = val.fullname;
			},

			'item.updated_by'(val) {
				this.updated_by = val.fullname;
			},
		},
	}
</script>
<style>

</style>
