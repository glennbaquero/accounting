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
        @load="load"
        >

            <template v-slot:body="{ items }">
                <tr v-for="item in items">
                    <td>{{ item.item_number }}</td>
                    <td>{{ item.product_name }}</td>
                    <td>{{ item.size }}</td>
                    <td>{{ item.ordered_quantity }}</td>
                    <td>{{ item.sales_quantity }}</td>
                    <td>{{ item.purchase_return }}</td>
                    <td>{{ item.physical_inventory }}</td>
                    <td>{{ item.received_quantity }}</td>
                    <td>{{ item.posted_quantity }}</td>
                    <td>{{ item.total_available }}</td>
                    <td>{{ item.physical_cost_amount }}</td>
                    <td>{{ item.financial_cost_amount }}</td>
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
                        alt-icon="fas   fa-trash-restore-alt"
                        confirm-dialog
                        :disabled="loading"
                        title="Archive Item"
                        alt-title="Restore Item"
                        :message="'Are you sure you want to archive Product ' + item.name + '?'"
                        :alt-message="'Are you sure you want to restore Product ' + item.name + '?'"
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
	import { bus }from 'Root/bus.js';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import SearchForm from 'Components/forms/SearchForm.vue';
    import ViewButton from 'Components/buttons/ViewButton.vue';

	export default {
		mixins: [ ListMixin ],

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
				    { text: 'Item Number', value: 'item_number' },
                    { text: 'Product Name', value: 'product_name' },
				    { text: 'Size', value: 'size' },
                    { text: 'Purchase Quantity', value: 'ordered_quantity' },
                    { text: 'Sales Quantity', value: 'sales_quantity' },
                    { text: 'Purchase Return Quantity', value: 'purchase_return' },
				    { text: 'Physical Inventory', value: 'physical_inventory' },
				    { text: 'Received Quantity', value: 'received_quantity' },
                    { text: 'Posted Quantity', value: 'posted_quantity' },
                    { text: 'Total Available', value: 'total_available' },
                    { text: 'Physical Cost Amount', value: 'physical_cost_amount' },
                    { text: 'Financial Cost Amount', value: 'financial_cost_amount' },
                    
				];


				array = array.concat([
				    { text: 'Created Date', value: 'created_at' },
				]);

				return array;
			}
		},

		mounted() {
			// this.$notification.show('Success', 'Yeheyyy', 'success');
		}
	}
</script>