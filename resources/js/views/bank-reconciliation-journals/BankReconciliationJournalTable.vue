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
                    <td>{{ item.bank_reconciliation_journal_number }}</td>
                    <td>{{ item.journal_batch_number }}</td>
                    <td>{{ item.journal_name_number }}</td>
                    <td>{{ item.journal_name }}</td>
                    <!-- <td>{{ item.journal_status }}</td> -->
                    <td>{{ item.balance_journal }}</td>
                    <td>{{ item.total_debit_journal }}</td>
                    <td>{{ item.total_credit_journal }}</td>
                    <td>{{ item.approved_date }}</td>
                    <td>{{ item.rejected_by }}</td>
                    <td>{{ item.posted_on }}</td>
                    <td>{{ item.log_date }}</td>
                    <td><input type="checkbox" disabled :checked="item.reversing_entry_checkbox"></td>
                    <td>{{ item.department }}</td>
                    <td><input type="checkbox" disabled :checked="item.in_use_checkbox"></td>
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
                        :message="`Are you sure you want to archive ${item.bank_reconciliation_journal_number}?`"
                        :alt-message="`Are you sure you want to restore ${item.bank_reconciliation_journal_number}?`"
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
                    { text: 'Bank Reconciliation Journal number', value:'bank_reconciliation_journal_number' },
                    { text: 'Journal Batch number', value:'journal_batch_number' },
                    { text: 'Journal Name number', value:'journal_name_number' },
                    { text: 'Journal Name', value:'journal_name' },
                    // { text: 'Journal Status', value:'journal_status' },
                    { text: '(Balance) Journal', value:'balance_journal' },
                    { text: '(Total debit) Journal', value:'total_debit_journal' },
                    { text: '(Total credit) Journal', value:'total_credit_journal' },
                    { text: 'Approved date', value:'approved_date' },
                    { text: 'Rejected by', value:'rejected_by' },
                    { text: 'Posted on', value:'posted_on' },
                    { text: 'Log Date', value:'log_date' },
                    { text: 'Reversing Entry', value:'reversing_entry_checkbox' },
                    { text: 'Department', value:'department' },
                    { text: 'In Use', value:'in_use_checkbox' },
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