<template>
	<div>
		<filter-box @refresh="fetch">
            <template v-slot:left>
                <date-range
                    :options="filterColumns"
                    class="mr-2"
                    @change="filter($event)">
                </date-range>
                <v-select style="margin-left:20px" class="mr-4 select-size"  v-model="client" label="name" placeholder="Select Client" :options="clients"></v-select>
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
                <tr v-for="item in items" v-bind:key="item.id">
                    <td> {{ item.vendor_invoice_number }} </td>
                    <td> {{ item.purchase_order_number }} </td>
                    <td> {{ item.vendor_account }} </td>
                    <td> {{ item.invoice_account }} </td>
                    <!-- <td> {{ item.payment_id }} </td> -->
                    <td> {{ item.vendor_name }} </td>
                    <td> {{ item.invoice_date }} </td>
                    <td> {{ item.invoice_status }} </td>
                    <td> {{ item.total_amount | currency }} </td>
                    <td> <input type="checkbox" :checked="item.invoice_onhold_checkbox" disabled> </td>
                    <td> {{ item.posting_date }} </td>
                    <td> {{ item.approved_date }} </td>
                    <td> {{ item.payment_due_date }} </td>
                    <td> {{ item.invoice_payment_received_date }} </td>
                    <td> {{ item.method_of_payment }} </td>
                    <td> {{ item.terms_of_payment }} </td>
                    <!-- <td> {{ item.bank_account }} </td> -->
                    <td> {{ item.department }} </td>
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
                            :message="'Are you sure you want to archive Cost Center ' + item.code + '?'"
                            :alt-message="'Are you sure you want to restore Cost Center ' + item.code + '?'"
                            @load="load"
                            @success="sync">
                        </action-button>
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
	import { bus }from 'Root/bus.js';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import SearchForm from 'Components/forms/SearchForm.vue';
    import ViewButton from 'Components/buttons/ViewButton.vue';
    import Vselect from "vue-select";
    import DateRange from 'Components/datepickers/DateRange.vue';

	export default {
	    mixins: [ ListMixin ],

        props: {
            clients : {
                default : [],
                type : Array
            },

            forAgingReport: {
                default: false,
                type: Boolean
            }
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
            'v-select' : Vselect,
            'date-range' : DateRange,
		},

		computed: {
			headers() {
                let array = [
                    { text: 'VI number', value: 'vendor_invoice_number' },
                    { text: 'PO number', value: 'purchase_order_number' },
                    { text: 'Vendor account', value: 'vendor_account' },
                    { text: 'Invoice account', value: 'invoice_account' },
                    // { text: 'Payment Id', value: 'payment_id' },
                    { text: 'Vendor name', value: 'vendor_name' },
                    { text: 'Invoice date', value: 'invoice_date' },
                    { text: 'Invoice Status', value: 'invoice_status' },
                    { text: 'Total Amount', value: 'total_amount' },
                    { text: 'Invoice On hold', value: 'invoice_onhold_checkbox' },
                    { text: 'Posting date', value: 'posting_date' },
                    { text: 'Approved date', value: 'approved_date' },
                    { text: 'Payment Due date', value: 'payment_due_date' },
                    { text: 'Payment Received date', value: 'invoice_payment_received_date' },
                    { text: 'Method of payment', value: 'method_of_payment' },
                    { text: 'Terms of payment', value: 'terms_of_payment' },
                    // { text: 'Bank account', value: 'bank_account' },
                    { text: 'Department', value: 'department' },
                ];
				return array;
			},

            filterColumns() {
                let array = [
                    { text: 'Payment Due Date', value: 'payment_due_date' },
                    { text: 'Invoice Date', value: 'invoice_date' },
                    { text: 'Created At', value: 'created_at' },
                    { text: 'Delivery Date', value: 'delivery_date' },
                ];
                return array;
            }
		}
	}
</script>