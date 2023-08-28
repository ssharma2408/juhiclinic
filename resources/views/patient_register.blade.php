@extends('layouts.layout')

@section('page')

<div>Patient Sign In</div>

@endsection

@section('content')


<div class="row">
    <div class="col-lg-5 mx-auto">
        <div class="card-box">
            <div class="section-title text-center pt-0">
                <h3 class="text-uppercase">Patient Register</h3>
                <div class="text-descripstion secondary-text text-center">
                Please fill in the form below
            </div>
            </div>
          
            <form method="post" action="{{ route('patient.register.save') }}" class="contact-form-inner">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                @include('layouts.partials.messages')
                <div class="single-input-wrap form-group mb-3">
                    <label class="form-label">Name*</label>
                    <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="" required="required" autocomplete="name" autofocus>
                </div>
                @if ($errors->has('name'))
                <span class="text-danger text-left">{{ $errors->first('name') }}</span>
                @endif
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
                <div class="single-input-wrap form-group mb-3">
                    <label class="form-label">Gender*</label>
                    <select name="gender" required class="form-select">
                        <option value="">Please Select</option>
                        <option @if(old('gender')==1) selected @endif value="1">Male</option>
                        <option @if(old('gender')==2) selected @endif value="2">Female</option>
                        <option @if(old('gender')==3) selected @endif value="3">Other</option>
                    </select>
                </div>
                @if ($errors->has('gender'))
                <span class="text-danger text-left">{{ $errors->first('gender') }}</span>
                @endif
                <div class="single-input-wrap form-group mb-3">
                    <label class="form-label">Date of Birth</label>
                    <input class="form-control" type="date" name="dob" max="<?php echo date('Y-m-d'); ?>" value="{{ old('dob') }}" />
                </div>
                @if ($errors->has('dob'))
                <span class="text-danger text-left">{{ $errors->first('dob') }}</span>
                @endif

                <div class="single-input-wrap form-group mb-3">
                    <label class="form-label">ReCaptcha:</label>
                    <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}"></div>
                    @if ($errors->has('g-recaptcha-response'))
                    <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                    @endif
                </div>

                <div class="single-input-wrap form-group mb-3 text-center">
                    <button class="btn btn-secondary btn-rounded" type="submit">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script src='https://www.google.com/recaptcha/api.js'></script>
@endsection