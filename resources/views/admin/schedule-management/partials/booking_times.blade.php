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
        @endphp
        {{-- @dd($breakTime[0]->toArray()); --}}
        {{-- {{ $time->first()->doctorBreakTime }} --}}

        {{-- [{"id":1,"uuid":null,"schedule_id":27,"doctors_availabilitie_id":2,"break_day":"Sunday","break_from":"14:30:00","break_to":"15:00:00","is_active":1,"created_at":null,"updated_at":null},{"id":2,"uuid":null,"schedule_id":27,"doctors_availabilitie_id":2,"break_day":"Sunday","break_from":"16:30:00","break_to":"16:50:00","is_active":1,"created_at":null,"updated_at":null},{"id":3,"uuid":null,"schedule_id":27,"doctors_availabilitie_id":2,"break_day":"Sunday","break_from":"19:30:00","break_to":"20:00:00","is_active":1,"created_at":null,"updated_at":null}] --}}
        {{-- @dd(timeConvert($breakTime[0]->break_from)) --}}

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
                    {{-- @foreach ($breakTime as $ky => $btime) --}}
                        {{-- {{ timeConvert($btime->break_from) }} --}}
                        {{-- @if (timeConvert($btime->break_from) <= $times->format('h:i A') &&  $times->format('h:i A') > timeConvert($btime->break_to)) --}}

                            <div class="col">
                                @if ($time->total_patient - $rac <= $k)
                                    {{-- RAC Slot --}}
                                    <div class="slot_statsb_single" style="background-color:#f5d1a2">
                                        <input type="hidden" id="time{{ $key . '-' . $k }}" name="slotId"
                                            value="{{ $time->id }}">
                                        <input id="time{{ $key . '-' . $k }}" type="radio" name="booking_time"
                                            value="{{ $date . ' ' . $times->format('G:i:s') }}" class="booking_time"
                                            disabled>
                                        {{-- {{ $k }} --}}
                                        <label for="time{{ $key . '-' . $k }}" class="slot_text-dsgn">
                                            {{ $times->format('h:i A') }}
                                        </label>
                                    </div>
                                @else
                                    {{-- //Emergency  slot  --}}
                                    @if ($k <= $emergency)
                                        <div class="slot_statsb_single " style="background-color:#8aaeed">
                                            <input type="hidden" id="time{{ $key . '-' . $k }}" name="slotId"
                                                value="{{ $time->id }}">
                                            <input id="time{{ $key . '-' . $k }}" type="radio" name="booking_time"
                                                value="{{ $date . ' ' . $times->format('G:i:s') }}"
                                                class="booking_time">
                                            {{-- {{ $k }} --}}
                                            <label for="time{{ $key . '-' . $k }}" class="slot_text-dsgn">
                                                {{ $times->format('h:i A') }}
                                            </label>
                                        </div>
                                    @else
                                        {{-- Available Slot --}}
                                        <div class="slot_statsb_single" style="background-color:#7df0ae">
                                            <input type="hidden" id="time{{ $key . '-' . $k }}" name="slotId"
                                                value="{{ $time->id }}">
                                            <input id="time{{ $key . '-' . $k }}" type="radio" name="booking_time"
                                                value="{{ $date . ' ' . $times->format('G:i:s') }}"
                                                class="booking_time" disabled>
                                            {{-- {{ $k }} --}}
                                            <label for="time{{ $key . '-' . $k }}" class="slot_text-dsgn">
                                                {{ $times->format('h:i A') }}
                                            </label>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        {{-- @endif
                    @endforeach --}}
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
{{-- array:2 [ // resources\views/admin/schedule-management/partials/booking_times.blade.php
  0 => array:10 [
    "id" => 1
    "uuid" => null
    "schedule_id" => 27
    "doctors_availabilitie_id" => 2
    "break_day" => "Sunday"
    "break_from" => "14:30:00"
    "break_to" => "15:00:00"
    "is_active" => 1
    "created_at" => null
    "updated_at" => null
  ]
  1 => array:10 [
    "id" => 2
    "uuid" => null
    "schedule_id" => 27
    "doctors_availabilitie_id" => 2
    "break_day" => "Sunday"
    "break_from" => "16:30:00"
    "break_to" => "16:50:00"
    "is_active" => 1
    "created_at" => null
    "updated_at" => null
  ]
] --}}
