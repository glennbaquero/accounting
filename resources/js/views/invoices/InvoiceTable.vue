<template>
	<div>
		<data-table :items="items" :headers="headers">
			<template v-slot:body="{ items }">
			   <tr v-for="item in items">
			       <td>{{ item.id }}</td>
			       <td>{{ item.invoice_no }}</td>
			       <td>{{ item.customer }}</td>
			       <td>{{ item.balance }}</td>
			       <td>{{ item.total }}</td>
			       <td>{{ item.created_at }}</td>
			       <td>
			           <a :href="item.showUrl" class="btn btn-sm btn-warning">
			               <i class="fa fa-eye"></i>
			           </a>
			           <button type="button" class="btn btn-sm btn-danger">
			               <i class="fas fa-trash"></i>
			           </button>
			       </td>
			   </tr>
			</template>         
		</data-table>
	</div>
</template>
<script>
	import DataTable from 'Components/tables/DataTable.vue';

	export default {
		props: {
			dataItems: Array
		},

		data() {
			return {
				items: [
					{
						id: 1,
						invoice_no: '10001',
						customer: 'John Doe',
						balance: 'PHP 9,999.00',
						total: 'PHP 9,999.00',
						created_at: 'November 16, 2020',
						showUrl: 'invoices/show/1'
					},
					{
						id: 2,
						invoice_no: '10002',
						customer: 'Jane Doe',
						balance: 'PHP 9,999.00',
						total: 'PHP 9,999.00',
						created_at: 'November 16, 2020',
						showUrl: 'invoices/show/1'
					},
				]
			}
		},

		components: {
			DataTable,
		},

		computed: {
			headers() {
				let array = [
				    { text: '#', value: 'id' },
				    { text: 'Invoice #', value: 'invoice_no' },
				    { text: 'Customer', value: 'customer' },
				    { text: 'Balance', value: 'balance' },
				    { text: 'Total', value: 'total' },
				];


				array = array.concat([
				    { text: 'Created Date', value: 'created_at' },
				    { text: 'Action', value: '' },
				]);

				return array;
			}
		}
	}
</script>