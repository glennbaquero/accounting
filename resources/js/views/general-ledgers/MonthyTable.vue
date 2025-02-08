<template>
	<div>
        <!-- DATATABLE -->
            <data-table 
                ref="data-table"
                :headers="headers" 
                :items="monthly_data"
            >
                    <template v-slot:body="{ items }">
                    <tr>
                        <template>
                            <td class="text-info"><b>Opening Balance</b></td>
                            <td>---</td>
                            <td>---</td>
                            <td>{{ opening_balance | currency }}</td>
                        </template>
                    </tr>
                    <tr :key="table_item.name + key + Math.random().toString(36).substr(2, 5)" v-for="(table_item, key) in items">

						<template v-if="table_item.row_type == 'title'">
							<td colspan="4" class="text-center"><b>{{ table_item.type }}</b></td>
						</template>
						<template v-if="table_item.row_type == 'data'">
							<td>{{ table_item.name }}</td>
							<td>---</td>
							<td>{{ table_item.total | currency }}</td>
							<td>---</td>
						</template>
						<template v-if="table_item.row_type == 'total'">
							<td><b>Total {{ table_item.type }}</b></td>
							<td>---</td>
							<td>---</td>
							<td><b>{{ table_item.total | currency }}</b></td>
						</template>
                    </tr>
                    </template>         
            </data-table>
	</div>
</template>
<script>
	import ListMixin from 'Mixins/list.js';
    import DateRange from 'Components/datepickers/DateRange.vue';
    import DataTable from 'Components/tables/DataTable.vue';
    import Card from 'Components/containers/Card.vue';

	export default {
		mixins: [ ListMixin ],


		data() {
			return {
                monthly_data: [],
                opening_balance : 0.00
			}
		},

        props : {
            item :  {
                type : Object,
                default : [],
            },
            fetchUrl : String,
        },

		components: {
            'date-range' : DateRange,
            'data-table' : DataTable,
            Card,
		},

		computed: {
			headers() {
				let array = [
				    { text: 'Chart of Accounts', value: 'name' },
                    { text: 'Normal Balance', value: 'normal_balance' },
                    { text: 'Operating', value: 'total_credit' },
					{ text: 'Total', value: 'total' },
                ];

				return array;
			},

		},

        watch : {
            'item.id'(id) { 
                if(id) {
                    this.initData(id);
                }
            }
        },

        methods: {
            parseRowData(data) {
                if(data == 0) {
                    return accounting.formatNumber(data);
                }
                if(data >= 0 || data <= 0) {
                    return accounting.formatNumber(data);
                }
                return '';
            },

            initData(id) {
                if(id) {
                    axios.post(this.fetchUrl, { id : id }).then((response)=>{
                        this.monthly_data = response.data.items ? response.data.items : this.monthly_data;
                        this.opening_balance = response.data.opening_balance ? response.data.opening_balance : this.opening_balance;
                    }).catch((error)=>{
                        console.log(error);
                    });
                }
            }
        }
	}
</script>