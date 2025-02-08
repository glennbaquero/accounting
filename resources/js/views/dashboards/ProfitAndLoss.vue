<template>
<div>
    <div class="card card-gray card-outline"> 
        <template v-if="pl_loading">
             <transition name="fade">
                <div class="overlay dark">
                    <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                </div> 
             </transition>
        </template>
        <div class="card-header">
            <b>Profit and Loss</b> <i class="fas fa-arrow-up text-success"></i><i class="fas fa-arrow-down text-danger"></i>
        </div>
        <div class="card-body">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <h2 class="mr-3">$ {{ amount | currency }} </h2> <h6 :class="percent > 0 ? 'text-success' : 'text-danger'">{{ percent | currency }}% </h6>
                        </div>
                    </div>
                    <div class="col-sm-6 form-group text-right">
                        <v-select v-model="pl_frequency" :options="frequencies" placeholder="Select Frequency"></v-select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-center">
                        <strong>Summary</strong>
                        </p>

                        <div class="progress-group mb-2">
                            Income
                            <span class="float-right">$<b>{{ income }}</b></span>
                            <div class="progress progress-lg">
                                <div class="progress-bar bg-primary" v-bind:style="{width: income_percent + '%'}"></div>
                            </div>
                        </div> 
                        <div class="progress-group">                   
                            Expense
                            <span class="float-right">$<b>{{ expense }}</b></span>
                            <div class="progress progress-lg">
                                <div class="progress-bar bg-warning" v-bind:style="{width: expense_percent + '%'}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-gray card-outline">
        <template v-if="loading">
             <transition name="fade">
                <div class="overlay dark">
                    <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                </div> 
             </transition>
        </template>
        <div class="card-header">
            <b>Payables and Receivable</b> <i class="fas fa-arrow-up text-success"></i><i class="fas fa-arrow-down text-danger"></i>
        </div>
        <div class="card-body">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <h2 class="mr-3">$ 5000 </h2> <h6 class="text-danger">-  16% </h6>
                        </div>
                    </div>
                    <div class="col-sm-6 form-group text-right">
                        <v-select v-model="pr_frequency" :options="frequencies" placeholder="Select Frequency"></v-select>
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                        <p class="text-center">
                            <strong>Summary</strong>
                        </p>

                        <div class="progress-group mb-2">
                            Accounts Payable 
                            <span class="float-right">$<b>2500</b></span>
                            <div class="progress progress-lg">
                                <div class="progress-bar bg-danger" style="width: 80%"></div>
                            </div>
                        </div> 
                        <div class="progress-group">                   
                             Accounts Receivable
                            <span class="float-right">$<b>2500</b></span>
                            <div class="progress progress-lg">
                                <div class="progress-bar bg-success" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import Vselect from 'vue-select';
import { bus }from 'Root/bus.js';

export default {

    components: {
        'v-select' : Vselect
    },
    
    data() {
        return {
            pl_loading : false,
            pr_loading : false,
            pl_frequency : 'This Month',
            pr_frequency : 'This Month',
            frequencies : ['This Month', 'Today' , 'This Year'],

            income : 0,
            expense : 0,
            amount : 0,
            income_percent : 0,
            expense_percent : 0,
            percent : 0,
        }
    },

    mounted() {
        bus.$on('select-client', (data) => {
            this.fetchProfitAndLoss(data);
        })
    },

    methods : {
        fetchProfitAndLoss(data) {
            this.pl_loading = true;
            axios.get('dashboard/profit-and-loss/' + data).then( response => {
                this.pl_loading = false;
                let data = response.data;

                this.income = response.data.income;
                this.expense = response.data.expense;
                this.amount  = response.data.amount;
                this.income_percent  = response.data.income_percent;
                this.expense_percent  = response.data.expense_percent;
                this.percent = response.data.percent;
            });
        }
    }
}
</script>