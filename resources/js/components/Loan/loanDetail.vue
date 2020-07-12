<template>
    <div class = 'container'>
        <div class="card mt-2">
            <div class="card-header">
              
                <div class="col-md-12 row">
                    <div class="col-md-4 border-right">
                        <strong class="indigo border-bottom">About Borrower</strong>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <img :src="image" class="rounded-circle" alt=""> <br>
                                    <small>{{ loan.borrower }}</small>
                            </div>
                            <div class="col-md-8">
                                <p>Borrower:  <strong>
                                    <router-link class="btn btn-outline-info" :to="{name: 'userview',params:{id: user.id}}">{{ loan.borrower }}</router-link></strong></p>
                                <p>National ID:  <strong>{{ user.NationalID }}</strong></p>
                                <p>Location:  <strong>{{ user.City }}</strong></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 border-right">
                        <strong class="pink border-bottom mb-2"> <i class="fas mr-2 green fa-money-bill    "></i> Financial Info.</strong>
                        <p>Occupation:  <strong>{{ profile.Occupation }}</strong></p>
                        <p>Monthly Income:  <strong>{{ profile.MonthlyIncome | formatMoney}}</strong></p>
                        <p>Annual Income:  <strong>{{ profile.AnualIncome | formatMoney}}</strong></p>
                    </div>
                    <div class="col-md-5 border-right">
                        <strong class="purple border-bottom mb-2"> <i class="fas orange mr-2 fa-money-bill-wave    "></i> Loan Info.</strong>
                        <div class="row">
                            <div class="col-md-6">
                                <p>Amount:  <strong>{{ loan.amount | formatMoney}}</strong></p>
                                <p>Installment:  <strong>{{ loan.installment | formatMoney}}</strong></p>
                                <p>Period:  <strong>{{ loan.period }} months</strong></p>
                            </div>

                            <div class="col-md-6">
                                <p>Total Amt:  <strong>{{ loan.totalRepayable | formatMoney }}</strong></p>
                                <p>Aliases:  <strong>{{ loan.alias | formatMoney }}</strong></p>
                                <p>Interest:  <strong>{{ loan.interest | formatMoney}} % p/M</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 row border-top">
                    <div class="col-md-3 border-right"><Strong>Loan Progress :</Strong></div>
                    <div class="col-md-3 border-right">
                        Guarantor status:
                        <span v-show="loan.guaranteeStatus"><span class="badge badge-success badge-sm">Guaranted</span></span>
                        <span v-show="!loan.guaranteeStatus"><span class="badge badge-danger badge-sm">Not Guaranted</span></span>
                    </div>
                    <div class="col-md-3 border-right">
                        Processing Status:
                        <span v-show="loan.isProcessed"><span class="badge badge-success badge-sm">processed</span></span>
                        <span v-show="!loan.isProcessed"><span class="badge badge-danger badge-sm">pending</span></span>
                    </div>
                    <div class="col-md-3">
                        Loan Status:
                        <span v-show="loan.status == 'pending'"><span class="badge badge-primary badge-sm">Pending</span></span>
                        <span v-show="loan.status == 'complete'"><span class="badge badge-info badge-sm">Complete</span></span>
                        <span v-show="loan.status == 'active'"><span class="badge badge-success badge-sm">Active</span></span>
                        <span v-show="loan.status == 'defaulting'"><span class="badge badge-warning badge-sm">Defaulting</span></span>
                        <span v-show="loan.status == 'defaulted'"><span class="badge badge-danger badge-sm">Defaulted</span></span>
                    </div>
                </div>
                     
                
            </div>
            <div class="card-body">
               <div class="col-md-12 mt-2">
                    <h4 class="card-title  blue"> <i class="fas fa-list    "></i> <strong class="text-left"> Loan Payment</strong></h4>
                    <button class="btn btn-outline-primary btn-sm float-right" @click="getGuarantors($route.params.id)"> view guarantors</button>
               </div>
                <table class="table table-bordered text-sm table-striped table-dark">
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
            <div class="card-footer text-muted">
            </div>
        </div>
    
        <!-- Modal -->
        <div class="modal fade guarantorsModal" id="guarantorsModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                                <h5 class="modal-title">Loan Guarantors</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                            </div>
                    <div class="modal-body">
                        <div class="container-fluid table-responsive">
                            <table class="table table-striped">
                                 <thead>
                                    <th>#</th>
                                    <th>Profile</th>
                                    <th>Guarantor</th>
                                    <th>National ID</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                 </thead>
                                 <tbody>
                                     <tr v-for="guarantor in guarantors.data" :key="guarantor.id">
                                         <td>{{ guarantor.id }}</td>
                                         <td><img :src="getAvatarImage(guarantor.avatar)" class="rounded-circle" style="width:50px; height:50px" alt=""></td>
                                         <td>{{ guarantor.name }}</td>
                                         <td>{{ guarantor.nationalId }}</td>
                                         <td>{{ guarantor.amount }}</td>
                                         <td>{{ guarantor.date | upDate }}</td>
                                     </tr>
                                 </tbody>
                                  <tfoot>
                                        <th> Total</th>
                                        <th><hr></th>
                                        <th><hr></th>
                                        <th><hr></th>
                                        <th>{{total}}</th>
                                        <th></th>
                                     </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-outline-primary"><i class="fas fa-print    "></i> Print</button>
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
            loan:{},
            user:{},
            profile:{},
            transactions : {},
            guarantors: {},
            total: 0,
            image: '',
        }
    },
    methods: {
        getGuarantors(id){
            this.$Progress.start();
            $('.guarantorsModal').modal('show');
           axios.get(`/api/loanguarantors/${id}`).then((data)=>{
               this.guarantors = data.data
               this.total = data.data.data[0].total
               this.$Progress.finish()
           }).catch(()=>{
               this.$Progress.fail()
           })
        },
        loadTransactions(id){
                 this.$Progress.start()
                 axios.get(`/api/loanpayment/${id}`).then(({data})=>{
                      this.transactions = data;
                      this.$Progress.finish()
                 }).catch(()=>{
                     this.$Progress.fail()
                 })
             },
        loanDetail(id){
            this.$Progress.start()
            axios.get(`/api/loan/${id}`).then((data)=>{
                this.loan = data.data.data
                this.getUser(this.loan.userId)
                this.getProfile(this.loan.userId)
                
                this.$Progress.finish()
            }).catch(()=>{
                this.$Progress.fail()
            })
        },
        getUser(id){
            axios.get(`/api/user/${id}`).then((data)=>{
                this.user = data.data
                
            })
        },
        getProfile(id){
            axios.get(`/api/show/${id}`).then((data)=>{
                this.profile = data.data
                this.getImage(this.profile)
            })
        },
        getImage(profile){
            this.image = '/images/avatar/'+ profile.Avatar
        },
        getAvatarImage(avatar){
            return `/images/avatar/${avatar}`
        }
    },
    mounted(){
         this.loanDetail(this.$route.params.id);
         this.loadTransactions(this.$route.params.id)

    }
}
</script>