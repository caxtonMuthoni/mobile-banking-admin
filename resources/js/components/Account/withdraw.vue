<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card mt-5">
                    <div class="card-header indigo"> <i class="fa fa-arrow-circle-down mr-2" aria-hidden="true"></i> <strong>Withdrwal cash</strong></div>

                    <div class="card-body">
                        <form @submit.prevent="Withdraw()" >
                             <div class="form-group">
                                    <label>Amount</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="pink fas fa-sort-amount-down    "></i></span>
                                        </div>
                                        <input v-model="form.Amount"  type="text" name="Amount"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('Amount') }">
                                        <has-error :form="form" field="Amount"></has-error>
                                    </div>
                
                              </div>
                              <div class="form-group">
                                    <label>Phone Number</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class=" pink fas fa-phone-square    "></i></span>
                                        </div>
                                        <input v-model="form.PhoneNumber"  type="text" name="PhoneNumber"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('PhoneNumber') }">
                                        <has-error :form="form" field="PhoneNumber"></has-error>
                                    </div>
                
                              </div>
                              <div class="alert alert-info">
                                  <strong>Click the <b class="pink">Get verification</b> button for authorization process</strong>
                              </div>
                              <button v-show="verified" class="btn btn-success float-right" type="submit">Request Payment</button>
                              
                        </form>
                        <button v-show="!verified" @click="generateOTP()" class="btn btn-primary float-right" >Get Verification code</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
<div class="modal fade" id="OTP" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title indigo" id="exampleModalLongTitle"><i class="fas fa-user-secret   mr-2 "></i><strong>OTP Verification</strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="verifyOTP()" >
          <div class="alert alert-success m-1">
              <strong>A one time Verification code was sent to your phone number.Please enter the code and press the <b class="pink">verify OTP</b> button</strong>
          </div>
      <div class="modal-body">
               <div class="form-group">
                <label>Enter the Security Code.</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="indigo fas fa-lock    "></i></span>
                    </div>
                    <input v-model="formOTP.otp"  type="text" name="otp"
                    class="form-control" :class="{ 'is-invalid': formOTP.errors.has('otp') }">
                    <has-error :form="formOTP" field="otp"></has-error>
                </div>

            </div>
           
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel Transaction</button>
        <button type="submit" class="btn btn-primary">Verify OTP</button>
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
               verified : false,
               formstatu: {},
               form : new Form({
                   Amount:'',
                   PhoneNumber:''
               }),
               formOTP : new Form({
                   otp : ''
               })
           }
        },
        methods:{
            verifyOTP(){
              this.$Progress.start();
                        $('#OTP').modal('show');
                        this.formOTP.post('/api/checkotp').then(({data})=>{
                            this.formstatu = data.status;
                            if(data.status == "true"){
                               this.verified = true;
                               $('#OTP').modal('hide');
                            }
                            else{
                                  Swal.fire({
                                    icon:'error',
                                    title: ' Failed',
                                    text: data.error})
                                    
                            }

                        }).catch(()=>{
                            this.$Progress.fail();
                        })
               },
            generateOTP(){
                  /* this.$Progress.start();
                  axios.post('/api/getotp').then(({data})=>{
                        if(data.status == "true"){ */
                            $('#OTP').modal('show');
                        
                        /* }else{
                            Swal.fire({
                                icon:'error',
                                title: 'An error occured',
                                text: data.error

                            });
                        }
                     
                  }).catch(()=>{
                       Swal.fire({
                            icon:'error',
                            title: 'Oops',
                            text: "There was an error in OTP generation. Try Again later."

                        });
                  }) */
            },
            Withdraw(){
                 this.$Progress.start();  
                this.form.post('/api/withdraw').then(({data})=>{
                if(data.status == "true"){
                    this.verified = false;
                    Swal.fire({
                        icon:'success',
                        title: 'Withdraw processed',
                        text: data.success
                  
                    });
                }
                else{
                    Swal.fire({
                        icon:'error',
                        title: 'Withdraw Failed',
                        text: data.error

                    });
                    this.verified = false;
                }
                this.$Progress.finish();
            }).catch(()=>{
                this.verified = false;
                this.$Progress.fail();
            })
                            
                        
            }
        },
        created() {
            console.log('Component mounted.')
        }
    }
</script>
