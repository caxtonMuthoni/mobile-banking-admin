<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11 ">
                <div class="card mt-5">
                    <div class="card-header indigo h5"><i class="fas fa-registered    "></i><strong>Registered Accounts</strong> 
                     <button class="btn btn-success float-right"> <i class="fas fa-user-plus    "></i> Add New</button>
                    </div>

                    <div class="card-body">
                      <table class="table table-striped table-bordered table-hover" id="mytable">
                          <thead>
                              <th>#</th>
                              <th>Account Number</th>
                              <th>Account Name</th>
                              <th>Current Balance</th>
                              <th>Status</th>
                              <th>Deposit</th>
                              <th>Withdraw</th>
                          </thead>
                          <tbody>
                              <tr v-for="account in this.accounts" :key="account.id">
                                  <td>{{account.id}}</td>
                                  <td>{{account.AccountNumber}}</td>
                                  <td>{{account.AccountName}}</td>
                                  <td>{{account.CurrentBalance}}</td>
                                  <td v-show="account.Status"><p  class="badge badge-success">Active</p></td>
                                  <td v-show="!account.Status"><p  class="badge badge-danger">Closed</p></td>
                                  <td><button @click.prevent="deposit(account)" class="btn btn-primary"><i class="fas fa-money-bill-wave    "></i> Deposit</button></td>
                                  <td><button @click.prevent="withdraw(account)"  class="btn btn-info"><i class="fas fa-money-check    "></i> Withdraw</button></td>
                              </tr>
                          </tbody>
                          <tfoot>
                              <th>#</th>
                              <th>Account Number</th>
                              <th>Account Name</th>
                              <th>Current Balance</th>
                              <th>Status</th>
                              <th>Deposit</th>
                              <th>Withdraw</th>
                          </tfoot>
                      </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deposit -->
             <!-- Modal -->
<div class="modal fade" id="deposit" tabindex="-1" role="dialog" aria-labelledby="deposit" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 v-show="depositing" class="modal-title" id="exampleModalLongTitle"> <strong class="indigo">Direct Cash Deposit</strong></h5>
         <h5 v-show="!depositing" class="modal-title" id="exampleModalLongTitle"> <strong class="blue">Direct Cash Withdraw</strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="depositing ? directDeposit() : directWithdraw()">
          <div class="modal-body">
         <div class="form-group">
                <div class="input-group mb-3">
                    <input hidden v-model="form.id" type="text" name="id"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('id') }">
                    <has-error :form="form" field="id"></has-error>
                </div>
                
            </div>
            <div class="form-group">
                
                <div class="input-group mb-3">
                    <input hidden v-model="form.AccountNumber" type="text" name="AccountNumber"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('AccountNumber') }">
                    <has-error :form="form" field="AccountNumber"></has-error>
                </div>
                
            </div>
            <div class="form-group">
                <label>Amount</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class=" indigo fab fa-first-order    "></i></span>
                    </div>
                    <input v-model="form.CurrentBal"  type="text" name="CurrentBal"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('CurrentBal') }">
                    <has-error :form="form" field="CurrentBal"></has-error>
                </div>
                
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button v-show="depositing" type="submit" class="btn btn-primary">Deposit</button>
         <button v-show=" !depositing" type="submit" class="btn btn-primary">Withdraw</button>
      </div>
      </form>
    </div>
  </div>
</div>
        <!-- End deposit -->

       
    </div>
</template>

<script>
    export default {

        data(){
            return {
                depositing: false,
                accounts:{},
                form : new Form({
                    id:'',
                    CurrentBalance:"",
                    AccountNumber:"",
                    CurrentBal:"",
                })
            }
        },

        methods:{
            directWithdraw(){
                this.$Progress.start();
                Swal.fire({
                title: 'Are you sure?',
                text: "You want to withdraw KES :"+ this.form.CurrentBal + " account Number" + this.form.AccountNumber,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Withdraw'
                }).then((result) => {
                    this.form.post('api/directwithdraw')
                .then(()=>{
                    Fire.$emit('accountsUpdate')
                    this.$Progress.finish();
                    if (result.value) {
                        Swal.fire(
                        'Trasaction Commited',
                        'Withdraw proccesse successifullly.',
                        'success'
                        )
                        $('#deposit').modal('hide');
                    }
                })
                .catch(()=>{
                    this.$Progress.fail();
                })
                    
                    })
             
            },
            directDeposit(){
                this.$Progress.start();
                Swal.fire({
                title: 'Are you sure?',
                text: "You want to deposit KES :"+ this.form.CurrentBal + " account Number" + this.form.AccountNumber,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Withdraw'
                }).then((result) => {
                     this.form.post('api/directdeposit')
                .then(()=>{
                    Fire.$emit('accountsUpdate')
                    this.$Progress.finish();
                    if (result.value) {
                        Swal.fire(
                        'Trasaction Commited',
                        'Diposit proccessed successifullly.',
                        'success'
                        )
                        $('#deposit').modal('hide');
                    }
                })
                .catch(()=>{
                    this.$Progress.fail();
                })
                    
                    })

            },

            deposit(account){
                this.depositing = true;
                this.form.fill(account);
                $('#deposit').modal('show');
            },
            withdraw(account){
                this.depositing = false;
                this.form.fill(account);
                $('#deposit').modal('show');
            },
            getAccounts(){
                axios.get('api/accounts').then(({data})=>{
                    this.accounts = data;
                });
            }
        },


        created() {
            this.getAccounts();
            Fire.$on('accountsUpdate',()=>{
                this.getAccounts();
            })
        }
    }
</script>
