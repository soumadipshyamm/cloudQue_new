@php
    $i = 1;
    $date = $date;
@endphp
{{-- @dd($fetchData->toArray()) --}}
@forelse ($fetchData->slots as $key => $time)
    {{-- @dd($time->first()->doctorBreakTime->toArray()) --}}
    @if ($time->available_from != null && $time->available_day == $dayOfWeek)
        @php
            $carbonStartTime = \Carbon\Carbon::parse($time->available_from);
            $carbonEndTime = \Carbon\Carbon::parse($time->available_to);
            $formattedStartTime = $carbonStartTime->format('H:i');
            $formattedEndTime = $carbonEndTime->format('H:i');
            $timeDifferenceInMinutes = $carbonEndTime->diffInMinutes($carbonStartTime);
            $totalTime = $timeDifferenceInMinutes;
            $dividedResult = $totalTime / $time->total_patient;
            $interval = $dividedResult . ' minutes';
            $rac = (10 / 100) * $time->total_patient;
            $emergency = (5 / 100) * $time->total_patient;
            $breakTime = $time->first()->doctorBreakTime;
            $fetchBreakTime = slotbreaktime($breakTime);
        @endphp
        {{-- @dd($breakTime->toArray()); --}}
        {{-- {{ $time->first()->doctorBreakTime }} --}}

        {{--  $breakTime =[{"id":1,"uuid":null,"schedule_id":27,"doctors_availabilitie_id":2,"break_day":"Sunday","break_from":"14:30:00","break_to":"15:00:00","is_active":1,"created_at":null,"updated_at":null},{"id":2,"uuid":null,"schedule_id":27,"doctors_availabilitie_id":2,"break_day":"Sunday","break_from":"16:30:00","break_to":"16:50:00","is_active":1,"created_at":null,"updated_at":null},{"id":3,"uuid":null,"schedule_id":27,"doctors_availabilitie_id":2,"break_day":"Sunday","break_from":"19:30:00","break_to":"20:00:00","is_active":1,"created_at":null,"updated_at":null}] --}}
        {{-- @dd(timeConvert($breakTime[0]->break_from)) --}}

        <div class="company_profiles card-body">
            <div class="stats_box row" id="bookingTimes">
                <div class="col-lg-12 col-md-12 ">
                    <div class="row doctor-details-style clinicsheading_title">
                        <div class="col-md-4">
                            <div class="">
                                Schedule-{{ $i }}--{{ $fetchBreakTime->schedule_id}}----{{ $fetchBreakTime->doctors_availabilitie_id}}---{{ $fetchBreakTime->break_day}}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                Start Time-{{ \Carbon\Carbon::parse($formattedStartTime)->format('h:i A') }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                End Time-{{ \Carbon\Carbon::parse($formattedEndTime)->format('h:i A') }}
                            </div>
                        </div>
                    </div>
                </div>

                @foreach (\Carbon\CarbonPeriod::create($formattedStartTime, $interval, $formattedEndTime) as $k => $times)
                    <div class="col">

                        {{-- @dd($fetchBreakTime->break_from->format('h:i A')) --}}
                        {{ timeConvert($fetchBreakTime->break_from) }}
                        {{ timeConvert($fetchBreakTime->break_to) }}
                        @if($fetchBreakTime->break_day==)
                        @if ($times->format('h:i A') < timeConvert($fetchBreakTime->break_from) || timeConvert($fetchBreakTime->break_to) < $times->format('h:i A'))
                            @php
                                $slotColor = '#df4508'; // Default color for unavailable slots
                                $isDisabled = true;

                                if ($time->total_patient - $rac <= $k) {
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
                                <input type="hidden" id="time{{ $key . '-' . $k }}" name="slotId"
                                    value="{{ $time->id }}">
                                <input id="time{{ $key . '-' . $k }}" type="radio" name="booking_time"
                                    value="{{ $date . ' ' . $times->format('G:i:s') }}" class="booking_time"
                                    {{ $isDisabled ? 'disabled' : '' }}>
                                <label for="time{{ $key . '-' . $k }}" class="slot_text-dsgn">
                                    {{ $times->format('h:i A') }}
                                </label>
                            </div>
                        @else
                            <div class="slot_statsb_single" style="background-color:#df4508">
                                <input type="hidden" id="time{{ $key . '-' . $k }}" name="slotId"
                                    value="{{ $time->id }}">
                                <input id="time{{ $key . '-' . $k }}" type="radio" name="booking_time"
                                    value="{{ $date . ' ' . $times->format('G:i:s') }}" class="booking_time" disabled>
                                <label for="time{{ $key . '-' . $k }}" class="slot_text-dsgn">
                                    {{ $times->format('h:i A') }}
                                </label>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @php
            $i++;
        @endphp
    @endif
@empty
    <div class="col">
        <p>Unavailable Slots</p>
    </div>
@endforelse
