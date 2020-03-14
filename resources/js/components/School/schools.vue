<template>
    <div class="container">
        <div class="card mt-4">
          <img class="card-img-top" src="holder.js/100px180/" alt="">
          <div class="card-body">
            <h4 class="card-title indigo"><i class="fas fa-school    mr-2"></i> Schools</h4>
             <button class="btn btn-success btn-sm float-right" @click.prevent="addScool"><i class="fa fa-plus mr-2" aria-hidden="true"></i> Add School</button>
             <table class="table table-striped table-bordered">
                 <thead>
                     <th>#</th>
                     <th>School</th>
                     <th>PayBill</th>
                     <th>Location</th>
                     <th>Created At</th>
                     <th>Action</th>
                 </thead>
                 <tbody>
                     <tr v-for="school in schools" :key="school.id">
                         <td>{{school.id}}</td>
                         <td>{{school.Name}}</td>
                         <td>{{school.Paybill}}</td>
                         <td>{{school.Location}}</td>
                         <td>{{school.created_at | upDate}}</td>
                         <td><a href="#" @click="editSchool(school)" class="btn btn-primary btn-sm"><i class="fas fa-edit    "></i></a></td>
                     </tr>
                 </tbody>
                 <tfoot>
                     <th>#</th>
                     <th>School</th>
                     <th>PayBill</th>
                     <th>Location</th>
                     <th>Created At</th>
                     <th>Action</th>
                 </tfoot>
             </table>
          </div>
        </div>
        <!-- Add Modal -->
          
          <!-- Modal -->
          <div class="modal fade" id="addSchool" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 v-show="!schoolEdit" class="modal-title indigo"><i class="fas mr-2 fa-calendar-plus    "></i><strong>School Registration</strong></h5>
                           <h5 v-show="schoolEdit" class="modal-title indigo"><i class="mr-2 fas fa-edit    "></i><strong>School Update</strong></h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                      </div>
                      <form @submit.prevent="schoolEdit ? updateSchool() : postSchool()">
                          <div class="modal-body">
                              <div class="form-group">
                                    <label>School Name</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="indigo fas fa-school    "></i></span>
                                        </div>
                                        <input v-model="form.Name"  type="text" name="Name"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('Name') }">
                                        <has-error :form="form" field="Name"></has-error>
                                    </div>
                                    
                                </div>
                                <div class="form-group">
                                    <label>PayBill NO.</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="indigo fas fa-money-bill    "></i></span>
                                        </div>
                                        <input v-model="form.Paybill"  type="text" name="Paybill"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('Paybill') }">
                                        <has-error :form="form" field="Paybill"></has-error>
                                    </div>
                                    
                                </div>
                                <div class="form-group">
                                    <label>Location</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="indigo fa fa-location-arrow" aria-hidden="true"></i></span>
                                        </div>
                                        <input v-model="form.Location"  type="text" name="Location"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('Location') }">
                                        <has-error :form="form" field="Location"></has-error>
                                    </div>
                                    
                                </div>
                          </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          <button type="submit" v-show="!schoolEdit" class="btn btn-primary">Save School</button>
                          <button type="submit" v-show="schoolEdit" class="btn btn-primary">Update School</button>
                      </div>
                      </form>
                  </div>
              </div>
          </div>
        <!-- End Modal Add -->

       
    </div>
</template>

<script>
    export default {
        data(){
            return{
                num: 0,
                schoolEdit : false,
                schools : {},
                form : new Form({
                    id:'',
                    Name : '',
                    Paybill:'',
                    Location:'',
                })
            }
        },
        methods:{
            addScool(){
               this.schoolEdit = false;
               this.form.reset();
               $('#addSchool').modal('show');
            },
            editSchool(school){
             this.schoolEdit = true;
             this.form.fill(school)
             $('#addSchool').modal('show');
            },
            updateSchool(){
             this.$Progress.start();
             this.form.post('api/editschool/'+this.form.id).then((data)=>{
                 this.$Progress.finish();
                 this.$emit('schoolAdded')
                 $('#addSchool').modal('hide');
                 if (data.data.status=="true") {
                        Swal.fire(
                        'Success',
                        data.data.success,
                        'success'
                        )
                    }

             }).catch(()=>{

             });
            },
            postSchool(){
             this.$Progress.start();
             this.form.post('api/addschool').then((data)=>{
                 this.$Progress.finish();
                 this.$emit('schoolAdded')
                 $('#addSchool').modal('hide');
                 if (data.data.status=="true") {
                        Swal.fire(
                        'Success',
                        data.data.success,
                        'success'
                        )
                    }

             }).catch(()=>{

             });
            },
            fetchSchools(){
                axios.get('api/schools').then((data)=>{
                  this.schools = data.data;
                }).catch(()=>{

                });
            }
        },
        created() {
            this.fetchSchools();
            this.$on('schoolAdded',()=>{
                this.fetchSchools();
            });
        }
    }
</script>
