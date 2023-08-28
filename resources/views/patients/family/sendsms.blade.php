@extends('layouts.layout')

@section('title')
Send SMS
@stop
@section('description', '')
@section('keywords', '')

@section('page')

<div>Send SMS</div>

@endsection
@section('content')



<div class="section-title text-uppercase">
    <h3 class="sub-title">Send SMS</h3>
</div>
@if(!empty($success))
	<div class="alert alert-danger" role="alert">
            {{ $success }}
    </div>
@endif

<div class="staff-area">
    <form method="POST" action="{{ route('family.sendsms') }}" class="staff-form-inner">       
        @csrf
		
        <div class="row">
            <div class="col-md-6">
                <div class="single-input-wrap form-group mb-3">
                    <label class="form-label">Name</label>
                    <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $member['name']) }}" required>
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
						<input id="mobile_number" class="form-control ps-5" type="text" name="mobile_number" value="{{ old('mobile_number', str_replace('+91', '', $member['mobile_number'])) }}" required autocomplete="mobile_number" autofocus>
					</div>
					@error('mobile_number')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>
			</div>         
        </div>		
        <button type="submit" class="btn btn-secondary btn-rounded text-uppercase">Send Joining Link</button>		
    </form>
</div>


@endsection
@section('scripts')
@parent
<script>

</script>
@endsection