<template>
	<div>
		<filter-box @refresh="fetch">
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
                    <td>{{ item.budget_code }}</td>
                    <td>{{ item.budget_name }}</td>
                    <td>{{ item.ledger_code }}</td>
                    <td>{{ item.fiscal_calendar_code }}</td>
                    <td>{{ item.budget_year }}</td>
                    <td>{{ item.total_budgeted_amount }}</td>
                    <td>{{ item.budget_status }}</td>

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
                        :message="'Are you sure you want to archive Budget ' + item.budget_name + '?'"
                        :alt-message="'Are you sure you want to restore Budget ' + item.budget_name + '?'"
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
                    { text: 'Client', value: 'client' },
				    { text: 'Budget Code', value: 'budget_code' },
				    { text: 'Budget Name', value: 'budget_name' },
				    { text: 'Ledger Code', value: 'ledger_code' },
				    { text: 'Fiscal Calendar Code', value: 'fiscal_calendar_code' },
				    { text: 'Budget Year', value: 'budget_year' },
				    { text: 'Total Budgeted', value: 'total_budgeted_amount' },
				    { text: 'Status', value: 'budget_status' },
				];

				return array;
			}
		}
	}
</script>
