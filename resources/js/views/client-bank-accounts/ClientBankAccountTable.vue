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
                    <td>{{ item.bank_account }}</td>
                    <td>
                        <span class="badge rounded-pill" :class="item.bank_account_status_class">
                            {{ item.bank_account_status }}
                        </span>
                    </td>
                    <td>{{ item.bank_account_number }}</td>
                    <td>{{ item.account_holder }}</td>
                    <td>{{ item.bank_account_type }}</td>
                    <td>{{ item.routing_number }}</td>
                    <td>{{ item.bank_name }}</td>
                    <td>{{ item.bank_branch }}</td>
                    <td>{{ item.bank_balance }}</td>
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
                        :message="`Are you sure you want to archive ${item.bank_name}?`"
                        :alt-message="`Are you sure you want to restore ${item.bank_name}?`"
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
                    { text: 'Bank Account', value: 'bank_account' },
                    { text: 'Bank Account Status', value: null },
                    { text: 'Bank Account Number', value: 'bank_account_number' },
                    { text: 'Account Holder', value: 'account_holder' },
                    { text: 'Bank Account Type', value: 'bank_account_type' },
                    { text: 'Routing Number', value: 'routing_number' },
                    { text: 'Bank Name', value: 'bank_name' },
                    { text: 'Bank Branch', value: 'bank_branch' },
                    { text: 'Bank Balance', value: 'bank_balance' },
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