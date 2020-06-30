<template>
    <div>
        <div class="card text-left">
        <div class="col-md-5 mt-3">
              <form @submit.prevent="loadUserTransactions()" class="search" >
                    <div class="form-group">
                        <div class="input-group mb-3">
                            
                            <input v-model="form.MSISDN"  type="text" name="MSISDN"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('MSISDN') }" placeholder="eg. 07XXXXXXXX">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><a href="#" type="submit"><i class="fas fa-search    "></i></a></span>
                            </div>
                            <has-error :form="form" field="MSISDN"></has-error>
                        </div>

               </div>
              </form>
        </div>
          <div class="card-body">
          <div class="row invoice-info top">
                <div class="col-sm-4 invoice-col">
                  <address>
                    <strong>Mbanking, Kenya.</strong><br>
                    364 Nairobi, Kenya<br>
                    Phone: (254) 743751575<br>
                    Email: info@mbankingkenya.com
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>Printed by :</b><br>
                  <b>Name:</b> {{user.FirstName}} {{user.MiddleName}} {{user.LastName}}<br>
                  <b>Phone No:</b> {{user.PhoneNumber}}<br>
                  <b>ID No:</b> {{user.NationalID}}
                </div>
                <!-- /.col -->
              </div>
            <h4 class="card-title indigo"><i class="fas fa-list-alt  mr-2  "></i><strong>My Transaction Statement</strong></h4>
            <div class="float-right m-1">
               <a href="#" @click.prevent="printStatement" class="btn btn-primary float-right search"><i class=" mr-2 fas fa-print    "></i> Print</a>
            </div>
            
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
                                <tr v-for="transaction in transactions" :key="transaction.id">
                                    <td>{{transaction.id}}</td>
                                    <td>{{transaction.description}}</td>
                                     <td>{{transaction.transactionID}}</td>
                                    <td>{{transaction.amount}}</td>
                                    <td>{{transaction.user}}</td>
                                    <td>{{transaction.original_bal}}</td>
                                    <td>{{transaction.new_bal}}</td>
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
          <div class="card-footer" >
              <table class="table table-striped">
                  <tbody>
                      <tr>
                          <td><strong>Total Balance</strong></td>
                          <td><strong class="float-right">{{total}}</strong></td>
                      </tr>
                  </tbody>
              </table>
          </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
             return{
                 user:{},
                 transactions : {},
                  total: 0,
                 form : new Form({
                  MSISDN: '',
                 })
             }
        },
        methods:{
           printStatement(){
             $('.search').hide();
             window.print()
           },

              loadUserTransactions(){
                 this.form.post('/api/mytransaction').then(({data})=>{
                     this.transactions = data.transactions
                     this.user = data.user
                     this.total = data.total
                 })
             },
             loadTransactions(){
                 $('.card-foote').hide();
                 axios.get('/api/trasactions').then(({data})=>{
                     this.transactions = data.data;
                 })
             }
        },
        created() {
           this.loadTransactions()
          
        }
}
</script>