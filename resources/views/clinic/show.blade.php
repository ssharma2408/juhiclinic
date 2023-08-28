@extends('layouts.layout')

@section('page')

<h1>My Clinic</h1>

@endsection

@section('content')

<!-- balance start -->

<div class="section-title d-flex justify-content-between align-items-center">
	<h3 class="title">{{$clinic->name}}</h3>
	<span class="title">Package:{{$clinic->package->package}} </span>
</div>
@include('layouts.partials.messages')

<div class="balance-area-bg bg-transaction-details">
	<div class="balance-title text-center">
		<h3><b>{{$clinic->clinic_admin->name}}</b></h3>
	</div>
	<div class="ba-balance-inner text-center">
		<div class="icon">
			<i class="ri-map-pin-line"></i>
		</div>
		<h6 class="title">{!! $clinic->address !!}</h6>
	</div>
</div>

<!-- balance End -->

@if(!empty($clinic->opening_hours))
<!-- my-clinic start -->
<div class="my-clinic-details">
	<div class="card-body py-0">
		<div class="section-title d-flex justify-content-between align-items-center">
			<h3 class="title-sm">Timings</h3>
			<span><a href="{{ route('clinic.timings.edit') }}" class="btn btn-secondary btn-sm">Update</a></span>
		</div>
		@foreach($day_arr as $key=>$day)
		<div class="bg-body-tertiary rounded-3 text-secondary">
			<div class="row d-flex justify-content-between align-items-center p-3 mb-2">
				<div class="col-auto">{{ $day }}</div>
				<div class="col-auto">
					@if(isset($clinic->opening_hours[$key+1]))
					<div class="float-right">
						@foreach($clinic->opening_hours[$key+1] as $slot=>$timing)
						<span class="d-block">{{$timing->start_hour}} - {{$timing->end_hour}}</span>
						@endforeach
					</div>
					@else
					<div class="float-right text-danger">
						<span class="badge text-bg-danger">Cloed</span>
					</div>
					@endif
				</div>
			</div>
		</div>
		@endforeach
	</div>
</div>

@endif

<div class="my-clinic-details">
	<div class="section-title d-flex justify-content-between align-items-center">
		<h3 class="title-sm"> Closed on</h3>
		<a href="{{ route('clinic.closed.edit', ['clinic_admin'=>$clinic->clinic_admin->id]) }}" class="btn btn-secondary btn-sm">Update</a>
	</div>
	@if(!empty($closed_days))
		@foreach($closed_days as $day)
		<div class="d-flex justify-content-between align-items-start p-3 mb-2 bg-body-tertiary rounded-3 text-secondary">
			<span class="float-left">{{date('d-M-y', strtotime($day->closed_on))}}</span>
		</div>
		@endforeach
	@endif
</div>

<p class="small mt-4 text-center mb-0">If haveing any timing issue, Please <a href="contact.html">contact us</a></p>
@endsection

@push('js')

@endpush