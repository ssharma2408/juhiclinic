@extends('layouts.layout')

@section('title')
Patient Booking
@stop
@section('description', '')
@section('keywords', '')

@section('page')

<div>Patient Booking</div>

@endsection
<?php 
	date_default_timezone_set("Asia/Kolkata");
	$timezone = 'Asia/Kolkata';
?>
				
@section('content')

<!-- balance start -->

<div class="section-title pt-0 mb-2 row d-flex justify-content-between align-items-center">
	<h3 class="title col-auto">Members</h3>
	<p class="text-descripstion secondary-text mb-0 col-auto" id="time"></p>
</div>

<div>
	<h4>Book appointment to Dr. {{$doctor->name}}</h4>
</div>

@foreach($members->members as $member)

<div class="card">
	<div class="card-body d-flex">
		<div class="details fw-bold secondary-text text-uppercase">
		{{$member->name}}
		</div>				
		@if( ! array_key_exists($member->id, $is_booked))
			<div class="ms-auto">
				 <button type="button" id="doc_{{$doctor->id}}_{{$slot_id}}_{{$member->id}}" class="btn btn-secondary btn-rounded btn-sm book">Book</button>
			</div>
			<div class="token_details row gy-2 row-cols-1" id="token_details_{{$member->id}}"></div>
		@else
			@if($is_booked[$member->id]['status'] != 0)
				<div class="token_details row gy-2 row-cols-1" id="token_details_{{$member->id}}">
					<div>
						Current token: <b>{{$is_booked[$member->id]['current_token']}}</b></div>
					<div>
						Your token number is <b>{{$is_booked[$member->id]['token_number']}}</b>
						@if($is_booked[$member->id]['current_token'] != "Not Started")
							and estimated time is <b>{{$is_booked[$member->id]['estimated_time']}}</b>
						@endif
					</div>
					<div>
						<button class='btn btn-secondary btn-rounded btn-sm refresh_status' id='doc_{{$doctor->id}}_{{$slot_id}}_{{$member->id}}' type='button'>Refresh</button>
					</div>
				</div>
				<div>* Estimated time depends on doctor sign in time and clinic opening time.</div>
			@else
				<div class="text-center">Token closed</div>
			@endif	
		@endif		
	</div>	
</div>

@endforeach

<!-- balance End -->
@endsection

@section('scripts')
@parent
<script>
	var timestamp = '<?php echo time(); ?>';

	function updateTime() {
		var time_arr = Date(timestamp).split(" ");
		$('#time').html(time_arr[0] + " " + time_arr[1] + " " + time_arr[2] + " " + time_arr[4]);
	
		timestamp++;
	}
	$(function() {
		setInterval(updateTime, 1000);		
	});

	$(".book").click(function() {
		var doc_id = $(this).attr("id").split("_")[1];
		var slot_id = $(this).attr("id").split("_")[2];
		var patient_id = $(this).attr("id").split("_")[3];
		$.ajax({
			type: 'GET',
			url: '/user_dashboard/book-appointment/' + doc_id + '/' + slot_id+ '/' + patient_id,
			success: function(data) {
				if (data.success) {
					$("#doc_"+doc_id+"_"+slot_id+"_"+patient_id).hide();
					$html = "<div class='alert alert-success alert-dismissible fade show'>" + data.msg + "</div><div>Current token:" + data.token.current_token + "<b></b></div><div>Your token number is <b>" + data.token.token_number + "</b>";

					if(data.token.current_token != "Not Started"){
						$html += "and estimated time is <b>" + data.token.estimated_time + "</b>";
					}
					$html += "</div><div><button class='btn btn-secondary btn-rounded btn-sm refresh_status' id='doc_" + doc_id + "_" + slot_id + "_" + patient_id +"' type='button'>Refresh</button></div>";
					
					$("#token_details_" + patient_id).show().html($html);
				} else {
					$html = "<div>" + data.msg + "</div>"
					$("#token_details_" + patient_id).show().html($html);
				}
			}
		});
	});

	$(document).on("click", ".refresh_status", function() {
		var doc_id = $(this).attr("id").split("_")[1];
		var slot_id = $(this).attr("id").split("_")[2];
		var patient_id = $(this).attr("id").split("_")[3];
		
		$.ajax({
			type: 'GET',
			url: '/user_dashboard/refresh-status/' + doc_id + '/' + slot_id+ '/' + patient_id,
			success: function(data) {
				if (data.success) {
					$("#doc_"+doc_id+"_"+slot_id+"_"+patient_id).hide();
					$html = "<div>Current token:" + data.token.current_token + "<b></b></div><div>Your token number is <b>" + data.token.token_number + "</b>";

					if(data.token.current_token != "Not Started"){
						$html += "and estimated time is <b>" + data.token.estimated_time + "</b>";
					}

					$html += "</div><div><button class='btn btn-secondary btn-rounded btn-sm refresh_status' id='doc_" + doc_id + "_" + slot_id + "_" + patient_id +"' type='button'>Refresh</button></div>";

					$("#token_details_" + patient_id).html($html);
				} else {
					$html = "<div>" + data.msg + "</div>"
					$("#token_details_" + patient_id).html($html);
				}
			}
		});
	});
</script>
@endsection