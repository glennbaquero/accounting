<template>
    <div>
        <form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success :params="item">
            <card>
                <template v-slot:header>
                    Bank Facility Group Information
                    <div class="float-right">
                        <action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
                    </div>
                </template>

                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-9">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#active" data-toggle="tab">Bank Facility Group</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#status" data-toggle="tab">Status</a></li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row" style="margin-right: 10px">
                                    <label class="col-sm-3 col-form-label">Client <b class="text-danger">*</b></label>
                                    <model-list-select :list="clients"
                                        v-model="item.client_id"
                                        option-value="id"
                                        option-text="name"
                                        placeholder="Select a client"
                                        class="form-control col-sm-9">
                                    </model-list-select>
                                    <input name="client_id" hidden v-model="item.client_id"> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane show active" id="active">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label class="">Bank Facility Group</label>
                                        <model-list-select :list="groups"
                                            v-model="item.bank_facility_group_id"
                                            option-value="id"
                                            option-text="name"
                                            placeholder="Select facility group"
                                            class="form-control">
                                        </model-list-select>
                                        <input name="bank_facility_group_id" hidden v-model="item.bank_facility_group_id"> 
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="bank_facility_type_code">Bank Facility Type Code</label>
                                        <input type="text" class="form-control" name="bank_facility_type_code" v-model="item.bank_facility_type_code">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="bank_facility_type_name">Bank Facility Type Name</label>
                                        <input type="number" min="0" step="any" name="bank_facility_type_name" class="form-control" v-model="item.bank_facility_type_name">
                                    </div>
                                </div>
                                <div class="row">
                                    <text-editor
                                    v-model="item.description"
                                    class="col-sm-12"
                                    label="Description"
                                    name="description"
                                    row="5"
                                    ></text-editor>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </card>

            <loader 
            :loading="loading">
            </loader>
        </form-request>
    </div>
</template>

<script>
    
    import CrudMixin from 'Mixins/crud.js';
    import SetupMixin from 'Mixins/setup.js';

    import FormRequest from 'Components/forms/FormRequest.vue';
    import ActionButton from 'Components/buttons/ActionButton.vue';
    import Datepicker from 'vuejs-datepicker';

    import flatpickr from 'flatpickr';
    import 'flatpickr/dist/flatpickr.css';
    import { ModelListSelect } from 'vue-search-select'
    import Vselect from "vue-select";
    import TextEditor from 'Components/inputs/TextEditor.vue';

    export default {

        data() {
            return {
                item: {},
                groups: [],
            }
        },

        methods: {
            fetchSuccess(data) {
                this.item = data.item ? data.item : this.item;
                this.groups = data.groups ? data.groups : this.groups;
            },

            formatDate(date) {
                return date ? moment(date).format('MM/DD/Y') : '';
            },
        },

        components: {
            'form-request': FormRequest,
            'action-button': ActionButton,
            ModelListSelect,
            'v-select' : Vselect,
            'text-editor': TextEditor,
        },

        mixins: [ CrudMixin, SetupMixin ],

        props: {
            clients: {
                type: Array,
                default: () => [],
            },
        },
    }

</script>