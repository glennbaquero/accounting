<template>
	<div>
        <card>
    		<filter-box @refresh="fetch">
                <template v-slot:left>
                    <v-select class="ml-2 w-25" placeholder="Main Account" :options="main_accounts" v-model="main_account" label="main_account_name" @input="filter($event,'main_account');" :reduce="item => item.id" ></v-select>
                </template>
            </filter-box>

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
            @load="load"
            >
                <template v-slot:body="{ items }">
                    <tr :key="item.id" v-for="(item, key) in items">
                        <!-- <td>{{ item.id }}</td> -->
                        <td>{{ item.ledger_journal_code }}</td>
                        <td>{{ key+1 }}</td>
                        <td>{{ item.main_account_code_number }}</td>
                        <td>{{ item.main_account_name }}</td>
                        <td>{{ item.main_account_type }}</td>
                        <td>{{ item.main_account_category_name }}</td>
                        <td>{{ item.main_account_normal_balance }}</td>
                        <td>{{ item.formatted_ledger_transaction_date }}</td>
                        <td>{{ item.adjusting_date }}</td>
                        <td>{{ item.matched_voucher_to_gl }}</td>
                        <td>{{ item.ledger_journal_line_status }}</td>
                        <td>{{ item.debit_amount_format }}</td>
                        <td>{{ item.credit_amount_format }}</td>
                        <td>{{ item.reverse_date }}</td>
                        <td>{{ item.adjusting_date }}</td>
                        <td>{{ item.posted_on }}</td>
                        <td>{{ item.created_at }}</td>
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
	import { bus }from 'Root/bus.js';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import SearchForm from 'Components/forms/SearchForm.vue';
    import ViewButton from 'Components/buttons/ViewButton.vue';
    import Card from 'Components/containers/Card.vue';
    import FilterDate from 'Components/inputs/FilterDate.vue';
    import Vselect from 'vue-select';

	export default {
		mixins: [ ListMixin ],

        props : {
            main_accounts : Array,
        },

		data() {
			return {
				items: [],
                main_account: null,
			}
		},

        watch : {
            'main_account'(val) {
               this.filter(val,'main_account');
            }
        },

		components: {
	        'search-form': SearchForm,
	        'action-button': ActionButton,
            'view-button': ViewButton,
            'v-select' : Vselect,
            Card,
            FilterDate
		},

		computed: {
			headers() {
				let array = [
				    // { text: '#', value: null },
				    { text: 'Ledger Journal Code', value: 'ledger_journal_code' },
				    { text: 'Ledger Line number', value: 'main_account' },
				    { text: 'Main Account Code', value: 'period_from' },
				    { text: 'Main Account', value: 'period_to' },
				    { text: 'Main Account Type', value: 'total_debit' },
                    { text: 'Main Account Category', value: 'total_credit' },
                    { text: 'Main account Normal Balance', value: 'total_journal_lines' },
                    { text: 'Ledger Transaction Date', value: 'reverse_date' },
                    { text: 'Adjusting Date', value: 'adjusting_date' },
                    { text: 'Matched Voucher to GL', value: 'posted_on' },
                    { text: 'Ledger Journal Line Status', value: 'posted_on' },
                    { text: 'Debit Amount', value: 'posted_on' },
                    { text: 'Credit Amount', value: 'posted_on' },
                    { text: 'Reverse Date', value: 'posted_on' },
                    { text: 'Adjusting Date', value: 'posted_on' },
                    { text: 'Posted On', value: 'posted_on' },
                ];

				array = array.concat([
				    { text: 'Created Date', value: 'created_at' },
				]);

				return array;
			},

            totalCreditAmount() {
                var total = 0;
                var items = this.items;

                if(!_.isEmpty(items)) {
                    total = _.sumBy(items, (item) => {
                            return parseFloat(item.credit_amount);
                        })  
                }

                return accounting.formatNumber(total);
            },

            totalDebitAmount() {
                var total = 0;
                var items = this.items;

                if(!_.isEmpty(items)) {
                    total = _.sumBy(items, (item) => {
                            return parseFloat(item.debit_amount);
                        })  
                }
                
                return accounting.formatNumber(total);
            },
		},

        mounted() {
            // flatpickr(this.$refs.period_from_jl);
            // flatpickr(this.$refs.period_to_jl);

            setTimeout(() => {
                this.fetchNewItems();
            }, 500)
        },

        methods: {
            fetchNewItems() {
                this.items = this.$refs['data-table'].items;
            }
        }
	}
</script>