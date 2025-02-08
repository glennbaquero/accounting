<template>
    <div class="row justify-content-center">
        <form-request :submit-url="submitUrl" @load="load" @success="authenticated" confirm-dialog sync-on-success>
            <div class="card">
                <div class="card-header">
                    Enter Closing Transaction Password
                </div>
                <div class="card-body">
                    <label>Password</label>
                    <div class="input-group mb-3">
                        <input class="form-control" :type="password_field_type" name="password" v-model="password">
                        <div class="input-group-prepend">
                            <button @click="switchVisibility('password')" class="btn btn-secondary" type="button">
                                <template v-if="password_field_type != 'password'">
                                    <i class="fas fa-eye"></i>	
                                </template>
                                <template v-else>
                                    <i class="fas fa-eye-slash"></i>
                                </template>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
                </div>
            </div>
        </form-request>
    </div>
</template>

<script>
import FormRequest from 'Components/forms/FormRequest.vue';
import SetupMixin from 'Mixins/setup.js';
import CrudMixin from 'Mixins/crud.js';
import ActionButton from 'Components/buttons/ActionButton.vue';
    
export default {

    mixins: [ CrudMixin, SetupMixin ],

    components: {
        'form-request': FormRequest,
        'action-button': ActionButton,
    },

    data() {
        return {
	        password : null,
            password_field_type : 'password',
        }
    },

    methods : {
        authenticated() {
            this.$emit('authenticated');
        },

        
        switchVisibility(type) {
            
            if(type == 'password') {
                this.password_field_type = this.password_field_type === "password" ? "text" : "password";
            }

            if(type == 'confirm_password') {
                this.confirm_password_field_type = this.confirm_password_field_type === "password" ? "text" : "password";
            }
            
        },

    }

}
</script>