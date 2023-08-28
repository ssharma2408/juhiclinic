@extends('layouts.layout')

@section('page')

<div>Staff</div>

@endsection

@section('content')

<div class="section-title text-uppercase pt-0 mb-2 d-flex justify-content-between">
	<h3 class="sub-title">Our Staff </h3>
	<span class="sub-title">Total-{{count($staffs)}}</span>
</div>

@include('layouts.partials.messages')

<div class="row gx-3 row-cols-1  row-cols-lg-2">
	@foreach($staffs as $key => $staff)
	<div class="col">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title text-secondary mb-2">{{$staff->name}}</h5>
				<p class="card-text mb-2 text-nowrap">Email : {{$staff->email}}</p>
				<p class="card-text mb-2">Phone : {{$staff->mobile_number}}</p>
				<p class="card-text mb-2">User ID : {{$staff->username}}</p>
				<div>
					<a href="{{ route('staffs.edit', $staff->id) }}" class="btn btn-primary btn-sm" role="button">Edit</a>
					<form action="{{ route('staffs.destroy', $staff->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="display: inline-block;">
						<input type="hidden" name="_method" value="DELETE">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<button type="submit" class="btn btn-secondary btn-sm">Delete</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	@endforeach
</div>
<div class="text-center">
	<a class="btn btn-secondary btn-rounded" href="{{route('staffs.create') }}">Add New Staff</a>
</div>

<!-- goal area End -->
@endsection
@section('scripts')
@parent
<script>

</script>
@endsection