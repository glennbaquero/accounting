<template>
	<div>
		<data-table :items="items" :headers="headers">
			<template v-slot:body="{ items }">
			   <tr v-for="item in items">
			       <td>{{ item.id }}</td>
			       <td>{{ item.name }}</td>
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
						name: 'Net 30',
						created_at: 'November 16, 2020',
						showUrl: 'terms/show/1'
					},
					{
						id: 2,
						name: 'Net 15',
						created_at: 'November 16, 2020',
						showUrl: 'terms/show/2'
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
				    { text: 'Name', value: 'name' },
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