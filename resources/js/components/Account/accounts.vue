<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11 ">
                <div class="card mt-5">
                    <div class="card-header indigo h5"><i class="fas fa-registered    "></i><strong>Registered Accounts</strong> 
                     <button class="btn btn-success float-right"> <i class="fas fa-user-plus    "></i> Add New</button>
                    </div>

                    <div class="card-body">
                      <table class="table table-striped table-bordered table-hover" id="mytable">
                          <thead>
                              <th>#</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Phone</th>
                              <th>National Id</th>
                              <th>Update</th>
                          </thead>
                          <tbody>
                              <tr>
                                  <td>1</td>
                                  <td>Caxton Muthoni</td>
                                  <td>githinjicaxton323@gmail.com</td>
                                  <td>0743751575</td>
                                  <td>33930519</td>
                                  <td><a href="" class="btn btn-primary"><i class="fas fa-user-edit    "></i></a></td>
                              </tr>
                          </tbody>
                          <tfoot>
                               <th>#</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Phone</th>
                              <th>National Id</th>
                              <th>Update</th>
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
        mounted() {
            console.log('Component mounted.')
            $(function () {
                $('#mytable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                });
            });
        }
    }
</script>
