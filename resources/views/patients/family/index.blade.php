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

<div class="section-title text-uppercase pt-0 mb-2 d-flex justify-content-between">
	<h3 class="sub-title">My Family</h3>	
</div>

@include('layouts.partials.messages')


	@foreach($members as $key => $all_members)
		@if(!empty($all_members))
		<div class="section-title pt-0">
		<h3 class="title-sm text-secondary">{{$type[$key]}}</h3>
		</div>
		
		<div class="row gx-3 row-cols-1  row-cols-lg-2">
			@foreach($all_members as  $member)
			
			<div class="col">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title text-secondary mb-2">{{$member->name}} <i class="ri-{{($member->gender == 1) ? 'men' : (($member->gender == 2) ? 'women' : 'genderless')}}-line"></i></h5>				
						@if(!empty($member->dob))
							<p class="card-text mb-2">Date of Birth : {{$member->dob}}</p>
						@endif
						@if($member->id == Session::get('user_details')->id)
							<div>
								<a href="{{route('patient.profile')}}" class="btn btn-primary btn-sm" role="button">Update Profile</a>
								@if($member->id != $owner_id)
									<form action="{{ route('family.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="display: inline-block;">
										<input type="hidden" name="_method" value="DELETE">
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<button type="submit" class="btn btn-secondary btn-sm">Exit Family</button>
									</form>
								@endif
							</div>
						@else
							<div>
								<a href="{{ route('family.edit', $member->id) }}" class="btn btn-primary btn-sm" role="button">Edit</a>
								<form action="{{ route('family.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="display: inline-block;">
									<input type="hidden" name="_method" value="DELETE">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<button type="submit" class="btn btn-secondary btn-sm">Delete</button>
								</form>
							</div>
						@endif
					</div>
				</div>
			</div>
			@endforeach
			</div>
		@endif
	@endforeach

<div class="text-center">
	<a class="btn btn-secondary btn-rounded" href="{{route('family.create') }}"><i class="ri-user-add-line"></i> Add New Member</a>
</div>

<!-- goal area End -->
@endsection
@section('scripts')
@parent
<script>

</script>
@endsection