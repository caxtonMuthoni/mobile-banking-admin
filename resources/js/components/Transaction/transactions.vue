<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mt-2">
                    <div class="card-header"><strong class="indigo">Transcations</strong></div>

                    <div class="card-body table-responsive">
                        <table class="table table-bordered text-sm table-striped">
                            <thead>
                                <th>#</th>
                                <th>Description</th>
                                <th>Transaction ID</th>
                                <th>Amount</th>
                                <th>Transcated By</th>
                                <th>Original Bal</th>
                                <th>New Bal</th>
                                <th>Date</th>
                            </thead>
                            <tbody>
                                <tr v-for="transaction in transactions.data" :key="transaction.id">
                                    <td>{{transaction.id}}</td>
                                    <td>{{transaction.description}}</td>
                                     <td>{{transaction.transactionID}}</td>
                                    <td>{{transaction.amount | formatMoney}}</td>
                                    <td>{{transaction.user}}</td>
                                    <td>{{transaction.original_bal | formatMoney}}</td>
                                    <td>{{transaction.new_bal | formatMoney}}</td>
                                    <td>{{transaction.created_at | upDate}}</td>
                                    
      
                                </tr>
                            </tbody>
                            <tfoot>
                                <th>#</th>
                                <th>Description</th>
                                <th>Transaction ID</th>
                                <th>Amount</th>
                                <th>Transcated By</th>
                                <th>Original Bal</th>
                                <th>New Bal</th>
                                <th>Date</th>
                            </tfoot>
                        </table>
                    </div>
                    <div class="card-footer">
                        <pagination class="float-right" :data="transactions" :limit="2" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
             return{
                 transactions : {}
             }
        },
        methods:{
             getResults(page = 1){
                this.$Progress.start()
                axios.get('/api/trasactions?page='+page).then(({data})=>{
                      this.transactions = data;
                      this.$Progress.finish()
                 }).catch(()=>{
                     this.$Progress.fail()
                 })
             },

             loadTransactions(){
                 this.$Progress.start()
                 axios.get('/api/trasactions').then(({data})=>{
                      this.transactions = data;
                      this.$Progress.finish()
                 }).catch(()=>{
                     this.$Progress.fail()
                 })
             }
        },
        created() {
           this.loadTransactions()
        }
    }
</script>
