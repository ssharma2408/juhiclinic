@extends('layouts.layout')

@section('title')
    Patient Dashboard
@stop
@section('description', '')
@section('keywords', '')

@section('page')

    <div>Patient Dashboard</div>

@endsection
<?php
date_default_timezone_set('Asia/Kolkata');
$timezone = 'Asia/Kolkata';
?>

@section('content')

    <!-- balance start -->
    @php
        $day = date('N');
    @endphp
    <div class="section-title pt-0 mb-2 row d-flex justify-content-between align-items-center">
        <h3 class="title col-auto">Doctors</h3>
        <p class="text-descripstion secondary-text mb-0 col-auto" id="time"></p>
    </div>
    <div class="row gx-3 row-cols-1  row-cols-lg-3">
        @foreach ($doctor_arr as $doctor)
            @if (isset($doctor['timings'][$day]))
                <div class="col">
                    <div class="secondary-text card min-h-100">
                        <div class="card-body">
                            <h5 class="card-title text-secondary"> Dr. {{ $doctor['name'] }}</h5>
                            @foreach ($doctor['timings'][$day] as $slot => $timing)
                                <div class="row my-2 justify-content-between align-items-center">
                                    <div class="col-auto">
                                        <span class="d-block secondary-text">{{ $timing['start_hour'] }} -
                                            {{ $timing['end_hour'] }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <a href="user_dashboard/booking/{{ $doctor['id'] }}/{{ $timing['slot_id'] }}"
                                            id="doc_{{ $doctor['id'] }}_{{ $timing['slot_id'] }}"
                                            data-endtime="<?php echo strtotime(date('Y-m-d ' . $timing['end_hour'] . '')); ?>"
                                            class="btn btn-secondary btn-rounded btn-sm book">Book</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    <!-- balance End -->
@endsection

@section('scripts')
    @parent
    <script>
        var timestamp = '<?php echo time(); ?>';

        function updateTime() {
            var time_arr = Date(timestamp).split(" ");
            $('#time').html(time_arr[0] + " " + time_arr[1] + " " + time_arr[2] + " " + time_arr[4]);

            $(".book").each(function() {
                var end_time = $(this).data("endtime");

                var currenttime = '<?php echo strtotime(date('Y-m-d H:i:s')); ?>';

                if (end_time < currenttime) {
                    $(this).hide()
                }
            });
            timestamp++;
        }
        $(function() {
            setInterval(updateTime, 1000);
        });
    </script>
@endsection
