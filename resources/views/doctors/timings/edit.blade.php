@extends('layouts.layout')

@section('page')

<div>Doctors</div>

@endsection

@section('content')
<!-- page-title stary -->
<div class="section-title pt-0 mb-2">
	<h1>Update Timings</h1>
</div>

<form method="post" action="{{route('timings.save', [$details->doctor->id])}}">
	@csrf
	@if(empty($details->opening_hours))
	@foreach($day_arr as $key=>$day)
	<div class="card">
		<div class="card-body">


			<div class="row daycontainer">
				<div class="col-md-2 fs-6 text-secondary align-self-center">
					{{ $day }}
					<input type="checkbox" id="day_{{$key+1}}" name="day_{{$key+1}}" class="check_day" />
				</div>
				<div class="timing col-md-10">
					<div class="row">
						<div class="col-md-3 col-6 mt-3">
							<label for="" class="form-label">Open at</label>
							<input class="form-control" type="time" id="" name="open_{{$key+1}}[]" min="07:00" max="23:00" />
						</div>
						<div class="col-md-3 col-6 mt-3">
							<label for="" class="form-label">Close at</label>
							<input class="form-control" type="time" id="" name="close_{{$key+1}}[]" min="07:00" max="23:00" />
						</div>
						<div class="col-md-3 col-6 mt-3">
							<label for="" class="form-label">
								Max Token
							</label>
							<input class="form-control" type="number" id="" name="maxtoken_{{$key+1}}[]" min="0" />
						</div>
						<div class="col-md-3 col-6 mt-3">
							<label for="" class="form-label">
								Time per Token
							</label>
							<input class="form-control" type="number" id="" name="timepertoken_{{$key+1}}[]" min="0" />
						</div>
						<div class="col-md-3 col-6 mt-3">
							<span class="add_row fs-3 text-secondary" id="add_row" data-key="{{$key+1}}"><i class="ri-add-circle-line"></i></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endforeach
	@else
	@foreach($day_arr as $key=>$day)
	<div class="card">
		<div class="card-body">
			<div class="row daycontainer">
				<div class="col-md-2 fs-6 text-secondary align-self-center">
					{{ $day }}
					@php
					$checked = "";
					if(!isset($details->opening_hours[$key+1])){
					$checked = "checked";
					}
					@endphp
					<input type="checkbox" id="day_{{$key+1}}" name="day_{{$key+1}}" class="check_day" {{$checked}} />
				</div>
				<div class="timing col-md-10">
					@if(isset($details->opening_hours[$key+1]))
					@foreach($details->opening_hours[$key+1] as $slot=>$timing)
					<div class="row">
						<div class="col-md-3 col-6 mt-3">
							<label for="" class="form-label">
								Open at
							</label>
							<input class="form-control" type="time" id="" name="open_{{$key+1}}[]" min="07:00" max="23:00" value="{{$timing->start_hour}}" />
						</div>
						<div class="col-md-3 col-6 mt-3">
							<label for="" class="form-label">
								Close at
							</label>
							<input class="form-control" type="time" id="" name="close_{{$key+1}}[]" min="07:00" max="23:00" value="{{$timing->end_hour}}" />
						</div>
						<div class="col-md-3 col-6 mt-3">
							<label for="" class="form-label">
								Max Token
							</label>
							<input class="form-control" type="number" id="" name="maxtoken_{{$key+1}}[]" min="0" value="{{$timing->max_token}}" />
						</div>
						<div class="col-md-3 col-6 mt-3">
							<label for="" class="form-label">
								Time per Token
							</label>
							<div class="d-flex">
								<input class="form-control me-2" type="number" id="" name="timepertoken_{{$key+1}}[]" min="0" value="{{$timing->time_per_token}}" />
								@if($slot ==0)
								<span class="add_row fs-3 text-secondary" id="add_row" data-key="{{$key+1}}"><i class="ri-add-circle-line"></i></span>
								@else
								<span class="remove_row fs-3 text-secondary" id="remove_row" data-key="{{$key+1}}"><i class="ri-indeterminate-circle-line"></i></span>
								@endif
							</div>
						</div>
					</div>
					@endforeach
					@else
					<div class="row">
						<div class="col-md-3 col-6 mt-3">
							<label for="" class="form-label">
								Open at
							</label>
							<input class="form-control" type="time" id="" name="open_{{$key+1}}[]" min="07:00" max="23:00" />
						</div>
						<div class="col-md-3 col-6 mt-3">
							<label for="" class="form-label">
								Close at
							</label>
							<input class="form-control" type="time" id="" name="close_{{$key+1}}[]" min="07:00" max="23:00" />
						</div>
						<div class="col-md-3 col-6 mt-3">
							<label for="" class="form-label">
								Max Token
							</label>
							<input class="form-control" type="number" id="" name="maxtoken_{{$key+1}}[]" min="0" />
						</div>
						<div class="col-md-3 col-6 mt-3">
							<label for="" class="form-label">
								Time per Token
							</label>
							<div class="d-flex">
							<input class="form-control me-2" type="number" id="" name="timepertoken_{{$key+1}}[]" min="0" />
							<span class="add_row fs-3 text-secondary" id="add_row" data-key="{{$key+1}}"><i class="ri-add-circle-line"></i></span>
							</div>
							
						</div>
					
					</div>
					@endif
				</div>
			</div>
		</div>
	</div>
	@endforeach
	@endif
	<button type="submit" class="btn btn-primary btn-rounded">Save</button>
	<input type="hidden" name="user_id" value="{{ $details->doctor->id }}">
</form>

<!-- goal area End -->
@endsection
@section('scripts')
@parent
<script>
	$(".check_day").click(function() {
		if ($(this).prop('checked') == true) {
			$(this).parent().next(".timing").find('input').val("");
		}
		$(this).parent().next(".timing").toggle();
	});

	$(".add_row").click(function() {
		$(this).parent().parent().parent().parent().append(row_html($(this).data("key")));
	});
	$(".timing").on('click', '.remove_row', function() {
		$(this).parent().parent().parent().remove();
	});

	function row_html(key) {
		return '<div class="row"><div class="col-md-3 col-6 mt-3"><label for="" class="form-label">Open at</label> <input class="form-control" type="time" id="" name="open_' + key + '[]" min="07:00" max="23:00" /></div><div class="col-md-3 col-6 mt-3"><label for="" class="form-label">Close at</label><input class="form-control" type="time" id="" name="close_' + key + '[]" min="07:00" max="23:00" /></div><div class="col-md-3 col-6 mt-3"><label for="" class="form-label">Max Token</label><input class="form-control" type="number" id="" name="maxtoken_' + key + '[]" min="0" /></div><div class="col-md-3 col-6 mt-3"><label for="" class="form-label">Time per Token</label><div class="d-flex"><input class="form-control me-2" type="number" id="" name="timepertoken_{{$key+1}}[]" min="0" /><span class="remove_row fs-3 text-secondary" id="remove_row fs-3 text-secondary"><i class="ri-indeterminate-circle-line"></i></span></div></div></div>';
	}
	$(function() {
		$(".check_day").each(function() {
			if ($(this).prop('checked') == true) {
				$(this).parent().next(".timing").toggle();
			}
		});
	});
</script>
@endsection