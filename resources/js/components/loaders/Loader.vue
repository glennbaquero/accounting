<template>
    <transition name="fade">
        <div v-show="isLoading" class="loader-container" :class="absolute ? 'absolute' : 'fixed'">
            <div class="loader loader-element" :class="absolute ? 'absolute' : 'fixed'"></div>
             <div class="text-loader mt-5 text-center">Loading ....</div>
           
        </div>
    </transition>
</template>

<script type="text/javascript">
import Card from '../containers/Card.vue'
/**
* ==================================================================================
* Loader for VUE components
*
* Required PROPS:
* - loading variable
*
* ==================================================================================
**/

export default {

    props : {
        loading : {
            default : false,
            type : Boolean
        },

        absolute: Boolean
    },

    watch : {
        'loading'(value)  {
            this.isLoading = value;
        }
    },
    
    mounted() {
        this.init();
    },

    data() {
        return {
            isLoading: false
        }
    },

    methods: {
        init:function() {
            if(typeof(Vue.prototype.loading) !== 'object') {
                Vue.prototype.$loading = {};
            }

            Vue.prototype.$loading = this;
        },

        show(value) {
            this.isLoading = value
        }

    }
}
</script>

<style type="text/css" scoped>
/*
|-----------------------------------------------
| @Preloaders
|-----------------------------------------------
*/

/*
| @General
|-----------------------------------------------
*/
.fixed {
    position: fixed;
}

.absolute {
    position: absolute;
}

.loader-container, .loader{
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    margin: auto;
    z-index: 99999;
}


.loader-container, .text-loader{
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    margin: auto;
    z-index: 99999;
}

.loader-container {
     background-color: rgba(255,255,255,0.7);
    height: 100%;
    width: 100%;
}

.loader{
    width: 35px;
    height: 35px;
}

.loader-element:before, .loader-element:after{
    content: "";
    position: absolute;
    top: -10px;
    left: -10px;
    width: 100%;
    height: 100%;
    border-radius: 100%;
    border: 5px solid transparent;
    border-top-color: #3a3e41;
}

.loader-element:before{
    z-index: 100;
    animation: spin 1s infinite;
}

.loader-element:after{
    border: 5px solid #ccc;
}

@keyframes spin{
    0%{
        -webkit-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }

    100%{
        -webkit-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
</style>