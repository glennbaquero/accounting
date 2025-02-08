<template>
    <div>
        <form-request :submit-url="submitUrl" @load="load" @success="fetch" confirm-dialog sync-on-success>
            <card>
                <template v-slot:header>Customer Payment Fee Information</template>

                <div class="row">

                    <div class="form-group col-sm-3">
                        <label>Client</label> 
                        <v-select v-model="item.client_id" placeholder="Select Client" :options="clients" :reduce="item => item.id" label="name"></v-select>
                        <input hidden v-model="item.client_id" name="client_id">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label>FEE ID</label>
                        <input name="fee_id" v-model="item.fee_id"  type="text" class="form-control">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Name</label>
                        <input name="name" v-model="item.name"  type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Charge To</label>
                        <select class="form-control" name="charge_to" v-model="item.charge_to">
                            <option value="Customer">Customer</option>
                            <option value="Ledger">Ledger</option>
                        </select>
                    </div>

                    <div class="form-group col-sm-6">
                        <label>Discount Account</label> 
                        <v-select v-model="item.fee_account" placeholder="Select Fee Account" :options="main_accounts" :reduce="item => item.id" label="main_account_name"></v-select>
                        <input hidden v-model="item.fee_account" name="fee_account">
                    </div>
                </div>

                <div class="row">
                    <text-editor
                    v-model="item.notes"
                    class="col-sm-12"
                    label="Notes"
                    name="notes"
                    row="5"
                    ></text-editor>
                </div>

                <template v-slot:footer>
                    <action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
                </template>
            </card>
            
        </form-request>
    </div>
</template>

<script>
    import CrudMixin from 'Mixins/crud.js';
    
    import Card from 'Components/containers/Card.vue';
    import TextEditor from 'Components/inputs/TextEditor.vue';
    import FormRequest from 'Components/forms/FormRequest.vue';
    import Datepicker from 'vuejs-datepicker';
    import ActionButton from 'Components/buttons/ActionButton.vue';

    import Vselect from 'vue-select';

    export default {
        components: {
            Card,
            'text-editor': TextEditor,
            'form-request': FormRequest,
            'datepicker': Datepicker,
            'action-button': ActionButton,
            'v-select' : Vselect
        },

        data() {
            return {
                item: {},
                main_accounts: [],
                clients: [],
            }
        },

        methods: {
            fetchSuccess(data) {
                this.item = data.item ? data.item : this.item;
                this.main_accounts = data.main_accounts ? data.main_accounts : this.main_accounts;
                this.clients = data.clients ? data.clients : this.clients;
            },
        },

        mixins: [ CrudMixin ],
    }
</script>