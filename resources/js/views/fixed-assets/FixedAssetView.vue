<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
			<card>
				<div class="card card-default">
			        <div class="card-header">
				        <h3 class="card-title"><b>Asset Information</b></h3>
			        </div>
				    <div class="card-body">
						<div class="row">
				    		<div class="form-group col-sm-6">
			    				<label>Client</label>
								<v-select v-model="item.client_id" :reduce="item => item.id" name="client_id" label="name" placeholder="Select Client" :options="clients"></v-select>
								<input hidden v-model="item.client_id" name="client_id">
			    			</div>
				    		<div class="form-group col-sm-6">
				    			<label>Asset Id</label>
				                <input name="asset_id" v-model="item.asset_id" class="form-control" readonly>
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<label>Asset Code</label>
				                <input name="asset_code" type="text" v-model="item.asset_code" class="form-control">
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<label>Asset Name</label>
				                <input name="asset_name" type="text" v-model="item.asset_name" class="form-control">
				    		</div>

				    		<div class="form-group col-sm-6">
								<label>Acquisition Date</label>
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
									</div>
				    				<input ref="acquisition_date" type="text" class="form-control calendar-form" name="acquisition_date" v-model="item.acquisition_date" readonly>
								</div>
							</div>
				    		<div class="form-group col-sm-6">
				    			<label>Status</label>
				                <input v-model="item.asset_status" class="form-control" readonly>
				    		</div>

				    		<div class="form-group col-sm-4">
				    			<label>Acquisition Cost</label>
				                <input name="acquisition_cost" type="number" step="0.01" v-model.number="item.acquisition_cost" class="form-control">
				    		</div>
				    		<div class="form-group col-sm-4">
				    			<label>Salvage Value</label>
				                <input name="salvage_value" type="number" step="0.01" v-model.number="item.salvage_value" class="form-control">
				    		</div>
				    		<div class="form-group col-sm-4">
				    			<label>Useful Life (Months)</label>
				                <input name="useful_life_months" type="number" v-model.number="item.useful_life_months" class="form-control">
				    		</div>

				    		<div class="form-group col-sm-6">
				    			<label>Depreciation Method</label>
				    			<select name="depreciation_method" v-model="item.depreciation_method" class="form-control">
				    				<option value="Straight-Line">Straight-Line</option>
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
				        <h3 class="card-title"><b>GL Accounts</b></h3>
			        </div>
				    <div class="card-body">
						<div class="row">
				    		<div class="form-group col-sm-6">
				    			<label>Asset Account</label>
				    			<select class="form-control" v-model.number="item.main_account" @change="onAccountChange('main_account')" name="main_account">
				    				<option :value="null">Select account</option>
				    				<option v-for="account in mainaccounts" :key="account.id" :value="account.id">{{ account.main_account_code }} - {{ account.main_account_name }}</option>
				    			</select>
				    			<input type="hidden" name="main_account" v-model="item.main_account">
				    			<input type="hidden" name="main_account_code" v-model="item.main_account_code">
				    			<input type="hidden" name="main_account_name" v-model="item.main_account_name">
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<label>Accumulated Depreciation Account</label>
				    			<select class="form-control" v-model.number="item.accumulated_depreciation_account" @change="onAccountChange('accumulated_depreciation_account')" name="accumulated_depreciation_account">
				    				<option :value="null">Select account</option>
				    				<option v-for="account in mainaccounts" :key="account.id" :value="account.id">{{ account.main_account_code }} - {{ account.main_account_name }}</option>
				    			</select>
				    			<input type="hidden" name="accumulated_depreciation_account" v-model="item.accumulated_depreciation_account">
				    			<input type="hidden" name="accumulated_depreciation_account_code" v-model="item.accumulated_depreciation_account_code">
				    			<input type="hidden" name="accumulated_depreciation_account_name" v-model="item.accumulated_depreciation_account_name">
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<label>Depreciation Expense Account</label>
				    			<select class="form-control" v-model.number="item.depreciation_expense_account" @change="onAccountChange('depreciation_expense_account')" name="depreciation_expense_account">
				    				<option :value="null">Select account</option>
				    				<option v-for="account in mainaccounts" :key="account.id" :value="account.id">{{ account.main_account_code }} - {{ account.main_account_name }}</option>
				    			</select>
				    			<input type="hidden" name="depreciation_expense_account" v-model="item.depreciation_expense_account">
				    			<input type="hidden" name="depreciation_expense_account_code" v-model="item.depreciation_expense_account_code">
				    			<input type="hidden" name="depreciation_expense_account_name" v-model="item.depreciation_expense_account_name">
				    		</div>
				    		<div class="form-group col-sm-6">
				    			<label>Gain/Loss on Disposal Account</label>
				    			<select class="form-control" v-model.number="item.gain_loss_account" @change="onAccountChange('gain_loss_account')" name="gain_loss_account">
				    				<option :value="null">Select account</option>
				    				<option v-for="account in mainaccounts" :key="account.id" :value="account.id">{{ account.main_account_code }} - {{ account.main_account_name }}</option>
				    			</select>
				    			<input type="hidden" name="gain_loss_account" v-model="item.gain_loss_account">
				    			<input type="hidden" name="gain_loss_account_code" v-model="item.gain_loss_account_code">
				    			<input type="hidden" name="gain_loss_account_name" v-model="item.gain_loss_account_name">
				    		</div>
						</div>
					</div>
				</div>

				<template v-slot:footer>
					<action-button type="submit" :disabled="loading" class="btn btn-sm btn-primary">Save Changes</action-button>
				</template>
			</card>

		</form-request>

		<card v-if="generateScheduleUrl" class="mt-4">
			<div class="card card-default">
		        <div class="card-header">
			        <h3 class="card-title"><b>Depreciation Schedule</b></h3>
			        <div class="card-tools">
			        	<button type="button" class="btn btn-sm btn-secondary" @click="generateSchedule"><i class="fa fa-calculator"></i> Generate Schedule</button>
			        	<button type="button" class="btn btn-sm btn-primary" @click="postAllDue"><i class="fa fa-check-double"></i> Post All Due</button>
			        </div>
		        </div>
			    <div class="card-body table-responsive p-0">
			    	<table class="table table-sm table-bordered">
			    		<thead>
			    			<tr>
			    				<th>#</th>
			    				<th>Period Date</th>
			    				<th>Fiscal Period</th>
			    				<th class="text-right">Amount</th>
			    				<th class="text-right">Accumulated</th>
			    				<th class="text-right">Book Value</th>
			    				<th>Status</th>
			    				<th></th>
			    			</tr>
			    		</thead>
			    		<tbody>
			    			<tr v-for="line in depreciation_lines" :key="line.id">
			    				<td>{{ line.period_number }}</td>
			    				<td>{{ line.period_date }}</td>
			    				<td>{{ line.fiscal_period_code || '---' }}</td>
			    				<td class="text-right">{{ formatNumber(line.depreciation_amount) }}</td>
			    				<td class="text-right">{{ formatNumber(line.accumulated_depreciation) }}</td>
			    				<td class="text-right">{{ formatNumber(line.book_value) }}</td>
			    				<td>
			    					<span v-if="line.posted_checkbox" class="badge badge-success">Posted</span>
			    					<span v-else class="badge badge-secondary">Unposted</span>
			    				</td>
			    				<td class="text-center">
			    					<button v-if="!line.posted_checkbox" type="button" class="btn btn-sm btn-primary" @click="postLine(line)"><i class="fa fa-check"></i> Post</button>
			    				</td>
			    			</tr>
			    			<tr v-if="!depreciation_lines.length">
			    				<td colspan="8" class="text-center text-muted">No schedule generated yet.</td>
			    			</tr>
			    		</tbody>
			    	</table>
			    </div>
		    </div>
		</card>

		<card v-if="disposeUrl" class="mt-4">
			<div class="card card-default">
		        <div class="card-header">
			        <h3 class="card-title"><b>Disposal</b></h3>
		        </div>
			    <div class="card-body">
			    	<div class="row" v-if="item.asset_status !== 'Disposed'">
			    		<div class="form-group col-sm-4">
			    			<label>Disposal Date</label>
			    			<div class="input-group mb-2">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
								</div>
			    				<input ref="disposal_date" type="text" class="form-control calendar-form" v-model="disposal.disposal_date" readonly>
							</div>
			    		</div>
			    		<div class="form-group col-sm-4">
			    			<label>Disposal Proceeds</label>
			    			<input type="number" step="0.01" class="form-control" v-model.number="disposal.disposal_proceeds">
			    		</div>
			    		<div class="form-group col-sm-4">
			    			<label>Proceeds Account (Cash/Bank/AR)</label>
			    			<select class="form-control" v-model.number="disposal.proceeds_account">
			    				<option :value="null">---</option>
			    				<option v-for="account in mainaccounts" :key="account.id" :value="account.id">{{ account.main_account_code }} - {{ account.main_account_name }}</option>
			    			</select>
			    		</div>
			    		<div class="form-group col-sm-12">
			    			<button type="button" class="btn btn-danger" @click="confirmDispose"><i class="fa fa-box-open"></i> Dispose Asset</button>
			    		</div>
			    	</div>
			    	<div v-else>
			    		<p><strong>Disposed on:</strong> {{ item.disposal_date }}</p>
			    		<p><strong>Proceeds:</strong> {{ formatNumber(item.disposal_proceeds) }}</p>
			    		<p><strong>Gain/(Loss):</strong> {{ formatNumber(item.disposal_gain_loss) }}</p>
			    	</div>
			    </div>
		    </div>
		</card>
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
			assetId: String,
			itemId: [String, Number],
			submitUrl: String,
			generateScheduleUrl: String,
			postAllDueUrl: String,
			disposeUrl: String,
			postLineUrlBase: String,
		},

		methods: {
			fetchSuccess(data) {
				this.item = data.item ? data.item : this.item;
				this.mainaccounts = data.mainaccounts ? data.mainaccounts : this.mainaccounts;
				this.clients = data.clients ? data.clients : this.clients;

				if (data.item && data.item.depreciation_lines) {
					this.depreciation_lines = data.item.depreciation_lines;
				}
			},

			onAccountChange(field) {
				let account = this.mainaccounts.find(a => a.id === this.item[field]);
				this.item[field + '_code'] = account ? account.main_account_code : null;
				this.item[field + '_name'] = account ? account.main_account_name : null;
			},

			formatNumber(value) {
				return value === null || value === undefined ? '0.00' : parseFloat(value).toFixed(2);
			},

			refreshItem() {
				if (!this.fetchUrl) {
					return;
				}
				axios.post(this.fetchUrl).then(response => {
					this.fetchSuccess(response.data);
				});
			},

			generateSchedule() {
				axios.post(this.generateScheduleUrl).then(response => {
					this.$notify({ type: 'success', text: response.data.message });
					this.refreshItem();
				}).catch(error => {
					this.$notify({ type: 'error', text: error.response?.data?.message?.[0] || error.response?.data?.message || 'Something went wrong.' });
				});
			},

			postAllDue() {
				axios.post(this.postAllDueUrl).then(response => {
					this.$notify({ type: 'success', text: response.data.message });
					this.refreshItem();
				}).catch(error => {
					this.$notify({ type: 'error', text: error.response?.data?.message?.[0] || error.response?.data?.message || 'Something went wrong.' });
				});
			},

			postLine(line) {
				axios.post(this.postLineUrlBase + '/' + line.id + '/post').then(response => {
					this.$notify({ type: 'success', text: response.data.message });
					this.refreshItem();
				}).catch(error => {
					this.$notify({ type: 'error', text: error.response?.data?.message?.[0] || error.response?.data?.message || 'Something went wrong.' });
				});
			},

			confirmDispose() {
				swal.fire({
					title: 'Dispose Asset',
					text: 'This will post the disposal entries to the General Ledger and cannot be undone. Continue?',
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Yes, dispose it',
					cancelButtonText: 'Cancel',
				}).then((result) => {
					if (result.value) {
						this.disposeAsset();
					}
				});
			},

			disposeAsset() {
				axios.post(this.disposeUrl, this.disposal).then(response => {
					this.$notify({ type: 'success', text: response.data.message });
					this.refreshItem();
				}).catch(error => {
					this.$notify({ type: 'error', text: error.response?.data?.message?.[0] || error.response?.data?.message || 'Something went wrong.' });
				});
			},
		},

		data() {
			var item = {};
			if (this.assetId) {
				item['asset_id'] = this.assetId;
			}
			return {
				item: item,
				mainaccounts: [],
				clients: [],
				depreciation_lines: [],
				disposal: {
					disposal_date: null,
					disposal_proceeds: 0,
					proceeds_account: null,
				},
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
			flatpickr(this.$refs.acquisition_date);
			if (this.$refs.disposal_date) {
				flatpickr(this.$refs.disposal_date, {
					onChange: (selectedDates, dateStr) => { this.disposal.disposal_date = dateStr; }
				});
			}
		},

		mixins: [ CrudMixin ],
	}
</script>
<style>

</style>
