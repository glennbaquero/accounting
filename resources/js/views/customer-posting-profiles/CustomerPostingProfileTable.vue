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
                    <td>{{ item.posting_profile }}</td>
                    <td>{{ item.account_code }}</td>
                    <td>{{ item.summary_account }}</td>
                    <td>{{ item.settle_account }}</td>
					<td>{{ item.sales_tax_prepayments }}</td>
                    <td>{{ item.arrival }}</td>
                    <td>{{ item.offset_account }}</td>
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
                        :message="'Are you sure you want to archive this Posting Profile? (' + item.posting_profile + ')'"
                        :alt-message="'Are you sure you want to restore Posting Profile? ('+ item.posting_profile + ')'"
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
    import HelperMixin from 'Mixins/list.js';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import SearchForm from 'Components/forms/SearchForm.vue';
    import ViewButton from 'Components/buttons/ViewButton.vue';

	export default {
		mixins: [ ListMixin, HelperMixin ],

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
				    { text: 'Posting profile', value: 'posting_profile' },
                    { text: 'Account code', value: 'account_code' },
				    { text: 'Summary account', value: 'summary_account' },
				    { text: 'Settle account', value: 'settle_account' },
					{ text: 'Sales tax prepayments', value: 'sales_tax_prepayments' },
				    { text: 'Arrival', value: 'arrival' },
				    { text: 'Offset account', value: 'offset_account' },
				];

				return array;
			}
		}
	}
</script>