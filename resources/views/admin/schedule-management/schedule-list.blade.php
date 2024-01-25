@extends('layouts.app', ['isSidebar' => true, 'isNavbar' => true, 'isFooter' => true])
@section('schedule', 'active')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables/responsive.bootstrap4.min.css') }}">
@endpush
@section('content')
    <div class="dashboard_mainsec">
        <!-- company Details -->
        <div class="companydetails_head">
            <h3 class="heading_title">
                Week Days List
            </h3>
            <div class="comdehead_right">
                <div class="backwardright">
                    <a href="{{ route('admin.schedule.list') }}"><i class="fa fa-backward"></i></a>
                </div>
            </div>
        </div>
        <div class="company_profiles card-body">
            <div class="row">
                <div class="col-6">
                    {{-- @dd($fetchSchedule->valid_date) --}}
                    <h5>Clinic Name:
                        {{ $fetchSchedule?->profileClinic?->clinic_name ?? '' }}
                    </h5>
                    <input type="hidden" class="clinicId" value="{{ $fetchSchedule?->profileClinic?->id ?? '' }}">
                    <input type="hidden" class="doctoreId" value="{{ $fetchSchedule?->users?->id ?? '' }}">
                </div>
                <div class="col-6">
                    <h5>Doctor Name:
                        {{ $fetchSchedule?->users?->name ?? '' }}
                    </h5>
                </div>
            </div>
        </div>
        @php
            $today = \Carbon\Carbon::today();
            $daysDifference = $today->diffInDays($fetchSchedule->valid_date);
        @endphp
        {{-- @dd($fetchSchedule->) --}}
        <div class="company_profiles card-body">
            <div class="stats_box row">
                @foreach (range(0, $daysDifference) as $i)
                    @php
                        $date = \Carbon\Carbon::today()->addDays($i);
                    @endphp
                    <div class="col-2">
                        <div class="statsb_single">
                            <input type="radio" id="date{{ $i }}" name="booking_date"
                                value="{{ $date->format('Y-m-d') }}" class="booking_date">
                            <label for="date{{ $i }}" class="statmain_box">
                                {{ $date->format('D') }}
                                <h6>{{ $date->format('d') }}</h6>
                                {{ $date->format('M') }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        {{-- <div class="company_profiles card-body"> --}}
        <div class="stats_box row" id="bookingTimes">
            <div class="time_row" style="display:none;">
                <!-- Booking times will be populated dynamically -->
            </div>
        </div>
        {{-- </div> --}}
        {{-- <div class="company_profiles card-body">
            <div class="time_row">
                <div class="stats_box row">
                    @foreach (\Carbon\CarbonPeriod::create('8:00', '15 minutes', '24:00') as $k => $time)
                        <div class="col">
                            <div class="slot_statsb_single">
                                <input id="time{{ $k }}" type="radio" name="booking_time"
                                    value="{{ $time->format('h:i A') }}" class="booking_time">
                                <div class="statmain_box">
                                    <label for="time{{ $k }}" class="slot_text-dsgn">
                                        {{ $time->format('h:i A') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div> --}}
        {{-- <div class="company_profiles card-body">
            <div class="time_row">
                <div class="stats_box row">
                    @foreach (\Carbon\CarbonPeriod::create('8:00', '15 minutes', '24:00') as $k => $time)
                        <div class="col">
                            <div class="slot_statsb_single">
                                <input id="time{{ $k }}" type="radio" name="booking_time"
                                    value="{{ $time }}" class="booking_time"
                                    data-date="{{ $time->format('Y-m-d') }}">
                                <div class="statmain_box">
                                    <label for="time{{ $k }}" class="slot_text-dsgn">
                                        @php echo $time->format('h:i A') @endphp
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div> --}}
    </div>
@endsection
@push('scripts')
    <script>
        var baseUrl = APP_URL + "/";
        $(document).ready(function() {
            var getClinicData = $('.clinicId').val();
            var getDoctorId = $('.doctoreId').val();
            $(document).on("change", ".booking_date", function() {
                var selectedDate = $(this).val();
                // alert(selectedDate + "/" + getClinicData + "/" + getDoctorId)
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });
                $.ajax({
                    url: baseUrl + "admin/schedule/get-booking-times",
                    method: 'POST',
                    data: {
                        date: selectedDate,
                        doctorId: getDoctorId,
                        clinicId: getClinicData
                    },
                    // dataType: 'json',
                    success: function(response) {
                        $("#bookingTimes").html(response);
                        $('.time_row').fadeIn();
                    },
                    error: function(error) {
                        console.error('Error fetching booking times:', error);
                    }
                });
            });
        });
    </script>
@endpush
