<template>
	<div>
		<data-table :items="items" :headers="headers">
			<template v-slot:body="{ items }">
			   <tr v-for="item in items">
			       <td>{{ item.reason_code }}</td>
			       <td>{{ item.default_comment }}</td>
			       <td>
			       		<input type="checkbox" name="" disabled :checked="item.is_ledger">
			       </td>
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
						reason_code: 'ADDR',
						default_comment: 'Check returned - unknown address',
						is_ledger: true,
						showUrl: 'ledger-reasons/show/1'
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
				    { text: 'Reason code', value: 'reason_code' },
				    { text: 'Default Comment', value: 'default_comment' },
				    { text: 'Ledger', value: 'is_ledger' },
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