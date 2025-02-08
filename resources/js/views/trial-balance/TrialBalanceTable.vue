<template>
	<div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>Financial dimension set</label>
					<select class="form-control">
						<option value="Main Account">Main Account</option>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group mt-4">

				</div>
			</div>
		</div>



		<data-table
        ref="data-table"
        :headers="headers"
        :no-action="true"
        order-by="id"
        order-desc
		:items="items">
            <template v-slot:body="{ items }">
			   <tr v-for="item in items">
			       <td>{{ item.ledger_account }}</td>
			       <td>{{ item.name }}</td>
			       <td>{{ toMoney(item.opening_balance, '') }}</td>
			       <td>{{ toMoney(item.debit, '') }}</td>
			       <td>{{ toMoney(item.credit, '') }}</td>
			       <td>{{ item.closing_transaction }}</td>
			       <!-- <td>{{ item.closing_balance }}</td> -->
			   </tr>
				<tr v-if="items.length">
 					<td><b>Total</b></td>
					<td></td>
					<td></td>
					<td>7,500.00</td>
					<td>7,500.00</td>
					<td></td>
			   </tr>
            </template>

        </data-table>
	</div>
</template>
<script>
	// import ListMixin from 'Mixins/list.js';
	import { bus }from 'Root/bus.js';
	import NumberMixin from 'Mixins/number.js';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import DataTable from 'Components/tables/DataTable.vue';

	export default {

		mixins: [ NumberMixin],

		components: {
	        'action-button': ActionButton,
			DataTable,
		},

		props: {
			dataItems: Array
		},

		data() {
			return {
				items: [
					{
						ledger_account: 1000,
						name: 'Cash',
						opening_balance: '0.00',
						debit: 5000,
						credit: '0.00',
						closing_transaction: this.renderClosingTransaction(0, 5000)
					},
					{
						ledger_account: 1100,
						name: 'Current assets',
						opening_balance: '0.00',
						debit: 2500,
						credit: '0.00',
						closing_transaction: this.renderClosingTransaction(0, 2500)
					},
					{
						ledger_account: 2000,
						name: 'Equity',
						opening_balance: '0.00',
						debit: '0.00',
						credit: 7500,
						closing_transaction: this.renderClosingTransaction(7500, 0)
					},
				]
			}
		},

		computed: {
			headers() {
				let array = [
				    { text: 'Ledger account', value: '' },
				    { text: 'Name', value: '' },
				    { text: 'Opening balance', value: '' },
				    { text: 'Debit', value: '' },
				    { text: 'Credit', value: '' },
				    { text: 'Closing transactions', value: '' },
				    // { text: 'Closing balance', value: '' },
				];

				return array;
			}
		},

		methods: {
			renderClosingTransaction(credit, debit) {
				var total = 0;

				total = credit - debit; 

				return this.toMoney(total, '');
			}
		}
	}
</script>