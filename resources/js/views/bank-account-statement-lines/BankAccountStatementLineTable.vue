<template>
	<div>
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

        <!-- DATATABLE -->
        <data-table
        ref="data-table"
        :headers="headers"
        :filters="filters"
        :fetch-url="fetchUrl"
        :no-action="noAction"
        :disabled="disabled"
        order-by="id"
        order-desc
        @load="load"
        >

            <template v-slot:body="{ items }">
                <tr v-for="item in items">
                    <td>{{ item.transaction_date }}</td>
                    <td>{{ item.payment_reference }}</td>
                    <td>{{ item.bank_transaction_code }}</td>
                    <td>{{ item.bank_reason }}</td>
                    <td>{{ item.withdrawal_debit_amount }}</td>
                    <td>{{ item.deposit_credit_amount }}</td>
                    <td>{{ item.reconciled_date }}</td>
                    <td>{{ item.adjustment_date }}</td>
                    <td><input type="checkbox" :checked="item.matched_checkbox" disabled></td>
                    <td>
                        <view-button :href="item.showUrl"></view-button>
                        <action-button
                        small 
                        color="btn-danger"
                        alt-color="btn-warning"
                        :show-alt="item.deleted_at"
                        :action-url="item.archiveUrl"
                        :alt-action-url="item.restoreUrl"
                        icon="fas fa-trash"
                        alt-icon="fas fa-trash-restore-alt"
                        confirm-dialog
                        :disabled="loading"
                        title="Archive Item"
                        alt-title="Restore Item"
                        :message="`Are you sure you want to archive ${item.bank_statement_id}?`"
                        :alt-message="`Are you sure you want to restore ${item.bank_statement_id}?`"
                        @load="load"
                        @success="sync"
                        ></action-button>
                    </td>
                </tr>
            </template>

        </data-table>

        <loader 
        :loading="loading">
        </loader>
	</div>
</template>
<script>
	import ListMixin from 'Mixins/list.js';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import SearchForm from 'Components/forms/SearchForm.vue';
    import ViewButton from 'Components/buttons/ViewButton.vue';
    import { ModelListSelect } from 'vue-search-select';
    import DateRange from 'Components/datepickers/DateRange.vue';
    import "vue-select/dist/vue-select.css";
    import Vselect from "vue-select";

	export default {
        mixins: [ ListMixin ],
        
        props : {
            createUrl: String,
        },

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
            'v-select' : Vselect,
            ModelListSelect,
		},
		
		computed: {
			headers() {
                let array = [
                    { text: 'Transaction Date', value: 'transaction_date' },
                    { text: 'Payment Reference', value: 'payment_reference' },
                    { text: 'Bank Transaction Code', value: 'bank_transaction_code' },
                    { text: 'Bank Reason', value: 'bank_reason' },
                    { text: 'Withdrawal Debit Amount', value: 'withdrawal_debit_amount' },
                    { text: 'Deposit Credit Amount', value: 'deposit_credit_amount' },
                    { text: 'Reconciled Date', value: 'reconciled_date' },
                    { text: 'Adjustment Date', value: 'adjustment_date' },
                    { text: 'Cancelled', value: 'matched_checkbox' },
                ];

                return array;
            },
            
            filterColumns() {
                let array = [
                    { text: 'Created At', value: 'created_at' },
                ];

                return array;
            },
		}
	}
</script>