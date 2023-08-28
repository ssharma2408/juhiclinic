@extends('layouts.layout')

@section('page')

<div>Create Token</div>

@endsection

@section('content')


<div class="row">
    <div class="col-lg-12 mx-auto">
        
		<div class="section-title text-center pt-0">
			<h3 class="text-uppercase">Create Token</h3>           
		</div>
		<div id="loader_div" class="text-center">
			<img src="{{asset('img/loader.svg') }}" />
		</div>
		<form method="post" class="contact-form-inner" id="token_frm">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />			
			
			<div class="single-input-wrap form-group mb-3">
				<label class="form-label">Mobile No*</label>
				<div class="input-box" style="position: relative;">
					<span class="prefix position-absolute top-50 start-0 translate-middle ms-4">+91</span>
					<input id="mobile_no" class="form-control ps-5" type="text" name="mobile_no" value="{{ old('mobile_no') }}" required autocomplete="mobile_no" autofocus>
				</div>
			</div>
			@if ($errors->has('mobile_no'))
			<span class="text-danger text-left">{{ $errors->first('mobile_no') }}</span>
			@endif
			<div class="single-input-wrap form-group mb-3" id="member_div"></div>
			<div class="single-input-wrap form-group mb-3 text-center">
				<button class="btn btn-secondary btn-rounded" id="frm_submit" type="submit">Create Token</button>
			</div>
			<input type="hidden" name="doctor_id" value="{{$doctor_id}}" />
			<input type="hidden" name="slot_id" value="{{$slot_id}}" />
		</form>
		<div class="token_msg text-danger small" id=""></div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>	
	
	$(function() {
		$(".token_msg").hide();
		$("#member_div").hide();
		$("#loader_div").hide();
	});
	
	$("#token_frm").submit(function(e) {
		e.preventDefault();
		var formData = new FormData(this);
		$("#loader_div").show();
		
		$(".token_msg").hide().html("");
		
		$.ajax({
			url: '/staff_dashboard/process_token',
			type: 'POST',
			data: formData,
			success: function(data) {
				$("#loader_div").hide();
				if (data.success == 1) {
					$html = "<div>" + data.msg + "</div>"
					$(".token_msg").show().html($html);
					$("#member_div").hide().html("");
					$("#mobile_no").val("");
				}else if(data.success == 2){					
					$html = "<label class='form-label'>Patients*</label><select name='member' class='form-select'>";
					for(var i=0; i < data.members.length; i++){
						$html += "<option value='"+data.members[i].id+"'>"+data.members[i].name+"</option>";
					}
					$html +=  "</select>";					
					$("#member_div").show().html($html);
				} else {
					$html = "<div>There is a technical error or change token status.</div>"
					$(".token_msg").show().html($html);
				}
			},
			cache: false,
			contentType: false,
			processData: false
		});		
	});
</script>
@endsection