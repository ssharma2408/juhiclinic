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
			<form action="{{ route('doctor.profile.update') }}" method="post" class="staff-form-inner">
				@csrf
				<div class="row">
					<div class="col-md-6">
						<div class="single-input-wrap form-group mb-3">
						<label class="form-label">Name</label>
							<input class="form-control" type="text" placeholder="Name" name="name" required autocomplete="name" autofocus value="{{ old('name', $details->name) }}">
							@error('name')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
						</div>
					</div>
					<div class="col-md-6">
						<div class="single-input-wrap form-group mb-3">
						<label class="form-label">Email</label>
							<input class="form-control" type="email" placeholder="Email Address" name="email" required autocomplete="email" value="{{ old('email', $details->email) }}">
							@error('email')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
						</div>
					</div>
					<div class="col-md-6">
						<div class="single-input-wrap form-group mb-3">
						<label class="form-label">Mobile</label>
							<input class="form-control" type="text" placeholder="Mobile" name="mobile_number" required autocomplete="mobile_number" value="{{ old('mobile_number', $details->mobile_number) }}">
							@error('mobile')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
						</div>
					</div>		
					<div class="col-md-6">
						<div class="single-input-wrap form-group mb-3">
						<label class="form-label">Password</label>
							<input class="form-control" type="password" placeholder="Password" name="password" autocomplete="new-password">
							@error('password')
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