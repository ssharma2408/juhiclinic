@extends('layouts.layout')

@section('title')
Update Member
@stop
@section('description', '')
@section('keywords', '')

@section('page')

<div>Update Member</div>

@endsection
@section('content')



<div class="section-title text-uppercase">
    <h3 class="sub-title">{{$member->name}}</h3>
</div>
<div class="staff-area">
    <form method="POST" action="{{ route('family.update', [$member->id]) }}" class="staff-form-inner">
        @method('PUT')
        @csrf
		@if($member->is_dependent)
			<div class="form-check form-switch single-input-wrap form-group mb-3">					
				<input class="form-check-input" name="has_mobile" type="checkbox" role="switch" id="has_mobile" value = "{{ old('has_mobile')}}">
				<label class="form-check-label" for="has_mobile">Has Mobile</label>						
			</div>
		@endif
        <div class="row">
            <div class="col-md-6">
                <div class="single-input-wrap form-group mb-3">
                    <label class="form-label">Name</label>
                    <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $member->name) }}" required>
                    @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                    @endif
                </div>
            </div>
			@if(!$member->is_dependent)
				<div class="col-md-6" id="mobile_div">
					<div class="single-input-wrap form-group mb-3">
					<label class="form-label">Mobile</label>
						<div class="input-box" style="position: relative;">
							<span class="prefix position-absolute top-50 start-0 translate-middle ms-4">+91</span>
							<input id="mobile_number" class="form-control ps-5" type="text" name="mobile_number" value="{{ old('mobile_number', str_replace('+91', '', $member->mobile_number)) }}" required autocomplete="mobile_number" autofocus>
						</div>
						@error('mobile_number')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror
					</div>
				</div>
			@else
				<div class="col-md-6" id="mobile_div_dep">
					<div class="single-input-wrap form-group mb-3">
					<label class="form-label">Mobile</label>
						<div class="input-box" style="position: relative;">
							<span class="prefix position-absolute top-50 start-0 translate-middle ms-4">+91</span>
							<input id="mobile_number_dep" class="form-control ps-5" type="text" name="mobile_number" value="{{ old('mobile_number') }}" autocomplete="mobile_number" autofocus>
						</div>
						@error('mobile_number')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror
					</div>
				</div>
			@endif
            <div class="col-md-6">
				<div class="single-input-wrap form-group mb-3">
				<label class="form-label">Gender</label>
					<select name="gender" required class="form-select">
						<option value="">Please Select</option>
						<option @if(old('gender')==1 || $member->gender == 1) selected @endif value="1">Male</option>
						<option @if(old('gender')==2 || $member->gender == 2) selected @endif value="2">Female</option>
						<option @if(old('gender')==3 || $member->gender == 3) selected @endif value="3">Other</option>
					</select>
					@error('gender')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>
			</div>				
			<div class="col-md-6">
				<div class="single-input-wrap form-group mb-3">
				<label class="form-label">Date of Birth</label>
					<input class="form-control" type="date" name="dob" max="<?php echo date('Y-m-d'); ?>" value="{{ old('dob', $member->dob) }}" />
					@error('dob')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>
			</div>
        </div>		
        <button type="submit" class="btn btn-secondary btn-rounded text-uppercase">Save</button>		
    </form>
</div>


@endsection
@section('scripts')
@parent
<script>
	$(function() {
		$("#mobile_div_dep").hide();
	});
	$("#has_mobile").click(function (){
		if($('#has_mobile').is(':checked')){			
			$("#mobile_number_dep").val("").prop('required', true);
		}else{			
			$("#mobile_number_dep").val("").prop('required', false);
		}		
		$("#mobile_div_dep").toggle();
	})
</script>
@endsection