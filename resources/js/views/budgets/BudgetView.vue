<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<input type="hidden" name="budget_lines" :value="serializedLines">

			<card>
				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>Budget Information</b></h3>
			        </div>
				    <div class="card-body">
						<div class="row">
				    		<div class="form-group col-sm-6">
			    				<label>Client</label>
								<v-select v-model="item.client_id" :reduce="item => item.id" name="client_id" label="name" placeholder="Select Client" :options="clients"></v-select>
								<input hidden v-model="item.client_id" name="client_id">
			    			</div>
			    			<div class="form-group col-sm-6">
			    				<label>Ledger</label>
									<model-list-select :list="filtered_ledgers"
									class="form-control"
									label=""
									name="ledger_id"
									v-model="item.ledger_id"
									option-value="ledger_id"
									option-text="ledger_name"
									placeholder="Please select a Ledger"
									>
									</model-list-select>
									<input type="hidden" name="ledger_id" v-model="item.ledger_id">
									<input type="hidden" name="ledger_code" v-model="ledger.ledger_code">
			    			</div>

				    		<div class="form-group col-sm-6">
				    			<label>Budget Id</label>
				                <input name="budget_id" v-model="item.budget_id" class="form-control" readonly>
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<label>Budget Code</label>
				                <input name="budget_code" type="text" v-model="item.budget_code" class="form-control">
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<label>Budget Name</label>
				                <input name="budget_name" type="text" v-model="item.budget_name" class="form-control">
				    		</div>
				    		<div class="form-group col-sm-6">
								<label>Budget Year</label>
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
									</div>
				    				<input ref="budget_year" type="text" class="form-control calendar-form" name="budget_year" v-model="item.budget_year" readonly>
								</div>
							</div>

			    			<div class="form-group col-sm-6">
			    				<label>Fiscal Calendar Code</label>
									<model-list-select :list="filtered_fiscalcalendars"
									class="form-control"
									label=""
									name="fiscal_calendar_code"
									v-model="item.fiscal_calendar_code"
									option-value="fiscal_calendar_code"
									option-text="fiscal_calendar_name"
									placeholder="Please select a Fiscal Calendar Code"
									>
									</model-list-select>
									<input type="hidden" name="fiscal_calendar_code" v-model="item.fiscal_calendar_code">
			    			</div>

			    			<div class="form-group col-sm-6">
			    				<label>Status</label>
			    				<select name="budget_status" v-model="item.budget_status" class="form-control">
			    					<option value="Draft">Draft</option>
			    					<option value="Active">Active</option>
			    					<option value="Closed">Closed</option>
			    				</select>
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
				        <h3 class="card-title"><b>Budget Lines</b></h3>
				        <div class="card-tools">
				        	<button type="button" class="btn btn-sm btn-primary" @click="addLine"><i class="fa fa-plus"></i> Add Line</button>
				        </div>
			        </div>
				    <div class="card-body table-responsive p-0">
				    	<table class="table table-sm table-bordered">
				    		<thead>
				    			<tr>
				    				<th style="min-width:220px;">Main Account</th>
				    				<th style="min-width:180px;">Fiscal Period</th>
				    				<th style="min-width:160px;">Department</th>
				    				<th style="min-width:160px;">Cost Center</th>
				    				<th style="min-width:200px;">Description</th>
				    				<th style="min-width:140px;">Budgeted Amount</th>
				    				<th></th>
				    			</tr>
				    		</thead>
				    		<tbody>
				    			<tr v-for="(line, index) in budget_lines" :key="index">
				    				<td>
				    					<select class="form-control" v-model.number="line.main_account" @change="onMainAccountChange(line)">
				    						<option :value="null">Select account</option>
				    						<option v-for="account in mainaccounts" :key="account.id" :value="account.id">{{ account.main_account_code }} - {{ account.main_account_name }}</option>
				    					</select>
				    				</td>
				    				<td>
				    					<select class="form-control" v-model="line.fiscal_period_id" @change="onFiscalPeriodChange(line)">
				    						<option :value="null">Select period</option>
				    						<option v-for="period in filtered_fiscalperiods" :key="period.id" :value="period.fiscal_period_id">{{ period.fiscal_period_code }}</option>
				    					</select>
				    				</td>
				    				<td>
				    					<select class="form-control" v-model="line.department">
				    						<option :value="null">---</option>
				    						<option v-for="dept in departments" :key="dept.id" :value="dept.financial_dimension_value_code">{{ dept.dimension_name }}</option>
				    					</select>
				    				</td>
				    				<td>
				    					<select class="form-control" v-model="line.cost_center">
				    						<option :value="null">---</option>
				    						<option v-for="center in cost_centers" :key="center.id" :value="center.financial_dimension_value_code">{{ center.dimension_name }}</option>
				    					</select>
				    				</td>
				    				<td><input type="text" class="form-control" v-model="line.description"></td>
				    				<td><input type="number" step="0.01" class="form-control text-right" v-model.number="line.budgeted_amount"></td>
				    				<td class="text-center">
				    					<button type="button" class="btn btn-sm btn-danger" @click="removeLine(index)"><i class="fa fa-trash"></i></button>
				    				</td>
				    			</tr>
				    			<tr v-if="!budget_lines.length">
				    				<td colspan="7" class="text-center text-muted">No budget lines added yet.</td>
				    			</tr>
				    		</tbody>
				    		<tfoot>
				    			<tr>
				    				<th colspan="5" class="text-right">Total</th>
				    				<th class="text-right">{{ totalBudgeted.toFixed(2) }}</th>
				    				<th></th>
				    			</tr>
				    		</tfoot>
				    	</table>
				    </div>
			    </div>

			    <div class="card card-default" v-if="varianceUrl">
			        <div class="card-header">
				        <h3 class="card-title"><b>Budget vs. Actual</b></h3>
				        <div class="card-tools">
				        	<button type="button" class="btn btn-sm btn-secondary" @click="loadVariance"><i class="fa fa-sync"></i> Refresh</button>
				        </div>
			        </div>
				    <div class="card-body table-responsive p-0">
				    	<table class="table table-sm table-bordered">
				    		<thead>
				    			<tr>
				    				<th>Main Account</th>
				    				<th>Fiscal Period</th>
				    				<th class="text-right">Budgeted</th>
				    				<th class="text-right">Actual</th>
				    				<th class="text-right">Variance</th>
				    			</tr>
				    		</thead>
				    		<tbody>
				    			<tr v-for="row in variance_items" :key="row.id">
				    				<td>{{ row.main_account_code }} - {{ row.main_account_name }}</td>
				    				<td>{{ row.fiscal_period_code }}</td>
				    				<td class="text-right">{{ row.budgeted_amount.toFixed(2) }}</td>
				    				<td class="text-right">{{ row.actual_amount.toFixed(2) }}</td>
				    				<td class="text-right" :class="row.variance < 0 ? 'text-danger' : 'text-success'">{{ row.variance.toFixed(2) }}</td>
				    			</tr>
				    			<tr v-if="!variance_items.length">
				    				<td colspan="5" class="text-center text-muted">Save the budget with lines, then refresh to see actuals.</td>
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
	import { ModelListSelect } from 'vue-search-select'

	import flatpickr from 'flatpickr';
	import 'flatpickr/dist/flatpickr.css';
	import "vue-select/dist/vue-select.css";
	import Vselect from "vue-select";
	import axios from 'axios';

	export default {
		props: {
			budgetId: String,
			submitUrl: String,
			varianceUrl: String,
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.ledgers = data.ledgers ? data.ledgers : this.ledgers;
				this.fiscalcalendars = data.fiscalcalendars ? data.fiscalcalendars : this.fiscalcalendars;
				this.fiscalperiods = data.fiscalperiods ? data.fiscalperiods : this.fiscalperiods;
				this.mainaccounts = data.mainaccounts ? data.mainaccounts : this.mainaccounts;
				this.departments = data.departments ? data.departments : this.departments;
				this.cost_centers = data.cost_centers ? data.cost_centers : this.cost_centers;
				this.clients = data.clients ? data.clients : this.clients;

				if (data.item && data.item.budget_lines) {
					this.budget_lines = data.item.budget_lines.map(line => ({ ...line }));
				}
			},

			addLine() {
				this.budget_lines.push({
					id: null,
					main_account: null,
					main_account_code: null,
					main_account_name: null,
					fiscal_period_id: null,
					fiscal_period_code: null,
					department: null,
					cost_center: null,
					description: null,
					budgeted_amount: 0,
				});
			},

			removeLine(index) {
				this.budget_lines.splice(index, 1);
			},

			onMainAccountChange(line) {
				let account = this.mainaccounts.find(a => a.id === line.main_account);
				line.main_account_code = account ? account.main_account_code : null;
				line.main_account_name = account ? account.main_account_name : null;
			},

			onFiscalPeriodChange(line) {
				let period = this.fiscalperiods.find(p => p.fiscal_period_id === line.fiscal_period_id);
				line.fiscal_period_code = period ? period.fiscal_period_code : null;
			},

			loadVariance() {
				if (!this.varianceUrl) {
					return;
				}

				axios.get(this.varianceUrl).then(response => {
					this.variance_items = response.data.items;
				});
			},
		},

		data() {
			var item = {};
			if (this.budgetId) {
				item['budget_id'] = this.budgetId;
			}
			return {
				item: item,
				ledger: {},
				ledgers: [],
				fiscalcalendars: [],
				fiscalperiods: [],
				mainaccounts: [],
				departments: [],
				cost_centers: [],
				clients: [],
				budget_lines: [],
				variance_items: [],
				created_by: null,
				updated_by: null,
			}
		},

		components: {
			ModelListSelect,
			Card,
			'text-editor': TextEditor,
			'form-request': FormRequest,
			'action-button': ActionButton,
			'v-select': Vselect
		},

		mounted() {
			flatpickr(this.$refs.budget_year, { dateFormat: 'Y' });

			if (this.varianceUrl) {
				this.loadVariance();
			}
		},

		mixins: [ CrudMixin ],

		computed: {
			filtered_ledgers() {
				var client_id = this.item.client_id;
				return this.ledgers.filter((ledger) => parseInt(ledger.client_id) == client_id);
			},

			filtered_fiscalcalendars() {
				var client_id = this.item.client_id;
				return this.fiscalcalendars.filter((fiscalcalendar) => parseInt(fiscalcalendar.client_id) == client_id);
			},

			filtered_fiscalperiods() {
				var fiscal_calendar_code = this.item.fiscal_calendar_code;
				return this.fiscalperiods.filter((period) => period.fiscal_calendar_code == fiscal_calendar_code);
			},

			totalBudgeted() {
				return this.budget_lines.reduce((total, line) => total + (parseFloat(line.budgeted_amount) || 0), 0);
			},

			serializedLines() {
				return JSON.stringify(this.budget_lines);
			},
		},

		watch: {
			'item.created_by'(val) {
				this.created_by = val.fullname;
			},

			'item.updated_by'(val) {
				this.updated_by = val.fullname;
			},

			'item.ledger_id'(val) {
				this.ledger = this.ledgers.filter(ledger => ledger.ledger_id == val)[0] ? this.ledgers.filter(ledger => ledger.ledger_id == val)[0] : "";
			},
		},
	}
</script>
<style>

</style>
