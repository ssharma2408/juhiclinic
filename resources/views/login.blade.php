@extends('layouts.layout')

@section('page')

<div>Sign In</div>

@endsection

@section('content')

<div class="text-center">
    <div class="page-title">
        <h2> Welcome to {{$_ENV['DOMAIN']}}</h2>
    </div>
    <div class="text-descripstion secondary-text">
        <p>Lorem Ipsum is simply dummy text of the printing and <br>
            typesetting industry. Lorem Ipsum has been </p>
    </div>
</div>
<div class="row">
    <div class="col-md-5 mx-auto">
        <div class="card-box text-center">
            <div class="section-title">
                <h3 class="text-uppercase">Clinic Login</h3>
                <div class="text-descripstion secondary-text">
                Please login to your account
            </div>
            </div>
           
            <div class="form-wrap text-start">
                <form method="post" action="{{ route('login.perform') }}" class="contact-form-inner">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    @include('layouts.partials.messages')
                    <div class="single-input-wrap form-group mb-3">
                        <label class="form-label">Email address<span class="star-red">*</span></label>
                        <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="Enter your email address" required="required" autofocus>
                    </div>
                    @if ($errors->has('username'))
                    <span class="text-danger text-left">{{ $errors->first('username') }}</span>
                    @endif
                    <div class="single-input-wrap form-group mb-3">
                        <label class="form-label">Password<span class="star-red">*</span></label>

                        <input type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="Enter your password" required="required">
                    </div>
                    @if ($errors->has('password'))
                    <span class="text-danger text-left">{{ $errors->first('password') }}</span>
                    @endif
                    <div class="single-input-wrap form-group mb-3 text-center">
                        <button class="btn btn-secondary btn-rounded" type="submit">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')

@endpush