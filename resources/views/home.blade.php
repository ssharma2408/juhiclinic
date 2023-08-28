@extends('layouts.layout')

@section('page')

<div>Home</div>

@endsection

@section('content')

<div class="mb-3">
    <img class="img-fluid" src="{{ asset('img/Book-an-Appointment_banner.jpg') }}" alt="Book-an-Appointment_banner">
</div>

@include('layouts.partials.messages')

@if(!empty($announcements))
	@foreach($announcements as $announcement)
		<div class="text-center">{!!$announcement->message!!}</div>
	@endforeach
@endif

@if (Session::has('user_details'))

	<!-- balance start -->
	<div class="balance-area pd-top-40 mg-top-50">
		<div class="container">
			<div class="balance-area-bg balance-area-bg-home">
				<div class="balance-title text-center">
					<p>Welcome! <br> {{Session::get('user_details')->name}}</p>
				</div>
			</div>
		</div>
	</div>
	<!-- balance End -->
	@if (!Session::has('close_status'))
		<a href="{{route('patient.login.show')}}" class="btn btn-secondary btn-rounded ">Book an Appointment</a>
	@endif
	<!-- transaction start -->
	<div class="transaction-area pd-top-36">
		<div class="container">
			<div class="section-title">
				<h3 class="title">My Dashboard</h3>
				<a href="#"><i class="fa fa-tachometer"></i></a>
			</div>
			<div class="ba-bill-pay-inner">
				<div class="ba-single-bill-pay">
					<div class="thumb">
						<img src="{{ asset('img/icon/7.png') }}" alt="img">
					</div>
					<div class="details">
						<h5>Lorem ipsum</h5>
						<p>Duis aute irure dolor in reprehenderit in voluptate </p>
					</div>
				</div>
				<div class="amount-inner">
					<h5>***</h5>
					<a class="btn btn-red" href="#">Read</a>
				</div>
			</div>
		</div>
	</div>
	<!-- transaction End -->

@else
	<div class="section-block my-5">
		<div class="row">
			<div class="col-lg-5 col-md-6 mx-auto">

				<div class="text-center">
					<div class="section-title pt-0">
						<h3 class="title-lg">Book your doctor appointment today!</h3>
						<p class="text-descripstion secondary-text mb-0">you can trust for all your healthcare needs. </p>
					</div>
					@if (!Session::has('close_status'))
					<a href="{{route('patient.login.show')}}" class="btn btn-secondary btn-rounded ">Book an Appointment</a>
					@endif
				</div>

			</div>
		</div>
	</div>
@endif

@php
$day = date( 'N' );
@endphp

@if(!empty($doctor_arr))
	<div class="section-title pt-0 mb-2 row d-flex justify-content-between align-items-center">
		<h3 class="title col-auto">Doctors</h3>	
	</div>

	<ul class="my-clinic-details-inner list-group">
		<li class="my-clinic-details-title  bg-body-tertiary list-group-item d-flex justify-content-between align-items-start fw-bold">
			<span class="col-3">Doctor</span>
			<span class="col-3">Timing</span>
			<span class="col-3 d-none d-lg-block">Total Tokens</span>
			<span class="col-3 d-none d-lg-block">Current Token</span>
		</li>

		@foreach($doctor_arr as $doctor)
			@if(isset($doctor['timings'][$day]))
				<li class="list-group-item">
					<div class="row justify-content-between align-items-center {{isset($doctor['timings'][$day+1]) ? '' : 'border-top mt-2 pt-2 border-light-subtle'  }}">				
							
						<span class="fw-bold col-lg-3">Dr. {{$doctor['name']}}</span>
							
						<div class="col-lg-9">
							@foreach($doctor['timings'][$day] as $slot=>$timing)
							<div class="row my-2">
								<div class="col-lg-4 col-12">
									<span class="">{{$timing['start_hour']}} - {{$timing['end_hour']}}</span>
								</div>
								<div class="col-lg-4 col-12">
									<span class="" id="status_{{$doctor['id']}}_{{$timing['slot_id']}}">@if(!empty($timing['total_token'])) {{$timing['total_token']}} @else 0 @endif</span>
								</div>
								<div class="col-lg-4 col-12">
									<span class="" id="status_{{$doctor['id']}}_{{$timing['slot_id']}}">{{$timing['current_token']}}</span>
								</div>
							</div>
							@endforeach
						</div>					
					</div>
				</li>
			@endif
		@endforeach
	</ul>
@endif
@endsection

@push('js')

@endpush