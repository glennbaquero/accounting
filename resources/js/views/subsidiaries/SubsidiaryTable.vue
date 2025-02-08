<template>
	<div>
		<filter-box @refresh="fetch">
		    <template v-slot:left>
		        <date-range
		        :options="filterColumns"
		        class="mr-2 mt-4"
		        @change="filter($event)"
		        ></date-range>
		    </template>
		</filter-box>

		<div class="row">
    		<div class="col-sm-12">
			    <data-table 
			    	ref="data-table"
			    	:headers="headers" 
			    	:items="items"
			    	:striped="false"
			    	showSelect
			    	@selectAll="selectAll(...arguments)"
			    	:filters="filters"
			    	:fetch-url="fetchUrl"
			    	order-by="id"
			    	order-asc
			    	@load="load"
			    	noAction
			    >
			    	<template v-slot:body="{ items }">
			    		<tr v-for="$item in items">
			    		    <td>
			    		    	<input type="checkbox" @change="dataSelected($item)" :checked="$item.alreadyInSelectedItem">
			    		    </td>
			    		    <td>{{ $item.entry_pair_number }}</td>
			    		    <td>{{ $item.voucher_number }}</td>
			    		    <!-- <td>{{ $item.invoice_voucher_number }}</td> -->
			    		    <td>{{ $item.voucher_date }}</td>
			    		    <td>{{ $item.transaction_date }}</td>
			    		    <td>{{ $item.main_account_name }}</td>
			    		    <td>{{ $item.account_type }}</td>
			    		    <td>{{ $item.debit_amount }}</td>
			    		    <td>{{ $item.credit_amount }}</td>
			    		    <td>{{ $item.balance }}</td>
			    		    <td>{{ $item.offset_account_name }}</td>
			    		    <td>{{ $item.offset_account_type }}</td>
			    		    <td>{{ $item.description }}</td>
			    		    <td>{{ $item.posted_on }}</td>
			    		    <td>{{ $item.ledger_line_no }}</td>
			    		    <td>{{ $item.approved_date }}</td>
			    		    <td>{{ $item.log_date }}</td>
			    		    <td>{{ $item.log_message }}</td>
			    		    <td>{{ $item.invoice_number }}</td>
			    		    <td>{{ $item.payment_id }}</td>
			    		</tr>
			    	</template>
            	</data-table>
    		</div>
		</div>

		<loader 
        :loading="loading">
        </loader>
        
	</div>
</template>

<script>

	import { bus }from 'Root/bus.js';
	import Selectize from 'vue2-selectize';
	import selectizecss from 'selectize/dist/css/selectize.css';
	import Datepicker from 'vuejs-datepicker';
	import ListMixin from 'Mixins/list.js';
	import DateRange from 'Components/datepickers/DateRange.vue';

	export default {
		mixins: [ ListMixin ],

		data() {
			return {
                items: [],
	      	}
		},

		methods: {
			dataSelected(item) {
				item.alreadyInSelectedItem = !item.alreadyInSelectedItem;
				this.items = this.$refs['data-table'].items;
				this.emitSelect();
			},

			selectAll(selected) {
	            this.$loading.show(true);
				_.map(this.$refs['data-table'].items, (line) => {
					line.alreadyInSelectedItem = selected;

					return line;
				});
				this.items = this.$refs['data-table'].items;
	            this.emitSelect();
	            this.$loading.show(false);
			},

			emitSelect() {
				this.$emit('selected', {
					balanceJournal:this.balanceJournal,
					debitJournal:this.debitJournal,
					creditJournal:this.creditJournal,
					balancePerVoucher:this.balancePerVoucher,
					debitPerVoucher:this.debitPerVoucher,
					creditPerVoucher:this.creditPerVoucher,
				});
			},
		},

		components: {
			'date-range': DateRange,
		},

		computed: {
			filterColumns() {
			    let array = [
			        { text: 'Created At', value: 'created_at' },
			        { text: 'Voucher Date', value: 'voucher_date' },
			        { text: 'Posted On', value: 'posted_on' },
			        { text: 'Approved Date', value: 'approved_date' },
			        { text: 'Log Date', value: 'log_date' },
			    ];

			    return array;
			},

			balanceJournal() {
            	var credit = 0;
            	var debit = 0;

            	credit = _.sumBy(this.items, (item) => {
            		return parseFloat(item.credit_amount);
            	});

            	debit = _.sumBy(this.items, (item) => {
            		return parseFloat(item.debit_amount);
            	});

            	return parseFloat(credit - debit);
            },
			debitJournal() {
            	return _.sumBy(this.items, (item) => {
            		return parseFloat(item.debit_amount);
            	});
            },

			creditJournal() {
            	return _.sumBy(this.items, (item) => {
            		return parseFloat(item.credit_amount);
            	});
            },

            balancePerVoucher() {
            	var credit = 0;
            	var debit = 0;

            	credit = _.sumBy(this.items, (item) => {
            		if(item.alreadyInSelectedItem) {
	            		return parseFloat(item.credit_amount);
            		} else {
            			return 0;
            		}
            	});

            	debit = _.sumBy(this.items, (item) => {
            		if(item.alreadyInSelectedItem) {
	            		return parseFloat(item.debit_amount);
            		} else {
            			return 0;
            		}
            	});

            	return parseFloat(credit - debit);
            },
			debitPerVoucher() {

            	var lines = _.filter(this.items, ['alreadyInSelectedItem', true]);
            	var total = 0;

            	if(!_.isEmpty(lines)) {
    		    	total = _.sumBy(lines, (item) => {
    		    		if(lines[0].payment_id == item.payment_id && lines[0].due_date == item.due_date) {
    						return parseFloat(item.debit_amount);
    		    		}
    		    	})
            	} else {
	            	total = 0;
            	}


            	return total;

            },
			creditPerVoucher() {
            	var lines = _.filter(this.items, ['alreadyInSelectedItem', true]);
            	var total = 0;

            	if(!_.isEmpty(lines)) {
    		    	total = _.sumBy(lines, (item) => {
    		    		if(lines[0].invoice_number == item.invoice_number && lines[0].invoice_date == item.invoice_date) {
    						return parseFloat(item.credit_amount);
    		    		}
    		    	})
            	} else {
	            	total = 0;
            	}


            	return total;
            },

			headers() {
                let array = [
                    // { text: '', value: '' },
                    { text: 'Entry Number', value: 'entry_pair_number' },
                    { text: 'Voucher', value: 'invoice_voucher_number' },
                    { text: 'Voucher date', value: 'voucher_date' },
                    { text: 'Transaction date', value: 'transaction_date' },
                    { text: 'Account', value: 'main_account' },
                    { text: 'Account type', value: 'account_type' },
                    { text: 'Debit', value: 'debit_amount' },
                    { text: 'Credit', value: 'credit_amount' },
                    { text: 'Balance', value: 'balance' },
                    { text: 'Offset account', value: 'offset_account' },
                    { text: 'Offset account type', value: 'offset_account_type' },
                    { text: 'Description', value: 'description' },

                    { text: 'Posted on', value: 'posted_on' },
                    { text: 'Ledger line no', value: 'ledger_line_no' },
                    { text: 'Approved date', value: 'approved_date' },

                    { text: 'Log date', value: 'log_date' },
                    { text: 'Log message', value: 'log_message' },
                    { text: 'Invoice', value: 'invoice_number' },
                    // { text: 'Invoice date', value: 'invoice_date' },
                    { text: 'Payment ID', value: 'payment_id' },
                    // { text: 'Payment Due', value: 'due_date' },
                ];

                return array;
            },
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