<template>
    <div class="container">
        <div class="card text-left">
          <div class="card-body">
            <h4 class="card-title indigo"><i class="fas mr-2 fa-chart-pie    "></i><strong>Loan Types</strong></h4>
             <button @click.prevent="showModal(false)" class="btn btn-sm float-right btn-success"><i class="fa fa-plus mr-2" aria-hidden="true"></i>Add New</button>
              <div class="card-body">
                  <table class="table table-striped">
                      <thead>
                          <th>#</th>
                          <th>Type</th>
                          <th>Max Interest</th>
                          <th>Max Duration</th>
                          <th>Max Amount</th>
                          <th>Update</th>
                          <th>Delete</th>
                      </thead>
                      <tbody>
                          <tr v-for="loantype in loantypes" :key="loantype.id">
                              <td>{{loantype.id}}</td>
                              <td>{{loantype.type}}</td>
                              <td>{{loantype.interest | formatMoney}} %</td>
                              <td>{{loantype.maxPeriod }} months</td>
                              <td>{{loantype.maxAmount | formatMoney}}</td>
                              <td><a href=""  @click.prevent="showModal(true,loantype)" class="btn btn-sm btn-primary"><i class="fas fa-edit    "></i></a></td>
                              <td><a href=""  @click.prevent="deleteLoanType(loantype.id)" class="btn btn-danger btn-sm"><i class="fas fa-trash    "></i></a></td>
                          </tr>
                      </tbody>
                      <tfoot>
                          <th>#</th>
                          <th>Type</th>
                          <th>Max Interest</th>
                          <th>Max Duration</th>
                          <th>Max Amount</th>
                          <th>Update</th>
                          <th>Delete</th>
                      </tfoot>
                  </table>
              </div>
          </div>
        </div>
       
       
       <!-- Modal -->
       <div class="modal fade" id="loan-type-modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
           <div class="modal-dialog modal-lg" role="document">
               <div class="modal-content">
                       <div class="modal-header">
                               <h5 v-show="!isUpdating" class="modal-title indigo"> <i class="fa fa-plus" aria-hidden="true"></i> <strong>Add Loan Type</strong></h5>
                               <h5 v-show="isUpdating" class="modal-title indigo"> <i class="fas fa-edit    "></i> <strong>Update Loan Type</strong></h5>
                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                       <span aria-hidden="true">&times;</span>
                                   </button>
                           </div>
                   <form @submit.prevent="isUpdating ? updateLoanType() : addLoanType()">
                       <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-6">
                                               <div class="form-group">
                                                <label>Loan type</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class=" indigo fab fa-first-order    "></i></span>
                                                    </div>
                                                    <input v-model="form.type"  type="text" name="type"
                                                    class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                                                    <has-error :form="form" field="type"></has-error>
                                                </div>

                                                <div class="form-group">
                                                <label>Interest</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class=" indigo fab fa-first-order    "></i></span>
                                                    </div>
                                                    <input v-model="form.interest"  type="text" name="interest"
                                                    class="form-control" :class="{ 'is-invalid': form.errors.has('interest') }">
                                                    <has-error :form="form" field="interest"></has-error>
                                                </div>
                                                </div>

                                                
                            
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                                <label>Max Amount</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class=" indigo fab fa-first-order    "></i></span>
                                                    </div>
                                                    <input v-model="form.max_amount"  type="text" name="max_amount"
                                                    class="form-control" :class="{ 'is-invalid': form.errors.has('max_amount') }">
                                                    <has-error :form="form" field="max_amount"></has-error>
                                            </div>
                                        </div>

                                            <div class="form-group">
                                                <label>Max period</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class=" indigo fab fa-first-order    "></i></span>
                                                    </div>
                                                    <input v-model="form.max_period"  type="text" name="max_period"
                                                    class="form-control" :class="{ 'is-invalid': form.errors.has('max_period') }">
                                                    <has-error :form="form" field="max_period"></has-error>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="!isUpdating" type="submit" class="btn btn-primary">Save</button>
                            <button v-show="isUpdating" type="submit" class="btn btn-primary">Update</button>
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
            loantypes : {},
            isUpdating : false,
            form : new Form({
                id : '',
                type: "",
                interest: "",
                max_amount: "",
                max_period: "",
            })
        }
    },

    methods: {

        deleteLoanType(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to reverse this process.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete it'
                }).then((result) => {
                     axios.get(`api/deleteloantype/${id}`)
                .then((data)=>{
                    Fire.$emit('loantypeUpdate')
                    this.$Progress.finish();
                    if (result.value) {
                        if(data.data.status){
                            Swal.fire(
                            'Success',
                            data.data.message,
                            'success'
                            );
                        }else{
                            Swal.fire({
                            title:'Deletion Error',
                            text: data.data.message,
                            icon: 'error'}
                            );
                       
                    }
                    }
                })
                .catch(()=>{
                    this.$Progress.fail();
                })
                    
                    })
             
        },

        updateLoanType(){
           this.$Progress.start();
           let id = this.form.id;
           this.form.post(`api/updateloantype/${id}`).then((data)=>{
               Toast.fire({
                        icon :'success',
                        title : data.data.message
                    });

               Fire.$emit('loantypeUpdate')
               this.$Progress.finish()

               this.form.reset();

               $('#loan-type-modal').modal('hide');

           }).catch((e)=>{
               this.$Progress.fail();
           })

        },

        addLoanType(){
           this.$Progress.start();
           this.form.post('api/addloantype').then((data)=>{
               Toast.fire({
                        icon :'success',
                        title : data.data.message
                    });

               Fire.$emit('loantypeUpdate')
               this.$Progress.finish()

               this.form.reset();

               $('#loan-type-modal').modal('hide');

           }).catch((e)=>{
               this.$Progress.fail();
           })
        },

        showModal(isUpdating,type){
            if(isUpdating){
                this.isUpdating = true;
                this.form.fill(type);
                this.form.max_amount = type.maxAmount;
                this.form.max_period = type.maxPeriod;
            }else{
                this.isUpdating = false;
            }
            $('#loan-type-modal').modal('show');
        },


        getLoanTypes(){
            axios.get('api/loantypes').then((data)=>{
                this.loantypes = data.data;
            }).catch((e)=>{
               console.log(e);
            })
        }
    },

    created(){
        this.getLoanTypes();
        Fire.$on('loantypeUpdate',()=>{
            this.getLoanTypes();
        })
    }
}
</script>