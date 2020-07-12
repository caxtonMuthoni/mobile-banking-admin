<template>
    <div class="container">
        <div class="card text-left">
          <div class="card-body">
            <h4 class="card-title pink"> <i class="fas fa-edit    "></i><strong>Customer Investments</strong></h4>
             <div class="card-body">
                 <table class="table table-striped">
                     <thead>
                         <th>#</th>
                         <th>Customer</th>
                         <th>Amount</th>
                         <th>Duration</th>
                         <th>Termination Date</th>
                         <th>Total Pay</th>
                         <th>Created at</th>
                         <th>Status</th>
                         <th>Action</th>
                     </thead>
                     <tbody>
                         <tr v-for="investment in investments" :key="investment.id">
                             <td>{{investment.id}}</td>
                             <td>{{investment.user}}</td>
                             <td>{{investment.amount | formatMoney}}</td>
                             <td>{{investment.duration}} months</td>
                             <td>{{investment.termination_date | upDate}}</td>
                             <td>{{investment.total_pay | formatMoney}}</td>
                             <td>{{investment.created_at | upDate}}</td>
                             <td>
                                 <a href="" v-show="investment.status" class="btn btn-success btn-sm">active</a>
                                 <a href="" v-show="!investment.status" class="btn btn-danger btn-sm">closed</a>
                             </td>
                             <td>
                                 <a v-show="investment.status" href="" @click.prevent="terminateInvestment(investment.id)" class="btn btn-primary btn-sm">terminate</a>
                                 <a v-show="!investment.status" disabled href="#" class="btn btn-warning btn-sm">terminated</a>
                                 </td>
                         </tr>
                     </tbody>
                     <tfoot>
                          <th>#</th>
                         <th>Customer</th>
                         <th>Amount</th>
                         <th>Duration</th>
                         <th>Termination Date</th>
                         <th>Total Pay</th>
                         <th>Created at</th>
                         <th>Status</th>
                         <th>Action</th>
                     </tfoot>
                 </table>
             </div>
          </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            investments : {},
            form : new Form({
                investmentId : ''
            })
        }
    },
    methods:{
        terminateInvestment(id){
           this.form.investmentId = id;
           Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to reverse this process.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, terminate'
                }).then((result) => {
                     this.form.post(`api/terminate`)
                .then((data)=>{
                    Fire.$emit('investmentupdate')
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
                            title:'Termination Error',
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
        getInvestMents(){
            this.$Progress.start();
            axios.get('/api/investments').then((data)=>{
                this.investments = data.data.data;
                this.$Progress.finish();
            }).catch((e)=>{
                this.$Progress.fali();
                console.log(e);
            })
        }
    },
    created() {
        this.getInvestMents();
        Fire.$on('investmentupdate',()=>{
            this.getInvestMents();
        })
    },
}
</script>