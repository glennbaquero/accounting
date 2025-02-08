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
                <tr :key="item.id" v-for="item in items">
					<td>{{ item.id }}</td>
					<td>{{ item.discount_cash }}</td>
					<!-- <td v-html="item.description"></td> -->
					<td>{{ item.net_or_current }}</td>
					<td>{{ item.discount_percent }}</td>
					<td>{{ item.customer_name }}</td>
					<td>{{ item.vendor_name }}</td>
					<td>{{ item.discount_offset_accounts }}</td>
					<td>{{ item.created_at }}</td>
                    <td>
                        <view-button :href="item.showUrl"></view-button>
                        
                        <action-button
                        v-if="!hideButtons"
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
                        :message="'Are you sure you want to archive Cash Discount ' + item.next_discount_code + '?'"
                        :alt-message="'Are you sure you want to restore Cash Discount ' + item.next_discount_code + '?'"
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
		components: {
	        'search-form': SearchForm,
	        'action-button': ActionButton,
            'view-button': ViewButton,
		},
		data() {
			return {
				items: []
			}
		},

		computed: {
			headers() {
				let array = [
				    { text: '#', value: 'id' },
				    { text: 'Cash discount', value: 'discount_cash' },
				    { text: 'Net/Current', value: 'net_or_current' },
				    { text: 'Discount percentage', value: 'discount_percent' },
				    { text: 'Main account for customer discounts', value: 'customer_name' },
				    { text: 'Main account for vendor discounts', value: 'vendor_name' },
				    { text: 'Discount offset accounts', value: 'discount_offset_accounts' },
				];


				array = array.concat([
				    { text: 'Created Date', value: 'created_at' },
				]);

				return array;
			}
		}
	}
</script>