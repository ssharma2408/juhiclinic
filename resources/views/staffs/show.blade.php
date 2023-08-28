@extends('layouts.layout')

@section('page')
 
	<div>Staff Details</div>
 
@endsection
@section('content')

<div class="page-title mg-top-50">
	<div class="container">
		<a class="float-left" href="/">Home</a>
		<span class="float-right">Staff</span>
	</div>
</div>
<div class="balance-area pd-top-40">
	<div class="container">
		<div class="section-title">
			<h3 class="title">Staff Details</h3>

		</div>
		<div class="balance-area-bg bg-transaction-details">
			<div class="balance-title text-center">
				<h1>{{ $staff->name }}</h1>				
			</div>
			<div class="ba-balance-inner text-center" style="background-image: url(assets/img/bg/2.png);">
				<div class="icon">
					<i class="fa fa-map-marker"></i>
				</div>
				<h6 class="title">256 Elizaberth Ave, Brooklyn, CA, 90025</h6>
			</div>
		</div>
	</div>
</div>
<!-- balance End -->

<!-- my-clinic start -->
<div class="my-clinic-details pd-top-36">
	<div class="container">
		<ul class="my-clinic-details-inner">
			<li class="my-clinic-details-title bg-purple">
				<span class="float-left">Timings</span>
				<span class="float-right"><i class="fa fa-calendar"></i></span>
			</li>
			<li>
				<span class="float-left">Monday</span>
				<div class="float-right">
					<a href="#" class="btn-c btn-red btn-sm ml-auto">Edit</a>
				</div>
				<div class="clearfix mb-3"></div>
				<div class="d-flex justify-content-between mb-2">
					<span>Start Time:</span> <span>10:00AM - 2:00PM</span>
				</div>
				<div class="d-flex justify-content-between">
					<span>Leaving Time:</span> <span>4:00PM - 7:00PM</span>
				</div>
			</li>
			<li>
				<span class="float-left">Tuesday</span>
				<div class="float-right">
					<a href="#" class="btn-c btn-red btn-sm ml-auto">Edit</a>
				</div>
				<div class="clearfix mb-3"></div>
				<div class="d-flex justify-content-between mb-2">
					<span>Start Time:</span> <span>10:00AM - 2:00PM</span>
				</div>
				<div class="d-flex justify-content-between">
					<span>Leaving Time:</span> <span>4:00PM - 7:00PM</span>
				</div>
			</li>
			<li>
				<span class="float-left">Wedenesday</span>
				<div class="float-right">
					<a href="#" class="btn-c btn-red btn-sm ml-auto">Edit</a>
				</div>
				<div class="clearfix mb-3"></div>
				<div class="d-flex justify-content-between mb-2">
					<span>Start Time:</span> <span>10:00AM - 2:00PM</span>
				</div>
				<div class="d-flex justify-content-between">
					<span>Leaving Time:</span> <span>4:00PM - 7:00PM</span>
				</div>
			</li>
			<li>
				<span class="float-left">Thursday</span>
				<div class="float-right">
					<a href="#" class="btn-c btn-red btn-sm ml-auto">Edit</a>
				</div>
				<div class="clearfix mb-3"></div>
				<div class="d-flex justify-content-between mb-2">
					<span>Start Time:</span> <span>10:00AM - 2:00PM</span>
				</div>
				<div class="d-flex justify-content-between">
					<span>Leaving Time:</span> <span>4:00PM - 7:00PM</span>
				</div>
			</li>
			<li>
				<span class="float-left">Friday</span>
				<div class="float-right">
					<a href="#" class="btn-c btn-red btn-sm ml-auto">Edit</a>
				</div>
				<div class="clearfix mb-3"></div>
				<div class="d-flex justify-content-between mb-2">
					<span>Start Time:</span> <span>10:00AM - 2:00PM</span>
				</div>
				<div class="d-flex justify-content-between">
					<span>Leaving Time:</span> <span>4:00PM - 7:00PM</span>
				</div>
			</li>
			<li>
				<span class="float-left">Saturday</span>
				<div class="float-right">
					<a href="#" class="btn-c btn-red btn-sm ml-auto">Edit</a>
				</div>
				<div class="clearfix mb-3"></div>
				<div class="d-flex justify-content-between mb-2">
					<span>Start Time:</span> <span>10:00AM</span>
				</div>
				<div class="d-flex justify-content-between">
					<span>Leaving Time:</span> <span>2:00PM</span>
				</div>
			</li>
		</ul>
	</div>
</div>
<div class="my-clinic-details pd-top-36">
	<div class="container">
		<ul class="my-clinic-details-inner">
			<li class="my-clinic-details-title bg-green">
				<span class="float-left">Open Slot</span>
				<span class="float-right"><i class="fa fa-envelope-open"></i></span>
			</li>
		</ul>
		<div class="ba-slot-inner bg-white mb-4">
			<ul>
				<li><span>10:00 AM - 2:00 PM</span><a href="clinic-tokens.html"> Token</a></li>
				<li><span>4:00 PM - 7:00 PM</span><a href="clinic-tokens.html"> Token</a></li>
			</ul>
		</div>
	</div>
</div>
<!-- my-clinic End -->

<div class="btn-wrap mg-top-40 text-center">
	<div class="container">
		<p class="btn-content-text">If haveing any timing issue, Please <a href="contact.html">contact us</a></p>
	</div>
</div>



@endsection