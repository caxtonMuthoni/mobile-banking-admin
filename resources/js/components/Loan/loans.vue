<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Loans</div>

                    <div class="card-body">
                        <table class="table table-striped table-bordered">
                        
                        <thead>
                             <th>#</th>
                             <th>image</th>
                             <th>Title</th>
                             <th>Description</th>
                             <th>Amount</th>
                             <th>Balance</th>
                             <th>Date</th>
                            
        
       
                        </thead>
                        <tbody>
                           <tr v-for="loan in loans" :key="loan.id">
                                <td>{{loan.userId}}</td>
                                <td><img :src="getImage(loan.project_image)" alt="" ></td> 
                                <td>{{loan.title }}</td>
                                <td>{{loan.description }}</td>                               
                                <td>{{loan.amountBorrowed}}</td> 
                                <td>{{loan.balance }}</td>
                                <td>{{loan.created_at | upDate}}</td>
                           </tr>
                        </tbody>
                        
                        
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
            return {
                loans : {}
            }

        },
        methods:{
            getImage(image){
                return 'images/project/'+image;
            },
            loadLoans(){
                axios.get('/api/borrows').then(({data})=>{
                    this.loans = data;
                })
            }

        },
        created() {
            this.loadLoans();
        }
    }
</script>
