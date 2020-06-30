<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mt-1">
                    <div class="card-header cyan col-md-12 row" :class="bg">
                          <div class="col-md-5">
                               <i class="fas mr-2 fa-chart-pie    "></i><Strong>Loans Info</Strong>
                          </div>
                          <div class="col-md-4">
                             
                
                          </div>
                          <div class="col-md-3">
                               <button v-show="!isDark" class="btn btn-outline-primary btn-sm float-right" @click="setDark">Night Mode</button>
                               <button  v-show="isDark" class="btn btn-outline-light btn-sm float-right" @click="setLight">Normal Mode</button>
                          </div>

                          
                    </div>

                    <div class="card-body table-responsive" :class="table">
                         <table class="table table-striped">
                             <thead>
                                 <th>Loan ID</th>
                                 <th>Borrower</th>
                                 <th>Loan Type</th>
                                 <th>Amount</th>
                                 <th>Alias</th>
                                 <th>Total Repayable</th>
                                 <th>Paid Amount</th>
                                 <th>Processing</th>
                                 <th>Status</th>
                                 <th></th>
                                 
                             </thead>
                                   
                                    <div v-show="length <= 0" class="p-3 col-md-12 bg-info col">
                                         <strong class="text-center"> No Data </strong>
                                     </div>
                             <tbody v-show="length > 0">
                                     
                                     <tr v-for="loan in loans" :key="loan.id">
                                     <td><b>{{ loan.loanId }}</b></td>
                                     <td>{{ loan.borrower }}</td>
                                     <td>{{ loan.loanType }}</td>
                                     <td>{{ loan.borrowedAmount }}</td>
                                     <td>{{ loan.alias }}</td>
                                     <td>{{ loan.totalRepayable }}</td>
                                     <td>{{ loan.paidAmount }}</td>
                                     <td v-show="loan.isProcessed"><span class="badge badge-success badge-sm">processed</span></td>
                                     <td v-show="!loan.isProcessed"><span class="badge badge-danger badge-sm">pending</span></td>
                                     <td v-show="loan.status == 'pending'"><span class="badge badge-primary badge-sm">Pending</span></td>
                                     <td v-show="loan.status == 'complete'"><span class="badge badge-info badge-sm">Complete</span></td>
                                     <td v-show="loan.status == 'active'"><span class="badge badge-success badge-sm">Active</span></td>
                                     <td v-show="loan.status == 'defaulting'"><span class="badge badge-warning badge-sm">Defaulting</span></td>
                                     <td v-show="loan.status == 'defaulted'"><span class="badge badge-danger badge-sm">Defaulted</span></td>
                                     <td><router-link :to="{ name:'loandetail',params:{id: loan.id}}" class="btn btn-sm btn-primary">view</router-link></td>
                                 </tr>
                             </tbody>
                             <tfoot>
                                 <th>Loan ID</th>
                                 <th>Borrower</th>
                                 <th>Loan Type</th>
                                 <th>Amount</th>
                                 <th>Alias</th>
                                 <th>Total Repayable</th>
                                 <th>Paid Amount</th>
                                 <th>Processing</th>
                                 <th>Status</th>
                                 <th></th>
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
                loans : {},
                bg: '',
                table: '',
                isDark:false,
                length:0,
            }

        },
        methods:{
            
            loadLoans(){
                axios.get('/api/defaulting').then(({data})=>{
                    this.loans = data;
                    this.length = data.length;
                })
            },

            setDark(){
                this.isDark = true;
                if(this.isDark){
                    this.bg = 'bg-dark'
                    this.table = 'table-dark'
                }

            },
            setLight(){
                this.isDark = false;
                 if(!this.isDark){
                    this.bg = ''
                    this.table = ''
                }

            }

        },
        created() {
            this.loadLoans();
        }
    }
</script>
