@extends('layouts.layout')

@section('title')
Add Member
@stop
@section('description', '')
@section('keywords', '')

@section('page')
<h1 class="page-title ">ADD MEMBER</h1>
<p class="text-descripstion text-secondary">Lorem Ipsum is simply dummy text of the printing and </p>
@endsection
@section('content')
	<div class="section-title text-uppercase">
		<h3 class="sub-title">Add Member</h3>
	</div>
	<div class="staff-area">
		<form action="{{ route('family.store') }}" method="post" class="staff-form-inner">
			@csrf
			<div class="form-check form-switch single-input-wrap form-group mb-3">					
				<input class="form-check-input" name="has_mobile" type="checkbox" role="switch" id="has_mobile" checked>
				<label class="form-check-label" for="has_mobile">Has Mobile</label>						
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="single-input-wrap form-group mb-3">
					<label class="form-label">Name</label>
						<input class="form-control" type="text" placeholder="Name" name="name" required autocomplete="name" autofocus>
						@error('name')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror
					</div>
				</div>
				<div class="col-md-6" id="mobile_div">
					<div class="single-input-wrap form-group mb-3">
					<label class="form-label">Mobile</label>
						<div class="input-box" style="position: relative;">
							<span class="prefix position-absolute top-50 start-0 translate-middle ms-4">+91</span>
							<input id="mobile_number" class="form-control ps-5" type="text" name="mobile_number" value="{{ old('mobile_number') }}" required autocomplete="mobile_number" autofocus>
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
							<option @if(old('gender')==1) selected @endif value="1">Male</option>
							<option @if(old('gender')==2) selected @endif value="2">Female</option>
							<option @if(old('gender')==3) selected @endif value="3">Other</option>
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
						<input class="form-control" type="date" name="dob" max="<?php echo date('Y-m-d'); ?>" value="{{ old('dob') }}" />
						@error('dob')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror
					</div>
				</div>
			</div>

			<button type="submit" class="btn btn-secondary btn-rounded">Save</button>
		</form>
	</div>


@endsection
@section('scripts')
@parent
<script>
	$("#has_mobile").click(function (){
		$("#mobile_number").val("").prop('required', false);
		$("#mobile_div").toggle();
	})
</script>
@endsection