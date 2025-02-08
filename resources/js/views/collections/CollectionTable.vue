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
                    <td>{{ item.collection_id }}</td>
                    <td>{{ item.collection_date }}</td>
                    <td>{{ item.sent_date }}</td>
                    <td>{{ item.due_date }}</td>
                    <td>{{ item.amount_to_settle }}</td>
                    <td>{{ item.customer_account }}</td>
                    <td>{{ item.invoice_date }}</td>
                    <td>{{ item.customer_address }}</td>
                    <td>{{ item.customer_name }}</td>
                    <td>{{ item.customer_contact_id }}</td>
                    <td>{{ item.customer_bank_account }}</td>
                    <td>{{ item.client_bank_account }}</td>
                    <td>{{ item.description }}</td>
                    <td>{{ item.bills_exchange_id }}</td>
                    <td>{{ item.bills_exchange_status }}</td>
                    <td>{{ item.voucher }}</td>
                    <td>{{ item.collection_status }}</td>
                    <td>{{ item.activity_type }}</td>
                    <td>{{ item.activity_start_date }}</td>
                    <td>{{ item.activity_date }}</td>
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
                        :message="`Are you sure you want to archive ${item.collection_id}?`"
                        :alt-message="`Are you sure you want to restore ${item.collection_id}?`"
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
                    { text:'Collection ID', value:'collection_id' },
                    { text:'Collection Date', value:'collection_date' },
                    { text:'Sent Date', value:'sent_date' },
                    { text:'Due Date', value:'due_date' },
                    { text:'Amount To Settle', value:'amount_to_settle' },
                    { text:'Customer Account', value:'customer_account' },
                    { text:'invoice_date', value:'invoice_date' },
                    { text:'Customer Address', value:'customer_address' },
                    { text:'Customer Name', value:'customer_name' },
                    { text:'Customer Contact ID', value:'customer_contact_id' },
                    { text:'Customer Bank', value:'customer_bank_account' },
                    { text:'Client Bank', value:'client_bank_account' },
                    { text:'description', value:'description' },
                    { text:'Bills of Exchange ID', value:'bills_exchange_id' },
                    { text:'Bills of Exchange Status', value:'bills_exchange_status' },
                    { text:'Voucher', value:'voucher' },
                    { text:'Collection Status', value:'collection_status' },
                    { text:'Activity Type', value:'activity_type' },
                    { text:'Activity Start Date', value:'activity_start_date' },
                    { text:'Activity Date', value:'activity_date' },
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