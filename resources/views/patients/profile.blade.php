@extends('layouts.layout')

@section('page')
 
	<h1>Profile</h1>
 
@endsection
 
@section('content')

<!-- balance start -->
<div class="balance-area pd-top-40">
	<div class="container">
		<div class="section-title">			
			<h3 class="title">Profile</h3>
		</div>
		@include('layouts.partials.messages')
		<div class="staff-area">
			<form action="{{ route('patient.profile.update') }}" method="post" class="staff-form-inner">
				@csrf
				<div class="row">
					<div class="col-md-6">
						<div class="single-input-wrap form-group mb-3">
							<label class="form-label">Name</label>
							<input class="form-control" type="text" name="name" id="name" value="{{ old('name', $details->name) }}" required>
							@if($errors->has('name'))
							<div class="invalid-feedback">
								{{ $errors->first('name') }}
							</div>
							@endif
						</div>
					</div>
					<div class="col-md-6" id="mobile_div">
						<div class="single-input-wrap form-group mb-3">
						<label class="form-label">Mobile</label>
							<div class="input-box" style="position: relative;">
								<span class="prefix position-absolute top-50 start-0 translate-middle ms-4">+91</span>
								<input id="mobile_number" class="form-control ps-5" type="text" name="mobile_number" value="{{ old('mobile_number', str_replace('+91', '', $details->mobile_number)) }}" required autocomplete="mobile_number" autofocus>
							</div>
							@error('mobile_number')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
						</div>
					</div>	
					<div class="col-md-6">
						<div class="single-input-wrap form-group mb-3">
						<label class="form-label">Gender</label>
							<select name="gender" required class="form-select">
								<option value="">Please Select</option>
								<option @if(old('gender')==1 || $details->gender == 1) selected @endif value="1">Male</option>
								<option @if(old('gender')==2 || $details->gender == 2) selected @endif value="2">Female</option>
								<option @if(old('gender')==3 || $details->gender == 3) selected @endif value="3">Other</option>
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
							<input class="form-control" type="date" name="dob" max="<?php echo date('Y-m-d'); ?>" value="{{ old('dob', $details->dob) }}" />
							@error('dob')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
						</div>
					</div>									
				</div>
				<button type="submit" class="btn btn-secondary btn-rounded">Save</button>
				<input type="hidden" name="user_id" value="{{$details->id}}" />
			</form>
		</div>	
	</div>
</div>
<!-- balance End -->
@endsection
 
@push('js')

@endpush