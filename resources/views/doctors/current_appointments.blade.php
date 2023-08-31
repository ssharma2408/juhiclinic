@extends('layouts.layout')

@section('page')

<div>Patients</div>

@endsection

@section('content')

<div id="loader_div" class="text-center">
	<img src="{{asset('img/loader.svg') }}" />
</div>

<!-- page-title stary -->

<!-- page-title end -->
<!-- balance start -->
@if(!empty($patient_arr))
	@php($i = 1)
	@foreach($patient_arr as $slot=>$patients)
		<div class="slot_container" id="slot_container_{{$i}}">
			<div class="section-title pt-0 mb-2 row d-flex justify-content-between align-items-center">
				<h1 class="title col-auto">Current Appointments</h1>
				<button class="btn btn-primary work_status" id="work_status_{{$patients['slot_id']}}" @if($patients['is_started']) disabled @endif>{{($patients['is_started']) ? 'Started ...' : 'Start' }}</button>
				<div class="text-descripstion secondary-text mb-0 col-auto">
					Slot: {{$slot}}
				</div>
			</div>
		@foreach($patients['patients'] as $patient)
			<form class="taken_frm" method="post" enctype="multipart/form-data" id="frm_{{$patient->id}}">
				@csrf
				<div class="card">
					<div class="card-body">
						<div class="d-flex">
							<div class="ms-auto fw-bold secondary-text">
								Approx Time : {{$patient->estimated_time}} Token : {{$patient->token_number}}
							</div>
						</div>
						<div class="single-goal single-goal-one">
							@if($patient->is_online)
								<div class="row align-items-center">
									<div class="col-md-4 col-12 mt-2">
										<label class="form-label">Patient Name</label>
										<input type="text" readonly class="form-control" value="{{$patient->name}}">
									</div>
									<div class="col-md-4 col-12 mt-2">
										<label class="form-label">Status</label>
										<select name="status" id="status_{{$patient->id}}" class="form-select status">
											<option value="0" @if($patient->status==0){{'selected'}} @endif>Close</option>
											<option value="1" @if($patient->status==1){{'selected'}} @endif>Open</option>
											<option value="2" @if($patient->status==2){{'selected'}} @endif>Hold</option>
										</select>
									</div>

									<div class="col-md-4 col-12 mt-2">
										<label class="form-label">Prescription</label>
										<input class="form-control" name="prescription" type="file" id="prescription_{{$patient->id}}">
									</div>
									<div class="col-md-6 col-12 mt-2">
										<label class="form-label">Comment</label>
										<textarea class="form-control" name="comment" id="comment_{{$patient->id}}"></textarea>
									</div>
									<div class="col-md-6 col-12 mt-2 next_visit">
										<label class="form-label">Next Visit</label>
										<input class="form-control" type="date" name="next_visit_date" min="<?php echo date('Y-m-d'); ?>" value="{{ old('next_visit_date') }}" />
									</div>
									<div class="col-md-6 col-12 mt-2 align-self-end">
										<button type="submit" class="btn_update btn btn-secondary btn-rounded">Update</button>
										<input type="hidden" name="patient_id" value="{{$patient->id}}" />
										<input type="hidden" name="token_id" value="{{$patient->token_id}}" />
										<input type="hidden" name="slot_id" value="{{$patient->timing_id}}" />
										<input type="hidden" name="is_online" value="{{$patient->is_online}}" />
										<div class="patient_msg text-danger small" id="msg_{{$patient->token_id}}"></div>
									</div>
								</div>
							@else
								<div class="row align-items-center">									
									<div class="col-md-4 col-12 mt-2">
										<label class="form-label">Off Line</label>										
									</div>
									<div class="col-md-4 col-12 mt-2">
										<label class="form-label">Status</label>
										<select name="status" id="status_{{$patient->id}}" class="form-select">
											<option value="0" @if($patient->status==0){{'selected'}} @endif>Close</option>
											<option value="1" @if($patient->status==1){{'selected'}} @endif>Open</option>
											<option value="2" @if($patient->status==2){{'selected'}} @endif>Hold</option>
										</select>
									</div>									
									
									<div class="col-md-6 col-12 mt-2 align-self-end">
										<button type="submit" class="btn_update btn btn-secondary btn-rounded">Update</button>
										<input type="hidden" name="token_id" value="{{$patient->token_id}}" />
										<input type="hidden" name="slot_id" value="{{$patient->timing_id}}" />
										<input type="hidden" name="is_online" value="{{$patient->is_online}}" />
										<div class="patient_msg text-danger small" id="msg_{{$patient->token_id}}"></div>
									</div>
								</div>
							@endif							
						</div>
					</div>
				</div>				
			</form>
		@endforeach
		</div>
		@php($i++)
	@endforeach
	<input type="hidden" name="timer" id="timer" value="0" />
@else
	<div class="section-title pt-0 mb-2 row d-flex justify-content-between align-items-center">
		<h1 class="title">Current Appointments</h1>
		<div class="text-descripstion text-center mt-4 secondary-text">
			There is no Appointments
		</div>
	</div>
@endif

<!-- balance End -->
@endsection

@section('scripts')
@parent
<script>
	
	var counter;
	
	$(function() {
		$(".patient_msg").hide();
		$(".next_visit").hide();
		$("#loader_div").hide();		
		$("#timer").val(0);
		$(".btn_update").attr("disabled" , true);
		
		$(".work_status").each(function() {
			if($( this ).text() == "Started ..."){
				$(this).parent().parent().find(".btn_update").attr("disabled", false);
				startTimer();
			}
		});
	});
	
	$(".status").change(function (){
		var patient_id = $(this).attr('id').split("_")[1];
		if($(this).val() == 0){
			$("#prescription_"+patient_id).prop('required', true);
			$(this).parent().parent().find(".next_visit").show();
		}else{
			$("#prescription_"+patient_id).prop('required', false);
			$("#comment_"+patient_id).prop('required', false);
			$(this).parent().parent().find(".next_visit").hide();
		}
	});

	$("form.taken_frm").submit(function(e) {
		var frm_id = $(this).attr('id');		
		e.preventDefault();
		var formData = new FormData(this);
		
		
		var token_id = $(this).find('input[name="token_id"]').val();
		var status = $(this).find('select[name="status"]').val();		
		
		if(status == 0 || status == 2){
			
			formData.append("time_taken", $("#timer").val());

			$("#loader_div").show();
			$.ajax({
				url: '/doctor_dashboard/update-token',
				type: 'POST',
				data: formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success: function(data) {
					$("#loader_div").hide();
					if (data.success) {
						$html = "<div>" + data.msg + "</div>"
						$("#msg_" + token_id + "").show().html($html);
						if(status == 0 ){
							setTimeout(
							  function() 
							  {
								$('#'+frm_id).hide('slow', function(){ $('#'+frm_id).remove(); });								
							  }, 2000);							  
						}
						startTimer();
					} else {
						$html = "<div>There is a technical error or change token status.</div>"
						$("#msg_" + token_id + "").show().html($html);
					}
				},
				cache: false,
				contentType: false,
				processData: false
			});
		}
	});
	
	function startTimer(){
		var steps = 0
		cleartimer();
		counter = setInterval(function() {
			steps++;
		
			$("#timer").val(steps);
	
	  }, 1000);
	}
	
	function cleartimer(){
		clearInterval(counter);
	}
	
	$(".work_status").click(function (){
		$(this).text("Started ...").attr('disabled', true);		
		var ele_id = $(this).parent().parent().attr("id").split("_")[2];
		
		var slot_id = $(this).attr('id').split("_")[2];
		$.ajax({
				url: '/doctor_dashboard/start-slot/'+slot_id+'/'+1,
				type: 'GET',				
				success: function(data) {
					if (data.success) {
						
						$("#slot_container_"+ele_id).find(".btn_update").attr("disabled", false);
						$(this).text("Started ...").attr('disabled', true);
						startTimer();
						
					} else {
						$(this).text("Start").attr('disabled', false);
					}
				},
				cache: false,
				contentType: false,
				processData: false
			});
	});
	
</script>
@endsection