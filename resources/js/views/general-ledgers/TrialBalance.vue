<template>
    <div class="card">
        <div class="card-header">
              <div class="row">
                <div class="col-md-6">
                    <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#unadjusted-trial-balance" data-toggle="tab">Unadjusted Trail Balance</a></li>
                            <li class="nav-item"><a class="nav-link" href="#adjusted-trial-balance" data-toggle="tab">Adjusted</a></li>
                            <li class="nav-item"><a class="nav-link" href="#post-closing-trial-balance" data-toggle="tab">Post Closing</a></li>
                     </ul>
                 </div>          
                <div class="col-md-6 text-right">
                    <action-button type="button" 
                    :disabled="loading" 
                    :action-url="approveClosingBalanceUrl"
                    title="Generate Closing Transaction"
                    :message="'Are you sure you want to generate closing transaction ?'" 
                    class="btn btn-success">Approved Closing Balance
                    </action-button>										
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane show active" id="unadjusted-trial-balance">
                    <data-table 
                        ref="data-table"
                        :headers="headers" 
                        :items="unadjusted_trial_balance"
                    >
                            <template v-slot:body="{ items }">
                            <tr :key="table_item.name + key" v-for="(table_item, key) in items">
                                <td>{{ table_item.name }}</td>
                                <td>{{ parseRowData(table_item.total_debit) }}</td>
                                <td>{{ parseRowData(table_item.total_credit) }}</td>
                                <td>{{ parseRowData(table_item.total) }}</td>
                            </tr>
                            </template>         
                    </data-table>
                </div>
                <div class="tab-pane show" id="adjusted-trial-balance">
                    <data-table 
                        ref="data-table"
                        :headers="headers" 
                        :items="adjusted_trial_balance"
                    >
                            <template v-slot:body="{ items }">
                            <tr :key="table_item.name + key" v-for="(table_item, key) in items">
                                <td>{{ table_item.name }}</td>
                                <td>{{ parseRowData(table_item.total_debit) }}</td>
                                <td>{{ parseRowData(table_item.total_credit) }}</td>
                                <td>{{ parseRowData(table_item.total) }}</td>
                            </tr>
                            </template>         
                    </data-table>
                </div>
                <div class="tab-pane show" id="post-closing-trial-balance">
                    <data-table 
                        ref="data-table"
                        :headers="headers" 
                        :items="post_closing_trial_balance"
                    >
                            <template v-slot:body="{ items }">
                            <tr :key="table_item.name + key" v-for="(table_item, key) in items">
                                <td>{{ table_item.name }}</td>
                                <td>{{ parseRowData(table_item.total_debit) }}</td>
                                <td>{{ parseRowData(table_item.total_credit) }}</td>
                                <td>{{ parseRowData(table_item.total) }}</td>
                            </tr>
                            </template>         
                    </data-table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
	import ListMixin from 'Mixins/list.js';
    import DateRange from 'Components/datepickers/DateRange.vue';
    import DataTable from 'Components/tables/DataTable.vue';
    import Card from 'Components/containers/Card.vue';
    import ActionButton from 'Components/buttons/ActionButton.vue';

	export default {
		mixins: [ ListMixin ],

        props : {
            adjusted_trial_balance :  {
                type : Array,
                default : [],
            },
            unadjusted_trial_balance :  {
                type : Array,
                default : [],
            },
            post_closing_trial_balance :  {
                type : Array,
                default : [],
            },
            approveClosingBalanceUrl : String
        },

		components: {
            'date-range' : DateRange,
            'data-table' : DataTable,
            'action-button': ActionButton,
            Card,
		},

		computed: {
			headers() {
				let array = [
				    { text: 'Main Account', value: 'name' },
                    { text: 'Debit Amount', value: 'total_debit' },
                    { text: 'Credit Amount', value: 'total_credit' },
                    { text: 'Total', value: 'total' },
                ];

				return array;
			},

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
            }
        }
	}
</script>