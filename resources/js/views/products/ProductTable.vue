<template>
	<div>
		<filter-box @refresh="fetch">
            <template v-slot:left>
                <v-select class="ml-4 mr-4 select-size"  v-model="client" :reduce="item => item.id" @input="filter($event, 'client')" label="name" placeholder="Select Client" :options="clients"></v-select>
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
                    <td>{{ item.product_number }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.variants }}</td>
                    <td>{{ item.average_price }}</td>
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
                        :message="'Are you sure you want to archive Product ' + item.name + '?'"
                        :alt-message="'Are you sure you want to restore Product ' + item.name + '?'"
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
    import "vue-select/dist/vue-select.css";
	import Vselect from "vue-select";

	export default {
        mixins: [ ListMixin ],
        
        props : {
            clients : {
                default: [],
                type: Array,
            }
        },

		data() {
			return {
                items: [],
                client : null,
			}
		},

		components: {
	        'search-form': SearchForm,
	        'action-button': ActionButton,
            'view-button': ViewButton,
            'v-select' : Vselect,
		},

		computed: {
			headers() {
				let array = [
                    { text: 'Client', value: 'client' },
				    { text: 'Item #', value: 'item_number' },
                    { text: 'Name', value: 'name' },
                    { text: 'Variants', value: 'variants' },
				    { text: 'Average Price', value: 'average_price' },
				];

				array = array.concat([
				    { text: 'Created Date', value: 'created_at' },
				]);

				return array;
			}
		},
	}
</script>