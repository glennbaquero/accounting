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
                    <td>{{ item.name }}</td>
                    <td>{{ item.level }}</td>
                    <td>{{ item.applied_to }}</td>
                    <td>{{ item.delivery_type }}</td>
                    <td>{{ item.vendor_payment_method }}</td>
                    <td>{{ item.customer_payment_method }}</td>
                    <td>{{ item.procurement }}</td>
                    <td>{{ item.product }}</td>
                    <td>{{ item.variant }}</td>
                    <td>{{ item.service }}</td>
                    <td>{{ item.serviceTask }}</td>
                    <td>{{ item.mainAccount }}</td>
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
                        :message="'Are you sure you want to archive Service Task ' + item.service + '?'"
                        :alt-message="'Are you sure you want to restore Service Task ' + item.service + '?'"
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
				    { text: 'Name', value: 'name' },
                    { text: 'Level', value: 'level' },
                    { text: 'Applied To', value: 'applied_to' },
                    { text: 'Delivery Type', value: 'delivery_type' },
                    { text: 'Vendor Payment Method', value: 'vendor_payment_method' },
                    { text: 'Customer Payment Method', value: 'customer_payment_method' },
                    { text: 'Procurement', value: 'procurement' },
                    { text: 'Product', value: 'product' },
                    { text: 'Variant', value: 'variant' },
                    { text: 'Service', value: 'service' },
                    { text: 'Service Task', value: 'serviceTask' },
                    { text: 'Main Account', value: 'mainAccount' },
				];

				array = array.concat([
				    { text: 'Created Date', value: 'created_at' },
				]);

				return array;
			}
		},
	}
</script>