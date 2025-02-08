<template>
	<div>
		<filter-box @refresh="fetch">
            <template v-slot:left>
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
        @load="load">
            <template v-slot:body="{ items }">
                <tr :key="item.id" v-for="item in items">
                    <td>{{ item.customer_summary_id }}</td>
                    <td>{{ item.customer }}</td>
                    <td>{{ item.issue_date_from }}</td>
                    <td>{{ item.issue_date_to }}</td>
                    <td>{{ item.prepared_by }}</td>
                    <td>{{ item.number_sales_order }}</td>
                    <td>{{ item.number_customer_invoice }}</td>
                    <td>{{ item.number_overdue_invoice }}</td>
                    <td>{{ item.opening_balance }}</td>
                    <td>{{ item.invoiced_amount }}</td>
                    <td>{{ item.amount_paid }}</td>
                    <td>{{ item.balance_due }}</td>
                    <td>{{ item.created_at }}</td>
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
                        :message="'Are you sure you want to archive customer summary ' + item.customer_summary_id + '?'"
                        :alt-message="'Are you sure you want to restore customer summary ' + item.customer_summary_id + '?'"
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

	export default {
		mixins: [ ListMixin],

		data() {
			return {
				items: []
			}
		},
		components: {
	        'search-form': SearchForm,
	        'action-button': ActionButton,
            'view-button': ViewButton,
		},

		computed: {
			headers() {
				let array = [
				    { text: 'Customer Summary ID', value: 'customer_summary_id' },
                    { text: 'Customer', value: 'customer' },
                    { text: 'Issue Date From', value: 'issue_date_from' },
                    { text: 'Issue Date To', value: 'issue_date_to' },
                    { text: 'Prepared By', value: 'prepared_by' },
                    { text: 'Number of Sales Order', value: 'number_sales_order' },
                    { text: 'Number of Customer Invoice', value: 'number_customer_invoice' },
                    { text: 'Number of Overdue Invoice', value: 'number_overdue_invoice' },
                    { text: 'Opening Balance', value: 'opening_balance' },
                    { text: 'Invoiced Amount', value: 'invoiced_amount' },
                    { text: 'Amount Paid', value: 'amount_paid' },
                    { text: 'Balance Due', value: 'balance_due' },
				];

				array = array.concat([
				    { text: 'Created Date', value: 'created_at' },
				]);

				return array;
			}
		},
	}
</script>