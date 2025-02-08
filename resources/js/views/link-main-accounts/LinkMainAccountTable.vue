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
			       <td>{{ item.client }}</td>
			       <td>{{ item.chart_of_accounts_name }}</td>
			       <td>{{ item.chart_of_accounts_code }}</td>
                    <td>{{ item.main_account_code }}</td>
                    <td>{{ item.main_account_type }}</td>
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
                        :message="'Are you sure you want to archive ' + item.coa_code + '?'"
                        :alt-message="'Are you sure you want to restore ' + item.coa_code + '?'"
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
				    { text: 'Client', value: 'client' },
				    { text: 'Chart of Acounts ID', value: 'coa_id' },
				    { text: 'Chart of Accounts Code', value: 'coa_code' },
                    { text: 'Main Accounts', value: 'main_accounts' },			
				    { text: 'Main Account Type', value: 'main_account_type' },
				];

				return array;
			}
		}
	}
</script>