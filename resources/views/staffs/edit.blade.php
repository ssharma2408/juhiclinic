@extends('layouts.layout')

@section('page')

<div>Add Staff</div>

@endsection
@section('content')



<div class="section-title text-uppercase">
    <h3 class="sub-title">{{$staff->name}}</h3>
</div>
<div class="staff-area">
    <form method="POST" action="{{ route("staffs.update", [$staff->id]) }}" class="staff-form-inner">
        @method('PUT')
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="single-input-wrap form-group mb-3">
                    <label class="form-label">Name</label>
                    <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $staff->name) }}" required>
                    @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="single-input-wrap form-group mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-control" type="email" name="email" id="email" value="{{ old('email', $staff->email) }}" required>
                    @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="single-input-wrap form-group mb-3">
                    <label class="form-label">Mobile</label>
                    <input class="form-control" type="number" name="mobile" id="mobile" value="{{ old('mobile', $staff->mobile_number) }}" required>
                    @if($errors->has('mobile'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mobile') }}
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="single-input-wrap form-group mb-3">
                    <label class="form-label">Prefix</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text">{{ Session::get('user_details')->prefix }}_</span>
                        <input type="text" class="form-control" name="username" id="username" value="{{ old('username', $staff->username) }}" required>
                    </div>
                    @if($errors->has('username'))
                    <div class="invalid-feedback">
                        {{ $errors->first('username') }}
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="single-input-wrap form-group mb-3">
                    <label class="form-label">Password</label>
                    <input class="form-control" type="password" name="password" id="password" value="">
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

</script>
@endsection