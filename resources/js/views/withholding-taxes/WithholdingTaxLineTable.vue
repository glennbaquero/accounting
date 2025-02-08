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
                    <td>{{ item.withholding_tax_id }}</td>
                    <td>{{ item.withholding_tax_name }}</td>
                    <td>{{ item.withholding_tax_posting }}</td>
                    <td>{{ item.description }}</td>
                    <td>{{ item.minimum_amount }}</td>
                    <td>{{ item.maximum_amount }}</td>
                    <td>{{ item.tax_percent }}</td>
                    <td><input type="checkbox" :checked="item.withholding_tax_exemptions_checkbox" disabled></td>
                    <td>{{ item.created_at }}</td>
                    <td>

                        <button 
                            class="btn btn-sm btn-primary" 
                            @click="edit(item)"
                        >
                            <i class="fas fa-pen"></i>
                        </button>

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
                        :message="`Are you sure you want to archive ${item.withholding_tax_posting}?`"
                        :alt-message="`Are you sure you want to restore ${item.withholding_tax_posting}?`"
                        @load="load"
                        @success="sync"
                        ></action-button>
                    </td>
                </tr>
            </template>

        </data-table>

        <template v-if="onEdit">
            <div ref="modal" class="modal fade"  id="ba-lines-modal" tabindex="-1" role="dialog" aria-labelledby="withholding_tax_line" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"  id="withholding_tax_line">Payment Schedule Line</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <withholding-tax-line-view
                                ref="form"
                                :clients="clients"
                                :parent="parent"
                                :fetch-url="item.showUrl"
                                :submit-url="item.updateUrl"
                                @submit-success="submitSuccess"
                            ></withholding-tax-line-view>
                        </div>
                    </div>
                </div>
            </div>
        </template>

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
                item: {},
                items: [],
                onEdit: false,
                client: null,
			}
		},

        methods: {
            edit(item) {
                this.onEdit = true;
                this.item = item;
                setTimeout(() => {
                    $(this.$refs.modal).modal('show');
                    this.modalOnclose();
                }, 500);
            },

            modalOnclose() {
                $(this.$refs.modal).on('hidden.bs.modal', () => {
                    setTimeout(() => {
                        this.onEdit = false;
                    }, 500);
                });
            },

            submitSuccess() {
                $(this.$refs.modal).modal('hide');
            },
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
            parent: {
                type: Object,
                default: () => {},
            },
        },
		
		computed: {
			headers() {
                let array = [
                    { text:'#', value:'id' },
                    { text:'Withholding Tax ID', value:'withholding_tax_id' },
                    { text:'Withholding Tax Name', value:'withholding_tax_name' },
                    { text:'Withholding Tax Posting', value:'withholding_tax_posting' },
                    { text:'Description', value:'description' },
                    { text:'Minimum Amount', value:'minimum_amount' },
                    { text:'Maximum Amount', value:'maximum_amount' },
                    { text:'Tax Percent', value:'tax_percent' },
                    { text:'Withholding Tax Exemptions', value:'withholding_tax_exemptions_checkbox' },
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