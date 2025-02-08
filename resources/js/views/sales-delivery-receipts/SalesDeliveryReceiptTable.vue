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
                    <td> {{ item.sales_delivery_receipt_number }} </td>
                    <td> {{ item.customer_account }} </td>
                    <td> {{ item.invoice_account }} </td>
                    <!-- <td> {{ item.payment_id }} </td> -->
                    <td> {{ item.customer_name }} </td>
                    <td> {{ item.invoice_date }} </td>
                    <td> {{ item.invoice_status }} </td>
                    <td> <input type="checkbox" :checked="item.invoice_onhold_checkbox" disabled> </td>
                    <td> {{ item.posting_date }} </td>
                    <td> {{ item.approved_date }} </td>
                    <td> {{ item.payment_due_date }} </td>
                    <td> {{ item.invoice_payment_received_date }} </td>
                    <td> {{ item.method_of_payment }} </td>
                    <td> {{ item.terms_of_payment }} </td>
                    <!-- <td> {{ item.bank_account }} </td> -->
                    <td> {{ item.total_amount }} </td>
                    <td> {{ item.department }} </td>
                    <td>
                        <view-button :href="item.showUrl"></view-button>
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
                client: null
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
                    { text: 'SD number', value: 'sales_delivery_receipt_number' },
                    { text: 'Customer account', value: 'customer_account' },
                    { text: 'Invoice account', value: 'invoice_account' },
                    // { text: 'Payment Id', value: 'payment_id' },
                    { text: 'Customer name', value: 'customer_name' },
                    { text: 'Invoice date', value: 'invoice_date' },
                    { text: 'Invoice Status', value: 'invoice_status' },
                    { text: 'Invoice On hold', value: 'invoice_onhold_checkbox' },
                    { text: 'Posting date', value: 'posting_date' },
                    { text: 'Approved date', value: 'approved_date' },
                    { text: 'Payment Due date', value: 'payment_due_date' },
                    { text: 'Payment Received date', value: 'invoice_payment_received_date' },
                    { text: 'Method of payment', value: 'method_of_payment' },
                    { text: 'Terms of payment', value: 'terms_of_payment' },
                    // { text: 'Bank account', value: 'bank_account' },
                    { text: 'Total Amount', value: 'total_amount' },
                    { text: 'Department', value: 'department' },
				];

				return array;
			},

            filterColumns() {
                let array = [
                    { text: 'Due Date', value: 'payment_due_date' },
                    { text: 'Invoice Date', value: 'invoice_date' },
                    { text: 'Created At', value: 'created_at' },
                    { text: 'Delivery Date', value: 'delivery_date' },
                ];

                return array;
            }
		}
	}
</script>