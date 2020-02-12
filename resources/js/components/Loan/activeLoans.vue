<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Loans</div>

                    <div class="card-body table-responsive">
                        <table class="table table-striped table-bordered">
                        
                            <thead>
                                <th>#</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Date</th>
                                <th>Payment Rate</th>
                                <th>Send Notification</th>
                                
            
        
                            </thead>
                            <tbody>
                            <tr v-for="loan in loans" :key="loan.id">
                                    <td>{{loan.userId}}</td>
                                    <td>{{loan.description | upText}}</td>                               
                                    <td>{{loan.amountBorrowed}}</td> 
                                    <td>{{loan.balance }}</td>
                                    <td>{{loan.created_at | upDate}}</td>
                                    <td>{{getPaymentRate(loan.amountBorrowed,loan.balance )}}%</td>
                                    <td><button class="btn btn-primary btn-sm"> send <i class="fa fa-angle-right ml-1" aria-hidden="true"></i></button></td>
                            </tr>
                            </tbody>
                            <tfoot>
                                <th>#</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Date</th>
                                <th>Payment Rate</th>
                                <th>Send Notification</th>
                                
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
            return {
                loans : {}
            }

        },
        methods:{
            getPaymentRate(bal,amount){
                return (((amount-bal)/amount) *100).toFixed(3);
            },
            loadLoans(){
                axios.get('/api/activeborrows').then(({data})=>{
                    this.loans = data;
                })
            }

        },
        created() {
            this.loadLoans();
        }
    }
</script>
