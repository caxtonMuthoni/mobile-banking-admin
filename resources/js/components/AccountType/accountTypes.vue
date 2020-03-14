<template>
    <div class="container">
        <div class="card mt-4">
          <img class="card-img-top" src="holder.js/100px180/" alt="">
          <div class="card-body">
            <h4 class="card-title indigo"> <i class="fas fa-cog    "></i> <strong>Registered Account types</strong></h4>
             <button class="btn btn-success btn-sm float-right" @click="addAccount"><i class="fa fa-plus" aria-hidden="true"></i>add account type</button>
          </div>
          <table class="table-striped table bordered">
              <thead>
                  <th>#</th>
                  <th>Account Type</th>
                  <th>Code</th>
                  <th>Processing Fee</th>
                  <th>Created At</th>
                  <th>Action</th>
              </thead>
              <tbody>
                  <tr v-for="accountType in accountTypes" :key="accountType.id">
                      <td>{{accountType.id}}</td>
                      <td>{{accountType.Name}}</td>
                      <td>{{accountType.Code}}</td>
                      <td>{{accountType.Fee}}</td>
                      <td>{{accountType.created_at | upDate}}</td>
                      <td><a href="#" @click="updateAccountType(accountType)" class="btn btn-primary btn-sm"><i class="fas fa-edit    "></i></a></td>
                  </tr>
              </tbody>
              <tfoot>
                  <th>#</th>
                  <th>Account Type</th>
                  <th>Code</th>
                  <th>Processing Fee</th>
                  <th>Created At</th>
                  <th>Action</th>
              </tfoot>
          </table>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="accountType" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!editing" class="modal-title indigo"><i class="fa fa-plus-square mr-2" aria-hidden="true"></i><strong>Add Account type</strong></h5>
                         <h5 v-show="editing" class="modal-title indigo"><i class="fas mr-2 fa-edit    "></i><strong>Update Account type</strong></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <form @submit.prevent="editing ? postUpdate()  : postAccoutnType()">
                        <div class="modal-body">
                       <div class="form-group">
                                    <label>Account type</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class=" indigo fab fa-the-red-yeti    "></i></span>
                                        </div>
                                        <input v-model="form.Name"  type="text" name="Name"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('Name') }">
                                        <has-error :form="form" field="Name"></has-error>
                                    </div>
                                    
                                </div>
                                 <div class="form-group">
                                    <label>Account Code</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-code indigo" aria-hidden="true"></i></span>
                                        </div>
                                        <input v-model="form.Code"  type="text" Code="Code"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('Code') }">
                                        <has-error :form="form" field="Code"></has-error>
                                    </div>
                                    
                                </div>
                                 <div class="form-group">
                                    <label>Processing Fee</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-money-bill indigo   "></i></span>
                                        </div>
                                        <input v-model="form.Fee"  type="text" Fee="Fee"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('Fee') }">
                                        <has-error :form="form" field="Fee"></has-error>
                                    </div>
                                    
                                </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" v-show="!editing" class="btn btn-primary">add account</button>
                         <button type="submit" v-show="editing" class="btn btn-primary">update account</button>
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
                editing : false,
                accountTypes : {},
                form : new Form({
                    id:'',
                    Name:'',
                    Code:'',
                    Fee: ''
                }),
            };
        },
        methods:{
            addAccount(){
                this.editing = false;
                this.form.reset();
               $('#accountType').modal('show');
            },
            updateAccountType(accountType){
               this.editing = true;
               this.form.fill(accountType);
               $('#accountType').modal('show');
            },
            postUpdate(){
               this.form.post('api/updateaccounttypes/'+ this.form.id).then((data)=>{
                    this.$emit('accounttypecreated')
                    this.$Progress.finish();
                    if (data.data.status=="true") {
                            Swal.fire(
                            'Success',
                            data.data.success,
                            'success'
                            );
                        }
                        $('#accountType').modal('hide');
                })
            },
            postAccoutnType(){
             this.$Progress.start()
             this.form.post('api/postaccounttypes').then((data)=>{
                 this.$emit('accounttypecreated')
                 this.$Progress.finish();
                 this.form.clear();
                 if (data.data.status=="true") {
                        Swal.fire(
                        'Success',
                        data.data.success,
                        'success'
                        );
                    }
                     $('#accountType').modal('hide');
             }).catch(()=>{

             });
            },
            fetchAccountTypes(){
                this.$Progress.start();
              axios.get('api/accounttypes').then((data)=>{
                  this.$Progress.finish();
                  this.accountTypes = data.data;
              }).catch(()=>{
                  this.$Progress.fail();
              });
            }
        },
        created() {
            this.fetchAccountTypes();
            this.$on('accounttypecreated',()=>{
                 this.fetchAccountTypes();
            })
        }
    }
</script>
