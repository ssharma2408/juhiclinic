@extends('layouts.layout')

@section('page')

<div>Patient Sign In</div>

@endsection

@section('content')
<div class="row">
    <div class="col-md-5 mx-auto">
        <div class="card-box">
            <div class="section-title text-center pt-0 text-uppercase">
                <h3>Patient Login</h3>
            </div>
            <div class="text-descripstion secondary-text text-center">
                Lorem Ipsum is simply dummy <br>
                text of the
            </div>
            <form method="post" action="{{ route('patient.login.perform') }}" class="contact-form-inner">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="patient_id" value="{{Session::get('patient_id')}}" />
                @include('layouts.partials.messages')
                <div class="single-input-wrap form-group mb-3">
                    <label class="form-label">OTP*</label>
                    <input id="otp" class="form-control" type="text" name="otp" value="{{ old('otp') }}" required autocomplete="otp" autofocus placeholder="Enter OTP">
					<div class="mt-4">Use <input type="text" value="123456" id="myInput" /> for testing <button type="button" onclick="myFunction()">Copy text</button></div>
                </div>
                @if ($errors->has('otp'))
                <span class="text-danger text-left">{{ $errors->first('otp') }}</span>
                @endif
                <div class="single-input-wrap form-group mb-3 text-center">
                    <button class="btn btn-secondary btn-rounded" type="submit">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
function myFunction() {
  // Get the text field
  var copyText = document.getElementById("myInput");

  // Select the text field
  copyText.select();
  copyText.setSelectionRange(0, 99999); // For mobile devices

   // Copy the text inside the text field
  navigator.clipboard.writeText(copyText.value);

  // Alert the copied text
  alert("Copied the text: " + copyText.value);
}
</script>
@endsection