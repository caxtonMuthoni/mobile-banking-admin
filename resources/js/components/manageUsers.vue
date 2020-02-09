<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11 ">
                <div class="card mt-5">
                    <div class="card-header indigo h5"><i class="fas fa-user-edit  fa-fw  "></i> <strong>Users Management</strong> 
                     <button class="btn btn-success float-right" @click="newModal"> <i class="fas fa-user-plus    "></i> Add New</button>
                    </div>

                    <div class="card-body">
                      <table class="table table-striped table-bordered table-hover" id="mytable">
                          <thead>
                              <th>#</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Phone</th>
                              <th>National Id</th>
                              <th>Created AT</th>
                              <th>Update</th>
                          </thead>
                          <tbody>
                              <tr v-for="user in this.users" :key="user.id">
                                  <td>{{user.id}}</td>
                                  <td>{{user.FirstName | upText}} {{user.LastName | upText}}</td>
                                  <td>{{user.email}}</td>
                                  <td>{{user.PhoneNumber}}</td>
                                  <td>{{user.NationalID}}</td>
                                  <td>{{user.created_at | upDate}}</td>
                                  <td>
                                      <a href="#" @click="editModal(user)" class="btn btn-primary"><i class="fas fa-user-edit    "></i></a>
                                      <a href="#" @click="deleteUser(user.id)" class="btn btn-danger" ><i class="fas fa-trash-alt    "></i></a>
                                      </td>
                              </tr>
                          </tbody>
                          <tfoot>
                               <th>#</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Phone</th>
                              <th>National Id</th>
                               <th>Created AT</th>
                              <th>Update</th>
                          </tfoot>
                      </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 v-show="!editMode" class="modal-title pink" id="exampleModalLongTitle"><i class="fas fa-user-plus mr-2" ></i> <strong>Add new user</strong></h5>
        <h5 v-show="editMode" class="modal-title blue" id="exampleModalLongTitle"><i class="fas fa-user-edit mr-2" ></i> <strong>Update user's info</strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="editMode ? updateUser() : createUser()">
      <div class="modal-body row">
          <div class="col-md-6">
               <div class="form-group">
                <label>FirstName</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class=" indigo fab fa-first-order    "></i></span>
                    </div>
                    <input v-model="form.FirstName" type="text" name="FirstName"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('FirstName') }">
                    <has-error :form="form" field="FirstName"></has-error>
                </div>
                
            </div>
             <div class="form-group">
                <label>MiddleName</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class=" indigo fas fa-user-slash    "></i></span>
                    </div>
                    <input v-model="form.MiddleName" type="text" name="MiddleName"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('MiddleName') }">
                    <has-error :form="form" field="MiddleName"></has-error>
                </div>
                
            </div>
             <div class="form-group">
                <label>LastName</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class=" indigo fab fa-lastfm    "></i></span>
                    </div>
                    <input v-model="form.LastName" type="text" name="LastName"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('LastName') }">
                    <has-error :form="form" field="LastName"></has-error>
                </div>
                
            </div>
             <div class="form-group">
                <label>NationalID</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="indigo fas fa-id-badge    "></i></span>
                    </div>
                    <input v-model="form.NationalID" type="numeric" name="NationalID"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('NationalID') }">
                    <has-error :form="form" field="NationalID"></has-error>
                </div>
                
            </div>
          </div>
            
            <div class="col-md-6">
                 <div class="form-group">
                <label>Email address</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class=" indigo fas fa-envelope    "></i></span>
                    </div>
                    <input v-model="form.email" type="email" name="email"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                    <has-error :form="form" field="email"></has-error>
                </div>
                
            </div>
             <div class="form-group">
                <label>PhoneNumber</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="indigo fas fa-blender-phone    "></i></span>
                    </div>
                    <input v-model="form.PhoneNumber" type="text" name="PhoneNumber"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('PhoneNumber') }">
                    <has-error :form="form" field="PhoneNumber"></has-error>
                </div>
                
            </div>
             <div class="form-group">
                <label>City</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="indigo fas fa-city    "></i></span>
                    </div>
                    <input v-model="form.City" type="text" name="City"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('City') }">
                    <has-error :form="form" field="City"></has-error>
                </div>
                
            </div>
             <div class="form-group">
                <label>password</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class=" indigo fas fa-lock    "></i></span>
                    </div>
                    <input v-model="form.password" type="password" name="password"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                    <has-error :form="form" field="password"></has-error>
                </div>
                
            </div>
            </div>
            
             
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="submit" v-show="!editMode" class="btn btn-success">Add User</button>
         <button type="submit" v-show="editMode" class="btn btn-primary">Update User</button>
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
            return {
                editUserId:'',
                editMode: true,
                users : {},
                form : new Form({
                    FirstName : '',
                    MiddleName : '',
                    LastName : '',
                    NationalID : '',
                    email : '',
                    PhoneNumber : '',
                    City : '',
                    password : '',
                })
            }
        },
        methods:{

            newModal(){
                this.editMode = false;
               $('#exampleModalCenter').modal('show');
               this.form.reset();
            },

            editModal(user){
                this.editMode = true;
                this.editUserId = user.id;
                 $('#exampleModalCenter').modal('show');
               this.form.fill(user);
            },

            deleteUser(id){
                Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                this.form.delete("/api/delete/"+id).then(()=>{
                    Fire.$emit('AfterCreate')
                    if (result.value) {
                    Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                    )
                }
                })
                })
            },

            loadUsers(){
                this.$Progress.start()
               
              axios.get('api/getusers').then(({data})=>(this.users = data.data));
              this.$Progress.finish()
            },

            createUser(){
                this.form.post('api/auth/signup').then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#exampleModalCenter').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: 'User added successfully'
                        })
                });
                 
            },
            updateUser(){
                this.$Progress.start();
               this.form.post("/api/updateuser/"+this.editUserId).then(()=>{
                    Fire.$emit('AfterCreate');
                    $('#exampleModalCenter').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: 'User\'s info updated successfully'
                        })
                        this.$Progress.finish();
               }).catch(
                   this.$Progress.fail()
               );
            }

        },
        created() {
            this.loadUsers()
            //setInterval(()=>this.loadUsers(),5000);
            Fire.$on('AfterCreate',()=>{
                this.loadUsers()
            })
        }
    }
</script>
