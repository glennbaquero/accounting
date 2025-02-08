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
        :no-action="true"
        :disabled="disabled"
        order-by="id"
        order-asc
        @load="load"
        >

            <template v-slot:body="{ items }">
                <tr v-for="item in items">
			        <td>{{ item.accrual_id }}</td>
			        <td>{{ item.accrual_posting }}</td>
			        <td>{{ item.period_code }}</td>
                    <td>{{ item.fiscal_period_type }}</td>
			        <td>{{ item.fiscal_period_start_date }}</td>
			        <td>{{ item.fiscal_period_end_date }}</td>
			        <td>{{ item.fiscal_month }}</td>
			        <td>{{ item.fiscal_quarter }}</td>
			        <td>{{ item.fiscal_period_status }}</td>
                    <td v-if="false">                 
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
                        :message="'Are you sure you want to archive Fiscal Period ' + item.fiscal_period_name + '?'"
                        :alt-message="'Are you sure you want to restore Fiscal Period ' + item.fiscal_period_name + '?'"
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
				    { text: 'Accrual Code', value: 'accrual_id' },				    
				    { text: 'Accrual Posting', value: 'accrual_posting' },				    
				    { text: 'Period Code', value: 'period_code' },
                    { text: 'Period Type', value: 'period_type' },						    
				    { text: 'Period Start date', value: 'fiscal_period_start_date' },				    
				    { text: 'Period End date', value: 'fiscal_period_end_date' },				    
				    { text: 'Month', value: 'fiscal_month' },				    
				    { text: 'Quarter', value: 'fiscal_quarter' },				    
				    { text: 'Fiscal Period status', value: 'fiscal_period_status' },				    

				];


				// array = array.concat([
				//     { text: 'Created Date', value: 'created_at' },
				// ]);

				return array;
			}
		}
	}
</script>