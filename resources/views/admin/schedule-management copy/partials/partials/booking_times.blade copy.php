@php
    $i = 1;
    $date = $date;
@endphp
{{-- @forelse ($fetchData->slots as $key => $time) --}}
{{-- @dd($processedSlots) --}}

{{-- @dd($breakTimeSlots) --}}
@forelse($processedSlots as $key => $time)
    {{-- @dd($time['person']) --}}
    {{-- @if ($time->available_from != null && $time->available_day == $dayOfWeek) --}}
    @php
        // $carbonStartTime = \Carbon\Carbon::parse($time->available_from);
        // $carbonEndTime = \Carbon\Carbon::parse($time->available_to);
        $formattedStartTime = \Carbon\Carbon::parse($time['start_time']);
        $formattedEndTime = \Carbon\Carbon::parse($time['end_time']);
        // $formattedStartTime = $time['start_time']->format('h:i A');
        // $formattedEndTime = $time['end_time']->format('h:i A');
        $interval = $time['interval'];

        $rac =  $time['rac'];
        $emergency =  $time['emergency'];
        $breakTime = $breakTimeSlots;
        // dd($breakTime);
        $fetchBreakTime = slotbreaktime($breakTime);
    @endphp
{{-- 
0 => array:6 [
    "start_time" => "09:21"
    "end_time" => "17:11"
    "interval" => "9.4 minutes"
    "person" => 50
    "rac" => 5.0
    "emergency" => 2.5
  ] --}}
    {{-- @if ($time->available_from != null && $time->available_day == $dayOfWeek)
        @php
            $carbonStartTime = \Carbon\Carbon::parse($time->available_from);
            $carbonEndTime = \Carbon\Carbon::parse($time->available_to);
            $formattedStartTime = $carbonStartTime->format('H:i');
            $formattedEndTime = $carbonEndTime->format('H:i');
            $timeDifferenceInMinutes = $carbonEndTime->diffInMinutes($carbonStartTime);
            $totalTime = $timeDifferenceInMinutes;
            $dividedResult = $totalTime / $time['person'];
            $interval = $dividedResult . ' minutes';
           
            $rac = (10 / 100) * $time->total_patient;
            $emergency = (5 / 100) * $time->total_patient;
            $breakTime = $time->first()->doctorBreakTime;
            $fetchBreakTime = slotbreaktime($breakTime);
        @endphp --}}
    <div class="company_profiles card-body">
        <div class="stats_box row" id="bookingTimes">
            <div class="col-lg-12 col-md-12 ">
                <div class="row doctor-details-style clinicsheading_title">
                    <div class="col-md-4">
                        <div class="">
                            Schedule-{{ $i }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="">
                            {{-- Start Time-{{ $time['start_time']->format('h:i A') }} --}}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="">
                            {{-- End Time-{{ $time['end_time']->format('h:i A') }} --}}
                        </div>
                    </div>
                </div>
            </div>
            @foreach (\Carbon\CarbonPeriod::create($formattedStartTime, $interval, $formattedEndTime) as $k => $times)
                <div class="col">
                    {{-- @for ($i = 0; $i < count($breakTime); $i++)
                            @if ($times->format('h:i A') < timeConvert($breakTime[$i]->break_from) || timeConvert($breakTime[$i]->break_to) < $times->format('h:i A')) --}}
                    @php
                        $slotColor = '#df4508'; // Default color for unavailable slots
                        $isDisabled = true;
                        if ($time['person'] - $rac <= $k) {
                            $slotColor = '#f5d1a2'; // RAC Slot
                            $isDisabled = true;
                        } elseif ($k <= $emergency) {
                            $slotColor = '#8aaeed'; // Emergency Slot
                            $isDisabled = false;
                        } else {
                            $slotColor = '#7df0ae'; // Available Slot
                            $isDisabled = true;
                        }
                    @endphp
                    <div class="slot_statsb_single" style="background-color:{{ $slotColor }}">
                        <input type="hidden" id="time{{ $key . '-' . $k }}" name="slotId" value="{{ $time['id'] }}">
                        <input id="time{{ $key . '-' . $k }}" type="radio" name="booking_time"
                            value="{{ $date . ' ' . $times->format('G:i:s') }}" class="booking_time"
                            {{ $isDisabled ? 'disabled' : '' }}>

                        {{ $k }}
                        <label for="time{{ $key . '-' . $k }}" class="slot_text-dsgn">
                            {{ $times->format('h:i A') }}
                        </label>
                    </div>
                    {{-- @else
                                <p style="color:#df4508">Break Time</p> --}}
                    {{-- @endif
                        @endfor --}}
                </div>
            @endforeach
        </div>
    </div>
    @php
        $i++;
    @endphp
    {{-- @endif --}}
@empty
    <div class="col">
        <p>Unavailable Slots</p>
    </div>
@endforelse
