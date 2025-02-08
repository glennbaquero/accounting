<template>
	<div>
		<filter-box @refresh="fetch">
            <template v-slot:left>
                <!-- <date-range
                class="mr-2"
                @change="filter($event)"
                ></date-range>

                <selector
                v-if="filterTypes"
                class="mt-2"
                :items="filterTypes"
                @change="filter($event, 'type')"
                placeholder="Filter by type"
                ></selector> -->
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
                <tr :key="item.id" v-for="item in items">
                    <td>{{ item.id }}</td>
                    <td>{{ item.company }}</td>
                    <td>{{ item.department }}</td>
                    <td>{{ item.code }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.status }}</td>
                    <td>{{ item.active_from }}</td>
                    <td>{{ item.active_to }}</td>
                    <td>{{ item.created_at }}</td>
                    <td>                   
                        <template v-if="company">
                            <view-button :href="item.withCompanyShowUrl"></view-button>
                        </template>
                        <template v-else>
                            <view-button :href="item.showUrl"></view-button>
                        </template>
                        
                        <action-button
                        v-if="!hideButtons"
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
                        :message="'Are you sure you want to archive Cost Center ' + item.code + '?'"
                        :alt-message="'Are you sure you want to restore Cost Center ' + item.code + '?'"
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
			company : {
				type : String,
				default : false,
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
                    { text: 'Company', value: 'company' },
                    { text: 'Department', value: 'department' },
				    { text: 'Code', value: 'code' },
				    { text: 'Name', value: 'name' },
				    { text: 'Status', value: 'status' },
				    { text: 'Active from', value: 'active_from' },
				    { text: 'Active to', value: 'active_to' },
				];


				array = array.concat([
				    { text: 'Created Date', value: 'created_at' },
				]);

				return array;
			}
		}
	}
</script>