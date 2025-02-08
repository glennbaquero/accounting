<template>
	<div>
		<div class="row mb-2">
			<div class="col-md-6">
				<label>Chart of accounts</label>
				<select class="form-control">
					<option value="Shared">Shared</option>
				</select>
			</div>
		</div>
		<data-table :items="items" :headers="headers">
			<template v-slot:body="{ items }">
			   <tr v-for="item in items">
			       <td>{{ item.account_structure }}</td>
			       <td>{{ item.name }}</td>
			   </tr>
			</template>         
		</data-table>

		<div class="row">
			<div class="col-md-6">
				<label>Fiscal calendar</label>
				<select class="form-control">
					<option value="Fiscal">Fiscal</option>
				</select>
			</div>
		</div>
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
						account_structure: 'Manufacturing B/S',
						name: 'Manufacturing Balance Sheet',
						showUrl: 'setups/show/1',
					},
					{
						account_structure: 'Manufacturing P&L',
						name: 'Manufacturing Profit and Loss',
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
				    { text: 'Account structure', value: 'account_structure' },
				    { text: 'Name', value: 'name' },
				];

				return array;
			}
		},

		mounted() {
			// this.$notification.show('Success', 'Yeheyyy', 'success');
		}
	}
</script>