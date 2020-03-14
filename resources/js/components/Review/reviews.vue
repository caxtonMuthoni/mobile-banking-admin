<template>
    <div class="container">
        <div class="card mt-5">
          <div class="card-body">
            <h4 class="card-title indigo"><i class=" mr-2 fas fa-comment    "></i><strong>Reviews</strong></h4> <br><br>
            <div class="dropdown-divider col-md-12"></div>
            <table class="table table-stripped table-bordered">
                <thead>
                    <th>#</th>
                    <th>Name</th>
                    <th>Title</th>
                    <th>Review</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <tr v-for="review in reviews" :key="review.id">
                        <td>{{review.id}}</td>
                        <td>{{getUser(review.userId)}}</td>
                        <td>{{review.title}}</td>
                        <td>{{review.review}}</td>
                        <td v-show="review.status"><p class="text-success">Verified</p></td>
                         <td v-show="!review.status"><span class="text-danger">pending</span></td>
                        <td >
                            <form @submit.prevent="verifyReview(review.id)">
                                <input v-model="form.id" type="text" name="id" hidden>
                                <button v-show="!review.status" type="submit" class="btn btn-success btn-sm">Verify</button>
                                <button v-show="review.status" class="btn btn-warning btn-sm">Unverify</button>
                            </form>
                        </td>
                         
                    </tr>
                </tbody>
                <tfoot>
                    <th>#</th>
                    <th>Name</th>
                    <th>Title</th>
                    <th>Review</th>
                    <th>Status</th>
                    <th>Action</th>
                </tfoot>
            </table>
          </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                reviews:{},
                name:'',
                form : new Form({
                    id: ''
                }),
            }
        },
        methods:{
            verifyReview(id){
                this.form.id = id;
                this.form.post('api/updatereview').then((data)=>{
                  this.$emit('reviewupdated');
                  Toast.fire({
                        icon :'success',
                        title : data.data.success
                    });
                }).catch(()=>{

                })
            },
            getUser(id){
             axios.get('api/user/'+id).then((data)=>{
                     return data.data.FirstName;
                 })
            },
            getReviews(){
                axios.get('api/all').then((data)=>{
                    this.reviews = data.data;
                }).catch(()=>{

                });
            }

        },
        created() {
            this.getReviews();
            this.$on('reviewupdated',()=>{
                this.getReviews();
            })
        }
    }
</script>
