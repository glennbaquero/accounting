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
                    <td>{{ item.fee_id }}</td>
                    <td>{{ item.payment_specification }}</td>
                    <td>{{ item.percentage_amount }}</td>
                    <td>{{ item.fee_amount }}</td>
                    <td>{{ item.minimum }}</td>
                    <td>{{ item.maximum }}</td>
                    <td>{{ item.from_date }}</td>
                    <td>{{ item.to_date }}</td>
                    <td>{{ item.minimum_fee }}</td>
                    <td>{{ item.tax_account }}</td>
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
                        :message="'Are you sure you want to archive Payment Setup ' + item.fee_id + '?'"
                        :alt-message="'Are you sure you want to restore Payment Setup ' + item.fee_id + '?'"
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
				    { text: 'Fee ID', value: 'fee_id' },
                    { text: 'Payment Specification', value: 'payment_specification' },
                    { text: 'Percentage Amount', value: 'percentage_amount' },
                    { text: 'Fee Amount', value: 'fee_amount' },
                    { text: 'Minimum', value: 'minimum' },
                    { text: 'Maximum', value: 'maximum' },
                    { text: 'From Date', value: 'from_date' },
                    { text: 'To Date', value: 'to_date' },
                    { text: 'Minimum Fee', value: 'minimum_fee' },
                    { text: 'Tax Account', value: 'tax_account' },
				];

				array = array.concat([
				    { text: 'Created Date', value: 'created_at' },
				]);

				return array;
			}
		},
	}
</script>