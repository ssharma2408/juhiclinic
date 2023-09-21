@extends('layouts.layout')

@section('title')
    Patient Booking
@stop
@section('description', '')
@section('keywords', '')

@section('page')

    <div>
        Patient Booking</div>

@endsection
<?php
date_default_timezone_set('Asia/Kolkata');
$timezone = 'Asia/Kolkata';
?>

@section('content')

    <!-- balance start -->

    <div class="section-title pt-0">
        <div class="row d-flex justify-content-between align-items-center">
            <div class="col-auto">
                <h3 class="sub-title text-uppercase">Members</h3>
            </div>
            <p class="text-descripstion secondary-text mb-0 col-auto" id="time"></p>
        </div>
        <p class="text-descripstion secondary-text mb-0">Book Appointment to Dr. {{ $doctor->name }}</p>


    </div>
    @foreach ($members->members as $member)
        <div class="card my-2">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="details fw-bold secondary-text text-uppercase mb-0">
                        {{ $member->name }}
                    </div>
                    <div class="ms-auto">
                        @if (!array_key_exists($member->id, $is_booked))
                            <button type="button" id="doc_{{ $doctor->id }}_{{ $slot_id }}_{{ $member->id }}"
                                class="btn btn-secondary btn-rounded btn-sm book">Book</button>
                        @else
                            @if ($is_booked[$member->id]['status'] != 0)
                            @else
                                <div class="badge text-bg-danger ms-auto">Token closed</div>
                            @endif
                        @endif
                    </div>
                </div>

                @if (!array_key_exists($member->id, $is_booked))

                    <div class="token_details" id="token_details_{{ $member->id }}"></div>
                @else
                    @if ($is_booked[$member->id]['status'] != 0)
                        @if (!$is_booked[$member->id]['is_tokens_discarded'])
                            <div class="token_details row gx-0" id="token_details_{{ $member->id }}">
                                <div class="col-8 small">
                                    Your token number is <b>{{ $is_booked[$member->id]['token_number'] }}</b>
                                    <p class="mb-1">
                                        @if ($is_booked[$member->id]['current_token'] != 'Not Started' && $is_booked[$member->id]['message'] == '')
                                            Current token:
                                            <b>{{ $is_booked[$member->id]['current_token'] }}</b> and estimated time is
                                            <b>{{ $is_booked[$member->id]['estimated_time'] }}</b>
                                        @else
                                            @if ($is_booked[$member->id]['current_token'] == 'Not Started')
                                                Current token: <b>{{ $is_booked[$member->id]['current_token'] }}</b>
                                            @else
                                                Message: <b>{{ $is_booked[$member->id]['message'] }}</b>
                                            @endif
                                        @endif
                                    </p>
                                </div>
                                <div class="col-auto ms-auto">
                                    <button class='btn btn-secondary btn-rounded btn-sm refresh_status'
                                        id='doc_{{ $doctor->id }}_{{ $slot_id }}_{{ $member->id }}'
                                        type='button'>Refresh</button>
                                </div>
                            </div>
                            <div class="secondary-text small">* Estimated time depends on doctor sign in time and clinic
                                opening time.</div>
                        @else
                            <b>{{ $is_booked[$member->id]['message'] }}</b>
                        @endif
                    @endif
                @endif
            </div>
        </div>

    @endforeach

    <!-- balance End -->
@endsection

@section('scripts')
    @parent
    <script>
        var timestamp = '<?php echo time(); ?>';

        function updateTime() {
            var time_arr = Date(timestamp).split(" ");
            $('#time').html(time_arr[0] + " " + time_arr[1] + " " + time_arr[2] + " " + time_arr[4]);

            timestamp++;
        }
        $(function() {
            setInterval(updateTime, 1000);
        });

        $(".book").click(function() {
            var doc_id = $(this).attr("id").split("_")[1];
            var slot_id = $(this).attr("id").split("_")[2];
            var patient_id = $(this).attr("id").split("_")[3];
            $.ajax({
                type: 'GET',
                url: '/user_dashboard/book-appointment/' + doc_id + '/' + slot_id + '/' + patient_id,
                success: function(data) {
                    if (data.success) {
                        $("#doc_" + doc_id + "_" + slot_id + "_" + patient_id).hide();
                        $html = "<div class='alert alert-success alert-dismissible fade show'>" + data
                            .msg + "</div><div class='col-8'>Your token number is <b>" + data.token
                            .token_number + "</b></div><div>";

                        if (data.token.current_token != "Not Started" && data.token.message == "") {
                            $html += "Current token: <b>" + data.token.current_token +
                                "</b> and estimated time is <b>" + data.token.estimated_time + "</b>";
                        } else {
                            if (data.token.current_token == "Not Started") {
                                $html += "Current token: <b>" + data.token.current_token + "</b>";
                            } else {
                                $html += "Message: <b>" + data.token.message + "</b>";
                            }
                        }
                        $html +=
                            "</div><div class='col-auto ms-auto'><button class='btn btn-secondary btn-rounded btn-sm refresh_status' id='doc_" +
                            doc_id + "_" + slot_id + "_" + patient_id +
                            "' type='button'>Refresh</button></div>";

                        $("#token_details_" + patient_id).show().html($html);
                    } else {
                        $html = "<div>" + data.msg + "</div>"
                        $("#token_details_" + patient_id).show().html($html);
                    }
                }
            });
        });

        $(document).on("click", ".refresh_status", function() {
            var doc_id = $(this).attr("id").split("_")[1];
            var slot_id = $(this).attr("id").split("_")[2];
            var patient_id = $(this).attr("id").split("_")[3];

            $.ajax({
                type: 'GET',
                url: '/user_dashboard/refresh-status/' + doc_id + '/' + slot_id + '/' + patient_id,
                success: function(data) {
                    if (data.success) {
                        $("#doc_" + doc_id + "_" + slot_id + "_" + patient_id).hide();
                        $html = "<div class='col-8'>Your token number is <b>" + data.token
                            .token_number + "</b></div><div>";

                        if (data.token.current_token != "Not Started" && data.token.message == "") {
                            $html += "Current token: <b>" + data.token.current_token +
                                "</b> and estimated time is <b>" + data.token.estimated_time + "</b>";
                        } else {
                            if (data.token.current_token == "Not Started") {
                                $html += "Current token: <b>" + data.token.current_token + "</b>";
                            } else {
                                $html += "Message: <b>" + data.token.message + "</b>";
                            }
                        }

                        $html +=
                            "</div><div class='col-auto ms-auto'><button class='btn btn-secondary btn-rounded btn-sm refresh_status' id='doc_" +
                            doc_id + "_" + slot_id + "_" + patient_id +
                            "' type='button'>Refresh</button></div>";

                        $("#token_details_" + patient_id).html($html);
                    } else {
                        $html = "<div>" + data.msg + "</div>"
                        $("#token_details_" + patient_id).html($html);
                    }
                }
            });
        });
    </script>
@endsection
