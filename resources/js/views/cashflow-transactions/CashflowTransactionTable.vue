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
        no-action
        :disabled="disabled"
        order-by="id"
        order-desc
        @load="load"
        >
            <template v-slot:body="{ items }">
                <tr v-for="item in items">
                    <td>{{ item.type }}</td>
                    <td>{{ item.vendor_payment_journal_voucher }}</td>
                    <td>{{ item.vendor_payment_journal_number }}</td>
                    <td>{{ item.customer_payment_journal_voucher_number }}</td>
                    <td>{{ item.customer_payment_journal_number }}</td>
                    <td>{{ item.vendor_account }}</td>
                    <td>{{ item.vendor_name }}</td>
                    <td>{{ item.invoice_number }}</td>
                    <td>{{ item.invoice_date }}</td>
                    <td>{{ item.customer_account }}</td>
                    <td>{{ item.customer_name }}</td>
                    <td>{{ item.vendor_invoice_number }}</td>
                    <td>{{ item.customer_invoice_number }}</td>
                    <td>{{ item.invoice_date }}</td>
                    <td>{{ item.payment_due_date }}</td>
                    <td>{{ item.method_of_payment }}</td>
                    <td>{{ item.vendor_payment_id }}</td>
                    <td>{{ item.customer_payment_id }}</td>
                    <td>{{ item.payment_status }}</td>
                    <td>{{ item.deposit_slip_number }}</td>
                    <td>{{ item.payment_specification }}</td>
                    <td>{{ item.payment_reference }}</td>
                    <td>{{ item.bank_transaction_type }}</td>
                    <td>{{ item.bank_account }}</td>
                    <td>{{ item.postdated_check_status }}</td>
                    <td>{{ item.check_number }}</td>
                    <td>{{ item.check_number_issued }}</td>
                    <td>{{ item.maturity_date }}</td>
                    <td>{{ item.received_date }}</td>
                    <td>{{ item.cashier }}</td>
                    <td>{{ item.salesperson }}</td>
                    <td>{{ item.issuing_bank_branch }}</td>
                    <td>{{ item.issuing_bank_name }}</td>
                    <td>{{ item.stop_payment }}</td>
                    <td>{{ item.replacement_check }}</td>
                    <td>{{ item.original_check }}</td>
                    <td>{{ item.check_amount }}</td>
                    <td>{{ item.recipient_name }}</td>
                    <td><input type="checkbox" :checked="item.reconciled_checkbox" disabled></td>
                    <td>{{ item.reconciled_date }}</td>
                    <td>{{ item.reconciled_by }}</td>
                    <td><input type="checkbox" :checked="item.adjustment_checkbox" disabled></td>
                    <td>{{ item.adjustment_date }}</td>
                    <td>{{ item.adjusted_by }}</td>
                    <td><input type="checkbox" :checked="item.matched_checkbox" disabled></td>
                    <!-- <td>
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
                    </td> -->
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
            clients: {
                default : () => [],
                type : Array
            },

            createUrl: String,
        },

        watch : {
            'client'(value) {
                this.filter(value, 'client');
            }
        },

		data() {
			return {
                items: [],
                client: null,
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
                    { text: 'Type', value: 'type' },
                    { text: 'Vendor Payment Journal Voucher Number', value: 'vendor_payment_journal_voucher' },
                    { text: 'Vendor Payment Journal Number', value: 'vendor_payment_journal_number' },
                    { text: 'Customer Payment Journal Voucher Number', value: 'customer_payment_journal_voucher_number' },
                    { text: 'Customer Payment Journal Number', value: 'customer_payment_journal_number' },
                    { text: 'Vendor account', value: 'vendor_account' },
                    { text: 'Vendor name', value: 'vendor_name' },
                    { text: 'Invoice number', value: 'invoice_number' },
                    { text: 'Invoice date', value: 'invoice_date' },
                    { text: 'Customer account', value: 'customer_account' },
                    { text: 'Customer  name', value: 'customer_name' },
                    { text: 'Vendor Invoice number', value: 'vendor_invoice_number' },
                    { text: 'Customer Invoice number', value: 'customer_invoice_number' },
                    { text: 'Invoice Date', value: 'invoice_date' },
                    { text: 'Payment Due date', value: 'payment_due_date' },
                    { text: 'Method of Payment', value: 'method_of_payment' },
                    { text: 'Vendor Payment ID', value: 'vendor_payment_id' },
                    { text: 'Customer Payment ID', value: 'customer_payment_id' },
                    { text: 'Payment Status', value: 'payment_status' },
                    { text: 'Deposit Slip Number', value: 'deposit_slip_number' },
                    { text: 'Payment Specification', value: 'payment_specification' },
                    { text: 'Payment Reference', value: 'payment_reference' },
                    { text: 'Bank Transaction Type', value: 'bank_transaction_type' },
                    { text: 'Bank Account', value: 'bank_account' },
                    { text: 'Postdated Check Status', value: 'postdated_check_status' },
                    { text: 'Check Number', value: 'check_number' },
                    { text: 'Check Number Issued', value: 'check_number_issued' },
                    { text: 'Maturity Date', value: 'maturity_date' },
                    { text: 'Received Date', value: 'received_date' },
                    { text: 'Cashier', value: 'cashier' },
                    { text: 'Salesperson', value: 'salesperson' },
                    { text: 'Issuing Bank Branch', value: 'issuing_bank_branch' },
                    { text: 'Issuing Bank Name', value: 'issuing_bank_name' },
                    { text: 'Stop Payment', value: 'stop_payment' },
                    { text: 'Replacement Check', value: 'replacement_check' },
                    { text: 'Original Check', value: 'original_check' },
                    { text: 'Check Amount', value: 'check_amount' },
                    { text: 'Recipient Name', value: 'recipient_name' },
                    { text: 'Reconciled', value: 'reconciled_checkbox' },
                    { text: 'Reconciled Date', value: 'reconciled_date' },
                    { text: 'Reconciled By', value: 'reconciled_by' },
                    { text: 'Adjustment', value: 'adjustment_checkbox' },
                    { text: 'Adjustment Date', value: 'adjustment_date' },
                    { text: 'Adjusted By', value: 'adjusted_by' },
                    { text: 'Matched', value: 'matched_checkbox' },
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


