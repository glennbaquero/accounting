<template>
	<div>
		<data-table :data-table-id="dataTableId" ref="data-table" :items="items" :headers="headers">
			<template v-slot:body="{ items }">
			   <tr v-for="item in items">
	               <td colspan="3" class="text-center">No Results Found</td>			   	

			   </tr>
			</template>         
		</data-table>
	</div>
</template>
<script>
	import DataTable from 'Components/tables/DataTable.vue';

	export default {
		props: {
			dataItems: Array,
			dataTableId: String
		},

		data() {
			return {
				items: [
					{

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
				    { text: 'Period', value: 'period' },
				    { text: 'Action', value: '' },
				];

				return array;
			}
		}
	}
</script>