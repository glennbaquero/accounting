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
                    <td>{{ item.payment_reversal_id }}</td>
                    <td>{{ item.reversed_date }}</td>
                    <td>{{ item.reason }}</td>
                    <td>{{ item.status }}</td>
                    <td>{{ item.approved_checkbox }}</td>
                    <td>{{ item.approved_by }}</td>
                    <td>{{ item.approved_date }}</td>
                    <td>{{ item.posted_checkbox }}</td>
                    <td>{{ item.posted_by }}</td>
                    <td>{{ item.posted_date }}</td>
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
                    { text: 'Payment Reversal ID', value:'payment_reversal_id' },
                    { text: 'Reversal Date', value:'reversed_date' },
                    { text: 'Reason', value:'reason' },
                    { text: 'Status', value:'status' },
                    { text: 'Approved', value:'approved_checkbox' },
                    { text: 'Approved By', value:'approved_by' },
                    { text: 'Approved Date', value:'approved_date' },
                    { text: 'Posted', value:'posted_checkbox' },
                    { text: 'Posted By', value:'posted_by' },
                    { text: 'Posted Date', value:'posted_date' },
                    { text: 'Create Date', value:'created_at' },
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