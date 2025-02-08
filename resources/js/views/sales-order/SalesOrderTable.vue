<template>
	<div>
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
                <tr v-for="item in items">
                    <td>{{ item.sales_order_number }}</td>
                    <td>{{ item.customer_account }}</td>
                    <td>{{ item.customer_name }}</td>
                    <td>{{ item.invoice_count  }}</td>
                    <td>{{ item.sales_order_date }}</td>
                    <td>{{ item.confirmed_date }}</td>
                    <td>{{ item.delivery_date }}</td>
                    <td>{{ item.due_date }}</td>
                    <td>{{ item.accounting_date }}</td>
                    <td>{{ item.total_amount }}</td>
                    <td>{{ item.sales_order_status }}</td>
                    <td>{{ item.sales_type }}</td>
                    <td>{{ item.method_of_payment }}</td>
                    <td>{{ item.terms_of_payment }}</td>
                    <td>{{ item.department }}</td>
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
                        :message="'Are you sure you want to archive purchase order #' + item.sales_order_number + '?'"
                        :alt-message="'Are you sure you want to restore purchase order #' + item.sales_order_number + '?'"
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

		data() {
			return {
				items: [],

                client: {},
			}
		},

        watch : {
            'client'(value) {
                this.filter(value, 'client');
            }
        },

		components: {
	        'search-form': SearchForm,
	        'action-button': ActionButton,
            'view-button': ViewButton,
            'v-select' : Vselect,
            'date-range' : DateRange
		},
		
		computed: {
			headers() {
				let array = [
				    { text: 'SO number', value: 'sales_order_number' },
				    { text: 'Customer account', value: 'customer_account' },
                    { text: 'Customer name', value: 'customer_name' },
                    { text: 'Invoice count', value: 'invoice_count' },
				    { text: 'SO date', value: '' },
                    { text: 'Confirmed date', value: '' },
				    { text: 'Delivery date', value: '' },
				    { text: 'Due date', value: '' },
                    { text: 'Accounting date', value: '' },
				    { text: 'Total amount', value: 'total_amount' },
                    { text: 'SO status', value: 'sales_order_status' },
				    { text: 'Sales type', value: '' },
                    { text: 'Method of payment', value: '' },
                    { text: 'Terms of payment', value: '' },
                    { text: 'Department', value: '' },
				];

				return array;
			},
            
            filterColumns() {
                let array = [
                    { text: 'Created At', value: 'created_at' },
                    { text: 'Delivery Date', value: 'delivery_date' },
                ];
                return array;
            }
		}
	}
</script>