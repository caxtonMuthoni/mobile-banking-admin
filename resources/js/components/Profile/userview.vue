<template>
    <!-- Content Wrapper. Contains page content -->
    <div class="container">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Profile</h1>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">

                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img class="profile-user-img img-fluid img-circle" :src="getAvatar()"
                                        alt="User profile picture">
                                </div>

                                <h3 class="profile-username text-center">{{user.FirstName}} {{user.LastName}}</h3>

                                <p class="text-muted text-center">{{user.Occupation}}</p>

                                <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item">
                                        <b>Account Balance</b> <a class="float-right">1,322</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Loans</b> <a class="float-right">543</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Loan Balance</b> <a class="float-right">13,287</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                        <!-- About Me Box -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">About Me</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <strong><i class="fas fa-book mr-1"></i> Education</strong>

                                <p class="text-muted">
                                    {{profile.EducationLevel}}
                                </p>

                                <hr>

                                <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                                <p class="text-muted">{{user.City}}</p>

                                <hr>


                                <strong><i class="far fa-file-alt mr-1"></i>Bio</strong>

                                <p class="text-muted">{{profile.Bio}}</p>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#settings"
                                            data-toggle="tab">Profile</a></li>
                                    <li class="nav-item"><a class="nav-link " href="#timeline"
                                            data-toggle="tab">Loans</a></li>

                                </ul>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">

                                    <!-- /.tab-pane -->
                                    <div class="tab-pane" id="timeline">
                                        <!-- Loans -->
                                          <table class="table table-striped">
                             <thead>
                                 <th>Loan Type</th>
                                 <th>Amount</th>
                                 <th>Paid Amount</th>
                                 <th>Processing</th>
                                 <th>Status</th>
                                 <th></th>
                                 
                             </thead>
                             <tbody>
                                 <tr v-for="loan in loans.data" :key="loan.id">
                                     <td>{{ loan.loanType }}</td>
                                     <td>{{ loan.borrowedAmount }}</td>
                                     <td>{{ loan.paidAmount }}</td>
                                     <td v-show="loan.isProcessed"><span class="badge badge-success badge-sm">processed</span></td>
                                     <td v-show="!loan.isProcessed"><span class="badge badge-danger badge-sm">pending</span></td>
                                     <td v-show="loan.status == 'pending'"><span class="badge badge-primary badge-sm">Pending</span></td>
                                     <td v-show="loan.status == 'complete'"><span class="badge badge-info badge-sm">Complete</span></td>
                                     <td v-show="loan.status == 'active'"><span class="badge badge-success badge-sm">Active</span></td>
                                     <td v-show="loan.status == 'defaulting'"><span class="badge badge-warning badge-sm">Defaulting</span></td>
                                     <td v-show="loan.status == 'defaulted'"><span class="badge badge-danger badge-sm">Defaulted</span></td>
                                     <td><router-link :to="{ name:'loandetail',params:{id: loan.id}}" class="btn btn-sm btn-primary">view</router-link></td>
                                 </tr>
                             </tbody>
                             <tfoot>
                                 <th>Loan Type</th>
                                 <th>Amount</th>
                                 <th>Paid Amount</th>
                                 <th>Processing</th>
                                 <th>Status</th>
                                 <th></th>
                             </tfoot>
                         </table>
                                       
                                    </div>
                                    <!-- /.tab-pane -->

                                    <div class="tab-pane active" id="settings">
                                        <form>
                                            <div class="modal-body row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>FirstName</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i
                                                                        class=" indigo fab fa-first-order    "></i></span>
                                                            </div>
                                                            <input disabled v-model="form.FirstName" type="text"
                                                                name="FirstName" class="form-control"
                                                                :class="{ 'is-invalid': form.errors.has('FirstName') }">
                                                            <has-error :form="form" field="FirstName"></has-error>
                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label>MiddleName</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i
                                                                        class=" indigo fas fa-user-slash    "></i></span>
                                                            </div>
                                                            <input disabled v-model="form.MiddleName" type="text"
                                                                name="MiddleName" class="form-control"
                                                                :class="{ 'is-invalid': form.errors.has('MiddleName') }">
                                                            <has-error :form="form" field="MiddleName"></has-error>
                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label>LastName</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i
                                                                        class=" indigo fab fa-lastfm    "></i></span>
                                                            </div>
                                                            <input disabled v-model="form.LastName" type="text"
                                                                name="LastName" class="form-control"
                                                                :class="{ 'is-invalid': form.errors.has('LastName') }">
                                                            <has-error :form="form" field="LastName"></has-error>
                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label>NationalID</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i
                                                                        class="indigo fas fa-id-badge    "></i></span>
                                                            </div>
                                                            <input disabled v-model="form.NationalID" type="numeric"
                                                                name="NationalID" class="form-control"
                                                                :class="{ 'is-invalid': form.errors.has('NationalID') }">
                                                            <has-error :form="form" field="NationalID"></has-error>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Email address</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i
                                                                        class=" indigo fas fa-envelope    "></i></span>
                                                            </div>
                                                            <input disabled v-model="form.email" type="email"
                                                                name="email" class="form-control"
                                                                :class="{ 'is-invalid': form.errors.has('email') }">
                                                            <has-error :form="form" field="email"></has-error>
                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label>PhoneNumber</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i
                                                                        class="indigo fas fa-blender-phone    "></i></span>
                                                            </div>
                                                            <input disabled v-model="form.PhoneNumber" type="text"
                                                                name="PhoneNumber" class="form-control"
                                                                :class="{ 'is-invalid': form.errors.has('PhoneNumber') }">
                                                            <has-error :form="form" field="PhoneNumber"></has-error>
                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label>City</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i
                                                                        class="indigo fas fa-city    "></i></span>
                                                            </div>
                                                            <input disabled v-model="form.City" type="text" name="City"
                                                                class="form-control"
                                                                :class="{ 'is-invalid': form.errors.has('City') }">
                                                            <has-error :form="form" field="City"></has-error>
                                                        </div>

                                                    </div>

                                                </div>


                                            </div>
                                            <div class="modal-footer">

                                            </div>
                                        </form>

                                        <form @submit.prevent="updateProfile()">
                                            <div class="modal-body row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Annual Income</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i
                                                                        class=" indigo fab fa-first-order    "></i></span>
                                                            </div>
                                                            <input disabled v-model="form2.AnualIncome" type="text"
                                                                name="AnualIncome" class="form-control"
                                                                :class="{ 'is-invalid': form2.errors.has('AnualIncome') }">
                                                            <has-error :form="form2" field="AnualIncome"></has-error>
                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label>Monthly Income</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i
                                                                        class=" indigo fas fa-user-slash    "></i></span>
                                                            </div>
                                                            <input disabled v-model="form2.MonthlyIncome" type="text"
                                                                name="MonthlyIncome" class="form-control"
                                                                :class="{ 'is-invalid': form2.errors.has('MonthlyIncome') }">
                                                            <has-error :form="form2" field="MonthlyIncome"></has-error>
                                                        </div>

                                                    </div>

                                                    <div class="form-group">
                                                        <label>Bio</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i
                                                                        class=" indigo fab fa-lastfm    "></i></span>
                                                            </div>
                                                            <textarea disabled v-model="form2.Bio" type="text" name="Bio"
                                                                rows="8" class="form-control"
                                                                :class="{ 'is-invalid': form2.errors.has('Bio') }"></textarea>
                                                            <has-error :form="form2" field="Bio"></has-error>
                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> Company</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i
                                                                        class="indigo fas fa-id-badge    "></i></span>
                                                            </div>
                                                            <input disabled v-model="form2.Company" type="numeric" name="Company"
                                                                class="form-control"
                                                                :class="{ 'is-invalid': form2.errors.has('Company') }">
                                                            <has-error :form="form2" field="Company"></has-error>
                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label>Employment Status:</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i
                                                                        class=" indigo fas fa-envelope    "></i></span>
                                                            </div>
                                                            <input disabled v-model="form2.EmploymentStatus"
                                                                name="EmploymentStatus" class="form-control"
                                                                :class="{ 'is-invalid': form2.errors.has('EmploymentStatus') }">
                                                            <has-error :form="form2" field="EmploymentStatus">
                                                            </has-error>
                                                        </div>

                                                    </div>

                                                    <div class="form-group">
                                                        <label>Occupation</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i
                                                                        class=" indigo fab fa-lastfm    "></i></span>
                                                            </div>
                                                            <input disabled v-model="form2.Occupation" type="text"
                                                                name="Occupation" class="form-control"
                                                                :class="{ 'is-invalid': form2.errors.has('Occupation') }">
                                                            <has-error :form="form2" field="Occupation"></has-error>
                                                        </div>

                                                    </div>

                                                    <div class="form-group">
                                                        <label>Education Level</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i
                                                                        class=" indigo fab fa-lastfm    "></i></span>
                                                            </div>
                                                            <input disabled v-model="form2.EducationLevel" type="text"
                                                                name="EducationLevel" class="form-control"
                                                                :class="{ 'is-invalid': form2.errors.has('EducationLevel') }">
                                                            <has-error :form="form2" field="EducationLevel"></has-error>
                                                        </div>

                                                    </div>
                                                    

                                                </div>


                                            </div>
                                            
                                        </form>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div><!-- /.card-body -->
                        </div>
                        <!-- /.nav-tabs-custom -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
</template>

<script>
    export default {
        data() {
            return {
                user: {},
                profile: {},
                loans: {},
                form: new Form({
                    FirstName: '',
                    MiddleName: '',
                    LastName: '',
                    NationalID: '',
                    email: '',
                    PhoneNumber: '',
                    City: '',
                    password: '',
                }),

                form2: new Form({
                    Avatar: '',
                    EmploymentStatus: '',
                    Company: '',
                    Occupation: '',
                    AnualIncome: '',
                    MonthlyIncome: '',
                    EducationLevel: '',
                    Bio: '',
                })
            }
        },
        methods: {
            getAvatar(){
              return "/images/avatar/"+this.form2.Avatar;
            },
            loadLoan(id) {
                axios.get(`/api/userloans/${id}`).then(({ data }) => {
                    this.loans = data;
                })
            },
            avatarProcess(e) {
                let file = e.target.files[0];
                this.$Progress.start();
                if (file['size'] < 2500000) {
                    let reader = new FileReader();
                    reader.onloadend = (file) => {

                        this.form2.Avatar = reader.result
                    }
                    reader.readAsDataURL(file)
                } else {
                    this.$$Progress.fail()
                    Swal.fire({
                        icon: 'error',
                        title: "Oops",
                        text: "The file you uploaded is large than 2mb"
                    })
                }


            },
            updateProfile() {
                this.form2.post('/api/saveprofile').then(() => {
                    Fire.$emit('profileUpdate')
                    Swal.fire({
                        icon: "success",
                        title: "Profile Updated successfully ",
                        text: "Thanks for choosing us"
                    })
                    this.$Progress.finish()
                })

            },

            loadUser(id) {
                axios.get(`/api/user/${id}`).then(({ data }) => {
                    this.user = data
                    this.form.fill(this.user)
                })
            },
            loadProfile(id) {
                axios.get(`/api/show/${id}`).then(({ data }) => {
                    this.profile = data
                    this.form2.fill(this.profile)
                })
            },


        },

        created() {
            this.loadUser(this.$route.params.id);
            this.loadProfile(this.$route.params.id);
            this.loadLoan(this.$route.params.id);
            Fire.$on('profileUpdate', () => {
                this.loadProfile()
            })
        }
    }
</script>