<template>
	<div>
		<filter-box @refresh="fetch">
            <template v-slot:left>

                <v-select 
                class="ml-4 mr-4 select-size" 
                :reduce="item => item.id" 
                v-model="client" 
                @input="filter($event, 'client')" 
                label="name" 
                placeholder="Select Client" :options="clients"
                ></v-select>

                <date-range
                :options="filterColumns"
                class="mr-2"
                @change="filter($event)"
                ></date-range>

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
                    <td>{{ item.id }}</td>
                    <td>{{ item.bills_of_exchange }}</td>
                    <td>{{ item.issue_date }}</td>
                    <td>{{ item.due_from }}</td>
                    <td>{{ item.due_to }}</td>
                    <td>{{ item.principal_amount }}</td>
                    <td>{{ item.number_of_times_to_settle }}</td>
                    <td>{{ item.ammount_to_settle }}</td>
                    <td>{{ item.terms_of_payment }}</td>
                    <td>{{ item.payment_day }}</td>
                    <td>{{ item.interest_rate }}</td>
                    <td>{{ item.interest_amount }}</td>
                    <td>{{ item.terms_of_interest }}</td>
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
                        :message="`Are you sure you want to archive ${item.bank_transaction_posting}?`"
                        :alt-message="`Are you sure you want to restore ${item.bank_transaction_posting}?`"
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

        props: {
            clients: {
                type: Array,
                default: () => [],
            },
        },
		
		computed: {
			headers() {
                let array = [
                    { text: '#', value: 'id' },
                    { text: 'Bills of Exchange', value: 'bills_of_exchange' },
                    { text: 'Issue Date', value: 'issue_date' },
                    { text: 'Due From', value: 'due_from' },
                    { text: 'Due To', value: 'due_to' },
                    { text: 'Principal Amount', value: 'principal_amount' },
                    { text: 'Number Of Times To Settle', value: 'number_of_times_to_settle' },
                    { text: 'Amount To Settle', value: 'ammount_to_settle' },
                    { text: 'Terms Of Payment', value: 'terms_of_payment' },
                    { text: 'Payment Day', value: 'payment_day' },
                    { text: 'Interest Rate', value: 'interest_rate' },
                    { text: 'Interest Amount', value: 'interest_amount' },
                    { text: 'Terms Of Interest', value: 'terms_of_interest' },
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