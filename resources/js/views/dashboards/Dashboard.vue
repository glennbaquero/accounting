<template>
    <div>
        
        <small-box></small-box>

         <div class="row mb-2">
            <div class="col-sm-4">
                <profit-and-loss></profit-and-loss>
            </div>
            <div class="col-sm-8 d-flex">
                <div class="card card-gray card-outline">
                    <div class="card-header">
                        <b>Income & Expense Chart</b>
                    </div>  
                    <div class="card-body">
                        <div class="row justify-content-end">
                            <div class="col-md-4">
                                <v-select v-model="frequency" :options="frequencies" placeholder="Select Frequency"></v-select>
                            </div>
                        </div>
                        <line-chart :height="325" :width="1000" :chart-data="datacollection"></line-chart>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import LineChart from './LineChart.js';
import PieChart from './PieChart.js';
import ProfitAndLoss from './ProfitAndLoss.vue';
import Vselect from 'vue-select';
import SmallBox from './SmallBox.vue';


export default {

    components: {
        LineChart,
        PieChart,
        'v-select' : Vselect,
        'small-box' : SmallBox,
        'profit-and-loss' : ProfitAndLoss,
    },
    
    data() {
        return {
            datacollection : {},
            options: {
                responsive:true,
                maintainAspectRatio: false,
            },

            client: null,
            clients : [],

            frequency : 'This Fiscal Year',
            frequencies : ['This Fiscal Year', 'This Quarter', 'This Month'],

            gradient: null,
            gradient2: null
        }
    },

    mounted() {
        this.fillData() 
    },

    methods : {
        fillData () {

    this.gradient = this.$refs.canvas
      .getContext("2d")
      .createLinearGradient(0, 0, 0, 450);
    this.gradient2 = this.$refs.canvas
      .getContext("2d")
      .createLinearGradient(0, 0, 0, 450);

    this.gradient.addColorStop(0, "rgba(255, 0,0, 0.5)");
    this.gradient.addColorStop(0.5, "rgba(255, 0, 0, 0.25)");
    this.gradient.addColorStop(1, "rgba(255, 0, 0, 0)");

    this.gradient2.addColorStop(0, "rgba(0, 231, 255, 0.9)");
    this.gradient2.addColorStop(0.5, "rgba(0, 231, 255, 0.25)");
    this.gradient2.addColorStop(1, "rgba(0, 231, 255, 0)");

            this.datacollection = {
    
            blabels: [
          "January",
          "February",
          "March",
          "April",
          "May",
          "June",
          "July"
        ],
        datasets: [
          {
            label: "Data One",
            borderColor: "#FC2525",
            pointBackgroundColor: "white",
            borderWidth: 1,
            pointBorderColor: "white",
            backgroundColor: this.gradient,
            data: [40, 39, 10, 40, 39, 80, 40]
          },
          {
            label: "Data Two",
            borderColor: "#05CBE1",
            pointBackgroundColor: "white",
            pointBorderColor: "white",
            borderWidth: 1,
            backgroundColor: this.gradient2,
            data: [60, 55, 32, 10, 2, 12, 53]
          }
        ]
            }
        },

      getRandomInt () {
        return Math.floor(Math.random() * (50 - 5 + 1)) + 5
      }
    }
}
</script>