<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card mt-5">
                    <div class="card-header"><strong class="indigo"><i class="fas fa-user-friends    "></i>Registered Users</strong></div>

                    <div class="card-body">
                        <table class="table table-striped table-bordered table-hover" id="mytable">
                          <thead>
                              <th>#</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Phone</th>
                              <th>National Id</th>
                              <th>Created AT</th>
                              
                          </thead>
                          <tbody>
                              <tr v-for="user in this.users" :key="user.id">
                                  <td>{{user.id}}</td>
                                  <td>{{user.FirstName | upText}} {{user.LastName | upText}}</td>
                                  <td>{{user.email}}</td>
                                  <td>{{user.PhoneNumber}}</td>
                                  <td>{{user.NationalID}}</td>
                                  <td>{{user.created_at | upDate}}</td>
            
                              </tr>
                          </tbody>
                          <tfoot>
                               <th>#</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Phone</th>
                              <th>National Id</th>
                               <th>Created AT</th>
                              
                          </tfoot>
                      </table>
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
                 users:''
             }
        },
        methods:{
            loadUsers(){
                this.$Progress.start()
               
              axios.get('api/getusers').then(({data})=>(this.users = data.data));
              this.$Progress.finish()
            },

        },
        created() {
            this.loadUsers()
        }
    }
</script>
