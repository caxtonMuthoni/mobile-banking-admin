<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>Mbanking</title>
</head>
<body>

	<!-- Header section -->
	<header class="header-section clearfix mt-0">
		<div class="container-fluid">
			<div class="responsive-bar"><i class="fa fa-bars"></i></div>
			<a href="" class="user"><i class="fa fa-user"></i></a>
			<nav class="main-men mt-2">
			<a href="index.html" class="site-logo ">
				<h3><strong>M-BANKING</strong></h3>
			</a>
			 <nav class="main-menu">

			 <ul class="menu-list">
					<!-- <li><a href="">About</a></li>
					<li><a href="">Products</a></li>
					<li><a href="">Pricing</a></li>
					<li><a href="">Contact</a></li> -->
					@if (Route::has('login'))
                    @auth
                        <li><a href="{{ url('/home') }}" class="site-btn">Home</a></li>
                    @else
                    <a href="{{ route('login') }}" class="site-btn">Login</a>

                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="site-btn">Register</a>
                        @endif
                    @endauth
            @endif
				</ul>

			 </nav>
			
			</nav>
		</div>
	</header>
	<!-- Header section end -->


	<!-- Hero section -->
	<section class="hero-section">
		<div class="container">
			<div class="row">
				<div class="col-md-6 hero-text">
					<h2>Manage Money with<span>M-Banking</span> <br></h2>
					<h4>Manage your money like a boss</h4>
					<p> When money realizes that it is in good hands, it wants to stay and multiply in those hands. <br>
                        M-Banking provides you with a simple platform to manage your money easily. We also provide short loans
                        to bost your financial growth. Get started by creating an acount for free !!!</p>
					
						<a href="" class="site-btn sb-gradients">Get Started  !!!</a>
					
				</div>
				<div class="col-md-6">
					<img src="{{asset('images/laptop.png')}}" class="laptop-image" alt="">
				</div>
			</div>
		</div>
	</section>
	<!-- Hero section end -->
<script src="{{asset('js/main.js')}}"></script>

</body>
</html>