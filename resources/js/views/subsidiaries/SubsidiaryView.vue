<template>
	<div>
		<div class="row">
			<div class="form-group col-md-4">
			    <label>Client</label>
			    <model-list-select 
			    :list="clients"
			    v-model="journalItem.client_id"
			    option-value="id"
			    option-text="name"
			    placeholder="Select Client"
			    class="form-control pull-right">
			    </model-list-select>
			    <input name="client_id" hidden v-model="journalItem.client_id"> 
			</div>
		</div>

		<div class="row">
			<div class="col-md-3 row">
				<div class="col-md-12">
					<label>Balance</label>
				</div>
				<div class="col-md-12">
					Journal <input type="text" name="balance_journal" :value="balanceJournal" class="form-control" disabled>
				</div>
				<div class="col-md-12">
					Per voucher <input type="text" name="" :value="balancePerVoucher" class="form-control" disabled>
				</div>
			</div>
			<div class="col-md-3 row">
				<div class="col-md-12">
					<label>Total debit</label>
				</div>
				<div class="col-md-12">
					Journal <input type="text" name="total_debit_journal" value="0.00"  :value="debitJournal" class="form-control" disabled>
				</div>
				<div class="col-md-12">
					Per voucher <input type="text" name="" value="0.00" :value="debitPerVoucher" class="form-control" disabled>
				</div>
			</div>
			<div class="col-md-3 row">
				<div class="col-md-12">
					<label>Total credit</label>
				</div>
				<div class="col-md-12">
					Journal <input type="text" name="total_credit_journal" value="0.00" :value="creditJournal" class="form-control" disabled>
				</div>
				<div class="col-md-12">
					Per voucher <input type="text" name="" value="0.00" :value="creditPerVoucher" class="form-control" disabled>
				</div>
			</div>
		</div>

		<div class="card mt-4">
		    <div class="card-header p-2">
		        <div class="row">
		        	<div class="col-md-9">
		        		<ul class="nav nav-pills">
		        		    <li class="nav-item"><a class="nav-link active" href="#invoice-approval-subsidiary" data-toggle="tab">{{ firstTabName }}</a></li>
		        		    <li class="nav-item"><a class="nav-link" href="#vendor-payment-subsidiary" data-toggle="tab">{{ secondTabName }}</a></li>
		        		    <li class="nav-item"><a class="nav-link" href="#general-subsidiary" data-toggle="tab">General Subsidiary</a></li>
		        		</ul>
		        	</div>
		        </div>
		    </div>
		    <div class="card-body">
		        <div class="tab-content">
		        	<div class="tab-pane show active" id="invoice-approval-subsidiary">
		        		<subsidiary-table
		        			@selected="handleSelected"
				            :fetch-url="invoiceApprovalUrl"
				        ></subsidiary-table>
		        	</div>
		        	<div class="tab-pane show" id="vendor-payment-subsidiary">
		        		<subsidiary-table
		        			@selected="handleSelected"
				            :fetch-url="vendorPaymentUrl"
				        ></subsidiary-table>
		        	</div>
		        	<div class="tab-pane show" id="general-subsidiary">
		        		<subsidiary-table
		        			@selected="handleSelected"
				            :fetch-url="generalJournalUrl"
				        ></subsidiary-table>
		        	</div>
		        </div>
		    </div>
		</div>
	</div>
</template>

<script>
	import { bus }from 'Root/bus.js';
	import { ModelListSelect } from 'vue-search-select'

	export default {
		props: {
			clients: {
				type: Array,
				default: () => [],
			},
			invoiceApprovalUrl: String,
			vendorPaymentUrl: String,
			generalJournalUrl: String,
			firstTabName: {
				default: 'Invoice Approval Subsidiary'
			},

			secondTabName: {
				default: 'Vendor Payment Subsidiary'
			},

			thirdTabName: {
				default: 'General Subsidiary'
			}
		},
		data() {
			return {
				journalItem: {},
				balanceJournal: 0,
				balancePerVoucher: 0,
				debitJournal: 0,
				debitPerVoucher: 0,
				creditJournal: 0,
				creditPerVoucher: 0,
			}
		},
		methods: {
			handleSelected(data) {
				this.balanceJournal = data.balanceJournal;
				this.balancePerVoucher = data.balancePerVoucher;
				this.debitJournal = data.debitJournal;
				this.debitPerVoucher = data.debitPerVoucher;
				this.creditJournal = data.creditJournal;
				this.creditPerVoucher = data.creditPerVoucher;
			}
		},
		components: {
			ModelListSelect,
		}
	}
</script>

<style type="text/css">
	tr {
		cursor: hand;
	}

	.selected-table {
		background: #C1C1C1;
	}
</style>