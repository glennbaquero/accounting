<template>
	<div>
		<filter-box @refresh="fetch">
            <template v-slot:left>

                <date-range
                :options="filterColumns"
                class="mr-2"
                @change="filter($event)"
                ></date-range>

                <v-select 
                :reduce="item => item.id" 
                class="mr-4 select-size"  
                v-model="client" 
                @input="filter($event, 'client_id')" 
                label="name"
                placeholder="Select Client" 
                :options="clients"
                 ></v-select>
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
                    <td>{{ item.client }}</td>
                    <td>{{ item.client_bank_account_number }}</td>
                    <td>{{ item.client_bank_account_holder }}</td>
                    <td>{{ item.client_bank_name }}</td>
                    <td>{{ item.customer_company }}</td>
                    <td>{{ item.customer_bank_account_number }}</td>
                    <td>{{ item.customer_bank_account_holder }}</td>
                    <td>{{ item.customer_bank_name }}</td>
                    <td>{{ item.vendor_bank_account_number }}</td>
                    <td>{{ item.vendor_bank_account_holder }}</td>
                    <td>{{ item.vendor_bank_name }}</td>
                    <td>{{ item.vendor_company }}</td>
                    <td>{{ item.vendor_contact }}</td>
                    <td>{{ item.check_number }}</td>
                    <td>{{ item.issue_date }}</td>
                    <td>{{ item.clearing_date }}</td>
                    <td>{{ item.reconciled_date }}</td>
                    <td>{{ item.check_amount }}</td>
                    <td>{{ item.bank_posting_profile }}</td>
                    <td>{{ item.method_of_payment_customer }}</td>
                    <td>{{ item.voucher_no }}</td>
                    <td>{{ item.postdated_check_status }}</td>
                    <td>{{ item.approved_date }}</td>
                    <td>{{ item.posting_date }}</td>
                    <td>{{ item.canceled }}</td>
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
                        :message="`Are you sure you want to archive ${item.deposit_slip_id}?`"
                        :alt-message="`Are you sure you want to restore ${item.deposit_slip_id}?`"
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
                    { text: 'Client', value: 'client_id' },
                    { text: 'Client Bank Account Number', value: 'client_bank_account_number' },
                    { text: 'Client Bank Account Holder', value: null },
                    { text: 'Client Bank Name', value: null },
                    { text: 'Customer Company', value: null },
                    { text: 'Customer Bank Account Number', value: 'customer_bank_account_number' },
                    { text: 'Customer Bank Account Holder', value: null },
                    { text: 'Customer Bank Name', value: null },
                    { text: 'Vendor Bank Account Number', value: 'vendor_bank_account_number' },
                    { text: 'Vendor Bank Account Holder', value: null },
                    { text: 'Vendor Bank Name', value: null },
                    { text: 'Vendor Company', value: 'vendor_company' },
                    { text: 'Vendor Contact', value: 'vendor_contact' },
                    { text: 'Check Number', value: 'check_number' },
                    { text: 'Issue Date', value: 'issue_date' },
                    { text: 'Clearing Date', value: 'clearing_date' },
                    { text: 'Reconciled Date', value: 'reconciled_date' },
                    { text: 'Check Amount', value: 'check_amount' },
                    { text: 'Bank Posting Profile', value: 'bank_posting_profile' },
                    { text: 'Method Of Payment Customer', value: 'method_of_payment_customer' },
                    { text: 'Voucher Number', value: 'voucher_no' },
                    { text: 'Posted Check Status', value: 'postdated_check_status' },
                    { text: 'Approved Date', value: 'approved_date' },
                    { text: 'Posting Date', value: 'posting_date' },
                    { text: 'Is Canceled', value: 'canceled' },

                    // <td>{{ item.client }}</td>
                    // <td>{{ item.client_bank_account_number }}</td>
                    // <td>{{ item.client_bank_account_holder }}</td>
                    // <td>{{ item.client_bank_name }}</td>
                    // <td>{{ item.customer_company }}</td>
                    // <td>{{ item.customer_bank_account_number }}</td>
                    // <td>{{ item.customer_bank_account_holder }}</td>
                    // <td>{{ item.customer_bank_name }}</td>
                    // <td>{{ item.check_number }}</td>
                    // <td>{{ item.issue_date }}</td>
                    // <td>{{ item.clearing_date }}</td>
                    // <td>{{ item.reconciled_date }}</td>
                    // <td>{{ item.check_amount }}</td>
                    // <td>{{ item.bank_posting_profile }}</td>
                    // <td>{{ item.method_of_payment_customer }}</td>
                    // <td>{{ item.voucher_no }}</td>
                    // <td>{{ item.postdated_check_status }}</td>
                    // <td>{{ item.approved_date }}</td>
                    // <td>{{ item.posting_date }}</td>
                    // <td>{{ item.canceled }}</td>
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