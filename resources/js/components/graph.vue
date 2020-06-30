<template>
  <div class="small">
    <h4>Reports</h4>
    <line-chart :chart-data="datacollection" :height="100"></line-chart>
  </div>
</template>

<script>

import LineChart from './LineCharts.js'

export default {
  components: {
    LineChart
  },
  data(){
    return {
      datacollection: null,
      usersInfo : []
    }
  },
 
  methods: {
    loadData(){
      axios.get('api/usersreport').then((data)=>{
        this.usersInfo = data.data
        this.fillData()

      })
    },
       fillData () {
      this.datacollection = {
        labels: [
                  window.moment().subtract(4, 'months').format('MMM'),
                  window.moment().subtract(3, 'months').format('MMM'),
                  window.moment().subtract(2, 'months').format('MMM'),
                  window.moment().subtract(1, 'months').format('MMM'),
                   window.moment().format('MMMM'),
                  ],
                 
        datasets: [
          {
            label: 'Users',
            backgroundColor: '#FF0066',
            data: this.usersInfo,
            title: "Registered users within last five months"
          },
        ]
      }
    }
  },
   created () {
    this.loadData()
  }
}
</script>

<style lang="css">
.small {
  max-width: 800px;
  /* max-height: 500px; */
  margin:  50px auto;
}
</style>