<template>
	<div>
        <card>
    		<filter-box @refresh="fetch">
                <template v-slot:left>
                    <date-range
                    :options="filterColumns"
                    class="mr-2"
                    @change="filter($event)"
                    ></date-range>
                </template>
                <template v-slot:right>
                    <search-form
                    @search="filter($event, 'search')">
                    </search-form>
                </template>
            </filter-box>

            <div class="row mb-2 mt-4">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Debit</label>
                        <input readonly :value="totalDebitAmount" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Credit</label>
                        <input readonly :value="totalCreditAmount" class="form-control">
                    </div>
                </div>
            </div>
            <!-- DATATABLE -->
            <data-table
            ref="data-table"
            :headers="headers"
            :filters="filters"
            :fetch-url="fetchUrl"
            :no-action="true"
            :disabled="disabled"
            order-by="id"
            order-desc
            @fetch="getData"
            @load="load"
            >
                <template v-slot:body="{ items }">
                    <tr :key="item.id" v-for="(item, key) in items">
                        <!-- <td>{{ item.id }}</td> -->
                        <td>{{ item.ledger_transaction_date }}</td>
                        <td>{{ item.main_account_name }}</td>
                        <td>{{ item.debit_amount_format }}</td>
                        <td>{{ item.credit_amount_format }}</td>
                        <td>{{ getBalance(key, item, items) }}</td>
                    </tr>
                </template>

            </data-table>

            <loader 
            :loading="loading">
            </loader>

        </card>
	</div>
</template>
<script>
	import ListMixin from 'Mixins/list.js';
    import DateRange from 'Components/datepickers/DateRange.vue';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import SearchForm from 'Components/forms/SearchForm.vue';
    import ViewButton from 'Components/buttons/ViewButton.vue';
    import Card from 'Components/containers/Card.vue';

	export default {
		mixins: [ ListMixin ],

		data() {
			return {
				items: [],
			}
		},

		components: {
	        'search-form': SearchForm,
	        'action-button': ActionButton,
            'view-button': ViewButton,
            'date-range' : DateRange,
            Card,
		},

		computed: {
			headers() {
				let array = [
				    // { text: '#', value: null },
				    { text: 'Date', value: 'ledger_transaction_date' },
				    { text: 'Main Account', value: 'main_account' },
                    { text: 'Debit Amount', value: 'posted_on' },
                    { text: 'Credit Amount', value: 'posted_on' },
                    { text: 'Balance', value: 'balance' },

                ];

				return array;
			},

            totalCreditAmount() {
                let total = _.sumBy(this.items, (item) => {
                    return parseFloat(item.credit_amount);
                })  
                
                return accounting.formatNumber(total);
            },

            totalDebitAmount() {
                let total = _.sumBy(this.items, (item) => {
                    return parseFloat(item.debit_amount);
                })  
                           
                return accounting.formatNumber(total);
            },

            filterColumns() {
                let array = [
                    { text: 'Transaction Date', value: 'transaction_date' },
                ];

                return array;
            },
		},

        mounted() {
            
        },

        methods: {
            getData(data) {
                this.items = data.items;
            },

            getBalance(key, item, items) {
                let computed = items.slice(0, key + 1)
                let debit = _.sumBy(computed, (item) => {
                    return parseFloat(item.debit_amount);
                })
                let credit = _.sumBy(computed, (item) => {
                    return parseFloat(item.credit_amount);
                })

                return accounting.formatNumber(credit - debit);
            }
        }
	}
</script>