<template>
	<div> 
		<filter-box @refresh="fetch">
            <template v-slot:left>
                <!-- <date-range
                class="mr-2"
                @change="filter($event)"
                ></date-range>

                <selector
                v-if="filterTypes"
                class="mt-2"
                :items="filterTypes"
                @change="filter($event, 'type')"
                placeholder="Filter by type"
                ></selector> -->
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
                    <td>{{ item.name }}</td>
                    <td>{{ item.ledger_journal_code }}</td>
                    <td>{{ item.period_from }}</td>
                    <td>{{ item.period_to }}</td>
                    <td>{{ item.total_debit | currency }}</td>
                    <td>{{ item.total_credit | currency }}</td>
                    <td>{{ item.total_journal_lines }}</td>
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
    import Card from 'Components/containers/Card.vue';
  
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
            Card,
		},

		computed: {
			headers() {
				let array = [
                    { text: 'Name', value: 'name' },
				    { text: 'Ledger Journal Code', value: 'ledger_journal_code' },
				    { text: 'Period From', value: 'period_from' },
				    { text: 'Period To', value: 'period_to' },
				    { text: 'Total Debit', value: 'total_debit' },
                    { text: 'Total Credit', value: 'total_credit' },
                    { text: 'Voucher Count', value: 'total_journal_lines' },
                ];

				return array;
			}
		}
	}
</script>