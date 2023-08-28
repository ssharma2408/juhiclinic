@extends('layouts.layout')

@section('page')

<div>Doctor Dashboard</div>

@endsection

@section('content')


<div class="section-title d-flex pt-0">
	<h3 class="title"> Patients History</h3> <i class="ri-history-line ms-auto fw-bold"></i> 
</div>

<div class="mb-4">
	<form>
		<div class="input-group search-field">
			<span class="input-group-text" id="free-search">
				<i class="ri-user-search-line"></i>
			</span>
			<input type="search" class="form-control" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" maxlength="512" placeholder="Search patients" aria-label="Search patients" aria-describedby="free-search" value="" id="InputSearch" name="query" />
		</div>		
	</form>
</div>
<div id="loader_div" class="text-center">
	<img src="{{asset('img/loader.svg') }}" />
</div>
<div id="patient_div">
	@foreach($patient_arr as $id => $details)
	<div class="card">
		<div class="card-body">
			<div class="details fw-bold secondary-text text-uppercase  mb-3">
			{{$details['name']}}
			</div>
			@foreach($details['visit_date'] as $date)
				<div class="d-flex mt-2">
					<div class="visit_date">{{$date['visit_date']}}</div>
					<button type="button" class="ms-auto btn btn-primary btn-sm show_btn" id="history_{{$date['history_id']}}">View</button>
				</div>
			@endforeach
		</div>
	</div>
	@endforeach
</div>


<!-- balance End -->
@endsection

@section('scripts')
@parent
<script>
	$(function() {
		$("#loader_div").hide();
	});
	$(document).on("click", ".show_btn", function () {

		load_history($(this).attr('id').split("_")[1]);
	});
	
	$(document).on("click", "#prev", function () {
		var control = document.getElementById("prev");
		var prev_id = control.getAttribute('data-prev_id');

		if(typeof prev_id !== 'undefined'){
			load_history(prev_id);
		}		
	});
	
	$(document).on("click", "#next", function () {
		var control = document.getElementById("next");
		var next_id = control.getAttribute('data-next_id');

		if(typeof next_id !== 'undefined'){
			load_history(next_id);
		}
	});
	
	
	function load_history(history_id)
	{
		var next_id, prev_id;
		
		$("#loader_div").show();
		
		$('#prev').show();
		$('#next').show();
		
		if($("#history_"+history_id).parent().next(".d-flex").length > 0){
			next_id = $("#history_"+history_id).parent().next().find(".show_btn").attr("id").split("_")[1];
		}else{
			next_id = 0;
		}
		
		if($("#history_"+history_id).parent().prev(".d-flex").length > 0){
			prev_id = $("#history_"+history_id).parent().prev().find(".show_btn").attr("id").split("_")[1];
		}else{
			prev_id = 0;
		}		
		
		$.ajax({
			type: 'GET',
			url: '/doctor_dashboard/get_history/' + history_id,
			success: function(data) {
				$("#loader_div").hide();
				if (data.success) {
					$("#p_name").text(data.history.patient.name);
					$("#p_visitdate").text(data.history.visit_date);
					$("#p_prescription").html('<img class="img-fluid" src ="' + data.history.prescription + '" />');
					$("#p_comment").text(data.history.comment);
					$('#historyModal').modal('show');
					if(prev_id > 0){
						$('#prev').attr("data-prev_id", prev_id);
					}else{
						$('#prev').hide();
					}
					if(next_id > 0){
						$('#next').attr("data-next_id", next_id);
					}else{
						$('#next').hide();
					}
					
					
				}
			}
		});
		
	}
	
	 $("#InputSearch").keyup(function() {		
		if($.trim($(this).val()).length > 2 ||  $.trim($(this).val()) == ""){
			get_patients($(this).val());
		}
	 });

	 function get_patients(search_term){
		if(search_term == "")
			search_term = 'all';
		$.ajax({
			url: '/doctor_dashboard/search_patients/'+search_term,
			type: "GET",
			beforeSend: function() {            
				$('#patient_div').html('<div class="loading">Loading ...</div>');
			},
			success: function(data) {
			   var patient_html = "";
			   if(data.success){

				   if(Object.keys(data.patient_arr).length > 0){

					for (var key in data.patient_arr){

						patient_html += '<div class="card"><div class="card-body"><div class="details fw-bold secondary-text text-uppercase  mb-3">'+data.patient_arr[key].name+'</div>';
						for (var k in data.patient_arr[key].visit_date){

							patient_html += '<div class="d-flex mt-2"><div class="visit_date">'+data.patient_arr[key].visit_date[k].visit_date+'</div><button type="button" class="ms-auto btn btn-primary btn-sm show_btn" id="history_'+data.patient_arr[key].visit_date[k].history_id+'">View</button></div>';
						}
						patient_html += '</div></div>';
					}
				   }else{
					   patient_html += 'No record found';
				   }
			   }else{
				   patient_html += 'There is a technical error.';
			   }
			   $("#patient_div").html(patient_html);
			},
			complete: function () {
			  
			}
		});
	}
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
				<span class="btn btn-primary" id="prev" data-prev_id=""><- Prev</span>
				<div>Name : <span id="p_name"></span></div>
				<div>Visit Date: <span id="p_visitdate"></span></div>
				<div>Prescription : <span id="p_prescription"></span></div>
				<div>Comment : <span id="p_comment"></span></div>
				<span class="btn btn-primary" id="next" data-next_id="">Next -></span>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
@endsection