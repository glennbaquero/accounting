<template>
	<div>
		<data-table :items="items" :headers="headers">
			<template v-slot:body="{ items }">
			   <tr v-for="item in items">
			       <td>{{ item.id }}</td>
			       <td>{{ item.year }}</td>
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
						year: '2020',
						created_at: 'November 20, 2020',
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
				    { text: 'Year', value: 'year' },
				];


				array = array.concat([
				    { text: 'Created Date', value: 'created_at' },
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