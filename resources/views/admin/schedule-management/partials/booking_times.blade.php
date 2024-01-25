@forelse ($fetchData->slots as $k => $time)

    @if ($time->available_from != null && $time->available_day == $dayOfWeek)
        <div class="company_profiles card-body">
            <div class="stats_box row" id="bookingTimes">
                <div class="col-lg-12 col-md-12 ">
                    <div class="doctor-details-style clinicsheading_title">
                        <div class="row">
                            <div class="col-4">
                                Schedule-{{ $k + 1 }}
                            </div>
                            <div class="col-4">
                                Start Time-{{ $time->available_from }}
                            </div>
                            <div class="col-4">
                                End Time-{{ $time->available_to }}
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $breakTime = 10;
                    $carbonStartTime = \Carbon\Carbon::parse($time->available_from);
                    $carbonEndTime = \Carbon\Carbon::parse($time->available_to);

                    $formattedStartTime = $carbonStartTime->format('H:i');
                    $formattedEndTime = $carbonEndTime->format('H:i');

                    $timeDifferenceInMinutes = $carbonEndTime->diffInMinutes($carbonStartTime);
                    $totalTime = $timeDifferenceInMinutes - $breakTime;

                    $dividedResult = $timeDifferenceInMinutes / $totalTime;
                    $interval = $dividedResult . ' minutes';
                @endphp
                @foreach (\Carbon\CarbonPeriod::create($formattedStartTime, $interval, $formattedEndTime) as $k => $time)
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
    @endif
@empty
    <div class="col">
        <p>Unavailable Slots</p>
    </div>
@endforelse
