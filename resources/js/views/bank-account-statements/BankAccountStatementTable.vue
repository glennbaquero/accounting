<template>
	<div>
		<filter-box @refresh="fetch">
            <template v-slot:left>

                <date-range
                :options="filterColumns"
                class="mr-2"
                @change="filter($event)"
                ></date-range>

                <v-select 
                :reduce="item => item.id" 
                class="mr-4 select-size"  
                v-model="client" 
                @input="filter($event, 'client_id')" 
                label="name"
                placeholder="Select Client" 
                :options="clients"
                 ></v-select>
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
                    <td>{{ item.client }}</td>
                    <td>{{ item.client_bank_account_number }}</td>
                    <td>{{ item.client_bank_account_holder }}</td>
                    <td>{{ item.client_bank_branch }}</td>
                    <td>{{ item.approved_date }}</td>
                    <td>{{ item.bank_statement_issue_date }}</td>
                    <td>{{ item.currency }}</td>
                    <td>{{ item.opening_balance }}</td>
                    <td>{{ item.ending_balance }}</td>
                    <td>{{ item.total_reconciled }}</td>
                    <td>{{ item.total_adjustment }}</td>
                    <td>{{ item.total_matched }}</td>
                    <td><input type="checkbox" :checked="item.reconciled_checkbox" disabled></td>
                    <td><input type="checkbox" :checked="item.adjustement_checkbox" disabled></td>
                    <td><input type="checkbox" :checked="item.canceled_checkbox" disabled></td>
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
                        :message="`Are you sure you want to archive ${item.bank_statement_id}?`"
                        :alt-message="`Are you sure you want to restore ${item.bank_statement_id}?`"
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
    import { ModelListSelect } from 'vue-search-select';
    import DateRange from 'Components/datepickers/DateRange.vue';
    import "vue-select/dist/vue-select.css";
    import Vselect from "vue-select";

	export default {
        mixins: [ ListMixin ],
        
        props : {
            clients: {
                default : () => [],
                type : Array
            },

            createUrl: String,
        },

        watch : {
            'client'(value) {
                this.filter(value, 'client');
            }
        },

		data() {
			return {
                items: [],
                client: null,
			}
		},

		components: {
	        'search-form': SearchForm,
	        'action-button': ActionButton,
            'view-button': ViewButton,
            'date-range' : DateRange,
            'v-select' : Vselect,
            ModelListSelect,
		},
		
		computed: {
			headers() {
                let array = [
                    { text: 'Client', value: 'client_id' },
                    { text: 'Client Bank Account Number', value: 'client_bank_account_number' },
                    { text: 'Client Bank Account Holder', value: null },
                    { text: 'Client Bank Branch', value: null },
                    { text: 'Approved Date', value: 'approved_date' },
                    { text: 'Bank Statement Issue Date', value: 'bank_statement_issue_date' },
                    { text: 'Currency', value: 'currency' },
                    { text: 'Opening Balance', value: 'opening_balance' },
                    { text: 'Ending Balance', value: 'ending_balance' },
                    { text: 'Total Reconciled', value: 'total_reconciled' },
                    { text: 'Total Adjustment', value: 'total_adjustment' },
                    { text: 'Total Matched', value: 'total_matched' },
                    { text: 'Is Reconciled', value: 'reconciled_checkbox' },
                    { text: 'Is Adjusted', value: 'adjustement_checkbox' },
                    { text: 'Is Cancelled', value: 'canceled_checkbox' },
                ];

                return array;
            },
            
            filterColumns() {
                let array = [
                    { text: 'Created At', value: 'created_at' },
                ];

                return array;
            },
		}
	}
</script>