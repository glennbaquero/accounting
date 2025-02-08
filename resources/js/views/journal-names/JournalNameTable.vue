<template>
	<div>
		<data-table :items="items" :headers="headers">
			<template v-slot:body="{ items }">
			   <tr v-for="item in items">
			       <td>{{ item.name }}</td>
			       <td v-html="item.description"></td>
			       <td>{{ item.account_type }}</td>
			       <td>{{ item.offset_account_type }}</td>
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
						name: 'PV',	
						description: 'Lorem ipsum',	
						account_type: 'Ledger',	
						offset_account_type: null,	
						created_at: 'November 16, 2020',
						showUrl: 'payment-methods/show/1',
						is_credit_card: false,
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
				    { text: 'Name', value: 'name' },
				    { text: 'Description', value: '' },
				    { text: 'Account type', value: '' },
				    { text: 'Offset Account type', value: '' },
				];


				array = array.concat([
				    { text: 'Action', value: '' },
				]);

				return array;
			}
		}
	}
</script>