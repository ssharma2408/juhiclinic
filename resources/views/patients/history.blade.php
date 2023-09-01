@extends('layouts.layout')

@section('page')
 
	<div>History</div>
 
@endsection
 
@section('content')

<div class="balance-area pd-top-40">
	<div class="container">
		<div class="section-title">			
			<h3 class="title">History</h3>
		</div>
		<form class="history_frm" method="post" id="history_frm">
			<div class="row">			
				<div class="col-lg-4">
					<select class="form-control" placeholder="Member" id="member" name="member">
						<option value="">-- Select Member --</option>
						@foreach($details->members as $member)
							<option value="{{$member->id}}">{{$member->name}}</option>
						@endforeach
					</select>
				</div>
				@if(!empty($details->clinics))
					<div class="col-lg-4">			
						<select class="form-control" placeholder="Clinic" id="clinic" name="clinic">
							<option value="">-- Select Clinic --</option>
							@foreach($details->clinics as $clinic)
								<option value="{{$clinic->id}}">{{$clinic->name}}</option>
							@endforeach
						</select>
					</div>
				@endif
				@if(!empty($details->doctors))
					<div class="col-lg-4">		
						<select class="form-control" placeholder="Doctor" id="doctor" name="doctor">
							<option value="">-- Select Doctor --</option>
							@foreach($details->doctors as $doctor)
								<option value="{{$doctor->id}}">Dr. {{$doctor->name}}</option>
							@endforeach
						</select>
					</div>
				@endif			
			</div>
		</form>
		<div class="history_container mt-4" id="history_container"></div>
	</div>
</div>
<!-- balance End -->
@endsection
 
@section('scripts')
@parent
<script>
	
	$(".form-control").change(function (){

		var formData = new FormData($('#history_frm')[0]);
		var member_id = $("#member").val();

		if(member_id !=""){
			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url: '/user_dashboard/history',
				type: 'POST',
				data: formData,
				dataType: 'JSON',
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success: function(data) {
					if (data.success) {
						var html = "<div class='text-center'><h2>No records found.</h2></div>";
						if(data.details.length > 0){
							html = '<ul class="my-clinic-details-inner list-group list"><li class="bg-body-tertiary list-group-item d-flex justify-content-between align-items-start fw-bold"><span class="col-4">Visit Date</span><span class="col-4">Prescription</span><span class="col-2 d-none d-lg-block">Comment</span><span class="col-2 d-none d-lg-block">Next Visit Date</span></li>';							
							
							$.each(data.details, function( index, value ) {
								
								var visit_date = new Date(value.visit_date).toDateString();
								var comment = (value.comment != null) ? value.comment : "-";
								var next_visit_date = (value.next_visit_date != null) ? new Date(value.next_visit_date).toDateString() : "-";
								
								html += '<li class="list-group-item"><div class="row justify-content-between align-items-center"><div class="col-4">'+visit_date+'</div><div class="col-4"><img class="zoom" src="'+value.prescription+'" height="100" width="100" /></div><div class="col-2">'+comment+'</div><div class="col-2">'+next_visit_date+'</div></div></li>';
							});
							
							html += '</ul>';
						}
						$("#history_container").html(html);
					} else {
						$("#history_container").html("");
					}
				},
				cache: false,
				contentType: false,
				processData: false
			});
		}
	});
	
	$(document).on("click", ".zoom", function () {
		$("#prescription").html("<img class='img-fluid'  src='"+$(this).attr("src")+"' />");
		$('#historyModal').modal('show');
	});
</script>
@endsection

@section('modal')
<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">				
				<h4 class="modal-title" id="myModalLabel">History</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">				
				<span id="prescription"></span>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
@endsection