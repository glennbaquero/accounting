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
                    <td>{{ item.general_ledger }}</td>
                    <td>{{ item.main_account_id }}</td>
                    <td>{{ item.main_account_code }}</td>
			        <td>{{ item.main_account_name }}</td>
			        <td>{{ item.main_account_type }}</td>
			        <td>{{ item.main_account_category_id }}</td>
                    <td>{{ item.normal_balance }}</td>
                    <td>{{ item.period_from }}</td>
                    <td>{{ item.period_to }}</td>
                    <td>{{ item.debit }}</td>
                    <td>{{ item.credit }}</td>
                    <td>{{ item.reverse_date }}</td>
                    <td>{{ item.adjusting_date }}</td>
                    <td>{{ item.posted_on }}</td>

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
                        :message="'Are you sure you want to archive Main account ' + item.main_account_name + '?'"
                        :alt-message="'Are you sure you want to restore Main account ' + item.main_account_name  + '?'"
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
    import HelperMixin from 'Mixins/helpers.js';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import SearchForm from 'Components/forms/SearchForm.vue';
    import ViewButton from 'Components/buttons/ViewButton.vue';

	export default {
	    mixins: [ ListMixin, HelperMixin ],

        props : {
            account : {
               default : null,
               type : Number,
           },
           pivot : {
               default : false,
               type : Boolean,
           },
            attach : {
               default : false,
               type : Boolean,
           },
            detach : {
               default : false,
               type : Boolean,
           },
        },

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
                let array = [];

				array = [
					{ text: 'Client', value: 'client' },
                    { text: 'Account Summary', value: 'account_summary' },
					{ text: 'MA ID', value: 'main_account_id' },
					{ text: 'MA Code', value: 'main_account_code' },
					{ text: 'MA Name', value: 'main_account_name' },
					{ text: 'MA Type', value: 'main_account_type' },
					{ text: 'MA Category', value: 'main_account_category_id' },
                    { text: 'Normal Balance', value: 'normal_balance' },
                    { text: 'Period From', value: 'period_from' },
                    { text: 'Period To', value: 'period_to' },
                    { text: 'Debit', value: 'debit' },
                    { text: 'Credit', value: 'credit' },   
                    { text: 'Reverse Date', value: 'reverse_date' },      
                    { text: 'Adjusting Date', value: 'adjusting_date' },
                    { text: 'Posted On', value: 'posted_on' },    
				];   

				return array;
			}
		},

        methods : {
            addParams(value) {
                return this.insertParam(value ,'linked', this.account);
            }
        }
	}
</script>