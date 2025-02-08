<template>
    <div>
        <v-select @input="selectClient($event)" placeholder="Select Client" :reduce="item => item.id" label="name" v-model="client" :clearable="false" :options="clients" ></v-select>
    </div>
</template>
<script>
import { bus }from 'Root/bus.js';
import Vselect from 'vue-select';

export default {

    components: {
          'v-select' : Vselect,
    },
    
    data() {
        return {
            client: null,
            clients : [],
        }
    },

    mounted() {
        this.init() 
    },

    methods : {
        init() {
            this.fetch();
        },

        fetch () {
            axios.get('/fetch-client').then( response => {
                let clients = response.data;
                this.clients = clients;
                this.setDefaultData(clients);
            }).catch( error => {

            });
        },

        setDefaultData(data) {
           if (data.length) {
                this.client = data[0].id;
                this.selectClient(data[0].id);
           }
         
        },

        selectClient(value) {
            bus.$emit('select-client', value);
        }
    }
}
</script>