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
                    <td>{{ item.adjustment_number }}</td>
                    <td>{{ item.adjustment_date }}</td>
                    <td>{{ item.adjustment_by }}</td>
                    <td>{{ item.status }}</td>
                    <td>{{ item.type }}</td>
                    <td>{{ item.sub_type }}</td>
                    <td>{{ item.other_adjustment }}</td>
                    <td>{{ item.created_at }}</td>
                    <td>{{ item.deleted_at }}</td>
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
                        :message="`Are you sure you want to archive ${item.adjustment_number}?`"
                        :alt-message="`Are you sure you want to restore ${item.adjustment_number}?`"
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
                    { text: 'Adjustment Number', value: 'adjustment_number' },
                    { text: 'Adjustment Date', value: 'adjustment_date' },
                    { text: 'Adjustment By', value: 'adjustment_by' },
                    { text: 'Status', value: 'status' },
                    { text: 'Type', value: 'type' },
                    { text: 'Sub Type', value: 'sub_type' },
                    { text: 'Other Adjustment', value: 'other_adjustment' },
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