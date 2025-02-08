<template>
    <div class="row">        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-white">

                <template v-if="loading">
                    <transition name="fade">
                        <div class="overlay dark">
                            <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                        </div>
                    </transition>
                </template>

                <div class="inner">
                    <h3>{{ pending_invoices }}</h3>
    
                    <p>Total Pending Invoice</p>
                </div>
                <div class="icon">
                   <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white">
                <template v-if="loading">
                    <transition name="fade">
                        <div class="overlay dark">
                            <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                        </div>
                    </transition>
                </template>
                <div class="inner">
                    <h3>{{ pending_purchase }}</h3>
    
                    <p>Total Pending Purchase Order</p>
                </div>
                <div class="icon">
                   <i class="fa fa-shopping-cart"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
            <!-- ./col -->
        <div class="col-lg-3 col-6">
                <!-- small box -->
            <div class="small-box bg-white">
                <template v-if="loading">
                    <transition name="fade">
                        <div class="overlay dark">
                            <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                        </div>
                    </transition>
                </template>
                <div class="inner"> 
                    <h3>{{ total_vendors }}</h3>
    
                    <p>Total Vendors</p>
                </div>
                <div class="icon">
                    <i class="far fa-building"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-white">
                <template v-if="loading">
                    <transition name="fade">
                        <div class="overlay dark">
                            <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                        </div>
                    </transition>
                </template>
                <div class="inner">
                    <h3>{{ total_customers }}</h3>

                    <p>Total Customer</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
</template>
<script>
import { bus }from 'Root/bus.js';

export default {

    components: {

    },
    
    data() {
        return {
            pending_invoices : 0,
            pending_purchase : 0,
            total_vendors : 0,
            total_customers : 0,

            loading : false,
        }
    },

    mounted() {
        bus.$on('select-client', (data) => {
            this.fetch(data);
        })
    },

    methods : {
        fetch(data) {
            this.loading = true;
            axios.get('dashboard/small-box/' + data).then( response => {
                this.loading = false;
                let data = response.data;
                this.pending_invoices = response.data.pending_invoices;
                this.pending_purchase = response.data.pending_purchase;
                this.total_vendors = response.data.total_vendors;
                this.total_customers = response.data.total_customers;
            });
        }
    }
}
</script>