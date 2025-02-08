<template>
	<div>
		<filter-box @refresh="fetch">
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
                    <template v-if="!pivot">
                        <td>{{ item.client }}</td>
                        <td>{{ item.main_account_id }}</td>
                    </template>
                    <td>{{ item.main_account_code }}</td>
			        <td>{{ item.main_account_name }}</td>
			        <td>{{ item.main_account_type }}</td>
			        <td>{{ item.main_account_category_id }}</td>
                    <template v-if="!pivot">
                        <td>{{ item.db_cr_requirement }}</td>
                        <td>{{ item.posting_type }}</td>
                    </template>
                    <td>
                        <template v-if="!pivot">
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
                            :message="'Are you sure you want to archive Main account ' + item.main_account_name + '?'"
                            :alt-message="'Are you sure you want to restore Main account ' + item.main_account_name  + '?'"
                            @load="load"
                            @success="sync"
                            ></action-button>
                        </template>

                        <action-button
                        v-if="attach"
                        small 
                        color="btn-success"
                        :action-url="addParams(item.attachUrl)"
                        icon="fa fa-plus"
                        confirm-dialog
                        :disabled="loading"
                        title="Add Main Account"
                        :message="'Are you sure you want to link ' + item.main_account_name + '?'"
                        @load="load"
                        @success="sync"
                        ></action-button>

                        <action-button
                        v-if="detach"
                        small 
                        color="btn-warning"
                        :action-url="addParams(item.detachUrl)"
                        icon="fa fa-minus"
                        confirm-dialog
                        :disabled="loading"
                        title="Remove Main Account"
                        :message="'Are you sure you want to detach ' + item.main_account_name + '?'"
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
    import HelperMixin from 'Mixins/helpers.js';
	import ActionButton from 'Components/buttons/ActionButton.vue';
	import SearchForm from 'Components/forms/SearchForm.vue';
    import ViewButton from 'Components/buttons/ViewButton.vue';

	export default {
	    mixins: [ ListMixin, HelperMixin ],

        props : {
            account : {
               default : null,
               type : Number,
           },
           pivot : {
               default : false,
               type : Boolean,
           },
            attach : {
               default : false,
               type : Boolean,
           },
            detach : {
               default : false,
               type : Boolean,
           },
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
                let array
                if(!this.pivot) {
                    array = [
                        { text: 'Client', value: 'client' },
                        { text: 'Main Account ID', value: 'main_account_id' },
                        { text: 'Main Account Code', value: 'main_account_code' },
                        { text: 'Main Account Name', value: 'main_account_name' },
                        { text: 'Main Account Type', value: 'main_account_type' },
                        { text: 'Main Account Category', value: 'main_account_category_id' },
                        { text: 'DB/CR requirement', value: 'db_cr_requirement' },
                        { text: 'Posting type', value: 'posting_type' },
                    ];
                }else {
                    array = [
                        { text: 'MA Code', value: 'main_account_code' },
                        { text: 'MA Name', value: 'main_account_name' },
                        { text: 'MA Type', value: 'main_account_type' },
                        { text: 'MA Category', value: 'main_account_category_id' },
                    ];
                }

				return array;
			}
		},

        methods : {
            addParams(value) {
                return this.insertParam(value ,'linked', this.account);
            }
        }
	}
</script>