<template>
    <div class="container">
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
                                  <small>Check in your phone and key in your mpesa pin to deposit</small>
                              </div>
                              <button class="btn btn-primary float-right" type="submit">Request Payment</button>
                        </form>
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
                user:{},
                form : new Form({
                    Amount:'',
                    PhoneNumber:'',
                })
            }
        },
        methods:{
            getStkPush(){
             this.$Progress.start();
             this.form.post('api/stkpush').then(()=>{
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
