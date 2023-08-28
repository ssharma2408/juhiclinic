@extends('layouts.layout')

@section('page')

<div>Doctors</div>

@endsection

@section('content')

<div class="section-title pt-0 mb-2">
	<h1>Doctor Timings</h1>
</div>

@include('layouts.partials.messages')

<ul class="my-clinic-details-inner list-group list">
	<li class="bg-body-tertiary list-group-item d-flex justify-content-between align-items-start fw-bold">
		<span class="col-4">Day</span>
		<span class="col-4">Timings</span>
		<span class="col-2 d-none d-lg-block">Max Token</span>
		<span class="col-2 d-none d-lg-block">Time Per Token</span>
	</li>

	@if(!empty($details->opening_hours))
	@foreach($day_arr as $key=>$day)
	<li class="list-group-item">
		<div class="row justify-content-between align-items-center">
			<span class="fw-bold col-lg-4 {{isset($details->opening_hours[$key+1]) ? '' : 'col-4'  }}">{{ $day }}</span>
			@if(isset($details->opening_hours[$key+1]))
			<div class="col-lg-8">
				@foreach($details->opening_hours[$key+1] as $slot=>$timing)
				<div class="row my-2">
					<div class="col-lg-6 col-12" data-title="Timings">
						<span>{{$timing->start_hour}} - {{$timing->end_hour}}</span>
					</div>
					<div class="col-lg-3 col-12" data-title="Max Token">{{$timing->max_token}}</div>
					<div class="col-lg-3 col-12" data-title="Time Per Token"> {{$timing->time_per_token}}</div>
				</div>
				@endforeach
			</div>
			@else
			<div class="col">
				<span class="badge text-bg-danger">Cloed</span>
			</div>
			@endif
		</div>
	</li>
	@endforeach
	@endif
</ul>
<div class="text-center mt-3">
	<a href="{{ route('timings.edit', $details->doctor->id) }}" class="btn btn-secondary btn-rounded">Update</a>
</div>

<!-- goal area End -->
@endsection
@section('scripts')
@parent
<script>

</script>
@endsection