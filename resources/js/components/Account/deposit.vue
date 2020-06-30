<template>
    <div class="container mt-4 col-md-9">
        <div class="alert alert-info">
            Please select an account you wish to deposit.
        </div>
        
 <ul class="nav nav-tabs paymenttab bg-primary " id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link  active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
      aria-selected="true"><i class="fas fa-money-bill  mr-2  "></i><strong>Top Up My Account</strong></a>
  </li>
  <li class="nav-item">
    <a class="nav-link " id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
      aria-selected="false"><i class="fas fa-money-check mr-2   "></i><strong>Top Up Services Wallet</strong></a>
  </li>
  
  <li class="nav-item">
    <a class="nav-link " id="contact-tab" data-toggle="tab" href="#claim" role="tab" aria-controls="contact"
      aria-selected="false"><i class="fab fa-servicestack   mr-2 "></i><strong>Claim MPESA payment</strong></a>
  </li>
</ul>
<div class="tab-content border " id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-5">
                    <div class="card-header"><strong class="green">Deposit Via Mpesa</strong></div>

                    <div class="card-body">
                        <form @submit.prevent="getStkPush()">
                             <div class="form-group">
                                    <label>Amount</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class=" indigo fab fa-first-order    "></i></span>
                                        </div>
                                        <input v-model="form.Amount"  type="text" name="Amount"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('Amount') }">
                                        <has-error :form="form" field="Amount"></has-error>
                                    </div>
                
                              </div>
                              <div class="alert alert-info">
                                  <strong>Check in your phone and key in your mpesa pin to deposit</strong>
                              </div>
                              <button class="btn btn-primary float-right" type="submit">Request Payment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
  </div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
      <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-5">
                    <div class="card-header"><strong class="green">Top Up Services Wallet</strong></div>

                    <div class="card-body">
                        <form @submit.prevent="topUpPaymentWallet()">
                             <div class="form-group">
                                    <label>Amount</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class=" indigo fab fa-first-order    "></i></span>
                                        </div>
                                        <input v-model="formWallet.Amount"  type="text" name="Amount"
                                        class="form-control" :class="{ 'is-invalid': formWallet.errors.has('Amount') }">
                                        <has-error :form="formWallet" field="Amount"></has-error>
                                    </div>
                
                              </div>
                              <div class="alert alert-info">
                                  <strong>Funds will be tranfered to the systems Services Account</strong>
                              </div>
                              <button class="btn btn-primary float-right" type="submit">Transfer Cash</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
  </div>
 

  <div class="tab-pane fade" id="claim" role="tabpanel" aria-labelledby="profile-tab">
      <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-5">
                    <div class="card-header"><strong class="green">Claim Mpesa Payment</strong></div>

                    <div class="card-body">
                        <form @submit.prevent="claimPayment">
                             <div class="form-group">
                                    <label>Enter the MPESA Transaction ID</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-code    "></i></span>
                                        </div>
                                        <input v-model="formclaim.MPESATransactionID"  type="text" name="MPESATransactionID"
                                        class="form-control" :class="{ 'is-invalid': formclaim.errors.has('MPESATransactionID') }">
                                        <has-error :form="formclaim" field="MPESATransactionID"></has-error>
                                    </div>
                
                              </div>
                              <div class="alert alert-info">
                                  <strong>Complete Transaction by providing your MPESA transaction ID</strong>
                              </div>
                              <button class="btn btn-primary float-right" type="submit">Confrim Transaction</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="claimMpesaPayment" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title indigo" id="exampleModalLongTitle"> <i class="fas fa-check-square   green mr-2 h3 "></i><Strong>Confirm Transaction </Strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="claimPayment">
      <div class="modal-body">
                        
                             <div class="form-group">
                                    <label class="teal">Enter the MPESA Transaction ID</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-code    "></i></span>
                                        </div>
                                        <input v-model="formclaim.MPESATransactionID"  type="text" name="MPESATransactionID"
                                        class="form-control" :class="{ 'is-invalid': formclaim.errors.has('MPESATransactionID') }">
                                        <has-error :form="formclaim" field="MPESATransactionID"></has-error>
                                    </div>
                
                              </div>
                              
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Confrim Payment</button>
      </div>
    </form>   
    </div>
  </div>
</div>
 </div>
</template>

<script>
    export default {
        data(){
            return{
                depositStatus:'',
                user:{},
                claim :{},
                form : new Form({
                    Amount:'',
                    PhoneNumber:'',
                }),
                formclaim : new Form({
                    MPESATransactionID : '',
                }),
                formWallet : new Form({
                    Amount:"",
                })
            }
        },
        methods:{
            topUpPaymentWallet(){
              this.$Progress.start();
              this.formWallet.post('api/topupsap').then(({data})=>{
                 /*  $('#claimMpesaPayment').modal('show'); */
                  this.$Progress.finish();
                  if(data.status){
                      Swal.fire({
                          icon: 'success',
                          title: 'Transaction Accepted',
                          text:   data.response.TransactionStatus
                      })
                  }else{
                       Swal.fire({
                          icon: 'error',
                          title: 'Transaction Failed',
                          text:   data.response
                      })
                  }

              })
            },
            claimPayment(){
                this.$Progress.start();
                this.formclaim.post('/api/deposit').then(({data})=>{
                    this.depositStatus = data
                    if(data.status == 'false'){
                         Swal.fire({
                        icon :'error',
                        title :'Request failed.',
                        text : data.error,
                        });
                         this.$Progress.fail();
                    }else{
                          Swal.fire({
                            icon :'success',
                            title :'Deposit proccessed',
                            text : data.success,
                        });
                         this.$Progress.finish();
                    }
                    

                }).catch(()=>{
                     this.$Progress.fail();
                    Toast.fire({
                        icon :'error',
                        title :'Request failed.'
                    })
                })

            },
            getStkPush(){
             this.$Progress.start();
             this.form.post('api/stkpush').then(()=>{
                  $('#claimMpesaPayment').modal('show');
                  this.$Progress.finish();
                 Toast.fire({
                     icon:'success',
                     title:'STK Push Sent Successifully',
                 })
             })
            },
            loadUser(){
                axios.get('/api/user').then(({ data }) => {
                    this.user = data
                    this.form.fill(this.user)
                })
            }

        },
        created() {
            this.loadUser();
           
        }
    }
</script>
