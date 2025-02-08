<template>
	<div>
		<data-table :items="items" :headers="headers">
			<template v-slot:body="{ items }">
			   <tr v-for="item in items">
			       <td>{{ item.id }}</td>
			       <td>{{ item.account_code }}</td>
			       <td>{{ item.group_number }}</td>
			       <td>{{ item.summary_account }}</td>
			       <td>{{ item.settle_account }}</td>
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
						account_code: 'GEN',
						group_number: '0001',
						summary_account: 'Lorem ipsum',
						settle_account: 'Lorem ipsum',
						showUrl: 'setups/show/1',
					},
					{
						id: 2,
						account_code: 'GEN',
						group_number: '0001',
						summary_account: 'Lorem ipsum',
						settle_account: 'Lorem ipsum',
						showUrl: 'setups/show/1',
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
				    { text: ' ', value: 'id' },
				    { text: 'Account code', value: 'account_code' },
				    { text: 'Account/Group number', value: 'group_number' },
				    { text: 'Summary account', value: 'summary_account' },
				    { text: 'Settle account', value: 'settle_account' },
				];


				array = array.concat([
				    { text: 'Action', value: '' },
				]);

				return array;
			}
		},

		mounted() {
			// this.$notification.show('Success', 'Yeheyyy', 'success');
		}
	}
</script>