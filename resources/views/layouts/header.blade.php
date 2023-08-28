<!-- preloader area start -->
<div class="preloader" id="preloader">
	<div class="preloader-inner">
		<div class="spinner">
			<div class="dot1"></div>
			<div class="dot2"></div>
		</div>
	</div>
</div>
<!-- preloader area end -->

<!-- header start -->
<div class="header-wrapper">
	<div class="container">
		<div class="row">
			<div class="col-auto">
				@if (Session::has('user_details'))
				<button class="btn navbar-toggle menu-bar d-lg-none me-2">
					<i class="ri-menu-2-fill"></i>
				</button>
				@endif
					<a href="{{route('clinic.home')}}" class="logo">
					<img class="img-fluid" src="{{asset('img/logo.svg') }}" alt="logo">
				</a>
			</div>
		
			<div class="col-auto text-right ms-auto">
				<ul class="nav user-menu float-end">
					<li class="nav user-menu float-end has-arrow user-profile-list">
						@if (Session::has('user_details'))
						<!-- <div class="user-names" data-toggle="dropdown">
							<span class="user-name">Hi {{Session::get('user_details')->name}}</span>
							<small class="user-role">{{Session::get('user_details')->role}}</small>
						</div>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="{{ route('login.logout') }}">Logout</a>
						</div> -->
						<a class="btn btn-secondary " href="{{ route('login.logout') }}">	<i class="ri-logout-circle-line"></i> Logout</a>
						@else
							<a href="{{ route('login.show') }}" class="btn btn-secondary "><i class="ri-login-circle-line me-2"></i>Login</a>						
						@endif
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<!-- header end -->
@if (Session::has('close_status'))
<div class="container">

	<div class="alert alert-danger text-center text-uppercase">Clinic is closed today</div>

</div>
@endif