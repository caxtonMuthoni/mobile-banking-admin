<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>mobile banking of kenya</title>

   <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <!-- <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
   Ionicons -->
  
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>
     
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-tem">
         <a href="" class="nav-link">@{{date}}</a>
      </li>
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
    
      <li class="nav-item">
      <a href="#" class="nav-link" data-toggle="modal" data-target="#modelId"> <i class="fas fa-power-off  text-danger  "></i></a>
        <!-- <a class="nav-link" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                         <i class="fas fa-power-off  text-danger  "></i>
          </a> -->

          <!-- <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form> -->
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Logout modal -->
  <!-- Button trigger modal -->
  <!-- <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modelId">
    Launch
  </button> -->
  
  <!-- Modal -->
  <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title text-danger"><i class="fas fa-lock  mr-3  "></i> <strong>End session</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
         <form action="{{ route('logout') }}" method="post">
           @csrf
         <div class="modal-body">
          <div class="container-fluid">
            <strong class="text-info">Are you sure you want to logout ?</strong>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">No,close</button>
          <button type="submit" class="btn btn-danger">Yes,Logout</button>
        </div>
         </form>
      </div>
    </div>
  </div>
  
   <!-- Logout modal -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
   <!-- Brand Logo -->
   <a href="" class="brand-link">
      <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">M~Banking</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
        <?php
           $avatar = App\Profile::where('UserId',Auth::user()->id)->value('Avatar');
        ?>
          <img src="{{asset('images/avatar/'.$avatar)}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <router-link to="/profile">{{Auth::user()->FirstName}}  {{Auth::user()->LastName}}</router-link>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-open">
            <router-link to="/home" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt cyan"></i>
              <p>
                M~Baking Dash
                <i class="right fas fa-angle-left"></i>
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/home" class="nav-link">
             <i class="fas fa-chart-area  indigo  "></i>
              <p>
                System Stats
                <span class="right badge badge-danger">New</span>
              </p>
            </router-link >
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy teal"></i>
              <p>
                Account
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">6</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/accounts" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Accounts</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/topup" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Top Up Wallets</p>
                </router-link >
              </li>
              <li class="nav-item">
                <router-link to="/withdraw" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Withdraw</p>
                </router-link>
              </li>
              

              <li class="nav-item">
                <router-link to="/statement" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Request Statement</p>
                </router-link>
              </li>
              
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie blue"></i>
              <p>
                Loans
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <router-link to="/loans" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Loans</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/loantypes" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Loan Types</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/defaulting" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Defaulting Loans</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/graph" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Processed Loans</p>
                </router-link>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit pink"></i>
              <p>
                Investments
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/investments" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Investment Management</p>
                </router-link>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fa fa-users nav-icon orange" aria-hidden="true"></i>
              <p>
                Users Management
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <router-link to="/users" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/manageusers" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage</p>
                </router-link>
              </li>
              
            </ul>
          </li>
          
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-table teal"></i>
              <p>
              Transactions
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/transactions" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Transactions</p>
                </router-link>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon green fas fa-school    "></i>
              <p>
              Schools
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/schools" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Schools</p>
                </router-link>
              </li>
          
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class=" nav-icon fas fa-comment    "></i>
              <p>
              Review
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/reviews" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Reviews</p>
                </router-link>
              </li>
          
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="fas fa-cogs  blue  "></i>
              <p>
              Account settings
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/acounttypes" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Account types</p>
                </router-link>
              </li>
          
            </ul>
          </li>
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper cntnt">
   
  <router-view></router-view>
  <vue-progress-bar></vue-progress-bar>
    
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2019 <a href="http://cgagimu865.gq">Mobile Bakning of Kenya</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.1
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('dist/js/demo.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>


<script>

  /* $(function () {
    $('#mytable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
    });
  }); */
</script>
</body>
</html>
