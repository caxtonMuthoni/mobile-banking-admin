<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><strong class="indigo">Transcations</strong></div>

                    <div class="card-body">
                        <table class="table table-bordered table-stripped">
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
                                <tr v-for="transaction in transactions" :key="transaction.id">
                                    <td>{{transaction.id}}</td>
                                    <td>{{transaction.TransID}}</td>
                                     <td>{{transaction.TransactionType}}</td>
                                    <td>{{transaction.TransAmount}}</td>
                                    <td>{{transaction.FirstName}} {{transaction.Githinji}}</td>
                                    <td>{{transaction.OrgAccountBalance}}</td>
                                    <td>{{transaction.CrtAccountBalance}}</td>
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
             loadTransactions(){
                 axios.get('/api/trasactions').then(({data})=>{
                      this.transactions = data.transactions.data;
                      this.user = data.user
                 })
             }
        },
        created() {
           this.loadTransactions()
        }
    }
</script>
