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
        <form action="{{ route('admin.schedule.add') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="uuid" id="uuid" value="{{ $data->id ?? '' }}">
            @php
                $today = \Carbon\Carbon::today();
                $daysDifference = $today->diffInDays($fetchSchedule->valid_date);
            @endphp
            <div class="company_profiles card-body">
                <div class="stats_box row">
                    @foreach (range(0, $daysDifference) as $i)
                        @php
                            $date = \Carbon\Carbon::today()->addDays($i);
                        @endphp
                        @if (checkAvalableSchedul($fetchSchedule->id, $date))
                            <div class="col-2">
                                <div class="statsb_single  ">
                                    <input type="radio" id="date{{ $i }}" name="booking_date"
                                        value="{{ $date->format('Y-m-d') }}" class="booking_date">
                                    <label for="date{{ $i }}" class="statmain_box">
                                        {{ $date->format('D') }}
                                        <h6>{{ $date->format('d') }}</h6>
                                        {{ $date->format('M') }}
                                    </label>
                                </div>
                            </div>
                        @else
                            <div class="col-2">
                                <div class="statsb_single bg-secondary">
                                    <input type="radio" id="date{{ $i }}" name="booking_date"
                                        value="{{ $date->format('Y-m-d') }}" class="booking_date" disabled>
                                    <label for="date{{ $i }}" class="statmain_box">
                                        {{ $date->format('D') }}
                                        <h6>{{ $date->format('d') }}</h6>
                                        {{ $date->format('M') }}
                                    </label>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="company_profiles card-body">
                <div class="stats_box row">
                    <div class="col-3">
                        <div class="d-flex align-items-center">
                            <div class=" p-2 rounded-circle" style="width:30px;height:30px;background-color:#7df0ae">
                            </div>
                            <p class="m-0 ml-2">Available</p>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="d-flex align-items-center">
                            <div class=" p-2 rounded-circle" style="width:30px;height:30px;background-color:#8aaeed">
                            </div>
                            <p class="m-0 ml-2">Premium/Emergency</p>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="d-flex align-items-center">
                            <div class=" p-2 rounded-circle" style="width:30px;height:30px;background-color:#f5d1a2">
                            </div>
                            <p class="m-0 ml-2">RAC</p>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="d-flex align-items-center">
                            <div class=" p-2 rounded-circle" style="width:30px;height:30px;background-color:#f1300e">
                            </div>
                            <p class="m-0 ml-2">Break Time</p>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </div>
    <div class="time_row" style="display:none;">
        <div class="stats_box row" id="bookingTimes">

        </div>
    </div>
    <div class="modal-footer">
        {{-- <a href="{{ route('admin.schedule.list') }}"><button type="button"
                        class="btn btn-cancle">Cancel</button></a> --}}
        {{-- <input type="submit" class="btn btn-book" value="submit"> --}}
    </div>
    </form>
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
