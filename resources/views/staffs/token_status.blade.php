@extends('layouts.layout')

@section('title')
Token Status
@stop
@section('description', '')
@section('keywords', '')

@section('page')

<div>Token Status</div>

@endsection
<?php 
	date_default_timezone_set("Asia/Kolkata");
	$timezone = 'Asia/Kolkata';
?>
				
@section('content')

<!-- balance start -->
@php
$day = date( 'N' );
@endphp
<div class="section-title pt-0 mb-2 row d-flex justify-content-between align-items-center">
	<h3 class="title col-auto">Doctors</h3>
	<p class="text-descripstion secondary-text mb-0 col-auto" id="time"></p>
</div>
@include('layouts.partials.messages')
<ul class="my-clinic-details-inner list-group">
	<li class="my-clinic-details-title  bg-body-tertiary list-group-item d-flex justify-content-between align-items-start fw-bold">
		<span class="col-4">Doctor</span>
		<span class="col-4">Timing</span>
		<span class="col-2 d-none d-lg-block">Current Token</span>
		<span class="col-2 d-none d-lg-block">Action</span>
	</li>
	
	@foreach($doctor_arr as $doctor)
		@if(isset($doctor['timings'][$day]))
			<li class="list-group-item">
				<div class="row justify-content-between align-items-center {{isset($doctor['timings'][$day+1]) ? '' : 'border-top mt-2 pt-2 border-light-subtle'  }}">				
						
					<span class="fw-bold col-lg-4">Dr. {{$doctor['name']}}</span>
						
					<div class="col-lg-8">
						@foreach($doctor['timings'][$day] as $slot=>$timing)
						<div class="row my-2">
							<div class="col-lg-6 col-12">
								<span class="">{{$timing['start_hour']}} - {{$timing['end_hour']}}</span>
							</div>
							<div class="col-lg-3 col-12">
								<span class="" id="status_{{$doctor['id']}}_{{$timing['slot_id']}}">@if(!empty($timing['current_token'])) {{$timing['current_token']}} @else 0 @endif</span>
							</div>
							<div class="col-lg-3 col-12 book" data-endtime = "<?php echo strtotime(date('Y-m-d '.$timing['end_hour'].'')); ?>">
								<a href="creat_token/{{$doctor['id']}}/{{$timing['slot_id']}}" id="doc_{{$doctor['id']}}_{{$timing['slot_id']}}"  class="btn btn-secondary btn-rounded btn-sm">Create</a>
								<button type="button" id="refresh_{{$doctor['id']}}_{{$timing['slot_id']}}"  class="btn btn-secondary btn-rounded btn-sm refresh">Refresh</button>
								<div id="err_{{$doctor['id']}}_{{$timing['slot_id']}}" class="error_div"></div>
							</div>
						</div>
						@endforeach
					</div>					
				</div>
			</li>
		@endif
	@endforeach
</ul>

<!-- balance End -->
@endsection

@section('scripts')
@parent
<script>
	var timestamp = '<?php echo time(); ?>';

	function updateTime() {
		var time_arr = Date(timestamp).split(" ");
		$('#time').html(time_arr[0] + " " + time_arr[1] + " " + time_arr[2] + " " + time_arr[4]);

		$(".book").each(function(){
			var end_time = $(this).data("endtime");
			
			var currenttime = '<?php echo strtotime(date('Y-m-d H:i:s')); ?>';
			
			if(end_time < currenttime){
				$(this).hide()
			}
		});		
		timestamp++;
	}
	$(function() {
		setInterval(updateTime, 1000);		
	});
	
	$(".refresh").click(function() {
		var doc_id = $(this).attr("id").split("_")[1];
		var slot_id = $(this).attr("id").split("_")[2];
		$(".error_div").html("")
		$.ajax({
			type: 'GET',
			url: '/staff_dashboard/refresh_token/' + doc_id + '/' + slot_id,
			success: function(data) {
				if (data.success) {
					$("#status_" + doc_id +"_"+slot_id).html(data.token);
				} else {
					$("#err_" + doc_id +"_"+slot_id).html(data.msg);
					
				}
			}
		});
	});
	
</script>
@endsection