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
                    <p>Clinic Name: {{ $fetchSchedule->first()->profileClinic->clinic_name }}</p>
                </div>
                <div class="col-6">
                    <p> Doctor Name: {{ $fetchSchedule->first()->users->name }}</p>
                </div>
            </div>
        </div>
        <div class="company_profiles card-body">
            <div class="stats_box row">
                {{-- @dd($fetchSchedule) --}}
                @foreach ($fetchSchedule->schedule as $keys => $weekDay)
                    @php
                        $calanders = getDateAndWeekDay('', $fetchSchedule->valid_date);
                    @endphp
                @endforeach

                @foreach ($calanders as $key => $calander)
                    @if ($weekDay['week_name'] == $calander['day'])
                        <div class="col-2">
                            <a
                                href="{{ route('admin.slot.list', [$fetchSchedule['id'], $weekDay['week_id'], $calander['date']]) }}">
                                <div class="statsb_single">
                                    <div class="statmain_box">
                                        <h3> {{ $calander['date'] }}
                                            {{ $calander['day'] }}</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>


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
@endpush
