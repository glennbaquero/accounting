<template>
	<div>
		<filter-box @refresh="fetch">
            <template v-slot:left>
                <v-select class="ml-4 mr-4 select-size" :reduce="item => item.id" v-model="client" @input="filter($event, 'client')" label="name" placeholder="Select Client" :options="clients"></v-select>
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
                    <td>{{ item.name }}</td>
                    <td>{{ item.display_name }}</td>
                    <td>{{ item.company }}</td>
                    <td>{{ item.customer_account }}</td>
                    <td>{{ item.mobile_number }}</td>
                    <td>{{ item.address }}</td>
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
                        :message="'Are you sure you want to archive Vendor ' + item.id + '?'"
                        :alt-message="'Are you sure you want to restore Vendor ' + item.id + '?'"
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
				let array = [
				    { text: '#', value: 'id' },
                    { text: 'Name', value: 'name' },
				    { text: 'Display Name', value: 'display_name' },
				    { text: 'Company', value: 'company' },
                    { text: 'Customer ID', value: 'customer_account' },
                    { text: 'Mobile Number', value: 'mobile_number' },
                    { text: 'Address', value: 'address' },
				];


				array = array.concat([
				    { text: 'Created Date', value: 'created_at' },
				]);

				return array;
			}
		}
	}
</script>