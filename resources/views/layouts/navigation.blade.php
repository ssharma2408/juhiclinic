<!-- navbar end -->
<div class="app-sidebar ba-navbar">
<div class="card-box sidebar-content px-0">
	<div class="ba-main-menu">
		<ul class="vertical-menu">
		<li class="vertical-header">Main</li>
			@if (Session::has('user_details') && Session::get('user_details')->role=="Clinic Admin")
				<li><a href="{{route('clinic.admin.dashboard')}}"><i class="ri-dashboard-line"></i><span> Home</span></a></li>
				<li><a href="{{route('my-clinic.show')}}"><i class="ri-service-line"></i><span> My Clinic</span></a></li>
				<li><a href="{{route('doctors.index')}}"><i class="ri-nurse-fill"></i><span> Doctors</span></a></li>
				<li><a href="{{route('staffs.index')}}"><i class="ri-group-line"></i><span> Staffs</span></a></li>
				<li><a href="{{route('clinic.admin.profile')}}"><i class="ri-profile-line"></i><span> Profile</span></a></li>
			
			@elseif(Session::has('user_details') && Session::get('user_details')->role=="Doctor")
				<li><a href="{{route('doctor.dashboard')}}"><i class="ri-dashboard-line"></i><span> Home</span></a></li>
				<li><a href="{{route('timings.index')}}"><i class="ri-history-line"></i><span> Timings</span></a></li>
				<li><a href="{{route('doctor.current.appointments')}}"><i class="ri-calendar-2-line"></i><span> Current Appointments</span></a></li>
				<li><a href="{{route('doctor.patients')}}"><i class="ri-group-line"></i><span> Patients</span></a></li>
				<li><a href="{{route('doctor.profile')}}"><i class="ri-profile-line"></i><span> Profile</span></a></li>
			
			@elseif(Session::has('user_details') && Session::get('user_details')->role=="Staff")
				<li><a href="{{route('staff.dashboard')}}"><i class="ri-dashboard-line"></i><span> Home</span></a></li>
				<li><a href="{{route('clinic.show')}}"><i class="ri-service-line"></i><span> My Clinic</span></a></li>
				<li><a href="{{route('clinic.doctors')}}"><i class="ri-nurse-fill"></i><span> Doctors</span></a></li>
				<li><a href="{{route('staff.token.status')}}"><i class="ri-price-tag-3-line"></i><span>Tokens</span></a></li>
				<li><a href="{{route('staff.profile')}}"><i class="ri-profile-line"></i><span> Profile</span></a></li>
			
			@else
				<li><a href="{{route('patient.dashboard')}}"><i class="ri-dashboard-line"></i><span> Home</span></a></li>
				<li><a href="{{route('family.index')}}"><i class="ri-parent-line"></i><span> My Family</span></a></li>
				<li><a href="{{route('patient.profile')}}"><i class="ri-profile-line"></i><span> Profile</span></a></li>

			@endif
				<li class="vertical-header">Extras</li>
				<li><a href="#"><i class="ri-book-read-line"></i><span> Pages</span></a></li>
				<li><a href="{{ route('login.logout') }}"><i class="ri-logout-circle-line"></i><span> Logout</span></a></li>
		</ul>
	</div>
</div>
</div>
<!-- navbar end -->