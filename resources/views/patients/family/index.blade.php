@extends('layouts.layout')

@section('title')
    Patient
@stop
@section('description', '')
@section('keywords', '')

@section('page')

    <div>Patient</div>

@endsection

@section('content')

    <div class="section-title pt-0">
        <div class="row justify-content-between">
            <div class="col-auto">
                <h3 class="sub-title text-uppercase">My Family</h3>
                @foreach ($members as $key => $all_members)
                    <p class="text-descripstion secondary-text mb-2">{{ $type[$key] }}</p>
                @endforeach
            </div>
            <div class="col-auto">
                <a class="btn btn-secondary btn-rounded" href="{{ route('family.create') }}">
                    <i class="ri-user-add-line"></i>
                    Add New <span class="d-none d-lg-inline-block">Member</span></a>
            </div>
        </div>
    </div>

    @include('layouts.partials.messages')

    <ul class="list-group">

        <li class="my-clinic-details-title bg-body-tertiary list-group-item">
            <div class="d-flex justify-content-between align-items-start fw-bold">
                <span class="col-md-3 ">Name</span>
                <span class="col-md-3">Gender</span>
                <span class="col-md-3 d-none d-lg-block">Date of Birth</span>
                <span class="col-md-3 text-center d-none d-lg-block">Action</span>
            </div>
        </li>


        @foreach ($members as $key => $all_members)
            @if (!empty($all_members))
                @foreach ($all_members as $member)
                    <li class="list-group-item">
                        <div class="d-flex row gx-0 justify-content-between align-items-start">
                            <div class="col-md-3 col-6 text-secondary text-uppercase">
                                <span>{{ $member->name }}</span>
                            </div>
                            <div class="col-md-3 col-auto">
                                <span>
                                    {{ $member->gender == 1 ? 'Men' : ($member->gender == 2 ? 'Women' : 'Other') }}</span>
                            </div>
                            <div class="col-md-3 col-12" data-title="DOB : ">
                                @if (!empty($member->dob))
                                    <span> {{ $member->dob }}</span>
                                @endif
                            </div>

                            <div class="col-md-3 text-center">
                                @if ($member->id == Session::get('user_details')->id)
                                    <div>
                                        <a href="{{ route('patient.profile') }}" class="btn btn-primary btn-sm"
                                            role="button">Update Profile</a>
                                        @if ($member->id != $owner_id)
                                            <form action="{{ route('family.destroy', $member->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure?');" style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <button type="submit" class="btn btn-secondary btn-sm">Exit
                                                    Family</button>
                                            </form>
                                        @endif
                                    </div>
                                @else
                                    <div>
                                        <a href="{{ route('family.edit', $member->id) }}" class="btn btn-primary btn-sm"
                                            role="button">Edit</a>
                                        <form action="{{ route('family.destroy', $member->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure?');" style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <button type="submit" class="btn btn-secondary btn-sm">Delete</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
            @endif
        @endforeach

    </ul>

    <!-- goal area End -->
@endsection
@section('scripts')
    @parent
    <script></script>
@endsection
