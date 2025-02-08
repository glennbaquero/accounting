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
                    <td>{{ item.interest_adjustment_id }}</td>
                    <td>{{ item.interest_adjustment_date }}</td>
                    <td>{{ item.start_date }}</td>
                    <td>{{ item.end_date }}</td>
                    <td>{{ item.customer_account }}</td>
                    <td>{{ item.customer }}</td>
                    <td>{{ item.transaction_date }}</td>
                    <td>{{ item.transaction_type }}</td>
                    <td>{{ item.interest_note_id }}</td>
                    <td>{{ item.interest_note_amount }}</td>
                    <td>{{ item.waived_amount }}</td>
                    <td>{{ item.unpaid_balance }}</td>
                    <td>{{ item.fee_amount }}</td>
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
                        :message="`Are you sure you want to archive ${item.interest_adjustment_id}?`"
                        :alt-message="`Are you sure you want to restore ${item.interest_adjustment_id}?`"
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
                    { text:'#', value:'id' },
                    { text:'Interest Adjustment ID', value:'interest_adjustment_id' },
                    { text:'Interest Adjustment Date', value:'interest_adjustment_date' },
                    { text:'Start Date', value:'start_date' },
                    { text:'End Date', value:'end_date' },
                    { text:'Csutomer Account', value:'customer_account' },
                    { text:'Customer', value:'customer' },
                    { text:'Transaction Date', value:'transaction_date' },
                    { text:'Transaction Type', value:'transaction_type' },
                    { text:'Interest Note ID', value:'interest_note_id' },
                    { text:'Interest Note Amount', value:'interest_note_amount' },
                    { text:'Waived Amount', value:'waived_amount' },
                    { text:'Unpaid Balance', value:'unpaid_balance' },
                    { text:'Fee Amount', value:'fee_amount' },
                    { text:'Created Date', value:'created_at' },
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