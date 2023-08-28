@extends('layouts.layout')

@section('page')

<h1>Staff Dashboard</h1>

@endsection

@section('content')
<!-- page-title stary -->

<!-- page-title end -->
<!-- balance start -->
<div class="section-title pt-0">
	<h3>Clinic Admin Closed</h3>
</div>
<form method="post" action="{{route('clinicadmin.closed.save')}}">
	@csrf
	<div class="closed_container">
		@if(empty($details))

		<div class="row mb-2 align-items-end">
			<div class="col">
				<label class="form-label" for="">Date</label>

				<input class="form-control" type="date" id="" name="closedon[]" />
			</div>
			<div class="col-auto">
				<span class="add_row fs-3 text-secondary" id="add_row"><i class="ri-add-circle-line"></i></span>
			</div>
		</div>
		@else
		@foreach($details as $key=>$val)
		<div class="row mb-2 align-items-end">
			<div class="col">
				<label for="" class="form-label">Date</label>
				<input class="form-control" type="date" id="" name="closedon[]" value="{{$val->closed_on}}" />
			</div>
			<div class="col-auto">
				@if($key ==0)
				<span class="add_row fs-3 text-secondary" id="add_row"><i class="ri-add-circle-line"></i></span>
				@else
				<span class="remove_row fs-3 text-secondary" id="remove_row"><i class="ri-indeterminate-circle-line"></i></span>
				@endif
			</div>
		</div>
		@endforeach
		@endif
	</div>
	<button type="submit" class="btn btn-secondary btn-rounded">Save</button>
	<input type="hidden" name="user_id" value="{{ $clinic_admin }}">
</form>

<!-- balance End -->
@endsection

@section('scripts')
@parent
<script>
	$(".add_row").click(function() {
		$(this).parent().parent().parent().append(row_html());
	});
	$(".closed_container").on('click', '.remove_row', function() {
		$(this).parent().parent().remove();
	});

	function row_html() {
		return '<div class="row mb-2 align-items-end"><div class="col">Date <input class="form-control" type="date" id="" name="closedon[]"  /></div><div class="col-auto"><span class="remove_row fs-3 text-secondary" id="remove_row"><i class="ri-indeterminate-circle-line"></i></span></div></div>';
	}
</script>
@endsection